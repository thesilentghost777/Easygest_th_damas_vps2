<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Models\Extra;
use App\Models\Salaire;
use App\Models\ACouper;
use App\Models\Complexe;
use App\Models\Horaire;
use App\Models\EmployeeRation;
use App\Models\Ration;
use App\Models\FirstConfigEmployee;
use App\Models\ManquantTemporaire;
use App\Traits\HistorisableActions;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\NotificationController;

class RegisteredUserController extends Controller
{
    use HistorisableActions;
    protected $notificationController;
    public function __construct(NotificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // Validation des données d'entrée
    try {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'date_naissance' => ['required', 'date', 'before:today'],
            'code_secret' => ['required', 'integer'],
            'secteur' => ['required', 'string'],
            'role' => ['required', 'string'],
            'num_tel' => ['required', 'regex:/^6[0-9]{8}$/'],
            'annee_debut_service' => ['required', 'integer', 'min:1950', 'max:' . date('Y')],
            'accept_terms' => ['required', 'accepted']
        ], [
            // Messages d'erreur personnalisés
            'name.required' => 'Le nom est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'code_secret.required' => 'Le code secret est obligatoire.',
            'code_secret.integer' => 'Le code secret doit être un nombre.',
            'secteur.required' => 'Le département est obligatoire.',
            'role.required' => 'Le rôle est obligatoire.',
            'num_tel.required' => 'Le numéro de téléphone est obligatoire.',
            'num_tel.regex' => 'Le numéro de téléphone doit commencer par 6 et contenir 9 chiffres.',
            'annee_debut_service.required' => 'L\'année de début de service est obligatoire.',
            'annee_debut_service.min' => 'L\'année de début de service ne peut pas être antérieure à 1950.',
            'annee_debut_service.max' => 'L\'année de début de service ne peut pas être supérieure à l\'année courante.',
            'accept_terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'accept_terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.'
        ]);
    } catch (ValidationException $e) {
        // Retourner avec les erreurs de validation et les anciennes valeurs
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput($request->except(['password', 'password_confirmation']))
            ->with('validation_failed', true);
    }

    try {
        DB::beginTransaction();

        // Validation des données d'entrée supplémentaire
        if (!$this->validateInputData($request)) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['message' => 'Données d\'entrée invalides. Veuillez vérifier vos informations.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Vérification supplémentaire de l'unicité de l'email
        if (User::where('email', $request->email)->exists()) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['email' => 'Cette adresse email est déjà utilisée par un autre utilisateur.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Vérification du numéro de téléphone
        if (User::where('num_tel', $this->formatPhoneNumber($request->num_tel))->exists()) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['num_tel' => 'Ce numéro de téléphone est déjà utilisé.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Création de l'utilisateur
        $user = new User();
        $user->name = trim($request->name);
        $user->email = strtolower(trim($request->email));
        $user->password = Hash::make($request->password);
        $user->date_naissance = $request->date_naissance;
        $user->code_secret = $request->code_secret;
        $user->secteur = $request->secteur;
        $user->role = $request->role;
        $user->num_tel = $this->formatPhoneNumber($request->num_tel);
        $user->annee_debut_service = $request->annee_debut_service;
        $user->created_at = now();

        if (!$user->save()) {
            DB::rollBack();
            Log::error('Échec de la sauvegarde de l\'utilisateur', [
                'email' => $request->email,
                'name' => $request->name
            ]);
            return redirect()->back()
                ->withErrors(['message' => 'Erreur lors de la création de l\'utilisateur. Veuillez réessayer.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Traitement spécial pour le DG
        if ($user->role === 'dg') {
            try {
                $this->historiser("L'utilisateur {$user->name} a été créé avec succès", 'create');
                event(new Registered($user));
                Auth::login($user);
                DB::commit();
                
                return redirect(route('dashboard'))
                    ->with('success', 'Inscription réussie ! Bienvenue sur votre tableau de bord.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur lors du traitement du DG: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                return redirect()->back()
                    ->withErrors(['message' => 'Erreur lors de la création du compte directeur général. Veuillez contacter l\'administrateur.'])
                    ->withInput($request->except(['password', 'password_confirmation']));
            }
        }

        // Validation des réglementations employé
        $validationResult = $this->validateEmployeeRegulations($user);
        if (!$validationResult['valid']) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['message' => $validationResult['message']])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Création des entrées par défaut avec gestion d'erreurs
        try {
            $this->createUserDefaults($user);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création des données par défaut: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            return redirect()->back()
                ->withErrors(['message' => 'Erreur lors de l\'initialisation du compte. Veuillez réessayer.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Historisation
        try {
            $this->historiser("L'utilisateur {$user->name} a été créé avec succès", 'create');
        } catch (\Exception $e) {
            Log::warning('Erreur lors de l\'historisation: ' . $e->getMessage());
            // Ne pas faire échouer le processus pour l'historisation
        }

        // Déclencher l'événement Registered
        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::warning('Erreur lors du déclenchement de l\'événement Registered: ' . $e->getMessage());
            // Ne pas faire échouer le processus pour l'événement
        }

        // Connexion de l'utilisateur
        try {
            Auth::login($user);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la connexion automatique: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            return redirect()->back()
                ->withErrors(['message' => 'Compte créé mais erreur de connexion. Veuillez vous connecter manuellement.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Commit de la transaction principale
        DB::commit();
        
        // Création de FirstConfigEmployee après commit (non critique)
        try {
            FirstConfigEmployee::create([
                'user_id' => $user->id,
                'status' => false,
            ]);
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la création de FirstConfigEmployee: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            // Ne pas faire échouer tout le processus pour cette étape non critique
        }

        // Envoi des notifications (non critique)
        try {
            $this->sendAllNotifications($user);
        } catch (\Exception $e) {
            Log::warning('Erreur lors de l\'envoi des notifications: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            // Ne pas faire échouer le processus pour les notifications
        }

        // Redirection avec message de succès
        return redirect(route('dashboard'))
            ->with('success', 'Inscription réussie ! Bienvenue sur votre tableau de bord.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur générale lors du traitement du nouvel employé: ' . $e->getMessage(), [
            'request_data' => $request->except(['password', 'password_confirmation']),
            'stack_trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->withErrors(['message' => 'Une erreur inattendue est survenue lors de la création de votre compte. Veuillez réessayer ou contacter l\'administrateur si le problème persiste.'])
            ->withInput($request->except(['password', 'password_confirmation']));
    }
}
        /**
         * Valide les données d'entrée
         */
        private function validateInputData($request): bool
        {
            $requiredFields = ['name', 'email', 'password', 'date_naissance', 'secteur', 'role'];
            
            foreach ($requiredFields as $field) {
                if (empty($request->$field)) {
                    Log::warning("Champ requis manquant: {$field}");
                    return false;
                }
            }
        
            // Validation de l'email
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                Log::warning("Format d'email invalide: {$request->email}");
                return false;
            }
        
            // Validation de la date de naissance
            try {
                $birthDate = Carbon::parse($request->date_naissance);
                if ($birthDate->isFuture()) {
                    Log::warning("Date de naissance dans le futur: {$request->date_naissance}");
                    return false;
                }
                if ($birthDate->diffInYears(now()) > 100) {
                    Log::warning("Âge invalide: {$request->date_naissance}");
                    return false;
                }
            } catch (\Exception $e) {
                Log::warning("Date de naissance invalide: {$request->date_naissance}");
                return false;
            }
        
            return true;
        }
        
        /**
         * Formate le numéro de téléphone
         */
        private function formatPhoneNumber($phoneNumber): ?string
        {
            if (empty($phoneNumber)) {
                return null;
            }
            
            // Supprimer tous les caractères non numériques
            $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            return $cleaned ?: null;
        }
        
        /**
         * Validation robuste des réglementations employé
         */
        private function validateEmployeeRegulations(User $user): array
        {
            try {
                $extra = Extra::where('secteur', $user->secteur)->first();
        
                if (!$extra) {
                    Log::warning("Pas de règles trouvées pour le secteur: {$user->secteur}");
                    return [
                        'valid' => true,
                        'message' => "Aucune réglementation trouvée pour le secteur {$user->secteur}"
                    ];
                }
        
                // Vérification de l'âge
                try {
                    $birthDate = Carbon::parse($user->date_naissance);
                    $age = $birthDate->age;
                    
                    if ($age < $extra->age_adequat) {
                        Log::warning("Employé {$user->name} n'a pas l'âge requis ({$age} ans, requis: {$extra->age_adequat})");
                        $this->sendAgeNotification($user);
                        return [
                            'valid' => false,
                            'message' => "L'âge minimum requis pour ce secteur est de {$extra->age_adequat} ans"
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error("Erreur lors de la validation de l'âge: " . $e->getMessage());
                    return [
                        'valid' => false,
                        'message' => "Erreur lors de la validation de l'âge"
                    ];
                }
        
                return ['valid' => true, 'message' => ''];
                
            } catch (\Exception $e) {
                Log::error("Erreur lors de la validation des réglementations: " . $e->getMessage());
                return [
                    'valid' => false,
                    'message' => "Erreur lors de la validation des réglementations employé"
                ];
            }
        }
        
        /**
         * Crée toutes les entrées par défaut pour l'utilisateur
         */
        private function createUserDefaults(User $user): void
        {
            $errors = [];
        
            try {
                $this->createDefaultSalary($user);
            } catch (\Exception $e) {
                $errors[] = 'salaire';
                Log::error("Erreur création salaire par défaut: " . $e->getMessage());
            }
        
            try {
                $this->createAcouperEntry($user);
            } catch (\Exception $e) {
                $errors[] = 'à couper';
                Log::error("Erreur création entrée à couper: " . $e->getMessage());
            }
        
        
            try {
                $this->createDefaultRation($user);
            } catch (\Exception $e) {
                $errors[] = 'ration';
                Log::error("Erreur création ration par défaut: " . $e->getMessage());
            }
        
            try {
                $this->createManquantTemporaireEntry($user);
            } catch (\Exception $e) {
                $errors[] = 'manquant temporaire';
                Log::error("Erreur création manquant temporaire: " . $e->getMessage());
            }
        
            if (!empty($errors)) {
                Log::warning("Erreurs lors de la création des défauts pour {$user->name}: " . implode(', ', $errors));
                // Vous pourriez choisir de faire échouer la transaction ici selon vos besoins
                // throw new \Exception("Erreur lors de la création des paramètres par défaut");
            }
        }
        
        /**
         * Création robuste du salaire par défaut
         */
        private function createDefaultSalary(User $user): void
        {
            $extra = Extra::where('secteur', $user->secteur)->first();
            
            if (!$extra) {
                Log::warning("Pas de secteur trouvé pour le salaire de {$user->name}");
                return;
            }
        
            if (!isset($extra->salaire_adequat) || $extra->salaire_adequat <= 0) {
                Log::warning("Salaire adéquat invalide pour le secteur {$user->secteur}");
                return;
            }
        
            Salaire::create([
                'id_employe' => $user->id,
                'somme' => $extra->salaire_adequat,
                'mois_salaire' => now()
            ]);
        }
        
        /**
         * Création robuste de l'entrée à couper
         */
        private function createAcouperEntry(User $user): void
        {
            $complexe = Complexe::first();
        
            ACouper::create([
                'id_employe' => $user->id,
                'caisse_sociale' => $complexe->caisse_sociale ?? 0,
                'manquants' => 0,
                'remboursement' => 0,
                'pret' => 0,
                'date' => now()
            ]);
        }
        
      
        
        /**
         * Création robuste de la ration par défaut
         */
        private function createDefaultRation(User $user): void
        {
            $defaultRation = Ration::first();
        
            if (!$defaultRation) {
                Log::warning("Aucune ration par défaut trouvée");
                return;
            }
        
            if (!isset($defaultRation->montant_defaut) || $defaultRation->montant_defaut < 0) {
                Log::warning("Montant de ration par défaut invalide");
                return;
            }
        
            EmployeeRation::create([
                'employee_id' => $user->id,
                'montant' => $defaultRation->montant_defaut,
                'personnalise' => false
            ]);
        }
    
        /**
         * Envoi sécurisé de la notification d'âge
         */
     
    private function sendAllNotifications(User $user)
    {

        // 2. Notification pour le jour de repos
        $this->sendNotifications($user);

        // 3. Notification pour les chefs de production
        $this->sendCPNotification($user);

        // 4. Notification pour le DG
        $this->sendDGNotification($user);

    }

    private function sendCPNotification(User $user)
    {
        $chefProductions = User::where('role', 'chef_production')->get();

        foreach ($chefProductions as $cp) {
            $request = new Request();
            $request->merge([
                'recipient_id' => $cp->id,
                'subject' => 'Nouvel employé - Actions requises',
                'message' => "Un nouvel employé ({$user->name}) a été ajouté. Veuillez remplir les informations relatives à son avance sur salaire et autres données nécessaires pour sa fiche de paie."
            ]);
            $this->notificationController->send($request);
        }
    }

    private function sendDGNotification(User $user)
    {
        $dg = User::where('role', 'dg')->first();
        if ($dg) {
            $request = new Request();
            $request->merge([
                'recipient_id' => $dg->id,
                'subject' => 'Nouvel employé - Prêt à définir',
                'message' => "Un nouvel employé ({$user->name}) a été ajouté. Veuillez définir s'il dispose d'un prêt à enregistrer dans l'application."
            ]);
            $this->notificationController->send($request);
        }
    }

    private function sendNotifications(User $user)
    {
        $salaire = Salaire::where('id_employe', $user->id)->first();
        $ration = EmployeeRation::where('employee_id', $user->id)->first();

        $message = "Bonjour,\n\n";
        $message .= "Votre salaire a été fixé.Consultez le via l'app et Veuillez vous rapprocher de la direction générale pour toute modification éventuelle.\n\n";
        $message .= "Votre jour de repos hebdomadaire n'a pas encore été assigné. Veuillez vous rapprocher du chef de production pour le définir.\n\n";
        $message .= "Votre ration a été fixée.Consultez le via l'app et Veuillez vous rapprocher de la direction générale pour toute modification éventuelle.\n\n";
        $message .= "Cordialement,\nL'administration.";

        $request = new Request();
        $request->merge([
            'recipient_id' => $user->id,
            'subject' => 'Informations importantes : Salaire, Jour de repos et Ration',
            'message' => $message
    ]);

        $this->notificationController->send($request);

    }

    private function sendAgeNotification(User $user)
    {
        $request = new Request();
        $request->merge([
            'recipient_id' => $user->id,
            'subject' => 'Echec lors de l\'inscription',
            'message' => "Votre âge ne correspond pas aux réglementations de l'entreprise. Veuillez vous rapprocher de la direction générale pour plus d'informations."
        ]);
        $this->notificationController->send($request);
    }

    
    private function createManquantTemporaireEntry(User $user)
    {
        ManquantTemporaire::create([
            'employe_id' => $user->id,
            'montant' => 0,
            'explication' => null,
            'statut' => 'en_attente',
            'commentaire_dg' => null,
            'valide_par' => null,
        ]);
    }
}
