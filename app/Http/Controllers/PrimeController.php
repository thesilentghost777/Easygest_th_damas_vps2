<?php

namespace App\Http\Controllers;

use App\Models\Prime;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HistorisableActions;
use Carbon\Carbon;
use App\Http\Controllers\NotificationController;
use App\Models\UserPin;
use App\Services\PinService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PrimeController extends Controller
{
    use HistorisableActions;
    protected $pinService;

    public function __construct(NotificationController $notificationController, PinService $pinService)
    {
        $this->pinService = $pinService;
        $this->notificationController = $notificationController;
    }

    public function index()
    {
        $employe = auth()->user();
        $primes = Prime::where('id_employe', $employe->id)
                      ->orderBy('created_at', 'desc')
                      ->get();

        $totalPrimes = $primes->sum('montant');
        $hasPrimes = $primes->count() > 0;
        $flag = UserPin::where('user_id', Auth::user()->id)->first();

        return view('pages.mes_primes', compact('flag','primes', 'totalPrimes', 'hasPrimes'));
    }

    public function create()
{
    // Récupérer les employés qui n'ont pas de prime ET qui ne sont pas DG/PDG/DDG
    $employes = User::whereNotIn('role', ['dg', 'pdg', 'ddg'])
        ->whereDoesntHave('primes') // Exclut les utilisateurs qui ont déjà des primes
        ->orderBy('name')
        ->get();

    // Récupérer toutes les primes avec les informations des employés
    $primes = Prime::with('user')
        ->where('montant','>', 0) // Exclut les primes avec un montant de 0
        ->where('id_employe', '!=', Auth::user()->id) // Exclut la prime de l'utilisateur connecté
        ->whereHas('user', function ($query) {
            $query->where('secteur', '!=', 'administration');
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $flag = UserPin::where('user_id', Auth::user()->id)->first();
    
    return view('pages.dg.attribution-prime', compact('flag', 'employes', 'primes'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_employe' => 'required|exists:users,id',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'pin' => 'required|string',
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

        $user = auth()->user();
        $cible = User::find($validated['id_employe']);
        $date = Carbon::now();

        // On cherche si l'employé a déjà une prime
        $existingPrime = Prime::where('id_employe', $validated['id_employe'])->first();

        if ($existingPrime) {
            // Si une prime existe déjà, on la met à jour
            $existingPrime->update([
                'libelle' => $validated['libelle'],
                'montant' => $validated['montant']
            ]);

            // On envoie une notification à l'utilisateur concerné
            $request->merge([
                'recipient_id' => $cible->id,
                'subject' => 'Prime mise à jour',
                'message' => 'Felicitations ! Vous avez reçu une prime de ' . $validated['montant'] . ' pour ' . $validated['libelle'],
            ]);

            // Appel de la méthode send
            $this->notificationController->send($request);
            $this->historiser("L'utilisateur {$user->name} a mis à jour une prime pour {$cible->name} le {$date}", 'update_prime');

            return redirect()->back()->with('success', 'Prime mise à jour avec succès');
        } else {
            // Si pas de prime existante, on en crée une nouvelle
            Prime::create($validated);

            $this->historiser("L'utilisateur {$user->name} a créé une prime pour {$cible->name} le {$date}", 'create_prime');

            // On envoie une notification à l'utilisateur concerné
            $request->merge([
                'recipient_id' => $cible->id,
                'subject' => 'Prime attribuée',
                'message' => 'Felicitations ! Vous avez reçu une prime de '. $validated['libelle'],
            ]);

            // Appel de la méthode send
            $this->notificationController->send($request);

            return redirect()->back()->with('success', 'Prime attribuée avec succès');
        }
    }

    public function edit($id)
    {
        $prime = Prime::with('user')->findOrFail($id);
        $employes = User::whereNotIn('role', ['dg', 'pdg', 'ddg'])
            ->orderBy('name')
            ->get();
        $flag = UserPin::where('user_id', Auth::user()->id)->first();

        return view('pages.dg.edit-prime', compact('prime', 'employes', 'flag'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_employe' => 'required|exists:users,id',
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
            'pin' => 'required|string',
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
                $flag->flag = true;
                $flag->save();
            }
        }

        $prime = Prime::findOrFail($id);
        $oldCible = $prime->user;
        $newCible = User::find($validated['id_employe']);
        $date = Carbon::now();

        $prime->update($validated);

        // Notification à l'ancien employé si changement d'employé
        if ($oldCible->id != $newCible->id) {
            $request->merge([
                'recipient_id' => $oldCible->id,
                'subject' => 'Prime retirée',
                'message' => 'Votre prime "' . $prime->libelle . '" a été retirée de votre profil.',
            ]);
            $this->notificationController->send($request);
        }

        // Notification au nouvel employé
        $request->merge([
            'recipient_id' => $newCible->id,
            'subject' => 'Prime mise à jour',
            'message' => 'Felicitations ! Votre prime a été mise à jour : ' . $validated['montant'] . ' FCFA pour ' . $validated['libelle'],
        ]);
        $this->notificationController->send($request);

        $this->historiser("L'utilisateur {$user->name} a modifié une prime pour {$newCible->name} le {$date}", 'update_prime');

        return redirect()->route('primes.create')->with('success', 'Prime modifiée avec succès');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'pin' => 'required|string',
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
                $flag->flag = true;
                $flag->save();
            }
        }

        $prime = Prime::with('user')->findOrFail($id);
        $cible = $prime->user;
        $date = Carbon::now();

        // Notification à l'employé
        $request->merge([
            'recipient_id' => $cible->id,
            'subject' => 'Prime supprimée',
            'message' => 'Votre prime "' . $prime->libelle . '" d\'un montant de ' . number_format($prime->montant, 0, ',', ' ') . ' FCFA a été supprimée.',
        ]);
        $this->notificationController->send($request);

        $this->historiser("L'utilisateur {$user->name} a supprimé une prime de {$cible->name} le {$date}", 'delete_prime');

        $prime->delete();

        return redirect()->back()->with('success', 'Prime supprimée avec succès');
    }
}