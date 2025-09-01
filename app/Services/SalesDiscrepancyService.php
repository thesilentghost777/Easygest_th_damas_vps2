<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionVente;
use App\Models\VersementChef;
use App\Models\User;
use App\Models\History;
use Carbon\Carbon;

class SalesDiscrepancyService
{
    /**
     * Collect sales discrepancy data for AI analysis
     */
    public function collectDiscrepancyData($startDate, $endDate)
    {
        Log::info('Collecting sales discrepancy data', [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
        
        try {
            
            // Récupérer les alertes de vol en caisse
            $theftDetections = $this->getTheftDetections();
            
            $data = [
                'theft_detections' => $theftDetections
            ];
            
            Log::info('Sales discrepancy data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting sales discrepancy data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données d\'écarts de vente: ' . $e->getMessage()
            ];
        }
    }
    
  
    /**
     * Get theft detection alerts from history
     */
    private function getTheftDetections()
    {
        return History::where('action_type', 'detection_de_vol_vendeuse')
            ->with('user:id,name,role,secteur')
            ->select('id', 'description', 'user_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'user' => $item->user ? [
                        'id' => $item->user->id,
                        'name' => $item->user->name,
                        'role' => $item->user->role,
                        'secteur' => $item->user->secteur
                    ] : null,
                    'date' => $item->created_at->format('Y-m-d H:i:s')
                ];
            });
    }
}
