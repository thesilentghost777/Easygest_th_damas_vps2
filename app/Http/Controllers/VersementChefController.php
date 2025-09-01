<?php

namespace App\Http\Controllers;

use App\Models\VersementChef;
use App\Models\SoldeCP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Traits\HistorisableActions;
use App\Models\UserPin;
use App\Models\Transaction;
use App\Services\PinService;

class VersementChefController extends Controller
{
    use HistorisableActions;

    protected $pinService;
 
    public function __construct(PinService $pinService,NotificationController $notificationController, MessageController $messageController)
    {
        $this->notificationController = $notificationController;
        $this->messageController = $messageController;
        $this->pinService = $pinService;
    }

    public function index()
    {
        $nom = auth()->user()->name;
        $role = auth()->user()->role;
        $secteur = auth()->user()->secteur;
        $versements = VersementChef::with('verseur')
            ->where('verseur', Auth::id())
            ->orderBy('created_at', 'desc')
         
            ->get();
        if ($secteur == 'administration') {
            $versements = VersementChef::orderBy('created_at', 'desc')
                ->get();
        }

        $total_non_valide = $versements->where('status', 0)->sum('montant');
        $total_valide = $versements->where('status', 1)->sum('montant');

        return view('versements.index', compact('versements', 'total_non_valide', 'total_valide', 'nom','role'));
    }

    
public function create()
{
    $role = auth()->user()->role;
    $nom = auth()->user()->name;
    
    // Récupérer tous les utilisateurs pour le sélecteur
    $users = User::select('id', 'name', 'role', 'secteur')
    ->where('secteur', 'vente')
    ->orWhere('secteur', 'glace')
    ->orWhere('role', 'caissiere')
    ->orderBy('name')
    ->get();

    // Get versements for display (tous les versements ou selon les droits)
    $versements = VersementChef::with('verseur')
        ->orderBy('created_at', 'desc')
        ->get();

    // Calculate totals
    $total_non_valide = $versements->where('status', 0)->sum('montant');
    $total_valide = $versements->where('status', 1)->sum('montant');

    return view('versements.create2', compact(
        'versements',
        'total_non_valide',
        'total_valide',
        'nom',
        'role',
        'users'
    ));
}

public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'verseur_id' => 'required|exists:users,id', // Nouveau champ pour l'utilisateur sélectionné
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'pin' => 'required|string',
        ]);

        $currentUser = Auth::user();
        
        // Vérification du PIN de l'utilisateur connecté
        $result = $this->pinService->verifyPin($currentUser->id, $request->pin);
        if (!$result) {
            return redirect()->back()->with('error', 'Le code pin est incorrect');
        }
        
        Log::info("PIN vérifié avec succès");

        // Récupérer l'utilisateur sélectionné comme verseur
        $verseur = User::findOrFail($validated['verseur_id']);

        // S'assurer que le montant est positif
        $montant = abs($validated['montant']);
        
        // Créer le nouveau versement
        $versement = new VersementChef();
        $versement->verseur = $validated['verseur_id']; // Utiliser l'ID de l'utilisateur sélectionné
        $versement->libelle = $validated['libelle'];
        $versement->montant = $montant;
        $versement->status = 0; // En attente par défaut
        $versement->date = $validated['date'];
        $versement->save();
        
        Log::info("Versement créé avec succès", ['versement_id' => $versement->id, 'verseur' => $verseur->name]);

        // Vérification pour chef de production
        if ($verseur->role == 'chef_production') {
            try {
                $soldecp = SoldeCP::first();
                if ($soldecp && $soldecp->montant != $versement->montant) {
                    $dg = User::where('role', 'dg')->first();
                    if ($dg) {
                        Log::info("Envoi de notification au DG pour incohérence de versement");
                        $notificationRequest = new Request([
                            'recipient_id' => $dg->id,
                            'subject' => 'Incohérence de versement',
                            'message' => 'Veuillez consulter la fonctionnalité << CP Expense Control >> pour vérifier l\'incohérence de versement du chef de production ' . $verseur->name . '. Le solde CP est de ' . $soldecp->montant . ' FCFA, mais le versement est de ' . $montant . ' FCFA.'
                        ]);
                        $this->notificationController->send($notificationRequest);
                        Log::info("Notification envoyée au DG pour incohérence de versement");
                    } else {
                        Log::warning("Aucun DG trouvé pour envoyer la notification");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Erreur lors de la vérification du solde CP", ['error' => $e->getMessage()]);
            }
        }

        // Notification pour les ventes
        if ($verseur->secteur == 'vente') {
            try {
                $dg = User::where('role', 'dg')->first();
                if ($dg) {
                    $notificationRequest = new Request([
                        'recipient_id' => $dg->id,
                        'subject' => 'Fermeture des sessions de ventes',
                        'message' => 'Veuillez consulter la fonctionnalité session de vente pour fermer la session de vente des vendeuses afin d\'évaluer le travail aujourd\'hui et de calculer leur manquant'
                    ]);
                    
                    $this->notificationController->send($notificationRequest);
                    Log::info("Notification envoyée au DG pour fermeture des sessions");
                } else {
                    Log::warning("Aucun DG trouvé pour envoyer la notification");
                }
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi de la notification", ['error' => $e->getMessage()]);
            }
        }

        // Historiser l'action
        try {
            $this->historiser("Un versement de {$montant} FCFA a été créé pour {$verseur->name} par {$currentUser->name}", 'create_versement');
            Log::info("Action historisée avec succès");
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'historisation", ['error' => $e->getMessage()]);
        }

        Log::info("Versement enregistré avec succès, redirection en cours");
        
        return redirect()->route('versements.index')
            ->with('success', 'Versement enregistré avec succès pour ' . $verseur->name);
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error("Erreur de validation", ['errors' => $e->errors()]);
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        Log::error("Erreur lors de l'enregistrement du versement", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Une erreur est survenue lors de l\'enregistrement du versement. Veuillez réessayer.')
            ->withInput();
    }
}

    public function edit(VersementChef $versement)
    {
        if ($versement->status == 1) {
            return redirect()->back()
                ->with('error', 'Impossible de modifier un versement validé');
        }

    

        return view('versements.edit', compact('versement'));
    }

    public function update(Request $request, VersementChef $versement)
    {
        if ($versement->status == 1) {
            return redirect()->back()
                ->with('error', 'Impossible de modifier un versement validé');
        }

        
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0.01'
        ]);

        // Ensure amount is positive
        $montant = abs($validated['montant']);
        $validated['montant'] = $montant;

        // Store old value for history
        $oldMontant = $versement->montant;

        // Update versement
        $versement->update($validated);

        // Log the action
        $user = Auth::user();
        $this->historiser("Le versement #{$versement->id} a été modifié par {$user->name} (Ancien montant: {$oldMontant}, Nouveau montant: {$montant})", 'update_versement');

        return redirect()->route('versements.index')
            ->with('success', 'Versement mis à jour avec succès');
    }

    public function destroy(VersementChef $versement)
    {
        if ($versement->status == 1) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer un versement validé');
        }

        if ($versement->verseur !== Auth::id() && Auth::user()->role!='dg') {
            return redirect()->back()
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer ce versement');
        }
        $verseurId = $versement->verseur;
        $versement2 = $versement;
        $versement->delete();

        $notificationRequest = new Request([
            'recipient_id' => $verseurId,
            'subject' => 'Versement rejeté',
            'message' => "Votre versement de {$versement->montant} FCFA a été rejeté par la direction. Veuillez contacter le DG pour plus d'informations."
        ]);
        $this->notificationController->send($notificationRequest);
    
        // Log the action
        $user = Auth::user();
        $this->historiser("Le versement #{$versement2->id} de {$versement2->montant} FCFA a été supprimé/rejeté par {$user->name}", 'reject_versement');
        return redirect()->back()
            ->with('success', 'Versement supprimé avec succès');
    }

    // Pour le DG
    public function validation()
    {
        $versements = VersementChef::with('verseur')
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $total_en_attente = $versements->sum('montant');

        return view('versements.validation', compact('versements', 'total_en_attente'));
    }

    public function valider(VersementChef $versement)
    {
        // Ajouter des logs au début pour le débogage
        \Log::info('Début validation du versement', [
            'versement_id' => $versement->id,
            'montant' => $versement->montant,
            'status' => $versement->status
        ]);
        
        $user = Auth::user();
        
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            \Log::error('Utilisateur non authentifié');
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour effectuer cette action');
        }
        
        \Log::info('Utilisateur authentifié', ['user_id' => $user->id, 'user_name' => $user->name]);
        
        // Vérifier si le versement est déjà validé
        if ($versement->status == 1) {
            \Log::warning('Tentative de validation d\'un versement déjà validé', ['versement_id' => $versement->id]);
            return redirect()->route('versements.validation')
                ->with('error', 'Ce versement a déjà été validé');
        }
        
        // Récupérer le montant du versement
        $montant = $versement->montant;
        \Log::info('Montant du versement', ['montant' => $montant]);
        
        try {
            // Récupérer l'utilisateur qui a fait le versement (correction variable $verseur)
            $verseur = User::findOrFail($versement->verseur);
            \Log::info('Détails du verseur', [
                'verseur_id' => $verseur->id,
                'verseur_name' => $verseur->name,
                'verseur_role' => $verseur->role,
                'verseur_secteur' => $verseur->secteur ?? 'non défini'
            ]);
            
            // Marquer le versement comme validé
            $versement->status = 1;
            $versement->save();
            \Log::info('Versement marqué comme validé', ['versement_id' => $versement->id]);
            
            // Si c'est un chef de production, remettre le solde CP à zéro
            if ($verseur->role == 'chef_production') {
                try {
                    $soldeCp = SoldeCp::first();
                    if ($soldeCp) {
                        \Log::info('Solde CP avant remise à zéro', ['solde' => $soldeCp->montant]);
                        
                        // Reset CP balance to 0
                        $soldeCp->montant = 0;
                        $soldeCp->derniere_mise_a_jour = now();
                        $soldeCp->description = "Solde remis à zéro après validation du versement #{$versement->id}";
                        $soldeCp->save();
                        
                        \Log::info('Solde CP remis à zéro avec succès');
                    } else {
                        \Log::error('Aucun enregistrement trouvé dans la table SoldeCp');
                    }
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de la mise à jour du solde CP', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            try {
                // Notification pour définir les objectifs
                \Log::info('Tentative de notification pour définition des objectifs');
                $this->notifierDefinitionObjectifs($versement->verseur);
                \Log::info('Notification envoyée avec succès');
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la notification pour définition des objectifs', [
                    'error' => $e->getMessage()
                ]);
            }
            
            // Historiser l'action
            try {
                \Log::info('Enregistrement de l\'historique');
                $this->historiser("Le versement #{$versement->id} de {$montant} a été validé par {$user->name} et le solde CP a été remis à zéro", 'validate');
                \Log::info('Historique enregistré avec succès');
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'enregistrement de l\'historique', [
                    'error' => $e->getMessage()
                ]);
            }
            
            \Log::info('Validation du versement terminée avec succès');
            return redirect()->back()
                ->with('success', 'Versement validé avec succès');
                
        } catch (\Exception $e) {
            \Log::error('Erreur critique lors de la validation du versement', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la validation du versement : ' . $e->getMessage());
        }
    }
    
    private function comparerEtNotifierDifference($soldeCp, $montant, $versementId)
    {
        // Compare the versement amount with the CP balance
        $difference = abs($soldeCp->montant - $montant);
        $tolerance = 0.01; // Small tolerance for floating point comparison

        if ($difference > $tolerance) {
            // There's a discrepancy
            if ($montant > $soldeCp->montant) {
                $message = "Le montant du versement #{$versementId} ({$montant}) est supérieur au solde CP ({$soldeCp->montant}). Possible erreur de saisie.";
            } else {
                $message = "Le montant du versement #{$versementId} ({$montant}) est inférieur au solde CP ({$soldeCp->montant}). Possible détournement de fonds.";
            }

            // Send alert to DG
            $signalementRequest = new Request([
                'message' => $message,
                'category' => 'report'
            ]);
            $this->messageController->store_message($signalementRequest);

            // Send notification to CP
            $cpId = Auth::id();
            $notificationRequest = new Request([
                'recipient_id' => $cpId,
                'subject' => 'Anomalie détectée dans votre versement',
                'message' => "Nous avons détecté une différence entre le montant de votre versement #{$versementId} ({$montant}) et le solde CP actuel ({$soldeCp->montant}). Veuillez vérifier et contacter la direction si nécessaire."
            ]);
            $this->notificationController->send($notificationRequest);

            // Log the anomaly
            $this->historiser($message, 'anomaly');
        }
    }

    /**
     * Notify CP to define objectives for the next day
     */
    private function notifierDefinitionObjectifs($cpId)
    {
        $notificationRequest = new Request([
            'recipient_id' => $cpId,
            'subject' => 'Définition des objectifs pour la prochaine journée',
            'message' => "Votre versement a été validé et le solde CP a été remis à zéro. Veuillez définir les objectifs et les attentes pour la prochaine journée."
        ]);
        $this->notificationController->send($notificationRequest);
    }

    public function visualisation(Request $request)
{
    // Paramètres de filtrage et tri
    $dateDebut = $request->input('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $dateFin = $request->input('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
    $verseur = $request->input('verseur');
    $status = $request->input('status');
    $orderBy = $request->input('order_by', 'date');
    $orderDirection = $request->input('order_direction', 'desc');
    
    // Construction de la requête avec le rôle du verseur
    $query = DB::table('Versement_chef')
        ->join('users', 'Versement_chef.verseur', '=', 'users.id')
        ->select(
            'Versement_chef.*',
            'users.name as verseur_name',
            'users.email as verseur_email',
            'users.role as verseur_role'  // Ajout du rôle
        )
        ->whereBetween('Versement_chef.date', [$dateDebut, $dateFin]);
    
    // Filtres optionnels
    if ($verseur) {
        $query->where('Versement_chef.verseur', $verseur);
    }
    
    if ($status !== null && $status !== '') {
        $query->where('Versement_chef.status', $status);
    }
    
    // Tri
    switch ($orderBy) {
        case 'montant':
            $query->orderBy('Versement_chef.montant', $orderDirection);
            break;
        case 'verseur':
            $query->orderBy('users.name', $orderDirection);
            break;
        case 'role':  // Nouveau tri par rôle
            $query->orderBy('users.role', $orderDirection);
            break;
        case 'status':
            $query->orderBy('Versement_chef.status', $orderDirection);
            break;
        default:
            $query->orderBy('Versement_chef.date', $orderDirection);
    }
    
    // Récupération des versements
    $versements = $query->get();
    
    // Liste des employés pour le filtre
    $employes = DB::table('users')
        ->join('Versement_chef', 'users.id', '=', 'Versement_chef.verseur')
        ->select('users.id', 'users.name')
        ->distinct()
        ->orderBy('users.name')
        ->get();
    
    // Statistiques globales
    $statistiques = $this->getStatistiquesVersements($dateDebut, $dateFin, $verseur, $status);
    
    return view('versements.visualisation', compact(
        'versements',
        'employes',
        'statistiques',
        'dateDebut',
        'dateFin',
        'verseur',
        'status',
        'orderBy',
        'orderDirection'
    ));
}
    
    private function getStatistiquesVersements($dateDebut, $dateFin, $verseur = null, $status = null)
    {
        $query = DB::table('Versement_chef')
            ->whereBetween('date', [$dateDebut, $dateFin]);
        
        if ($verseur) {
            $query->where('verseur', $verseur);
        }
        
        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }
        
        // Statistiques générales
        $totalVersements = $query->count();
        $montantTotal = $query->sum('montant');
        $montantMoyen = $totalVersements > 0 ? $montantTotal / $totalVersements : 0;
        
        // Statistiques par statut
        $versementsEnAttente = (clone $query)->where('status', 0)->count();
        $versementsValides = (clone $query)->where('status', 1)->count();
        $montantEnAttente = (clone $query)->where('status', 0)->sum('montant');
        $montantValide = (clone $query)->where('status', 1)->sum('montant');
        
        // Top 5 des verseurs
        $topVerseurs = DB::table('Versement_chef')
            ->join('users', 'Versement_chef.verseur', '=', 'users.id')
            ->select(
                'users.name',
                DB::raw('COUNT(*) as nombre_versements'),
                DB::raw('SUM(montant) as montant_total')
            )
            ->whereBetween('Versement_chef.date', [$dateDebut, $dateFin]);
            
        if ($status !== null && $status !== '') {
            $topVerseurs->where('Versement_chef.status', $status);
        }
        
        $topVerseurs = $topVerseurs
            ->groupBy('users.id', 'users.name')
            ->orderBy('montant_total', 'desc')
            ->limit(5)
            ->get();
        
        // Évolution mensuelle (derniers 6 mois)
        $evolutionMensuelle = DB::table('Versement_chef')
            ->select(
                DB::raw('YEAR(date) as annee'),
                DB::raw('MONTH(date) as mois'),
                DB::raw('COUNT(*) as nombre'),
                DB::raw('SUM(montant) as montant')
            )
            ->where('date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->get();
        
        return [
            'total_versements' => $totalVersements,
            'montant_total' => $montantTotal,
            'montant_moyen' => $montantMoyen,
            'versements_en_attente' => $versementsEnAttente,
            'versements_valides' => $versementsValides,
            'montant_en_attente' => $montantEnAttente,
            'montant_valide' => $montantValide,
            'top_verseurs' => $topVerseurs,
            'evolution_mensuelle' => $evolutionMensuelle
        ];
    }
    
    public function export(Request $request)
    {
        $dateDebut = $request->input('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $verseur = $request->input('verseur');
        $status = $request->input('status');
        
        // Logique d'export CSV/Excel
        return response()->json([
            'message' => 'Export des versements',
            'periode' => "$dateDebut - $dateFin"
        ]);
    }
}
