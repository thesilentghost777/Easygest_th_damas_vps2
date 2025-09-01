<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\RapportConfig;
use Carbon\Carbon;

class SherlockAdvisorService
{
    protected $aiQueryServiceSherlock;
    protected $productAnalysisService;
    protected $wasteAnalysisService;
    protected $salesDiscrepancyService;
    protected $employeePerformanceService;
    protected $materialUsageService;
    protected $spoilageAnalysisService;
    protected $objectiveAnalysisService;
    protected $hrAnalysisService;
    protected $orderAnalysisService;
    protected $marketAnalysisService;
    protected $iceCreamSectorService;

    public function __construct(
        AIQueryServiceSherlock $aiQueryServiceSherlock,
        ProductAnalysisService $productAnalysisService,
        WasteAnalysisService $wasteAnalysisService,
        SalesDiscrepancyService $salesDiscrepancyService,
        EmployeePerformanceService $employeePerformanceService,
        MaterialUsageService $materialUsageService,
        SpoilageAnalysisService $spoilageAnalysisService,
        ObjectiveAnalysisService $objectiveAnalysisService,
        HRAnalysisService $hrAnalysisService,
        OrderAnalysisService $orderAnalysisService,
        MarketAnalysisService $marketAnalysisService,
        IceCreamSectorService $iceCreamSectorService
    ) {
        $this->aiQueryServiceSherlock = $aiQueryServiceSherlock;
        $this->productAnalysisService = $productAnalysisService;
        $this->wasteAnalysisService = $wasteAnalysisService;
        $this->salesDiscrepancyService = $salesDiscrepancyService;
        $this->employeePerformanceService = $employeePerformanceService;
        $this->materialUsageService = $materialUsageService;
        $this->spoilageAnalysisService = $spoilageAnalysisService;
        $this->objectiveAnalysisService = $objectiveAnalysisService;
        $this->hrAnalysisService = $hrAnalysisService;
        $this->orderAnalysisService = $orderAnalysisService;
        $this->marketAnalysisService = $marketAnalysisService;
        $this->iceCreamSectorService = $iceCreamSectorService;
    }

    /**
     * Generate a comprehensive analysis with AI
     */
    public function generateAnalysis(int $month = null, int $year = null)
    {
        // Use current month if not specified
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        Log::info('Starting Sherlock Advisor analysis', [
            'month' => $month,
            'year' => $year,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ]);
        
        // Get configuration to determine which modules to run
        $config = RapportConfig::first() ?? new RapportConfig();
        
        // Initialize AI with a system prompt
        $this->aiQueryServiceSherlock->initConversation();
        
        // Collect all data based on enabled modules
        $collectedData = [];
        
        // Context information about the business
        $this->aiQueryServiceSherlock->addMessage("Je suis le directeur d'une boulangerie-pâtisserie qui inclut également un secteur de production de glaces. J'ai besoin d'une analyse approfondie de mon activité pour le mois de " . $startDate->locale('fr')->format('F Y') . ".");
        
        try {
            // 1. Product Performance Analysis
            if ($config->analyze_product_performance ?? true) {
                $productData = $this->productAnalysisService->collectProductPerformanceData($startDate, $endDate);
                $this->aiQueryServiceSherlock->addStructuredData("Données de performance des produits", $productData);
                $collectedData['product_performance'] = $productData;
            }
            
            // 2. Waste Analysis
            if ($config->analyze_waste ?? true) {
                $wasteData = $this->wasteAnalysisService->collectWasteData();
                $this->aiQueryServiceSherlock->addStructuredData("Données sur le gaspillage", $wasteData);
                $collectedData['waste'] = $wasteData;
            }
            
            // 3. Sales Discrepancy Analysis
            if ($config->analyze_sales_discrepancies ?? true) {
                $discrepancyData = $this->salesDiscrepancyService->collectDiscrepancyData($startDate, $endDate);
                $this->aiQueryServiceSherlock->addStructuredData("Analyse des écarts de vente et détections de vols potentiels", $discrepancyData);
                $collectedData['sales_discrepancies'] = $discrepancyData;
            }
            
            // 4. Employee Performance Analysis
            if ($config->analyze_employee_performance ?? true) {
                $employeeData = $this->employeePerformanceService->collectEmployeePerformanceData($startDate, $endDate);
                $this->aiQueryServiceSherlock->addStructuredData("Données de performance des employés", $employeeData);
                $collectedData['employee_performance'] = $employeeData;
            }
            
    
            
            // 6. Spoilage Analysis
            if ($config->analyze_spoilage ?? true) {
                $spoilageData = $this->spoilageAnalysisService->collectSpoilageData();
                $this->aiQueryServiceSherlock->addStructuredData("Analyse des avaries et invendus", $spoilageData);
                $collectedData['spoilage'] = $spoilageData;
            }
            
            // 7. Objective Analysis
            if ($config->analyze_objectives ?? true) {
                $objectiveData = $this->objectiveAnalysisService->collectObjectiveData($month, $year);
                $this->aiQueryServiceSherlock->addStructuredData("Analyse des objectifs commerciaux", $objectiveData);
                $collectedData['objectives'] = $objectiveData;
            }
            
            // 8. HR Analysis
            if ($config->analyze_hr_data ?? true) {
                $hrData = $this->hrAnalysisService->collectHRData($month, $year);
                $this->aiQueryServiceSherlock->addStructuredData("Analyse des ressources humaines", $hrData);
                $collectedData['hr_data'] = $hrData;
            }
            
            // 9. Order Analysis
            if ($config->analyze_orders ?? true) {
                $orderData = $this->orderAnalysisService->collectOrderData($month, $year);
                $this->aiQueryServiceSherlock->addStructuredData("Analyse des commandes", $orderData);
                $collectedData['orders'] = $orderData;
            }
            
            // 10. Market Trends Analysis
            if ($config->analyze_market_trends ?? true) {
                $marketData = $this->marketAnalysisService->collectMarketData();
                $this->aiQueryServiceSherlock->addStructuredData("Analyse des tendances du marché", $marketData);
                $collectedData['market_trends'] = $marketData;
            }
            
            // 11. Ice Cream Sector Analysis
            if ($config->analyze_ice_cream_sector ?? true) {
                $iceCreamData = $this->iceCreamSectorService->collectIceCreamData($month, $year);
                $this->aiQueryServiceSherlock->addStructuredData("Analyse du secteur des glaces", $iceCreamData);
                $collectedData['ice_cream_sector'] = $iceCreamData;
            }
            

            $userLanguage = auth()->user()->language;

            if ($userLanguage == 'fr') {
                $langue = 'française';
            } else {
                $langue = 'anglaise';
            }
            // Add final prompt for the analysis
            $this->aiQueryServiceSherlock->addMessage("
                En tant qu'expert en analyse de données pour les boulangeries-pâtisseries aux cameroun et en afrique centrale avec la monnaie principale le FCFA, analyse toutes ces données et produis un rapport complet divisé en sections :
               Aussi produit un document lisible sans les caracetere comme  bizarre
                1. RÉSUMÉ EXÉCUTIF : Une synthèse des points clés et des recommandations prioritaires (max. 300 mots).
                
                2. ANALYSE FINANCIÈRE :
                   - Analyse des coûts de production,gains generer  et marges
                   - Points d'attention et opportunités d'optimisation pour les produits et la production
                
                3. ANALYSE PAR SECTEUR :
                   - Boulangerie-Pâtisserie : performance, produits phares, optimisation
                   - Secteur Glaces : tendances, performance, recommandations spécifiques
                   - Analyse comparative des secteurs
                
                4. GESTION DES RESSOURCES :
                   - Productivité des employés et recommandations RH
                   - Gestion des matières premières et optimisation des recettes
                   - Réduction du gaspillage et des avaries
                
                5. PROBLÈMES DÉTECTÉS :
                   - Anomalies financières et suspicions de vol
                   - Inefficacités dans la production ou la vente
                   - Problèmes de qualité ou de satisfaction client
                
                6. PLAN D'ACTION :
                   - Actions prioritaires à mettre en œuvre immédiatement
                   - Ajustements recommandés à moyen terme
                   - Opportunités stratégiques à étudier
                
                7. EMPLOYES A ENCOURAGER/PUNIR
                    -A partir des donnees,dire en justifiant les employes qu'on devrait encourager
                    -dire en justifiant les employes qu'on devrait retrograder , virer ou sensibiliser pour possible erreurs (si ce n'est pas du vol)
                
                8. OBJECTIFS
                    -analyse breve de l'etat actuel des objectifs
                    -les raisons pour les quelles (s'il y'en a )certains objectifs n'ont pas marcher
                Pour chaque section, sois précis, factuel et actionnable. Utilise des chiffres précis et des exemples concrets. N'hésite pas à signaler clairement les problèmes tout en proposant des solutions concrètes.
                Renvoie le rapport dans la langue {$langue}.

            ");
            
            // Get AI response
            Log::info('Requesting AI analysis with all collected data');
            $cacheKey = 'sherlock_advisor_' . $month . '_' . $year;
            $analysis = $this->aiQueryServiceSherlock->getResponse($cacheKey);
            
            Log::info('AI analysis completed successfully', [
                'analysis_length' => strlen($analysis)
            ]);
            
            return [
                'success' => true,
                'month' => $startDate->locale('fr')->format('F Y'),
                'analysis' => $analysis,
                'data' => $collectedData // Include raw data for debugging
            ];
            
        } catch (\Exception $e) {
            Log::error('Error generating Sherlock Advisor analysis', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'month' => $startDate->locale('fr')->format('F Y'),
                'error' => 'Erreur lors de la génération de l\'analyse: ' . $e->getMessage(),
                'data' => $collectedData // Include partial data for debugging
            ];
        }
    }
}
