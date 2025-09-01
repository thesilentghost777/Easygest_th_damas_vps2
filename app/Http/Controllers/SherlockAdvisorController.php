<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\SherlockAdvisorService;
use App\Models\RapportConfig;
use Carbon\Carbon;

class SherlockAdvisorController extends Controller
{
    protected $sherlockAdvisorService;

    public function __construct(SherlockAdvisorService $sherlockAdvisorService)
    {
        $this->sherlockAdvisorService = $sherlockAdvisorService;
    }

    /**
     * Display the Sherlock Advisor dashboard
     */
    public function index()
    {
        // Get available months for reports (last 12 months)
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $months[$date->format('Y-m')] = $date->locale('fr')->isoFormat('MMMM YYYY');
        }
        
        // Get current configuration
        $config = RapportConfig::first() ?? new RapportConfig();
        
        return view('sherlock.index', compact('months', 'config'));
    }
    
    /**
     * Display configuration page for Sherlock Advisor
     */
    public function configure()
    {
        // Get current configuration
        $config = RapportConfig::first() ?? new RapportConfig();
        
        return view('sherlock.configure', compact('config'));
    }
    
    /**
     * Save configuration for Sherlock Advisor
     */
    public function saveConfig(Request $request)
{
    // Initialise tous les champs à false
    $configData = [
        'analyze_product_performance' => false,
        'analyze_waste' => false,
        'analyze_sales_discrepancies' => false,
        'analyze_employee_performance' => false, 
        'analyze_theft_detection' => false,
        'analyze_material_usage' => false,
        'analyze_spoilage' => false,
        'analyze_objectives' => false,
        'analyze_hr_data' => false,
        'analyze_orders' => false,
        'analyze_market_trends' => false,
        'analyze_event_impact' => false,
        'analyze_ice_cream_sector' => false,
    ];
    
    // Met à jour les valeurs avec celles de la requête (seulement les cases cochées)
    foreach ($configData as $field => $default) {
        $configData[$field] = $request->has($field);
    }
    
    // Récupère la configuration existante ou crée une nouvelle instance
    $config = RapportConfig::first() ?? new RapportConfig();
    
    // Remplit l'objet avec les données
    $config->fill($configData);
    
    // Sauvegarde la configuration
    $config->save();
    
    Log::info('Sherlock Advisor configuration updated', ['config' => $configData]);
    
    return redirect()->route('sherlock.configure')
        ->with('success', 'La configuration a été enregistrée avec succès.');
}

    /**
     * Generate and display the advisor analysis
     */
    public function analyze(Request $request)
    {
        $monthYear = $request->input('month_year', Carbon::now()->format('Y-m'));
        list($year, $month) = explode('-', $monthYear);
        
        Log::info('Starting Sherlock Advisor analysis', [
            'month_year' => $monthYear,
            'month' => $month,
            'year' => $year
        ]);
        
        // Get analysis
        $result = $this->sherlockAdvisorService->generateAnalysis((int)$month, (int)$year);
        
        if (!$result['success']) {
            return redirect()->route('sherlock.index')
                ->with('error', 'Une erreur est survenue lors de l\'analyse: ' . ($result['error'] ?? 'Erreur inconnue'));
        }
        
        return view('sherlock.analysis', [
            'month_year' => $monthYear,
            'month_name' => Carbon::createFromDate($year, $month, 1)->locale('fr')->format('F Y'),
            'analysis' => $result['analysis'],
            'raw_data' => $request->has('debug') ? $result['data'] : null
        ]);
    }
    
    /**
     * Show debug information for developers
     */
    public function debug(Request $request)
    {
        if (!app()->environment('local')) {
            abort(403, 'Cette fonctionnalité est disponible uniquement en environnement de développement.');
        }
        
        $monthYear = $request->input('month_year', Carbon::now()->format('Y-m'));
        list($year, $month) = explode('-', $monthYear);
        
        // Get analysis with full raw data
        $result = $this->sherlockAdvisorService->generateAnalysis((int)$month, (int)$year);
        
        return view('sherlock.debug', [
            'month_year' => $monthYear,
            'month_name' => Carbon::createFromDate($year, $month, 1)->locale('fr')->format('F Y'),
            'raw_data' => $result['data'],
            'success' => $result['success'],
            'error' => $result['error'] ?? null
        ]);
    }
}