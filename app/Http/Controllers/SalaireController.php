<?php

namespace App\Http\Controllers;

use App\Models\Salaire;
use App\Models\AvanceSalaire;
use App\Models\User;
use App\Models\UserPin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prime;
use App\Models\Configuration;
use App\Models\ACouper;
use App\Models\Deli;
use App\Models\Evaluation;
use App\Models\DeliUser;
use App\Models\Complexe;
use App\Models\PaydayConfig;
use App\Models\ManquantTemporaire;
use Carbon\Carbon;
use App\Traits\HistorisableActions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Services\PinService;
//au debut du mois on replace tous les compteurs de salaires a 0

class SalaireController extends Controller
{
    use HistorisableActions;
    protected $notificationController;
    protected $messageController;
    protected $pinService;
    public function __construct(NotificationController $notificationController, MessageController $messageController,PinService $pinService)
    {
        $this->pinService = $pinService;
        $this->notificationController = $notificationController;
        $this->messageController = $messageController;
    }
   
    public function reclamerAs()
    {
        $employe = auth()->user();

        #verification du blocage des avances sur salaire
        $config = Configuration::first();
        if ($config) {
            $flag3 = $config->flag3;
        if (!$flag3) {
            // Messages selon la langue de l'utilisateur
            $errorMessages = [
                'fr' => 'Les avances sur salaire sont actuellement bloquées',
                'en' => 'Salary advances are currently blocked'
            ];
            
            // Langue par défaut si la langue de l'utilisateur n'est pas supportée
            $userLanguage = $employe->language ?? 'fr';
            $errorMessage = $errorMessages[$userLanguage] ?? $errorMessages['fr'];

           return redirect()->back()->with('error',$errorMessage);
        }
        }
        
        $paydayConfig = PaydayConfig::first();
        $jour_payement_salaire = $paydayConfig->salary_day;
        $jour_payement_avance_salaire = $paydayConfig->advance_day;
        
        // Vérification de la plage de jours autorisés
        $jourActuel = now()->day;
        $debutPlage = $jour_payement_salaire + 3;
        $finPlage = $jour_payement_avance_salaire + 2;
        
        // Gérer le cas où la plage traverse la fin du mois
        $jourDansPlage = false;
        if ($debutPlage <= $finPlage) {
            // Plage normale (ne traverse pas la fin du mois)
            $jourDansPlage = ($jourActuel >= $debutPlage && $jourActuel <= $finPlage);
        } else {
            // Plage qui traverse la fin du mois
            $jourDansPlage = ($jourActuel >= $debutPlage || $jourActuel <= $finPlage);
        }
        
        /*if (!$jourDansPlage) {
            // Messages selon la langue de l'utilisateur
            $errorMessages = [
                'fr' => 'Les demandes d\'avance sur salaire ne sont autorisées que du ' . $debutPlage . ' au ' . $finPlage . ' du mois',
                'en' => 'Salary advance requests are only allowed from the ' . $debutPlage . ' to the ' . $finPlage . ' of the month'
            ];
            
            // Langue par défaut si la langue de l'utilisateur n'est pas supportée
            $userLanguage = $employe->language ?? 'fr';
            $errorMessage = $errorMessages[$userLanguage] ?? $errorMessages['fr'];
            
            return view('pages.error_as', [
                'error' => $errorMessage,
                'hasRequest' => false,
                'outOfRange' => true
            ]);
        }*/
        
        // Vérification si l'employé a déjà une avance ce mois-ci
        $hasRequest = AvanceSalaire::where('id_employe', $employe->id)
            ->whereMonth('mois_as', now()->month)
            ->whereYear('mois_as', now()->year)
            ->where('flag', true)
            ->exists();
            
        if ($hasRequest) {
            // Messages selon la langue de l'utilisateur
            $errorMessages = [
                'fr' => 'Vous avez déjà soumis une demande pour ce mois-ci',
                'en' => 'You have already submitted a request for this month'
            ];
            
            // Langue par défaut si la langue de l'utilisateur n'est pas supportée
            $userLanguage = $employe->language ?? 'fr';
            $errorMessage = $errorMessages[$userLanguage] ?? $errorMessages['fr'];
            
            return view('pages.error_as', [
                'error' => $errorMessage,
                'hasRequest' => true,
                'outOfRange' => false
            ]);
        }
        
        // Si toutes les conditions sont satisfaites
        $as = new AvanceSalaire();
        return view('salaires.reclamer-as', compact('as'));
    }

    public function store_demandes_AS(Request $request)
    {
        $request->validate([
            'sommeAs' => 'required|numeric|min:0',
            'code_pin' => 'required|string',
        ]);

        $user = Auth::user();
        $result = $this->pinService->verifyPin(
            $user->id,
            $request->code_pin
        );
        if (!$result) {
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }
        $salaire = Salaire::where('id_employe', $user->id)->first();

        if (!$salaire) {
            return redirect()->back()->with('error', 'Aucun salaire trouvé.Veuillez vous rapprocher de l\'administration');
        }

        // Vérification que le montant demandé n'est pas supérieur au salaire
        if ($request->sommeAs > $salaire->somme) {
            return redirect()->back()->with('error', 'Le montant demandé ne peut pas être supérieur à votre salaire.');
        }

        // Création ou mise à jour de l'avance sur salaire
        $avanceSalaire = AvanceSalaire::updateOrCreate(
            ['id_employe' => $user->id],
            [
                'sommeAs' => $request->sommeAs,
                'flag' => false,
                'retrait_demande' => false,
                'retrait_valide' => false,
                'mois_as' => now()
            ]
        );

      

        // Historiser l'action
        $this->historiser("L'utilisateur {$user->name} a demandé une avance sur salaire de {$request->sommeAs}", 'avance_salaire');

        return redirect()->route('voir-status')->with('success', 'Demande d\'avance sur salaire envoyée avec succès.');
    }

    public function voir_Status()
    {
        $as = AvanceSalaire::where('id_employe', Auth::id())
            ->whereMonth('created_at', now()->month)
            ->first();

        return view('salaires.status', compact('as'));
    }

    public function validerAs()
{
    // Récupérer le mois courant
    $currentMonth = now()->month;
    $currentYear = now()->year;

    // Récupérer les demandes en attente
    $demandes = AvanceSalaire::with('employe')
        ->where('flag', false)
        ->where('sommeAs', '>', 0)
        ->get();

        $flag = UserPin::where('user_id', Auth::user()->id)->value('flag');

    return view('salaires.valider-as', compact(
        'demandes','flag'
    ));
}

public function store_validation(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'as_id' => 'required|exists:avance_salaires,id',
        'decision' => 'required|boolean',
        'pin' => 'required|string',
    ]);
    $flag = UserPin::where('user_id', $user->id)->first();
    if($flag->flag == false){
        Log::info("verification du pin");
        $result = $this->pinService->verifyPin(
            $user->id,
            $request->pin
    );
    if (!$result) {
        Log::info("pin : incorrect");
        return redirect()->back()->with('error', 'Le code pin est incorrect');
    }else{
        Log::info("pin : correct");
        Log::info($flag);
        $flag->flag = true;
        $flag->save();
        Log::info("flag passe a true");
    }
    
    }
    
    $as = AvanceSalaire::findOrFail($request->as_id);

    // Si la décision est refusée (0), supprimer l'entrée
    if ($request->decision == 0) {
        // Récupérer l'employé concerné avant de supprimer
        $employe = User::findOrFail($as->id_employe);

        // Préparer le message de notification
        $sujet = "Demande d'avance sur salaire refusée";
        $message = "Votre demande d'avance sur salaire d'un montant de {$as->sommeAs} a été refusée. Veuillez contacter votre responsable pour plus d'informations.";

        // Historiser l'action avant suppression
        $currentUser = auth()->user();
        $this->historiser("L'utilisateur {$currentUser->name} a refusé la demande d'avance sur salaire de {$employe->name}", 'validation_avance');

        // Envoyer la notification à l'employé
        $notificationRequest = new Request([
            'recipient_id' => $as->id_employe,
            'subject' => $sujet,
            'message' => $message
        ]);
        $this->notificationController->send($notificationRequest);

        // Supprimer l'entrée
        $as->delete();
    } else {
        // Si approuvée, mettre à jour le flag
        $as->flag = $request->decision;
        $as->save();

        // Récupérer l'employé concerné
        $employe = User::findOrFail($as->id_employe);

        // Préparer le message de notification
        $sujet = "Demande d'avance sur salaire approuvée";
        $message = "Votre demande d'avance sur salaire d'un montant de {$as->sommeAs} a été approuvée. Le montant sera disponible selon les modalités habituelles.";

        // Historiser l'action
        $currentUser = auth()->user();
        $this->historiser("L'utilisateur {$currentUser->name} a approuvé la demande d'avance sur salaire de {$employe->name}", 'validation_avance');

        // Envoyer la notification à l'employé
        $notificationRequest = new Request([
            'recipient_id' => $as->id_employe,
            'subject' => $sujet,
            'message' => $message
        ]);
        $this->notificationController->send($notificationRequest);
    }

    return redirect()->back()->with('success', 'Décision enregistrée et employé notifié.');
}


    public function validation_retrait()
    {
        $as = AvanceSalaire::where('id_employe', Auth::id())
            ->where('flag', true)
            ->where('retrait_demande', false)
            ->where('retrait_valide', false)
            ->first();
        
        $as2 =AvanceSalaire::where('id_employe', Auth::id())
            ->where('flag', true)
            ->where('retrait_demande', true)
            ->where('retrait_valide', false)
            ->first();
        $as3 =AvanceSalaire::where('id_employe', Auth::id())
        ->where('flag', true)
        ->where('retrait_demande', true)
        ->where('retrait_valide', true)
        ->first();
        if ($as2) {
            return redirect()->route('voir-status');
        }
        if ($as3) {
            return redirect()->route('voir-status');
        }
        if (!$as) {
            return redirect()->back()->with(['error' => 'Aucune demande d\'AS enregistrée']);
        }else{
            return view('salaires.validation-retrait', compact('as'));
        }
    }

    public function recup_retrait(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        $user = Auth::user();
        $result = $this->pinService->verifyPin(
            $user->id,
            $request->pin
        );
        if (!$result) {
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }
        $as = AvanceSalaire::findOrFail($request->as_id);
        $as->retrait_demande = true;
        $as->save();
        $user = User::findOrFail($as->id_employe);
        //historiser l'action
        $this->historiser("L'utilisateur {$user->name} a demandé le retrait de l'avance sur salaire de {$as->sommeAs}", 'retrait_as');        
        return redirect()->route('voir-status')->with('success', 'Demande de retrait enregistree');
    }

    public function valider_retraitcp()
    {
        $user = Auth::user();
        $demandes = AvanceSalaire::with('employe')
            ->where('retrait_demande', true)
            ->where('retrait_valide', false)
            ->get();
        $flag = UserPin::where('user_id', $user->id)->first('flag');
        return view('salaires.valider-retrait-cp', compact('demandes','flag'));
    }

    public function recup_retrait_cp(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'pin' => 'required|string',
        ]);
        $flag = UserPin::where('user_id', $user->id)->first();
        if($flag->flag == false){
            Log::info("verification du pin");
            $result = $this->pinService->verifyPin(
                $user->id,
                $request->pin
        );
        if (!$result) {
            Log::info("pin : incorrect");
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }else{
            Log::info("pin : correct");
            Log::info($flag);
            $flag->flag = true;
            $flag->save();
            Log::info("flag passe a true");
        }
    }

        $as = AvanceSalaire::findOrFail($request->as_id);
        $as->retrait_valide = true;
        $as->mois_as = now()->format('Y-m-d');
        $as->save();
       //historiser l'action
        $this->historiser("L'utilisateur {$user->name} a validé le retrait de l'avance sur salaire de {$as->employe->name}", 'validation_retrait_as');

        return redirect()->back()->with('success', 'Retrait validé avec succès.');
    }

    public function form_salaire()
    {
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first('flag');
        $employes = User::all();
        return view('salaires.form', compact('employes','flag'));
    }

    public function store_salaire(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'pin' => 'required|string',
        ]);
        $flag = UserPin::where('user_id', $user->id)->first();
        if($flag->flag == false){
            Log::info("verification du pin");
            $result = $this->pinService->verifyPin(
                $user->id,
                $request->pin
        );
        if (!$result) {
            Log::info("pin : incorrect");
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }else{
            Log::info("pin : correct");
            Log::info($flag);
            $flag->flag = true;
            $flag->save();
            Log::info("flag passe a true");
        }
    }

        $request->validate([
            'id_employe' => 'required|exists:users,id',
            'somme' => 'required|numeric|min:0'
        ]);

        Salaire::updateOrCreate(
            ['id_employe' => $request->id_employe],
            [
                'somme' => $request->somme,
            ]
        );
        // Historiser l'action
        $this->historiser("L'utilisateur {$user->name} a mis à jour le salaire de l'employé ID: {$request->id_employe} : nouveau salaire : {$request->somme}", 'update_salaire');
        return redirect()->back()->with('success', 'Salaire enregistré avec succès.');
    }
    /*salaire*/
    public function index()
    {
        //on renvoie les salaire par ordre alphabétique des employés
        
        $salaires = Salaire::with('employe')
                ->orderBy('somme','asc')->get();
        return view('salaires.index', compact('salaires'));
    }

    public function create()
    {
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first();
        $employes = User::whereNotIn('id', function($query) {
            $query->select('id_employe')->from('salaires');
        })->get();

        return view('salaires.create', compact('employes','flag'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_employe' => 'required|exists:users,id|unique:salaires,id_employe',
            'somme' => 'required|numeric|min:0',
            'pin' => 'required|string'
        ]);

        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first();
        if($flag->flag == false){
            Log::info("verification du pin");
            $result = $this->pinService->verifyPin(
                $user->id,
                $request->pin
        );
        if (!$result) {
            Log::info("pin : incorrect");
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }else{
            Log::info("pin : correct");
            Log::info($flag);
            $flag->flag = true;
            $flag->save();
            Log::info("flag passe a true");
        }
    }

        Salaire::create([
            'id_employe' => $request->id_employe,
            'somme' => $request->somme,
        ]);

        $request->merge([
            'recipient_id' => $request->id_employe,
            'subject' => 'Salaire Mis a Jour',
            'message' => 'Bonjour votre salaire a ete mis a jour : '.$request->somme
        ]);
        // Historiser l'action
        $this->historiser("L'utilisateur {$user->name} a créé un salaire pour l'employé ID: {$request->id_employe}", 'create_salaire');
        // Appel de la méthode send
        $this->notificationController->send($request);
        return redirect()->route('salaires.index')
            ->with('success', 'Salaire créé avec succès');
    }

    public function edit(Salaire $salaire)
    {
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first('flag');
        
        return view('salaires.edit', compact('salaire','flag'));
    }

    public function update(Request $request, Salaire $salaire)
    {
        $request->validate([
            'somme' => 'required|numeric|min:0',
            'pin' => 'required|string'

        ]);
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first();
        if($flag->flag == false){
            Log::info("verification du pin");
            $result = $this->pinService->verifyPin(
                $user->id,
                $request->pin
        );
        if (!$result) {
            Log::info("pin : incorrect");
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }else{
            Log::info("pin : correct");
            Log::info($flag);
            $flag->flag = true;
            $flag->save();
            Log::info("flag passe a true");
        }
    }
        $id_employe = $salaire->id_employe;
        $salaire->update([
            'somme' => $request->somme,
        ]);
        $request->merge([
            'recipient_id' => $id_employe,
            'subject' => 'Salaire Mis a Jour',
            'message' => 'Bonjour votre salaire a ete mis a jour. Le nouveau Montant est '.$request->somme
        ]);
        // Historiser l'action
        $this->historiser("L'utilisateur {$user->name} a mis à jour le salaire de l'employé ID: {$id_employe} : nouveau salaire : {$request->somme}", 'update_salaire');
        // Appel de la méthode send
        $this->notificationController->send($request);


        return redirect()->route('salaires.index')
            ->with('success', 'Salaire mis à jour avec succès');
    }

    public function destroy(Salaire $salaire)
    {
        $salaire->delete();
        // Historiser l'action
        $user = Auth::user();
        $this->historiser("L'utilisateur {$user->name} a supprimé le salaire de l'employé ID: {$salaire->id_employe}", 'delete_salaire');
        return redirect()->route('salaires.index')->with('success', 'Salaire supprimé avec succès');
    }

    public function fichePaie($id = null)
    {
	Log::info("message de debug : fichePaie");
        
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first();
        
        $employe = auth()->user();
	if($id){
        $employe = User::findOrFail($id);
	}
        $salaire = Salaire::where('id_employe', $employe->id)->firstOrFail();
        $mois = Carbon::now();

        // Récupérer les déductions
        $deductions = Acouper::where('id_employe', $employe->id)
                            ->where('date', '<=', now())
                            ->first();

        // Récupérer les incidents (delis)
        $incidents = DeliUser::where('user_id', $employe->id)
                            ->whereMonth('date_incident', $mois->month)
                            ->whereYear('date_incident', $mois->year)
                            ->with('deli')
                            ->get();
        $totalDelis = $incidents->sum(function($incident) {
            return $incident->deli->montant ?? 0;
        });

        $debutPeriode = now()->subMonth()->startOfMonth(); // Début du mois précédent
        $finPeriode = now()->endOfMonth(); // Fin du mois courant
        
        $avanceSalaire = DB::table('avance_salaires')
            ->where('id_employe', $employe->id)
            ->where('retrait_valide', true)
            ->whereBetween('mois_as', [$debutPeriode, $finPeriode])
            ->value('sommeAs') ?? 0;

        // Récupérer les primes
        $primes = Prime::where('id_employe', $employe->id)
                      ->whereMonth('created_at', $mois->month)
                      ->whereYear('created_at', $mois->year)
                      ->get();
        $totalPrimes = $primes->sum('montant');

        // Calculer le salaire net
        $fichePaie = [
            'salaire_base' => $salaire->somme,
            'avance_salaire' => $avanceSalaire,
            'deductions' => [
                'manquants' => $deductions->manquants ?? 0,
                'remboursement' => $deductions->remboursement ?? 0,
                'caisse_sociale' => $deductions->caisse_sociale ?? 0,
                'incidents' => $totalDelis,
            ],
            'primes' => $totalPrimes,
            'salaire_net' => $salaire->somme - $avanceSalaire
                            - ($deductions->manquants ?? 0)
                            - ($deductions->remboursement ?? 0)
                            - ($deductions->caisse_sociale ?? 0)
                            - $totalDelis
                            + $totalPrimes
        ];

        // Liste des incidents pour affichage détaillé
        $listeIncidents = $incidents->map(function($incident) {
            return [
                'date' => Carbon::parse($incident->date_incident)->format('d/m/Y'),
                'description' => $incident->deli->description ?? 'Incident non spécifié',
                'montant' => $incident->deli->montant ?? 0
            ];
        });
//historiser les details de la fiche de paie
$message = sprintf(
    "Fiche de paie - Employé ID: %d | Mois: %s | Salaire base: %s | Avance: %s | Déductions: Manquants-%s, Remboursement-%s, Caisse sociale-%s, Incidents-%s | Primes: %s | Salaire net: %s",
    $employe->id,
    $mois->format('F Y'),
    number_format($fichePaie['salaire_base'], 0, ',', ' '),
    number_format($fichePaie['avance_salaire'], 0, ',', ' '),
    number_format($fichePaie['deductions']['manquants'], 0, ',', ' '),
    number_format($fichePaie['deductions']['remboursement'], 0, ',', ' '),
    number_format($fichePaie['deductions']['caisse_sociale'], 0, ',', ' '),
    number_format($fichePaie['deductions']['incidents'], 0, ',', ' '),
    number_format($fichePaie['primes'], 0, ',', ' '),
    number_format($fichePaie['salaire_net'], 0, ',', ' ')
);

$this->historiser($message, 'details_fiche_paie');
Log::info("message de debug2 : fichePaie");
        return view('salaires.fiche-paie', compact('flag','employe', 'salaire', 'mois', 'fichePaie', 'listeIncidents'));
    }



    public function demandeRetrait(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|string'

        ]);
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first();
        if($flag->flag == false){
            Log::info("verification du pin");
            $result = $this->pinService->verifyPin(
                $user->id,
                $request->pin
        );
        if (!$result) {
            Log::info("pin : incorrect");
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }else{
            Log::info("pin : correct");
            Log::info($flag);
            $flag->flag = true;
            $flag->save();
            Log::info("flag passe a true");
        }
    }
        $id = auth()->user()->id;
        $salaire = Salaire::where('id_employe', $id)->first();
        $salaire->retrait_demande = true;
        $salaire->save();
        //historiser l'action
        $this->historiser("L'utilisateur {$user->name} a demandé le retrait de son salaire de {$salaire->somme}", 'retrait_salaire');
        return redirect()->back()->with('success', 'Demande de retrait envoyée avec succès');
    }

    public function consulter_fichePaie()
    {
        #verification du blocage des salaire
        $config = Configuration::first();
        if ($config) {
           $flag2 = $config->flag2;
            if (!$flag2) {
                // Messages selon la langue de l'utilisateur
                $errorMessages = [
                    'fr' => 'Les salaires sont actuellement bloqués',
                    'en' => 'Salaries are currently blocked'
                ];
            
                // Langue par défaut si la langue de l'utilisateur n'est pas supportée
                $userLanguage = $employe->language ?? 'fr';
                $errorMessage = $errorMessages[$userLanguage] ?? $errorMessages['fr'];

                return redirect()->back()->with('error',$errorMessage);
            }
        }
        
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first();
       
        $employe = auth()->user();
       
        $salairetemp = Salaire::where('id_employe', $employe->id)->first();
        if (!$salairetemp) {
            return redirect()->back()->with('error', 'Votre salaire n\'est pas défini');
        }

        $salaire = Salaire::where('id_employe', $employe->id)->firstOrFail();
        $mois = Carbon::now();

        // Récupérer les déductions
        $deductions = Acouper::where('id_employe', $employe->id)
                            ->where('date', '<=', now())
                            ->first();

        // Récupérer les primes
        $primes = Prime::where('id_employe', $employe->id)->get();
        $totalPrimes = $primes->sum('montant');

        $debutPeriode = now()->subMonth()->startOfMonth(); // Début du mois précédent
        $finPeriode = now()->endOfMonth(); // Fin du mois courant
        

        $as = DB::table('avance_salaires')
        ->where('id_employe', $employe->id)
        ->where('retrait_valide', true)
        ->whereBetween('mois_as', [$debutPeriode, $finPeriode])
        ->value('sommeAs') ?? 0;
        // Calculer le salaire net
         // Récupérer les incidents (delis)
         $incidents = DeliUser::where('user_id', $employe->id)
         ->whereMonth('date_incident', $mois->month)
         ->whereYear('date_incident', $mois->year)
         ->with('deli')
         ->get();
        $totalDelis = $incidents->sum(function($incident) {
            return $incident->deli->montant ?? 0;
        });
        $fichePaie = [
            'salaire_base' => $salaire->somme,
            'avance_salaire' => $as,
            'deductions' => [
                'manquants' => $deductions->manquants ?? 0,
                'caisse_sociale' => $deductions->caisse_sociale ?? 0,
                'remboursement' => $deductions->remboursement ?? 0,
                'incidents' => $totalDelis,
            ],
            'primes' => $totalPrimes,
            'salaire_net' => $salaire->somme - ($as)
                            - ($deductions->manquants ?? 0)
                            - ($deductions->remboursement ?? 0)
                            - ($deductions->caisse_sociale ?? 0)
                            - $totalDelis
                            + $totalPrimes
        ];

        return view('salaires.fiche-paie2', compact('flag','employe', 'salaire', 'mois', 'fichePaie'));
    }

    public function demandeRetrait2(Request $request)
    {
        $request->validate([
            'pin' => 'required|string'
        ]);
        $user = Auth::user();
        $flag = UserPin::where('user_id', $user->id)->first();
        if($flag->flag == false){
            Log::info("verification du pin");
            $result = $this->pinService->verifyPin(
                $user->id,
                $request->pin
        );
        if (!$result) {
            Log::info("pin : incorrect");
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }else{
            Log::info("pin : correct");
            Log::info($flag);
            $flag->flag = true;
            $flag->save();
            Log::info("flag passe a true");
        }
    }
        $id = auth()->user()->id;
        $salaire = Salaire::where('id_employe', $id)->first();

        $salaire->retrait_demande = true;
        $salaire->save();
        //historiser l'action
        $this->historiser("L'utilisateur {$user->name} a demandé le retrait de son salaire de {$salaire->somme}", 'retrait_salaire');
        return redirect()->back()->with('success', 'Demande de retrait envoyée avec succès');
    }


    public function validerRetrait(Request $request,$id)
{
    $request->validate([
        'pin' => 'required|string'

    ]);
    $user = Auth::user();
    $flag = UserPin::where('user_id', $user->id)->first();
    if($flag->flag == false){
        Log::info("verification du pin");
        $result = $this->pinService->verifyPin(
            $user->id,
            $request->pin
    );
    if (!$result) {
        Log::info("pin : incorrect");
        return redirect()->back()->with('error', 'Le code pin est incorrect');
    }else{
        Log::info("pin : correct");
        Log::info($flag);
        $flag->flag = true;
        $flag->save();
        Log::info("flag passe a true");
    }
}
    #verifier si l'emplyer dispose de manquanttemporaire
    $manquantTemporaire = ManquantTemporaire::where('employe_id', $id)->first();
    #verifier si le manquant temporaire est valide
    if($manquantTemporaire && $manquantTemporaire->valide_par == null){
        $flag = true;
    }else{
        $flag = false;
    }
    if ($flag) {
        return redirect()->back()->with('error', 'Impossible de valider le retrait, l\'employé a des manquants temporaires.Veuillez les traiter d\'abord.');
    }
    return DB::transaction(function () use ($id) {
        #verifier si il y'a encore les manquants temporaire

        $salaire = Salaire::where('id_employe', $id)->first();
        $acouper = ACouper::where('id_employe', $id)->first();
        $avanceSalaire = AvanceSalaire::where('id_employe', $id)->first();
        $user = User::findOrFail($id);
        $complexe = Complexe::first(); // Récupérer le complexe (supposant qu'il n'y en a qu'un seul)

        // Montant du salaire avant réinitialisation
        $montantSalaire = 0;

        // Réinitialisation complète du salaire
        if ($salaire) {
            // Sauvegarde du montant du salaire avant réinitialisation
            $montantSalaire = $salaire->somme;

            // Réinitialisation de tous les flags et statuts
            $salaire->retrait_valide = true;
            $salaire->retrait_demande = true;
            $salaire->mois_salaire = now()->format('Y-m-d');
            $salaire->flag = true;
            // Si d'autres champs doivent être réinitialisés, ajoutez-les ici
            $salaire->save();
        }

        #verifier si l'employé a des entrer dans la table acouper
        if (!$acouper) {
            // Si l'employé n'a pas d'entrées dans la table acouper, on crée une nouvelle entrée
            $acouper = new ACouper();
            $acouper->id_employe = $id;
            $acouper->pret = 0; // Initialiser le prêt à 0
            $acouper->date = now()->format('Y-m-d'); // Date actuelle
            $acouper->remboursement = 0; // Initialiser le remboursement à 0
            $acouper->manquants = 0; // Initialiser les manquants à 0
            #recuperere la caisse sociale
            $caisseSociale = Complexe::first()->caisse_sociale ?? 0;
            $acouper->caisse_sociale = $caisseSociale; // Initial
            $acouper->save();
        }

        if($acouper->remboursement > 0) {
            
         DB::table('loan_repayments')->insert([
            'user_id' => $id,
            'amount' => $acouper->remboursement,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

        // Réinitialisation des déductions
        if ($acouper) {
            $acouper->pret-= $acouper->remboursement;
            $acouper->remboursement = 0;
            $acouper->manquants = 0;
            $acouper->save();
        }


        // Réinitialisation de l'avance sur salaire
        if ($avanceSalaire) {
            $avanceSalaire->sommeAs = 0;
            $avanceSalaire->flag = false;
            $avanceSalaire->retrait_demande = false;
            $avanceSalaire->retrait_valide = false;
            $avanceSalaire->save();
        }

        // Réinitialisation des primes
        Prime::where('id_employe', $id)->delete();

        // Mise à jour de la caisse sociale
        if ($complexe) {
            $complexe->valeur_caisse_sociale += $complexe->caisse_sociale;
            $complexe->save();
        }

        //mise a jour de la note evaluation
        $evaluation = Evaluation::where('user_id', $id)->first();
        if ($evaluation) {
            $evaluation->note = 0;
            $evaluation->save();
        }


        $day = Carbon::now()->format('d/m/Y');

        // Envoyer une notification à l'employé
        $notificationRequest = new Request([
            'recipient_id' => $id,
            'subject' => 'Salaire disponible',
            'message' => 'Vous venez de récupérer votre salaire de ' . $montantSalaire . ' en ce jour ' . $day
        ]);
        $this->notificationController->send($notificationRequest);

        // Historiser l'action
        $currentUser = auth()->user();
        $this->historiser("L'utilisateur {$currentUser->name} a validé le retrait du salaire pour {$user->name} et réinitialisé son compte", 'validation_retrait');

        return redirect()->back()->with('success', 'Retrait validé avec succès et compte réinitialisé');
    });
}

    public function generatePDF($id)
    {
        $employe = User::findOrFail($id);
        $mois = Carbon::now();

        //au debut du mois on replace tous les compteurs de salaires a 0

        $pdf = PDF::loadView('salaires.fiche-paie-pdf', compact('employe', 'mois', 'fichePaie'));
        return $pdf->download('fiche-paie-'.$employe->name.'-'.$mois->format('F-Y').'.pdf');
    }
}
