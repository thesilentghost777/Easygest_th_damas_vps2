<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Horaire;
use App\Models\User;
use Carbon\Carbon;

class HRAnalysisService
{
    /**
     * Collect HR analysis data for AI analysis
     */
    public function collectHRData($month, $year)
    {
        Log::info('Collecting HR analysis data', [
            'month' => $month,
            'year' => $year
        ]);
        
        try {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            // Analyse de présence
            $presenceData = $this->analyzePresence($startDate, $endDate);
            
            // Heures travaillées par employé
            $workHoursData = $this->analyzeWorkHours($startDate, $endDate);
            
            // Retards et absences
            $latenessData = $this->analyzeLatenessAndAbsences($startDate, $endDate);
            
            $data = [
                'presence' => $presenceData,
                'work_hours' => $workHoursData,
                'lateness' => $latenessData
            ];
            
            Log::info('HR data collected successfully', [
                'data_size' => strlen(json_encode($data))
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error collecting HR data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'Erreur lors de la collecte des données RH: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Analyze employee presence
     */
    private function analyzePresence($startDate, $endDate)
    {
        // Obtenir tous les horaires pour la période
        $horaires = Horaire::whereBetween('arrive', [$startDate, $endDate])
            ->orWhereBetween('depart', [$startDate, $endDate])
            ->get();
            
        // Grouper par employé
        $presenceParEmploye = $horaires->groupBy('employe')
            ->map(function($employeHoraires, $employeId) {
                $user = User::find($employeId);
                $totalJoursTravailles = $employeHoraires->count();
                $totalHeuresTravaillees = $employeHoraires->sum(function($horaire) {
                    if ($horaire->arrive && $horaire->depart) {
                        return $horaire->arrive->diffInHours($horaire->depart);
                    }
                    return 0;
                });
                
                return [
                    'id' => $employeId,
                    'name' => $user ? $user->name : 'Employé #' . $employeId,
                    'role' => $user ? $user->role : 'Inconnu',
                    'secteur' => $user ? $user->secteur : 'Inconnu',
                    'jours_travailles' => $totalJoursTravailles,
                    'heures_travaillees' => $totalHeuresTravaillees,
                    'moyenne_heures_par_jour' => $totalJoursTravailles > 0 ? $totalHeuresTravaillees / $totalJoursTravailles : 0
                ];
            })
            ->values()
            ->all();
            
        // Statistiques globales
        $totalEmployes = count($presenceParEmploye);
        $moyenneJoursTravailles = collect($presenceParEmploye)->avg('jours_travailles');
        $moyenneHeuresTravaillees = collect($presenceParEmploye)->avg('heures_travaillees');
        
        return [
            'stats' => [
                'total_employes' => $totalEmployes,
                'moyenne_jours_travailles' => $moyenneJoursTravailles,
                'moyenne_heures_travaillees' => $moyenneHeuresTravaillees
            ],
            'presence_par_employe' => $presenceParEmploye
        ];
    }
    
    /**
     * Analyze work hours
     */
    private function analyzeWorkHours($startDate, $endDate)
    {
        // Obtenir tous les horaires pour la période
        $horairesPeriode = Horaire::join('users', 'Horaire.employe', '=', 'users.id')
            ->whereBetween('arrive', [$startDate, $endDate])
            ->select(
                'Horaire.id',
                'Horaire.employe',
                'users.name as employee_name',
                'users.role',
                'users.secteur',
                'Horaire.arrive',
                'Horaire.depart'
            )
            ->get();
            
        // Heures travaillées par jour de la semaine
        $heuresParJour = [0, 0, 0, 0, 0, 0, 0]; // dimanche à samedi
        
        foreach ($horairesPeriode as $horaire) {
            if ($horaire->arrive && $horaire->depart) {
                $jourSemaine = $horaire->arrive->dayOfWeek;
                $heuresTravaillees = $horaire->arrive->diffInHours($horaire->depart);
                $heuresParJour[$jourSemaine] += $heuresTravaillees;
            }
        }
        
        // Formater les données par jour
        $joursNoms = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $heuresFormatees = [];
        
        for ($i = 0; $i < 7; $i++) {
            $heuresFormatees[] = [
                'jour' => $joursNoms[$i],
                'heures' => $heuresParJour[$i]
            ];
        }
        
        // Heures travaillées par secteur
        $heuresParSecteur = $horairesPeriode
            ->groupBy('secteur')
            ->map(function($horaires, $secteur) {
                return [
                    'secteur' => $secteur ?: 'Non défini',
                    'heures' => $horaires->sum(function($h) {
                        return $h->arrive && $h->depart ? $h->arrive->diffInHours($h->depart) : 0;
                    })
                ];
            })
            ->values()
            ->all();
            
        return [
            'heures_par_jour' => $heuresFormatees,
            'heures_par_secteur' => $heuresParSecteur,
            'total_heures' => array_sum($heuresParJour)
        ];
    }
    
    /**
     * Analyze lateness and absences
     */
    private function analyzeLatenessAndAbsences($startDate, $endDate)
    {
        // Obtenir tous les employés actifs
        $employes = User::whereNotNull('role')
            ->whereNotNull('secteur')
            ->get();
            
        // Nombre de jours ouvrés dans la période
        $nbJoursOuvres = $this->getWorkingDaysCount($startDate, $endDate);
        
        // Analyser les présences et absences
        $presenceAbsence = [];
        
        foreach ($employes as $employe) {
            // Jours de présence réels
            $joursPresence = Horaire::where('employe', $employe->id)
                ->whereNotNull('arrive')
                ->whereBetween('arrive', [$startDate, $endDate])
                ->distinct('DATE(arrive)')
                ->count();
                
            // Calculer les retards (arrivée après 8h)
            $retards = Horaire::where('employe', $employe->id)
                ->whereBetween('arrive', [$startDate, $endDate])
                ->whereRaw('HOUR(arrive) >= 8 AND MINUTE(arrive) > 0')
                ->count();
                
            // Calculer les absences
            $absences = $nbJoursOuvres - $joursPresence;
            
            $presenceAbsence[] = [
                'id' => $employe->id,
                'name' => $employe->name,
                'role' => $employe->role,
                'secteur' => $employe->secteur,
                'jours_presence' => $joursPresence,
                'jours_absence' => max(0, $absences),
                'retards' => $retards,
                'taux_presence' => $nbJoursOuvres > 0 ? ($joursPresence / $nbJoursOuvres) * 100 : 0
            ];
        }
        
        return [
            'jours_ouvres' => $nbJoursOuvres,
            'presence_absence' => $presenceAbsence
        ];
    }
    
    /**
     * Get working days count between two dates
     */
    private function getWorkingDaysCount($startDate, $endDate)
    {
        $days = 0;
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            // Exclure weekend (0 = dimanche, 6 = samedi)
            if ($current->dayOfWeek !== 0 && $current->dayOfWeek !== 6) {
                $days++;
            }
            $current->addDay();
        }
        
        return $days;
    }
}
