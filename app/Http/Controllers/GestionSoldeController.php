<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GestionSoldeController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer le solde actuel
        $soldeActuel = DB::table('solde_cp')->first();
        
        // Date par défaut : mois courant
        $dateDebut = $request->input('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Dépenses diverses (réparation, fiscale, autre)
        $depensesDiverses = $this->getDepensesByType(
            ['reparation', 'depense_fiscale', 'autre'], 
            $dateDebut, 
            $dateFin
        );
        
        // Dépenses matière (achat_matiere, livraison_matiere)
        $depensesMatiere = $this->getDepensesByType(
            ['achat_matiere', 'livraison_matiere'], 
            $dateDebut, 
            $dateFin
        );
        
        // Historique total
        $historiqueTotal = $this->getHistoriqueTotal($dateDebut, $dateFin);
        
        // Statistiques
        $stats = $this->getStatistiques($dateDebut, $dateFin);

        return view('gestion_solde.index', compact(
            'soldeActuel',
            'depensesDiverses',
            'depensesMatiere',
            'historiqueTotal',
            'stats',
            'dateDebut',
            'dateFin'
        ));
    }
    
    private function getDepensesByType(array $types, $dateDebut, $dateFin)
    {
        return DB::table('depenses')
            ->join('users', 'depenses.auteur', '=', 'users.id')
            ->leftJoin('Matiere', 'depenses.idm', '=', 'Matiere.id')
            ->select(
                'depenses.*',
                'users.name as auteur_name',
                'Matiere.nom as matiere_nom'
            )
            ->whereIn('depenses.type', $types)
            ->whereBetween('depenses.date', [$dateDebut, $dateFin])
            ->where('depenses.valider', true)
            ->orderBy('depenses.date', 'desc')
            ->get();
    }
    
    private function getHistoriqueTotal($dateDebut, $dateFin)
    {
        return DB::table('historique_solde_cp')
            ->join('users', 'historique_solde_cp.user_id', '=', 'users.id')
            ->select(
                'historique_solde_cp.*',
                'users.name as user_name'
            )
            ->whereBetween('historique_solde_cp.created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->orderBy('historique_solde_cp.created_at', 'desc')
            ->get();
    }
    
    private function getStatistiques($dateDebut, $dateFin)
    {
        $totalDepensesDiverses = DB::table('depenses')
            ->whereIn('type', ['reparation', 'depense_fiscale', 'autre'])
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->where('valider', true)
            ->sum('prix');
            
        $totalDepensesMatiere = DB::table('depenses')
            ->whereIn('type', ['achat_matiere', 'livraison_matiere'])
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->where('valider', true)
            ->sum('prix');
            
        $nombreOperations = DB::table('historique_solde_cp')
            ->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->count();
            
        return [
            'total_depenses_diverses' => $totalDepensesDiverses,
            'total_depenses_matiere' => $totalDepensesMatiere,
            'nombre_operations' => $nombreOperations,
            'total_general' => $totalDepensesDiverses + $totalDepensesMatiere
        ];
    }
    
    public function export(Request $request)
    {
        $type = $request->input('type', 'all');
        $dateDebut = $request->input('date_debut', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Logique d'export selon le type
        switch ($type) {
            case 'diverses':
                return $this->exportDepensesDiverses($dateDebut, $dateFin);
            case 'matiere':
                return $this->exportDepensesMatiere($dateDebut, $dateFin);
            case 'historique':
                return $this->exportHistorique($dateDebut, $dateFin);
            default:
                return $this->exportAll($dateDebut, $dateFin);
        }
    }
    
    private function exportDepensesDiverses($dateDebut, $dateFin)
    {
        // Implémentation export CSV/Excel pour dépenses diverses
        return response()->json(['message' => 'Export dépenses diverses']);
    }
    
    private function exportDepensesMatiere($dateDebut, $dateFin)
    {
        // Implémentation export CSV/Excel pour dépenses matière
        return response()->json(['message' => 'Export dépenses matière']);
    }
    
    private function exportHistorique($dateDebut, $dateFin)
    {
        // Implémentation export CSV/Excel pour historique
        return response()->json(['message' => 'Export historique']);
    }
    
    private function exportAll($dateDebut, $dateFin)
    {
        // Implémentation export complet
        return response()->json(['message' => 'Export complet']);
    }
}