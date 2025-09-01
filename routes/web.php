
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProducteurController;
use App\Http\Controllers\DgController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DdgController;
use App\Http\Controllers\PdgController;
use App\Http\Controllers\Chef_productionController;
use App\Http\Controllers\ServeurController;
use App\Http\Controllers\AlimentationController;
use App\Http\Controllers\GlaceController;
use App\Http\Controllers\PointeurController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SalaireController;
use App\Http\Controllers\HoraireController;
use App\Http\Controllers\PrimeController;
use App\Http\Controllers\RecetteController;
use App\Http\Controllers\ReservationMpController;
use App\Http\Controllers\AssignationMatiereController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VersementController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\VersementChefController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ReposCongeController;
use App\Http\Controllers\EmployeeRankingController;
use App\Http\Controllers\StatistiquesController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\DeliController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\QueryInterfaceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StagiaireController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SoldeController;
use App\Http\Controllers\EmployeePerformanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeProductionController;
use App\Http\Controllers\IncoherenceController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\BagAssignmentController;
use App\Http\Controllers\BagReceptionController;
use App\Http\Controllers\BagSaleController;
use App\Http\Controllers\BagDiscrepancyController;
use App\Http\Controllers\BagRecoveryController;
use App\Http\Controllers\RapportsController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\SoldeCPController;
use App\Http\Controllers\ManquantController;
use App\Http\Controllers\MatiereComplexeController;
use App\Http\Controllers\FactureComplexeController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\StagiaireStatisticsController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\MouvementStockController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\AvanceSalaireController;
use App\Http\Controllers\ProductGroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MissingCalculationController;
use App\Http\Controllers\CashDistributionController;
use App\Http\Controllers\AccountAccessController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RecipeCategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RationController;
use App\Http\Controllers\AvarieController;
use App\Http\Controllers\DamagedBagController;
use App\Http\Controllers\WorkspaceSwitcherController;
use App\Http\Controllers\TypeTauleController;
use App\Http\Controllers\TauleInutiliseeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PinConfigController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\AnalyseProduitController;
use App\Http\Controllers\MatiereRecommanderController;
use App\Http\Controllers\GaspillageController;
use App\Http\Controllers\MatiereUtilisationController;
use App\Http\Controllers\RapportMensuelController;
use App\Http\Controllers\SherlockAdvisorController;
use App\Http\Controllers\SherlockRecipeController;
use App\Http\Controllers\PaydayConfigController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductionSuggestionController;
use App\Http\Controllers\ProductionEditController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\QueryInterface2Controller;
use App\Http\Controllers\ProductionStatController;
use App\Http\Controllers\ManquantInventaireController;
use App\Http\Controllers\ManquantProduitController;
use App\Http\Controllers\MatiereNotificationController;
use App\Http\Controllers\MatiereRetourController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ErrorLogController;
use App\Http\Controllers\FluxProduitController;
use App\Http\Controllers\RepartiteurController;
use App\Http\Controllers\GestionSoldeController;
use App\Http\Controllers\ManquantFluxController;
use App\Http\Controllers\CommandeReductionController;
use App\Http\Controllers\ReceptionPointeurController;
use App\Http\Controllers\ReceptionVendeurController;
use App\Http\Controllers\CalculVentesController;
use App\Http\Controllers\Avarie2Controller;
use App\Http\Controllers\DepenseValidationController;
use App\Http\Controllers\BoulangerieController;

require __DIR__.'/auth.php';
require __DIR__.'/api.php';

/*
    |--------------------------------------------------------------------------
    | Routes accessibles à tous les utilisateurs non
    |--------------------------------------------------------------------------
    */
    Route::get('/test-error', function() {
    throw new Exception('Test erreur pour vérifier le système');
});

    Route::get('/offline', function () {
        return view('offline');
    });
    Route::get('/', function () {
        return view('index');
    })->name('index');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/destroy_idiot', [ProfileController::class, 'destroyidiot'])->name('destroy_idiot');
    Route::get('/problem', [DashboardController::class, 'problem'])->name('problem');
    Route::get('/about', [DashboardController::class, 'about'])->name('about');


Route::middleware(['auth', 'track_satistic'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Routes accessibles à tous les utilisateurs authentifiés
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/destroy_idiot', [ProfileController::class, 'destroyidiot'])->name('destroy_idiot');
    
    
    Route::get('/workspace', [DashboardController::class, 'redirectToWorkspace'])
        ->name('workspace.redirect');

        Route::get('/setup2', [PinConfigController::class, 'showSetupForm'])->name('setup2.create');
        Route::post('/setup2', [PinConfigController::class, 'processSetup'])->name('setup2.store');

    // Messages (accessible à tous)
    Route::get('message', [MessageController::class, 'message'])->name('message');
    Route::post('message/store_message', [MessageController::class, 'store_message'])->name('message-post');
   
    // Horaires (accessible à tous)
    Route::get('/horaire', [HoraireController::class, 'index'])->name('horaire.index');
    Route::post('/horaire/arrivee', [HoraireController::class, 'marquerArrivee'])->name('horaire.arrivee');
    Route::post('/horaire/depart', [HoraireController::class, 'marquerDepart'])->name('horaire.depart');
    Route::post('/horaire/enregistrer', [HoraireController::class, 'enregistrerHoraire'])->name('horaire.enregistrer');

    // Salaires - Routes employés (accessible à tous)
    Route::get('/reclamer-as', [SalaireController::class, 'reclamerAs'])->name('reclamer-as');
    Route::post('/store-demandes-as', [SalaireController::class, 'store_demandes_AS'])->name('store-demandes-as');
    Route::get('/voir-status', [SalaireController::class, 'voir_Status'])->name('voir-status');
    Route::get('/validation-retrait', [SalaireController::class, 'validation_retrait'])->name('validation-retrait');
    Route::post('/recup-retrait', [SalaireController::class, 'recup_retrait'])->name('recup-retrait');
    Route::get('/consulter_fp_employee', [SalaireController::class, 'consulter_fichePaie'])->name('consulterfp');
    Route::post('/salaires/{salaire}/demande-retrait', [SalaireController::class, 'demandeRetrait2'])->name('salaires.demande-retrait2');

    // Routes employés standard (accessible à tous)
    Route::get('/manquants', [MessageController::class, 'showManquants'])->name('manquant');
    Route::get('/mes-primes', [PrimeController::class, 'index'])->name('primes.index');
    Route::get('/mes-repos-conges', [ReposCongeController::class, 'show'])->name('repos-conges.employee');
    Route::get('/employe/reglementation', [ExtraController::class,'index2'])->name('extras.index2');
    Route::get('/mes-manquants', [ManquantController::class, 'mesManquants'])->name('manquant.view');
    Route::get('/mes-deductions', [ManquantController::class, 'mesDeductions'])->name('manquant.mes-deductions');
    Route::get('/solde', [SoldeController::class, 'index'])->name('solde');
    Route::get('/mes-assignations', [AssignationMatiereController::class, 'mesAssignations'])->name('assignations.mes-assignations');
    Route::get('/mon-planning', [PlanningController::class, 'monPlanning'])->name('planning.mon-planning');

    // Routes de prêts pour employés
    Route::prefix('loans')->group(function () {
        Route::get('/my-loans', [LoanController::class, 'employeeView'])->name('loans.my-loans');
        Route::post('/request', [LoanController::class, 'requestLoan'])->name('loans.request');
    });

    // Gestion de langue
    Route::get('/language', [LanguageController::class, 'index'])->name('language.index');
    Route::put('/language', [LanguageController::class, 'update'])->name('language.update');

    // Commutateur d'espace de travail
    Route::get('/workspace/switcher', [WorkspaceSwitcherController::class, 'index'])->name('workspace.switcher');
    Route::post('/workspace/switch', [WorkspaceSwitcherController::class, 'switchMode'])->name('workspace.switch');

    Route::prefix('account-access')->group(function () {
            Route::get('/', [AccountAccessController::class, 'index'])->name('account-access.index');
            Route::get('/{id}/access', [AccountAccessController::class, 'accessAccount'])->name('account-access.access');
            Route::get('/return', [AccountAccessController::class, 'returnToOriginal'])->name('account-access.return');
        });
    /*
    |--------------------------------------------------------------------------
    | Routes Développeur (Accès à tout)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:developper'])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::get('/stats', [AdminController::class, 'stats'])->name('stats');
            Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
            Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
            Route::post('/reset-today-stats', [AdminController::class, 'resetTodayStats'])->name('reset-today-stats');
        });
      
         Route::get('/errors', [ErrorLogController::class, 'index'])->name('errors.index');
        Route::get('/errors/{id}', [ErrorLogController::class, 'show'])->name('errors.show');
        Route::delete('/errors/clear', [ErrorLogController::class, 'clear'])->name('errors.clear');

        Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
        Route::post('/features/toggle-status', [FeatureController::class, 'toggleStatus'])->name('features.toggle-status');
        Route::get('/features/enable-all', [FeatureController::class, 'enableAll'])->name('features.enable-all');
        Route::get('/features/disable-category/{category}', [FeatureController::class, 'disableCategory'])->name('features.disable-category');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes PDG/DG/DDG (Accès complet)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:pdg,dg,ddg,developper'])->group(function () {
        Route::get('/pdg/dashboard', [PdgController::class, 'dashboard'])->name('pdg.workspace');
        Route::get('/dg/dashboard', [DgController::class,'dashboard'])->name('dg-dashboard');
        Route::get('/dashboard2', [DgController::class, 'index'])->name('dg.workspace');
        Route::get('/ddg/dashboard', [DdgController::class,'dashboard'])->name('ddg-dashboard');
        Route::get('lecture_message', [MessageController::class, 'lecture_message'])->name('lecture_message');
        Route::post('/messages/mark-read/{type}', [MessageController::class, 'markRead'])->name('messages.markRead');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    
        Route::prefix('depenses/validation')->name('depenses.validation.')->group(function () {
            Route::get('/', [DepenseValidationController::class, 'index'])->name('index');
            Route::post('/confirm/{id}', [DepenseValidationController::class, 'confirm'])->name('confirm');
            Route::post('/cancel/{id}', [DepenseValidationController::class, 'cancel'])->name('cancel');
        });
        // Validation salaires DG
        Route::get('/form-salaire', [SalaireController::class, 'form_salaire'])->name('form-salaire');
        Route::post('/store-salaire', [SalaireController::class, 'store_salaire'])->name('store-salaire');
        
        // Gestion des stagiaires
        Route::resource('stagiaires', StagiaireController::class);
        Route::patch('/stagiaires/{stagiaire}/remuneration', [StagiaireController::class, 'setRemuneration'])->name('stagiaires.remuneration');
        Route::patch('/stagiaires/{stagiaire}/appreciation', [StagiaireController::class, 'setAppreciation'])->name('stagiaires.appreciation');
        Route::get('/stagiaires/{stagiaire}/report', [StagiaireController::class, 'generateReport'])->name('stagiaires.report');

          
        // Interface de requête
        Route::post('/process-query', [QueryController::class, 'processNaturalLanguageQuery'])->name('process.query');
        Route::get('/query', [QueryInterfaceController::class, 'showQueryForm'])->name('sherlock.copilot');
        Route::get('/query-result', [QueryController::class, 'index'])->name('query.result');
        Route::get('/advice', [QueryInterface2Controller::class, 'showQueryForm'])->name('sherlock.conseiller');
        Route::get('/query2', [QueryController::class, 'index2'])->name('query.index');
        Route::post('/query/analyze', [QueryController::class, 'analyze'])->name('query.analyze');

        // Performance des employés
        Route::get('/employee-code_list', [EmployeePerformanceController::class, 'code_list'])->name('employee.code_list');
        Route::get('/employee-performance', [EmployeePerformanceController::class, 'index'])->name('employee.performance');
        Route::get('/employee-performance/{id}', [EmployeePerformanceController::class, 'show'])->name('employee.details');
        Route::post('/employee-performance/filter', [EmployeePerformanceController::class, 'filter'])->name('employee.filter');

        // Rapports
        Route::prefix('rapports')->group(function () {
            Route::get('/select', [RapportsController::class, 'select'])->name('rapports.select');
            Route::get('/', [RapportsController::class, 'index'])->name('rapports.index');
            Route::get('/employee/{id}', [RapportsController::class, 'genererRapport'])->name('rapports.generer');
            Route::get('/production/global', [RapportsController::class, 'productionGlobal'])->name('rapports.production.global');
            Route::get('/vente/global', [RapportsController::class, 'venteGlobal'])->name('rapports.vente.global');
            Route::get('/employee/{id}/pdf', [RapportsController::class, 'genererRapport'])->name('rapports.pdf')->defaults('format', 'pdf');
            Route::get('/avances_salaire', [RapportsController::class, 'avancesSalaire'])->name('avances_salaire');
            Route::get('/salaire', [RapportsController::class, 'salaires'])->name('rapport_salaire');
            Route::get('/depenses', [RapportsController::class, 'depenses'])->name('depenses');
            Route::get('/versements-chef', [RapportsController::class, 'versementsChef'])->name('versements_chef');
            Route::get('/commandes', [RapportsController::class, 'commandes'])->name('commandes');
            Route::get('/deductions', [RapportsController::class, 'deductions'])->name('deductions');
            Route::get('/primes', [RapportsController::class, 'primes'])->name('primes');
            Route::get('/evaluations', [RapportsController::class, 'evaluations'])->name('evaluations');
            Route::get('/repos-conges', [RapportsController::class, 'reposConges'])->name('repos_conges');
            Route::get('/delis', [RapportsController::class, 'delis'])->name('delis');
            Route::get('/transactions', [RapportsController::class, 'transactions'])->name('transactions');
        });

        

        // DG - Gestion sessions et dépenses
        Route::get('/dg/depenses', [DgController::class, 'depenses'])->name('dg.depenses');
        Route::get('/dg/sessions', [DgController::class, 'sessions'])->name('dg.sessions');
        Route::post('/dg/sessions/{session}/calculate-missing', [DgController::class, 'calculateMissing'])->name('dg.sessions.calculate-missing');
        Route::post('/dg/sessions/{session}/validate-missing', [DgController::class, 'validateMissing'])->name('dg.sessions.validate-missing');
        Route::get('/dg/sessions/{session}/details', [DgController::class, 'getSessionDetails'])->name('dg.sessions.details');

        // Configuration des jours de paie
        Route::get('/payday/config', [PaydayConfigController::class, 'index'])->name('payday.config');
        Route::put('/payday/config', [PaydayConfigController::class, 'update'])->name('payday.config.update');

        Route::prefix('rations')->name('rations.')->group(function () {
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get('/', [RationController::class, 'index'])->name('index');
                Route::post('/default', [RationController::class, 'updateDefaultRation'])->name('update-default');
                Route::post('/employee', [RationController::class, 'updateEmployeeRation'])->name('update-employee');
                Route::get('/statistics', [RationController::class, 'statistics'])->name('statistics');
            });
        });

        Route::get('/primes/{id}/edit', [PrimeController::class, 'edit'])->name('primes.edit');
        Route::put('/primes/{id}', [PrimeController::class, 'update'])->name('primes.update');
        Route::delete('/primes/{id}', [PrimeController::class, 'destroy'])->name('primes.destroy');

        // Rapports mensuels
        Route::prefix('rapports/mensuel')->name('rapports.mensuel.')->group(function () {
            Route::get('/', [RapportMensuelController::class, 'index'])->name('index');
            Route::get('/configure', [RapportMensuelController::class, 'configure'])->name('configure');
            Route::post('/save-config', [RapportMensuelController::class, 'saveConfig'])->name('save-config');
            Route::get('/show', [RapportMensuelController::class, 'show'])->name('show');
            Route::get('/export', [RapportMensuelController::class, 'export'])->name('export');
        });

        // Sherlock Conseiller
        Route::prefix('sherlock')->name('sherlock.')->group(function () {
            Route::get('/', [SherlockAdvisorController::class, 'index'])->name('index');
            Route::get('/configure', [SherlockAdvisorController::class, 'configure'])->name('configure');
            Route::post('/save-config', [SherlockAdvisorController::class, 'saveConfig'])->name('save-config');
            Route::get('/analyze', [SherlockAdvisorController::class, 'analyze'])->name('analyze');
            Route::get('/debug', [SherlockAdvisorController::class, 'debug'])->name('debug');

            // Sherlock Recette
            Route::prefix('sherlock_recipes')->name('recipes.')->group(function () {
                Route::get('/', [SherlockRecipeController::class, 'index'])->name('index');
                Route::post('/analyze', [SherlockRecipeController::class, 'analyze'])->name('analyze');
                Route::get('/create', [SherlockRecipeController::class, 'createForm'])->name('create');
                Route::post('/generate', [SherlockRecipeController::class, 'generate'])->name('generate');
                Route::get('/optimize/{recipeId}', [SherlockRecipeController::class, 'optimizeForm'])->name('optimize.form');
                Route::post('/optimize/{recipeId}', [SherlockRecipeController::class, 'optimize'])->name('optimize');
                Route::post('/save', [SherlockRecipeController::class, 'save'])->name('save');
                Route::get('/debug', [SherlockRecipeController::class, 'debug'])->name('debug');
            });
        });

        
        // Manquants - Gestion avancée
        Route::get('/manquants', [ManquantController::class, 'index'])->name('manquants.index');
        Route::get('/manquants/calculer', [ManquantController::class, 'calculerTousLesManquants'])->name('manquants.calculer');
        Route::get('/manquants/{id}/ajuster', [ManquantController::class, 'ajuster'])->name('manquants.ajuster');
        Route::post('/manquants/{id}/ajuster', [ManquantController::class, 'sauvegarderAjustement'])->name('manquants.sauvegarder-ajustement');
        Route::get('/manquants/{id}/valider', [ManquantController::class, 'valider'])->name('manquants.valider');
        Route::get('/manquants/{id}/details', [ManquantController::class, 'details'])->name('manquants.details');

       
        // Configuration avancée
        Route::get('/setup', [SetupController::class, 'showSetupForm'])->name('setup.create');
        Route::post('/setup', [SetupController::class, 'processSetup'])->name('setup.store');
        Route::get('/setup/edit', [SetupController::class, 'edit'])->name('setup.edit');
        Route::put('/setup', [SetupController::class, 'update'])->name('setup.update');
       
       
         // Routes générales nécessitant une révision
    Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']); //--review
    Route::resource('transactions', TransactionController::class)->except(['create', 'edit', 'show']); //--review
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit'); //--review
    
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store'); //--review
    
    Route::resource('delis', DeliController::class); //--review
    Route::prefix('versements')->name('versements.')->group(function () {
        Route::get('/visualisation', [VersementChefController::class, 'visualisation'])->name('visualisation');
        Route::get('/export', [VersementChefController::class, 'export'])->name('export');
    });
     
     // Objectifs
     Route::get('/objectives', [ObjectiveController::class, 'index'])->name('objectives.index'); //--review
     Route::get('/objectives/dashboard', [ObjectiveController::class, 'dashboard'])->name('objectives.dashboard'); //--review
     Route::get('/objectives/create', [ObjectiveController::class, 'create'])->name('objectives.create'); //--review
     Route::post('/objectives', [ObjectiveController::class, 'store'])->name('objectives.store'); //--review
     Route::get('/objectives/{objective}', [ObjectiveController::class, 'show'])->name('objectives.show'); //--review
     Route::get('/objectives/{objective}/edit', [ObjectiveController::class, 'edit'])->name('objectives.edit'); //--review
     Route::put('/objectives/{objective}', [ObjectiveController::class, 'update'])->name('objectives.update'); //--review
     Route::delete('/objectives/{objective}', [ObjectiveController::class, 'destroy'])->name('objectives.destroy'); //--review
     Route::patch('/objectives/{objective}/confirm', [ObjectiveController::class, 'confirm'])->name('objectives.confirm'); //--review
     Route::post('/objectives/{objective}/sub-objectives', [ObjectiveController::class, 'storeSubObjective'])->name('objectives.sub-objectives.store'); //--review
     Route::put('/objectives/{objective}/sub-objectives/{subObjective}', [ObjectiveController::class, 'updateSubObjective'])->name('objectives.sub-objectives.update'); //--review
     Route::delete('/objectives/{objective}/sub-objectives/{subObjective}', [ObjectiveController::class, 'destroySubObjective'])->name('objectives.sub-objectives.destroy'); //--review
 
    // Historique
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index'); //--review
    Route::delete('/history/{history}', [HistoryController::class, 'destroy'])->name('history.destroy'); //--review
    
    Route::get('/configurations', [ConfigurationController::class, 'index'])->name('configurations.index');
    Route::post('/configurations/toggle-salaire', [ConfigurationController::class, 'toggleSalaire'])->name('configurations.toggle-salaire');
    Route::post('/configurations/toggle-avance-salaire', [ConfigurationController::class, 'toggleAvanceSalaire'])->name('configurations.toggle-avance-salaire');
    

    });
    /*
    |--------------------------------------------------------------------------
    | Routes Chef de Production
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:chef_production,dg,pdg,ddg,developper'])->group(function () {
        Route::get('cp/dashboard', [Chef_productionController::class, 'index'])->name('production.chief.workspace'); 
        Route::prefix('commandes/reduction')->name('commandes.reduction.')->group(function () {
            Route::get('/', [CommandeReductionController::class, 'index'])->name('index');
            Route::post('/selection', [CommandeReductionController::class, 'processSelection'])->name('selection');
            Route::post('/apply-reduction', [CommandeReductionController::class, 'applyReduction'])->name('apply_reduction');
            Route::post('/valider', [CommandeReductionController::class, 'validerCommandes'])->name('valider');
        });
        Route::prefix('gestion_solde')->name('gestion_solde.')->group(function () {
            Route::get('/', [GestionSoldeController::class, 'index'])->name('index');
            Route::get('/export', [GestionSoldeController::class, 'export'])->name('export');
        });

        Route::prefix('calcul-ventes')->name('calcul-ventes.')->group(function () {
    Route::get('/', [CalculVentesController::class, 'index'])->name('index');
    Route::post('/calculer', [CalculVentesController::class, 'calculer'])->name('calculer');
    Route::get('/resultats/{mois}/{annee}', [CalculVentesController::class, 'resultats'])->name('resultats');
});

	Route::get('/valider-as', [SalaireController::class, 'validerAs'])->name('valider-as');
        Route::post('/store-validation', [SalaireController::class, 'store_validation'])->name('store-validation');



          Route::prefix('manquants/flux_reel')->name('manquants_flux.')->group(function () {
            Route::get('/', [ManquantFluxController::class, 'index'])->name('index');
            Route::post('/calculer', [ManquantFluxController::class, 'calculerFluxJournalier'])->name('calculer');
            Route::get('/details/{flux}', [ManquantFluxController::class, 'voirDetails'])->name('details');
        });
        Route::get('/manquant-flux/repartition', [ManquantFluxController::class, 'afficherRepartition'])
    ->name('manquant-flux.repartition');
Route::post('/manquant-flux/repartir-manquants', [ManquantFluxController::class, 'repartirManquants'])
    ->name('manquant-flux.repartir');

	Route::get('/calcul-production-boulangerie', function () {
    return view('calcul-production-boulangerie');
})->name('calcul-production-boulangerie.index');

Route::post('/calcul-production-boulangerie', [RepartiteurController::class, 'calculProductionBoulangerie'])->name('repartiteur.calcul-production-boulangerie');
  

        
        // Routes pour le module de gestion des manquants de flux
        Route::middleware(['auth', 'verified'])->prefix('manquant-flux')->name('manquant-flux.')->group(function () {
             Route::get('/', [ManquantFluxController::class, 'index'])->name('index');
             Route::get('/details/{idLot}', [ManquantFluxController::class, 'details'])->name('details');
        });

        // Prêts - Gestion DG
        Route::prefix('loans')->group(function () {
            Route::get('/pending', [LoanController::class, 'pendingLoans'])->name('loans.pending');
            Route::post('/approve/{id}', [LoanController::class, 'approveLoan'])->name('loans.approve');
            Route::post('/reject/{id}', [LoanController::class, 'rejectLoan'])->name('loans.reject');
            Route::get('/employees-with-loans', [LoanController::class, 'employeesWithLoans'])->name('loans.employees-with-loans');
            Route::get('/employee/{id}', [LoanController::class, 'employeeDetail'])->name('loans.employee-detail');
            Route::post('/employee/{id}/set-monthly-repayment', [LoanController::class, 'setMonthlyRepayment'])->name('loans.set-monthly-repayment');
            Route::post('/employee/{id}/record-repayment', [LoanController::class, 'recordRepayment'])->name('loans.record-repayment');
        });
        // Gestion des produits
        Route::get('cp/produits', [Chef_productionController::class, 'gestionProduits'])->name('chef.produits.index');
        Route::post('cp/produits', [Chef_productionController::class, 'storeProduit'])->name('chef.produits.store');
        Route::put('cp/produits/{produit}', [Chef_productionController::class, 'updateProduit'])->name('chef.produits.update');
        Route::delete('cp/produits/{produit}', [Chef_productionController::class, 'destroyProduit'])->name('chef.produits.destroy');
        
        // Gestion des matières premières
        Route::get('cp/matieres', [Chef_productionController::class, 'gestionMatieres'])->name('chef.matieres.index');
        Route::post('cp/matieres', [Chef_productionController::class, 'storeMatiere'])->name('chef.matieres.store');
        Route::put('/chef/matieres/{matiere}', [Chef_productionController::class, 'updateMatiere'])->name('chef.matieres.update');
        Route::delete('/chef/matieres/{matiere}', [Chef_productionController::class, 'destroyMatiere'])->name('chef.matieres.destroy');
        Route::get('/chef/matieres/{matiere}/edit', [Chef_productionController::class, 'editMatiere'])->name('chef.matieres.edit');
        
         // Notifications de matières
        Route::prefix('matieres')->name('matieres.')->group(function () {
            Route::get('/notifications', [MatiereNotificationController::class, 'index'])->name('notifications.index');
            Route::put('/notifications/{matiere}', [MatiereNotificationController::class, 'update'])->name('notifications.update');
            Route::post('/notifications/update-batch', [MatiereNotificationController::class, 'updateBatch'])->name('notifications.update-batch');
            Route::post('/notifications/check', [MatiereNotificationController::class, 'checkThresholds'])->name('notifications.check');
        });

        // Validation retrait CP
        Route::get('/valider-retraitcp', [SalaireController::class, 'valider_retraitcp'])->name('valider-retraitcp');
        Route::post('/recup-retrait-cp', [SalaireController::class, 'recup_retrait_cp'])->name('recup-retrait-cp');

        // Gestion des réservations
        Route::get('/chef/reservations', [ReservationMpController::class, 'index'])->name('chef.reservations.index');
        Route::post('/chef/reservations/{reservation}/valider', [ReservationMpController::class, 'validerReservation'])->name('chef.reservations.valider');
        Route::post('/chef/reservations/{reservation}/refuser', [ReservationMpController::class, 'refuserReservation'])->name('chef.reservations.refuser');

        // Assignation de matières premières
        Route::get('/assignations', [AssignationMatiereController::class, 'index'])->name('assignations.index');
        Route::get('/assignations/create', [AssignationMatiereController::class, 'create'])->name('assignations.create');
        Route::post('/assignations', [AssignationMatiereController::class, 'store'])->name('assignations.store');
        Route::get('/assignations/{assignation}/edit', [AssignationMatiereController::class, 'edit'])->name('assignations.edit');
        Route::put('/assignations/{assignation}', [AssignationMatiereController::class, 'update'])->name('assignations.update');
        Route::get('/assignations/{assignation}/facture', [AssignationMatiereController::class, 'facture'])->name('assignations.facture');
        Route::get('/assignations/resume-quantites', [AssignationMatiereController::class, 'resumeQuantites'])->name('assignations.resume-quantites');

        // Commandes
        Route::get('/chef_production/commandes/create', [Chef_productionController::class, 'createcommande'])->name('chef.commandes.create');
        Route::post('/chef/commandes/store', [Chef_productionController::class, 'storecommande'])->name('chef.commandes.stores');
        Route::post('/chef/commande/store2', [Chef_productionController::class, 'storecommande'])->name('chef.commandes.store2');
        Route::get('/commandes/{id}/edit', [Chef_productionController::class, 'editcommande'])->name('commande.edit');
        Route::put('/commandes/{id}', [Chef_productionController::class, 'updatecommande'])->name('commande.update');
        Route::delete('/commandes/{id}', [Chef_productionController::class, 'destroycommande'])->name('chef.commandes.destroy');
        Route::post('/chef/commandes', [AssignationMatiereController::class, 'storeassignation'])->name('chef.commandes.store');
        Route::put('/chef/assignations/{assignation}', [AssignationMatiereController::class, 'update'])->name('chef.assignations.update');
        Route::delete('/chef/assignations/{assignation}', [AssignationMatiereController::class, 'destroy'])->name('chef.assignations.destroy');

        // Gestion avancée
        Route::post('/assigner-production', [Chef_productionController::class, 'assignerProduction'])->name('chef.assigner-production');
        Route::get('/api/user-info', [Chef_productionController::class, 'getUserInfo']);
        Route::get('/sidebar', [Chef_productionController::class, 'getUserInfos']);
        Route::get('/manquant/create', [Chef_productionController::class, 'createmanquant'])->name('manquant.create');
        Route::post('/manquant/store', [Chef_productionController::class, 'storemanquant2'])->name('manquant.store');
        
        // Choix classement
        Route::get('/choix-classement', [Chef_productionController::class, 'choix_classement'])->name('choix_classement');

        // Stock
        Route::prefix('stock')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('stock.index');
            Route::get('/search-matiere', [StockController::class, 'searchMatiere'])->name('stock.search-matiere');
            Route::get('/search-produit', [StockController::class, 'searchProduit'])->name('stock.search-produit');
            Route::post('/adjust-matiere-quantity/{matiere}', [StockController::class, 'adjustMatiereQuantity'])->name('stock.adjust-matiere-quantity');
            Route::post('/adjust-produit-quantity/{produit}', [StockController::class, 'adjustProduitQuantity'])->name('stock.adjust-produit-quantity');
            Route::get('/api/produits/{produit}', [StockController::class, 'getProduit'])->name('stock.get-produit');
        });

        // Versements
        Route::prefix('versements')->name('versements.')->group(function () {
          Route::get('/validation', [VersementChefController::class, 'validation'])->name('validation');
            Route::post('/{versement}/valider', [VersementChefController::class, 'valider'])->name('valider');
        });

        // Dépenses
        Route::prefix('depenses')->group(function () {
            Route::get('/', [DepenseController::class, 'index'])->name('depenses.index');
            Route::get('/livraison', [DepenseController::class, 'index2'])->name('depenses.index2');
            Route::get('/create', [DepenseController::class, 'create'])->name('depenses.create');
            Route::post('/', [DepenseController::class, 'store'])->name('depenses.store');
            Route::get('/{depense}/edit', [DepenseController::class, 'edit'])->name('depenses.edit');
            Route::put('/{depense}', [DepenseController::class, 'update'])->name('depenses.update');
            Route::delete('/{depense}', [DepenseController::class, 'destroy'])->name('depenses.destroy');
            Route::post('/{depense}/valider-livraison', [DepenseController::class, 'validerLivraison'])->name('depenses.valider-livraison');
        });

         // Manquant inventaire et produit
        Route::prefix('manquant-inventaire')->name('manquant-inventaire.')->group(function () {
            Route::get('/', [ManquantInventaireController::class, 'index'])->name('index');
            Route::post('/calculer', [ManquantInventaireController::class, 'calculer'])->name('calculer');
        });
        Route::get('/manquant-produit', [ManquantProduitController::class, 'index'])->name('manquant-produit.index');
        Route::post('/manquant-produit/calculer', [ManquantProduitController::class, 'calculer'])->name('manquant-produit.calculer');

        // Planning
        Route::get('/planning', [PlanningController::class, 'index'])->name('planning.index');
        Route::post('/planning', [PlanningController::class, 'store'])->name('planning.store');
        Route::put('/planning/{planning}', [PlanningController::class, 'update'])->name('planning.update');
        Route::delete('/planning/{planning}', [PlanningController::class, 'destroy'])->name('planning.destroy');
        Route::get('/planning/events', [PlanningController::class, 'getEvents'])->name('planning.events');

        // Évaluations
        Route::get('/employees', [EvaluationController::class, 'index'])->name('employees.index');
        Route::get('/employees/{user}', [EvaluationController::class, 'show'])->name('employees.show');
        Route::post('/employees/{user}/evaluate', [EvaluationController::class, 'evaluate'])->name('employees.evaluate');
        Route::get('/employees-stats', [EvaluationController::class, 'stats'])->name('employees.stats');
        Route::resource('repos-conges', ReposCongeController::class);

        // Classements
        Route::get('classement/employe', [EmployeeRankingController::class, 'index'])->name('rankings.index');

        // Primes
        Route::get('/attribution-prime', [PrimeController::class, 'create'])->name('primes.create');
        Route::post('/attribution-prime', [PrimeController::class, 'store'])->name('primes.store');

       
        // Matières complexes
        Route::get('/matieres/complexe', [MatiereComplexeController::class, 'index'])->name('matieres.complexe.index');
        Route::post('/matieres/complexe/{id}/toggle', [MatiereComplexeController::class, 'toggle'])->name('matieres.complexe.toggle');
        Route::post('/matieres/complexe/{id}/prix', [MatiereComplexeController::class, 'updatePrix'])->name('matieres.complexe.prix');
        Route::get('/matieres/complexe/statistiques', [MatiereComplexeController::class, 'statistiques'])->name('matieres.complexe.statistiques');

        // Solde CP
        Route::get('/solde-cp2', [SoldeCPController::class, 'index'])->name('solde-cp.index');
        Route::get('/solde-cp/ajuster', [SoldeCPController::class, 'ajuster'])->name('solde-cp.ajuster');
        Route::post('/solde-cp/ajuster', [SoldeCPController::class, 'storeAjustement'])->name('solde-cp.store-ajustement');
	      // Nouvelles routes pour la gestion des historiques
    Route::get('/historique/{id}', [SoldeCPController::class, 'show'])->name('solde-cp.show');
    Route::get('/historique/{id}/edit', [SoldeCPController::class, 'edit'])->name('solde-cp.edit');
    Route::put('/historique/{id}', [SoldeCPController::class, 'update'])->name('solde-cp.update');
    Route::delete('/historique/{id}', [SoldeCPController::class, 'destroy'])->name('solde-cp.destroy');
        /**** */
        // Produits
        Route::resource('produits', ProduitController::class);
        Route::get('/produits/type/{type}', [ProduitController::class, 'indexByType'])->name('produits.by.type');
        Route::get('/produits-alertes', [ProduitController::class, 'alertes'])->name('produits.alertes');

        // Mouvements de stock
        Route::post('/stock/entree/{produit}', [MouvementStockController::class, 'entree'])->name('stock.entree');
        Route::post('/stock/sortie/{produit}', [MouvementStockController::class, 'sortie'])->name('stock.sortie');
        Route::get('/stock/mouvements', [MouvementStockController::class, 'index'])->name('stock.mouvements');
        
        Route::resource('inventory/groups', ProductGroupController::class);
        
        // Avances sur salaire
        Route::prefix('avance-salaires')->name('avance-salaires.')->group(function () {
            Route::get('/', [AvanceSalaireController::class, 'dashboard'])->name('dashboard');
            Route::get('/{avanceSalaire}', [AvanceSalaireController::class, 'show'])->name('show');
            Route::patch('/{avanceSalaire}/valider', [AvanceSalaireController::class, 'valider'])->name('valider');
            Route::get('/export', [AvanceSalaireController::class, 'export'])->name('export');
            Route::get('/api/stats', [AvanceSalaireController::class, 'getStats'])->name('api.stats');
        });
          // Statistiques avancées
          Route::get('statistiques/employe', [StatistiquesController::class, 'index'])->name('statistiques');
          Route::get('statistiques/ventes', [StatistiquesController::class,'ventes'])->name('statistiques.ventes');
          Route::get('statistiques/autres', [StatistiquesController::class, 'autre'])->name('statistiques.autres');
          Route::get('statistiques/finance', [StatistiquesController::class, 'finance'])->name('statistiques.finance');
          Route::get('statistiques/commande', [StatistiquesController::class, 'commande'])->name('statistiques.commande');
          Route::get('/statistiques/horaires', [StatistiquesController::class, 'horaires'])->name('statistiques.horaires');
          Route::get('/statistiques/absences', [StatistiquesController::class, 'listeAbsences'])->name('statistiques.absences');
          Route::get('/statistiques/production', [StatistiquesController::class, 'production'])->name('statistiques.production');
          Route::get('/statistiques/stagiaire', [StatistiquesController::class, 'stagiere'])->name('statistiques.stagiere');
          Route::get('/statistiques/argent_employe', [StatistiquesController::class, 'salaire_argent'])->name('statistiques.argent');
          Route::get('/statistiques/details', [EmployeePerformanceController::class, 'productionDetails'])->name('statistiques.details');
      

        // Suggestions de production
        Route::prefix('production/suggestions')->name('production.suggestions.')->group(function () {
            Route::get('/', [ProductionSuggestionController::class, 'index'])->name('index');
            Route::post('/', [ProductionSuggestionController::class, 'store'])->name('store');
            Route::delete('/{id}', [ProductionSuggestionController::class, 'destroy'])->name('destroy');
        });

        // Édition de production
        Route::prefix('production/edit')->name('production.edit.')->group(function () {
            Route::get('/', [ProductionEditController::class, 'index'])->name('index');
            Route::get('/ventes', [ProductionEditController::class, 'ventes'])->name('ventes');
            Route::get('/ventes/{id}/get', [ProductionEditController::class, 'getVente']);
            Route::post('/ventes/{id}/update', [ProductionEditController::class, 'updateVente']);
            Route::post('/ventes/{id}/delete', [ProductionEditController::class, 'destroyVente']);
            Route::get('/utilisations', [ProductionEditController::class, 'utilisations'])->name('utilisations');
            Route::get('/utilisations/{id}/get', [ProductionEditController::class, 'getUtilisation']);
            Route::post('/utilisations/{id}/update', [ProductionEditController::class, 'updateUtilisation']);
            Route::post('/utilisations/{id}/delete', [ProductionEditController::class, 'destroyUtilisation']);
        });
         // Routes recettes avec révision
      Route::prefix('recipes')->name('recipe.')->group(function () {
        Route::get('/categories', [RecipeCategoryController::class, 'index'])->name('categories.index'); //--review
        Route::get('/categories/create', [RecipeCategoryController::class, 'create'])->name('categories.create'); //--review
        Route::post('/categories', [RecipeCategoryController::class, 'store'])->name('categories.store'); //--review
        Route::get('/categories/{category}/edit', [RecipeCategoryController::class, 'edit'])->name('categories.edit'); //--review
        Route::put('/categories/{category}', [RecipeCategoryController::class, 'update'])->name('categories.update'); //--review
        Route::delete('/categories/{category}', [RecipeCategoryController::class, 'destroy'])->name('categories.destroy'); //--review

        Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients.index'); //--review
        Route::get('/ingredients/create', [IngredientController::class, 'create'])->name('ingredients.create'); //--review
        Route::post('/ingredients', [IngredientController::class, 'store'])->name('ingredients.store'); //--review
        Route::get('/ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('ingredients.edit'); //--review
        Route::put('/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('ingredients.update'); //--review
        Route::delete('/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('ingredients.destroy'); //--review
        });

       

        // Analyse des produits
        Route::get('/analyse/produits', [AnalyseProduitController::class, 'index'])->name('analyse.produits');
        Route::get('analyse/produits/{id}', [AnalyseProduitController::class, 'details'])->name('analyse.produits.details');

        // Gaspillage
        Route::prefix('gaspillage')->name('gaspillage.')->group(function () {
            Route::get('/', [GaspillageController::class, 'index'])->name('index');
            Route::get('/production/{idLot}', [GaspillageController::class, 'detailsProduction'])->name('details-production');
            Route::get('/produit/{codeProduit}', [GaspillageController::class, 'detailsProduit'])->name('details-produit');
            Route::get('/matiere/{idMatiere}', [GaspillageController::class, 'detailsMatiere'])->name('details-matiere');
        });
        Route::get('/gaspillage_par_produit', [GaspillageController::class,'choose_produit'])->name('gaspillage.details-produit.choose');
        Route::get('/gaspillage_par_matiere', [GaspillageController::class,'choose_matiere'])->name('gaspillage.details-matiere.choose');

         // Dashboard du flux de produits (accessible aux DG, PDG, Chef de Production)
    Route::get('/flux-produit/dashboard', [FluxProduitController::class, 'dashboard'])
        ->name('flux-produit.dashboard');
    
    // Calcul automatique des manquants
    Route::post('/flux-produit/calculer-manquants', [FluxProduitController::class, 'calculerManquantsAuto'])
        ->name('flux-produit.calculer-manquants');
    
    // Détails d'un flux spécifique
    Route::get('/flux-produit/details', [FluxProduitController::class, 'detailsFlux'])
        ->name('flux-produit.details');
    
    // Explication du calcul des manquants
    Route::get('/flux-produit/explication-manquants', function() {
        $isFrench = session('locale', 'fr') === 'fr';
        return view('flux-produit.explication-manquants', compact('isFrench'));
    })->name('flux-produit.explication');
    
    // Export des rapports
    Route::get('/flux-produit/export', [FluxProduitController::class, 'exportRapport'])
        ->name('flux-produit.export');
    
    // Gestion des anomalies
    Route::get('/flux-produit/anomalies', [FluxProduitController::class, 'gererAnomalies'])->name('flux-produit.anomalies');
    Route::post('/flux-produit/resoudre-anomalie', [FluxProduitController::class, 'resoudreAnomalie'])->name('flux-produit.resoudre-anomalie');

        
});


    /*
    |--------------------------------------------------------------------------
    | Routes Gestionnaire Alimentation / Chef Rayoniste
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:gestionnaire_alimentation,chef_rayoniste,chef_production,dg,pdg,ddg,developper'])->group(function () {
        Route::get('/alimchefworkspace', [EmployeeController::class, 'index3'])->name('alimchef.workspace');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Producteurs (Pâtissier, Boulanger)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:patissier,boulanger,chef_production,dg,pdg,ddg,developper'])->group(function () {
        Route::get('/producteur/produit', [ProducteurController::class,'produit'])->name('producteur.workspace');
        Route::get('/producteur/pdefault', [ProducteurController::class,'pdefault'])->name('producteur_default');
        Route::post('/producteur/store', [ProducteurController::class,'store'])->name('enr_produits');
        Route::get('producteur/commande', [ProducteurController::class, 'commande'])->name('producteur-commande');
        Route::get('/producteur/stat-production', [ProducteurController::class, 'stat_prod'])->name('producteur.sp');
        Route::get('/producteur/produit_mp', [ProducteurController::class, 'create'])->name('produitmp');
        Route::post('producteur/store2', [ProducteurController::class, 'store2'])->name('utilisations.store');
        Route::get('classement/producteur', [ProducteurController::class, 'comparaison'])->name('producteur.comparaison');
        Route::get('/producteur/lots', [ProducteurController::class, 'produit_par_lot'])->name('producteur.lots');
        Route::get('/production/fiche', [ProducteurController::class, 'fiche_production'])->name('production.fiche');
        

	      // Route pour afficher les productions du producteur
    Route::get('/mes-productions', [ProductionEditController::class, 'mesProductions'])
        ->name('productions.mes-productions');
    
    // Route pour supprimer une production (AJAX)
    Route::delete('/production/supprimer', [ProductionEditController::class, 'supprimerProduction'])
        ->name('production.supprimer');

// Routes de configuration (Admin)
Route::prefix('boulangerie/configuration')->name('boulangerie.configuration.')->group(function () {
    Route::get('/', [BoulangerieController::class, 'indexConfiguration'])->name('index');
    Route::get('/create', [BoulangerieController::class, 'createConfiguration'])->name('create');
    Route::post('/', [BoulangerieController::class, 'storeConfiguration'])->name('store');
    Route::get('/{id}/edit', [BoulangerieController::class, 'editConfiguration'])->name('edit');
    Route::put('/{id}', [BoulangerieController::class, 'updateConfiguration'])->name('update');
    Route::delete('/{id}', [BoulangerieController::class, 'destroyConfiguration'])->name('destroy');
});

// Routes de production (Producteurs)
Route::prefix('boulangerie/production')->name('boulangerie.production.')->group(function () {
    Route::get('/', [BoulangerieController::class, 'indexProduction'])->name('index');
    Route::get('/create', [BoulangerieController::class, 'createProduction'])->name('create');
    Route::post('/', [BoulangerieController::class, 'storeProduction'])->name('store');
    Route::get('/{id}', [BoulangerieController::class, 'showProduction'])->name('show');
    Route::get('/{id}/edit', [BoulangerieController::class, 'editProduction'])->name('edit');
    Route::put('/{id}', [BoulangerieController::class, 'updateProduction'])->name('update');
    Route::delete('/{id}', [BoulangerieController::class, 'destroyProduction'])->name('destroy');
});

// Routes API pour AJAX
Route::prefix('api/boulangerie')->name('api.boulangerie.')->group(function () {
    Route::get('/sac/{id}', [BoulangerieController::class, 'getSacDetails'])->name('sac.details');
    Route::get('/produit/{id}/prix', [BoulangerieController::class, 'getProduitPrix'])->name('produit.prix');
});


	// Statistiques de production
        Route::prefix('production/stats')->name('production.stats.')->group(function () {
            Route::get('/', [ProductionStatController::class, 'index'])->name('index');
            Route::get('/custom', [ProductionStatController::class, 'customStats'])->name('custom');
            Route::get('/details', [ProductionStatController::class, 'productionDetails'])->name('details');
        });


        // Réservation de matières premières
        Route::get('/producteur/reserver-mp', [ReservationMpController::class, 'create'])->name('producteur.reservations.create');
        Route::post('/producteur/reserver-mp', [ReservationMpController::class, 'store'])->name('producteur.reservations.store');
        Route::get('/producteur/mes-assignations', [AssignationMatiereController::class, 'index'])->name('producteur.assignations.index');

        // Avaries
        Route::get('/producteur/avaries', [AvarieController::class, 'index'])->name('producteur.avaries.index');
        Route::get('/producteur/avaries/create', [AvarieController::class, 'create'])->name('producteur.avaries.create');
        Route::post('/producteur/avaries', [AvarieController::class, 'store'])->name('producteur.avaries.store');

        // Gestion des taules
        Route::prefix('taules')->name('taules.')->group(function () {
            Route::prefix('types')->name('types.')->group(function () {
                Route::get('/', [TypeTauleController::class, 'index'])->name('index');
                Route::get('/create', [TypeTauleController::class, 'create'])->name('create');
                Route::post('/', [TypeTauleController::class, 'store'])->name('store');
                Route::get('/{type}/edit', [TypeTauleController::class, 'edit'])->name('edit');
                Route::put('/{type}', [TypeTauleController::class, 'update'])->name('update');
                Route::delete('/{type}', [TypeTauleController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('inutilisees')->name('inutilisees.')->group(function () {
                Route::get('/', [TauleInutiliseeController::class, 'index'])->name('index');
                Route::get('/create', [TauleInutiliseeController::class, 'create'])->name('create');
                Route::post('/', [TauleInutiliseeController::class, 'store'])->name('store');
                Route::post('/calculer', [TauleInutiliseeController::class, 'calculerMatieres'])->name('calculer');
                Route::post('/{tauleInutilisee}/recuperer', [TauleInutiliseeController::class, 'recuperer'])->name('recuperer');
            });
        });

        Route::prefix('repartiteur')->name('repartiteur.')->group(function () {
            Route::get('/', [RepartiteurController::class, 'index'])->name('index');
            Route::post('/store', [RepartiteurController::class, 'store'])->name('store');
        });

        // Matières recommandées
        Route::get('/matieres/recommandees', [MatiereRecommanderController::class, 'index'])->name('matieres.recommandees.index');
        Route::get('/matieres/recommandees/create/{produitId?}', [MatiereRecommanderController::class, 'create'])->name('matieres.recommandees.create');
        Route::post('/matieres/recommandees', [MatiereRecommanderController::class, 'store'])->name('matieres.recommandees.store');
        Route::get('/matieres/recommandees/{id}', [MatiereRecommanderController::class, 'show'])->name('matieres.recommandees.show');
        Route::get('/matieres/recommandees/{id}/edit', [MatiereRecommanderController::class, 'edit'])->name('matieres.recommandees.edit');
        Route::put('/matieres/recommandees/{id}', [MatiereRecommanderController::class, 'update'])->name('matieres.recommandees.update');
        Route::delete('/matieres/recommandees/{id}', [MatiereRecommanderController::class, 'destroy'])->name('matieres.recommandees.destroy');
        Route::post('/matieres/recommandees/{produitId}/ajouter', [MatiereRecommanderController::class, 'addMatiere'])->name('matieres.recommandees.add-matiere');
        Route::get('/matieres/recommandees/conversion', [MatiereRecommanderController::class, 'getConversion'])->name('matieres.recommandees.conversion');

        // Rapport matières
        Route::get('/rapport-matieres', [MatiereUtilisationController::class, 'rapportJournalier'])->name('matieres.rapport-journalier');

        // Production details
        Route::get('/employees2', [EmployeeProductionController::class, 'index'])->name('employees2');
        Route::get('/employees2/{id}', [EmployeeProductionController::class, 'showEmployeeDetails'])->name('employee.details2');
        
        Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index'); //--review
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create'); //--review
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store'); //--review
    Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show'); //--review
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit'); //--review
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update'); //--review
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy'); //--review
    Route::get('/instructions', [RecipeController::class, 'instructions'])->name('recipes.instructions'); //--review
    Route::get('/instructions/{recipe}', [RecipeController::class, 'showInstructions'])->name('recipes.show_instructions'); //--review
    Route::get('/recipes-admin', [RecipeController::class, 'adminIndex'])->name('recipes.admin'); //--review

     // Recettes
     Route::get('/recettes', [RecetteController::class, 'index'])->name('recettes.index');
     Route::get('/recettes/create', [RecetteController::class, 'create'])->name('recettes.create');
     Route::post('/recettes', [RecetteController::class, 'store'])->name('recettes.store');
     Route::post('/recettes/calculate', [RecetteController::class, 'calculateIngredients'])->name('recettes.calculate');
     Route::delete('/recettes/{produit}', [RecetteController::class, 'destroy'])->name('recettes.destroy');


      // Routes pour les retours de matières
    Route::prefix('/matieres-retour')->name('matieres.retours.')->group(function () {
        Route::get('/', [MatiereRetourController::class, 'index'])->name('index');
        Route::get('/create', [MatiereRetourController::class, 'create'])->name('create')->middleware('role:patissier,boulanger,glace');
        Route::post('/', [MatiereRetourController::class, 'store'])->name('store')->middleware('role:patissier,boulanger,glace');
        Route::post('/{id}/valider', [MatiereRetourController::class, 'valider'])->name('valider')->middleware('role:chef_production,pdg,dg');
    });
});

    /*
    |--------------------------------------------------------------------------
    | Routes Glace (accès aux routes producteur + vendeur + employés)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:glace,chef_production,dg,pdg,ddg,developper'])->group(function () {
        Route::get('glace/dashboard', [GlaceController::class, 'dashboard'])->name('ice.workspace');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Vendeurs (Vendeur Boulangerie, Vendeur Pâtisserie)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:vendeur_boulangerie,vendeur_patisserie,glace,chef_production,dg,pdg,ddg,developper'])->group(function () {
        Route::prefix('serveur')->name('serveur.')->group(function () {
            Route::get('/dashboard', [ServeurController::class, 'dashboard'])->name('workspace');
            Route::get('/vente/create', [ServeurController::class, 'createVente'])->name('vente.create');
            Route::post('/vente', [ServeurController::class, 'storeVente'])->name('vente.store');
            Route::get('/vente/liste', [ServeurController::class, 'listeVentes'])->name('vente.liste');
            Route::post('/invendus/recuperer', [ServeurController::class, 'recupererInvendusHier'])->name('invendus.recuperer');
            Route::post('/reception/{id}/confirmer', [ServeurController::class, 'confirmerReception'])->name('reception.confirmer');
            Route::post('/reception/{id}/rejeter', [ServeurController::class, 'rejeterReception'])->name('reception.rejeter');
            Route::get('/classement', [ServeurController::class, 'classementVendeurs'])->name('classement');
        });
        // Routes pour les réceptions vendeurs
        Route::prefix('receptions/vendeurs')->name('receptions.vendeurs.')->group(function () {
            Route::get('/', [ReceptionVendeurController::class, 'index'])->name('index');
            Route::get('/create', [ReceptionVendeurController::class, 'create'])->name('create');
            Route::post('/', [ReceptionVendeurController::class, 'store'])->name('store');
            Route::get('/{id}', [ReceptionVendeurController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ReceptionVendeurController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ReceptionVendeurController::class, 'update'])->name('update');
            Route::delete('/{id}', [ReceptionVendeurController::class, 'destroy'])->name('destroy');
    
            // Routes API pour AJAX
            Route::get('/api/by-date', [ReceptionVendeurController::class, 'getReceptionsByDate'])->name('api.by-date');
            Route::get('/rapport', [ReceptionVendeurController::class, 'rapport'])->name('rapport');
        });

        Route::get('serveur/ajouterProduit_recu', [ServeurController::class, 'ajouterProduit_recu'])->name('serveur-ajouterProduit_recu');
        Route::post('serveur/store', [ServeurController::class, 'store'])->name('addProduit_recu');
        Route::get('/serveur/nbre_sacs', [ServeurController::class, 'nbre_sacs_vente'])->name('serveur-nbre_sacs_vente');
        Route::post('serveur/nbre_sacs_vente', [ServeurController::class, 'nbre_sacs'])->name('serveur-nbre_sacs');
        
        // Routes pour les ventes
        Route::get('/ventes', [VenteController::class, 'index'])->name('ventes.index');
        Route::get('/classement/serveuse', [VenteController::class, 'compareVendeurs'])->name('ventes.compare');
        
        // Gestion des sacs
        Route::get('/bags/receptions/create', [BagReceptionController::class, 'create'])->name('bag.receptions.create');
        Route::post('/bags/receptions', [BagReceptionController::class, 'store'])->name('bag.receptions.store');
        Route::get('/bags/receptions/{reception}/edit', [BagReceptionController::class, 'edit'])->name('bag.receptions.edit');
        Route::put('/bags/receptions/{reception}', [BagReceptionController::class, 'update'])->name('bag.receptions.update');
        
        Route::get('/bags/sales/create', [BagSaleController::class, 'create'])->name('bag.sales.create');
        Route::post('/bags/sales', [BagSaleController::class, 'store'])->name('bag.sales.store');
        Route::get('/bags/sales/{sale}/edit', [BagSaleController::class, 'edit'])->name('bag.sales.edit');
        Route::put('/bags/sales/{sale}', [BagSaleController::class, 'update'])->name('bag.sales.update');

        Route::get('/bags/assignments/create', [BagAssignmentController::class, 'create'])->name('bag.assignments.create');
        Route::post('/bags/assignments', [BagAssignmentController::class, 'store'])->name('bag.assignments.store');
        Route::get('/bags/assignments/{assignment}/edit', [BagAssignmentController::class, 'edit'])->name('bag.assignments.edit');
        Route::put('/bags/assignments/{assignment}', [BagAssignmentController::class, 'update'])->name('bag.assignments.update');

        Route::get('/bags/discrepancies', [BagDiscrepancyController::class, 'index'])->name('bag.discrepancies.index');
        Route::get('/bag3', [BagRecoveryController::class, 'ex'])->name('bag.recovery.index');
        Route::post('/bags/recovery/{sale}', [BagRecoveryController::class, 'recover'])->name('bag.recovery.recover');

        Route::get('/bags2', [BagController::class, 'index2'])->name('bags.index2');
        Route::get('/bags/create', [BagController::class, 'create2'])->name('bags.create2');
        Route::post('/bags2', [BagController::class, 'store2'])->name('bags.store2');
        Route::get('/bags/{bag}', [BagController::class, 'show'])->name('bags.show');
        Route::get('/bags/{bag}/edit', [BagController::class, 'edit'])->name('bags.edit');
        Route::put('/bags/{bag}', [BagController::class, 'update'])->name('bags.update');
        Route::patch('/bags/{bag}', [BagController::class, 'update']);
        Route::delete('/bags/{bag}', [BagController::class, 'destroy'])->name('bags.destroy');

        Route::get('/bags/create', [BagController::class, 'create'])->name('bags.create');
        Route::post('/bags', [BagController::class, 'store'])->name('bags.store');
        Route::get('/bags/receive', [BagController::class, 'receive'])->name('bags.receive');
        Route::post('/bags/receive', [BagController::class, 'storeReceived'])->name('bags.store-received');
        Route::get('/bags/sell', [BagController::class, 'sell'])->name('bags.sell');
        Route::post('/bags/sell', [BagController::class, 'storeSold'])->name('bags.store-sold');

        Route::prefix('bags')->name('bags.')->group(function () {
    Route::get('/', [BagController::class, 'index'])->name('index');
    Route::post('/{bag}/add-stock', [BagController::class, 'addStock'])->name('add-stock');
    Route::post('/{bag}/remove-stock', [BagController::class, 'removeStock'])->name('remove-stock');
    Route::post('/{bag}/update-alert', [BagController::class, 'updateAlertThreshold'])->name('update-alert');
});

// Route optionnelle pour les statistiques
Route::get('/bags/stats', [BagController::class, 'getStockStats'])->name('bags.stats');
        // Sacs avariés
        Route::get('/sacs-avaries', [DamagedBagController::class, 'index'])->name('damaged-bags.index');
        Route::get('/sacs-avaries/{id}/declarer', [DamagedBagController::class, 'create'])->name('damaged-bags.create');
        Route::post('/sacs-avaries/{id}', [DamagedBagController::class, 'store'])->name('damaged-bags.store');
    
          // Gestion de la monnaie
      Route::prefix('cash')->name('cash.')->group(function () {
        Route::prefix('distributions')->name('distributions.')->group(function () {
            Route::get('/', [CashDistributionController::class, 'index'])->name('index');
            Route::get('/create', [CashDistributionController::class, 'create'])->name('create');
            Route::post('/', [CashDistributionController::class, 'store'])->name('store');
            Route::get('/{distribution}', [CashDistributionController::class, 'show'])->name('show');
            Route::get('/{distribution}/edit', [CashDistributionController::class, 'edit'])->name('edit');
            Route::put('/{distribution}', [CashDistributionController::class, 'update'])->name('update');
            Route::get('/{distribution}/close', [CashDistributionController::class, 'closeForm'])->name('close.form');
            Route::put('/{distribution}/close', [CashDistributionController::class, 'close'])->name('close');
            Route::put('/{distribution}/update-missing', [CashDistributionController::class, 'updateMissingAmount'])->name('update-missing');
            Route::put('/{distribution}/update-sales', [CashDistributionController::class, 'updateSalesAmount'])->name('update-sales');
        });
});
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Pointeur
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:pointeur,chef_production,dg,pdg,ddg,developper'])->group(function () {
        Route::prefix('pointeur')->name('pointeur.')->group(function () {
            Route::get('/dashboard', [PointeurController::class, 'dashboard'])->name('workspace');
            Route::post('/produits/enregistrer', [PointeurController::class, 'enregistrerProduit'])->name('produits.enregistrer');
            Route::post('/commandes/{commande}/valider', [PointeurController::class, 'validerCommande'])->name('commandes.valider');
            Route::get('/assignation', [PointeurController::class, 'listeProduitsPourAssignation'])->name('assignation.create');
            Route::post('/assignation', [PointeurController::class, 'assignerProduits'])->name('assignation.store');
            Route::get('/assignations', [PointeurController::class, 'listeAssignations'])->name('assignation.liste');
            Route::get('/rapport/vendeurs', [PointeurController::class, 'rapportVendeurs'])->name('rapport.vendeurs');
        });
        
        Route::resource('avaries', Avarie2Controller::class)->only([
            'index', 'create', 'store', 'show'
        ]);

     // Route API pour récupérer le prix d'un produit
        Route::get('api/produit/{id}/prix', [Avarie2Controller::class, 'getPrixProduit'])
            ->name('produit.prix');
    
        
         // Commandes
        Route::get('/chef_production/commandes/create', [Chef_productionController::class, 'createcommande'])->name('chef.commandes.create');
        Route::post('/chef/commandes/store', [Chef_productionController::class, 'storecommande'])->name('chef.commandes.stores');
        Route::post('/chef/commande/store2', [Chef_productionController::class, 'storecommande'])->name('chef.commandes.store2');
        Route::get('/commandes/{id}/edit', [Chef_productionController::class, 'editcommande'])->name('commande.edit');
        Route::put('/commandes/{id}', [Chef_productionController::class, 'updatecommande'])->name('commande.update');
        Route::delete('/commandes/{id}', [Chef_productionController::class, 'destroycommande'])->name('chef.commandes.destroy');
        
        Route::prefix('receptions')->name('receptions.')->group(function () {
            Route::prefix('pointeurs')->name('pointeurs.')->group(function () {
                Route::get('/', [ReceptionPointeurController::class, 'index'])->name('index');
                Route::get('/create', [ReceptionPointeurController::class, 'create'])->name('create');
                Route::post('/', [ReceptionPointeurController::class, 'store'])->name('store');
                Route::get('/{id}', [ReceptionPointeurController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [ReceptionPointeurController::class, 'edit'])->name('edit');
                Route::put('/{id}', [ReceptionPointeurController::class, 'update'])->name('update');
                Route::delete('/{id}', [ReceptionPointeurController::class, 'destroy'])->name('destroy');
    });
});


    });

    /*
    |--------------------------------------------------------------------------
    | Routes Employés (Chef Rayoniste, Caissière, Calviste, etc.)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:chef_rayoniste,caissiere,calviste,magasinier,rayoniste,controleur,tech_surf,virgile,enfourneur,gestionnaire_alimentation,patissier,boulanger,pointeur,glace,vendeur_boulangerie,vendeur_patisserie,chef_production,dg,pdg,ddg,developper'])->group(function () {
        Route::get('/alimDashboard', [EmployeeController::class, 'index'])->name('alim.workspace');
        Route::get('/caisseDashboard', [EmployeeController::class, 'index2'])->name('cashier.workspace');
        Route::get('/mcworkspace', [EmployeeController::class, 'index4'])->name('mc.workspace');

        // Caissier
        Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
        Route::post('/cashier/start-session', [CashierController::class, 'startSession'])->name('cashier.start-session');
        Route::get('/cashier/session/{session}', [CashierController::class, 'showSession'])->name('cashier.session');
        Route::post('/cashier/session/{session}/withdraw', [CashierController::class, 'recordWithdrawal'])->name('cashier.withdraw');
        Route::post('/cashier/session/{session}/end', [CashierController::class, 'endSession'])->name('cashier.end-session');
        Route::get('/cashier/reports', [CashierController::class, 'generateReport'])->name('cashier.reports');

        // Incohérences
        Route::get('/produit_vs_vendu', [IncoherenceController::class, 'index'])->name('incoherence.index');

        // Rations employés
        Route::prefix('rations')->name('rations.')->group(function () {
          
            Route::prefix('employee')->name('employee.')->group(function () {
                Route::post('/claim', [RationController::class, 'claim'])->name('submit-claim');
                Route::get('/claim', [RationController::class, 'claimForm'])->name('claim');
            });
        });

        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/groups', [ProductGroupController::class, 'index'])->name('groups.index');
            Route::post('/groups', [ProductGroupController::class, 'store'])->name('groups.store');
            Route::get('/groups/create', [ProductGroupController::class, 'create'])->name('groups.create');
            Route::get('/groups/{group}', [ProductGroupController::class, 'show'])->name('groups.show');
            Route::put('/groups/{group}', [ProductGroupController::class, 'update'])->name('groups.update');
            Route::delete('/groups/{group}', [ProductGroupController::class, 'destroy'])->name('groups.destroy');
            Route::get('/groups/{group}/edit', [ProductGroupController::class, 'edit'])->name('groups.edit');
            Route::get('/groups/{group}/products', [ProductController::class, 'index'])->name('products.index');
            Route::post('/groups/{group}/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/groups/{group}/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
           
            Route::get('/groups/{group}/calculations', [MissingCalculationController::class, 'index'])->name('calculations.index');
            Route::post('/groups/{group}/calculations', [MissingCalculationController::class, 'store'])->name('calculations.store');
            Route::get('/groups/{group}/calculations/create', [MissingCalculationController::class, 'create'])->name('calculations.create');
            Route::get('/calculations/{calculation}', [MissingCalculationController::class, 'show'])->name('calculations.show');
            Route::put('/calculations/{calculation}', [MissingCalculationController::class, 'update'])->name('calculations.update');
            Route::delete('/calculations/{calculation}', [MissingCalculationController::class, 'destroy'])->name('calculations.destroy');
            Route::post('/calculations/{calculation}/close', [MissingCalculationController::class, 'close'])->name('calculations.close');
            Route::post('/calculations/{calculation}/items', [MissingCalculationController::class, 'addItem'])->name('calculations.add-item');
            Route::put('/calculations/items/{item}', [MissingCalculationController::class, 'updateItem'])->name('calculations.update-item');
            Route::delete('/calculations/items/{item}', [MissingCalculationController::class, 'deleteItem'])->name('calculations.delete-item');
        
        });
        

        // Extras et Delis
    Route::resource('extras', ExtraController::class); //--review
    Route::post('/announcements/{announcement}/react', [AnnouncementController::class, 'storeReaction'])->name('announcements.react'); //--review

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index'); //--review
        Route::post('/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark-read'); //--review
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read'); //--review
        Route::post('/mark-processed/{id}', [NotificationController::class, 'markAsProcessed'])->name('mark-processed'); //--review
        Route::post('/renew/{id}', [NotificationController::class, 'renew'])->name('renew'); //--review
        Route::delete('/{id}', [NotificationController::class, 'delete'])->name('delete'); //--review
        Route::get('/test', [NotificationController::class, 'index'])->name('test'); //--review
        Route::post('/send', [NotificationController::class, 'send'])->name('send'); //--review
        Route::get('/unread', [NotificationController::class, 'unreadNotifications'])->name('unread'); //--review
        Route::post('/send-bulk', [NotificationController::class, 'sendBulk'])->name('send-bulk'); //--review
        Route::post('/schedule', [NotificationController::class, 'schedule'])->name('schedule'); //--review
        Route::post('/retry-failed', [NotificationController::class, 'retryFailed'])->name('retry-failed'); //--review
    });


    // Factures complexes
    Route::resource('factures-complexe', FactureComplexeController::class); //--review
    Route::get('factures-complexe-statistiques', [FactureComplexeController::class, 'statistiques'])->name('factures-complexe.statistiques'); //--review
    Route::get('factures-complexe-en-attente', [FactureComplexeController::class, 'facturesEnAttente'])->name('factures-complexe.en-attente'); //--review
    Route::patch('factures-complexe/{id}/valider', [FactureComplexeController::class, 'valider'])->name('factures-complexe.valider'); //--review
    Route::patch('factures-complexe/{id}/annuler', [FactureComplexeController::class, 'annuler'])->name('factures-complexe.annuler'); //--review
    Route::post('/factures-complexe/{facture}/validate', [FactureComplexeController::class, 'validate'])->name('factures-complexe.validate'); //--review

     
    Route::resource('salaires', SalaireController::class); //--review
    Route::get('/salaires/{id}/fiche-paie', [SalaireController::class, 'fichePaie'])->name('salaires.fiche-paie'); //--review
    Route::post('/salaires/{id}/demande-retrait', [SalaireController::class, 'demandeRetrait'])->name('salaires.demande-retrait'); //--review
    Route::post('/salaires/{id}/valider-retrait', [SalaireController::class, 'validerRetrait'])->name('salaires.valider-retrait'); //--review
    Route::get('/salaires/{id}/generate-pdf', [SalaireController::class, 'generatePDF'])->name('salaires.generate-pdf'); //--review
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index'); //--review
    
    Route::prefix('versements')->name('versements.')->group(function () {
        Route::get('/', [VersementChefController::class, 'index'])->name('index');
        Route::get('/create', [VersementChefController::class, 'create'])->name('create');
        Route::post('/', [VersementChefController::class, 'store'])->name('store');
        Route::get('/{versement}/edit', [VersementChefController::class, 'edit'])->name('edit');
        Route::put('/{versement}', [VersementChefController::class, 'update'])->name('update');
        Route::delete('/{versement}', [VersementChefController::class, 'destroy'])->name('destroy');
        
    });

});

});
//bags
