<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssignationMatiere;
use App\Models\Utilisation;
use App\Models\User;
use App\Models\Matiere;
use App\Services\UniteConversionService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MatiereUtilisationController extends Controller
{
    protected $conversionService;
    
    public function __construct(UniteConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }
    
    public function rapportJournalier()
    {
        Log::info('Génération du rapport journalier des matières');
        
        // Date d'hier
        $hier = Carbon::yesterday()->toDateString();
        $dateDebut = Carbon::yesterday()->startOfDay();
        $dateFin = Carbon::yesterday()->endOfDay();
        
        Log::info("Période analysée: {$dateDebut} à {$dateFin}");
        
        // Récupérer les assignations d'hier
        $assignations = AssignationMatiere::with(['producteur', 'matiere'])
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->get();
            
        // Récupérer les utilisations d'hier
        $utilisations = Utilisation::with(['producteur', 'matierePremiere', 'produitFixe'])
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->get();
            
        Log::info("Nombre d'assignations récupérées: " . $assignations->count());
        Log::info("Nombre d'utilisations récupérées: " . $utilisations->count());
        
        // Calcul des coûts totaux
        $coutTotalAssignations = $this->calculerCoutTotalAssignations($assignations);
        $coutTotalUtilisations = $this->calculerCoutTotalUtilisations($utilisations);
        $coutTotalRestant = $coutTotalAssignations - $coutTotalUtilisations;
        
        // Alerte si le coût restant est élevé (plus de 10% du coût total assigné)
        $alerteGaspillage = ($coutTotalRestant > 0) && ($coutTotalRestant / $coutTotalAssignations > 0.1);
        
        // Récupérer les producteurs qui ont reçu des assignations hier
        $producteursIds = $assignations->pluck('producteur_id')->unique()->toArray();
        $producteurs = User::whereIn('id', $producteursIds)->get();
        
        // Préparer les données par producteur
        $donneesProducteurs = [];
        
        foreach ($producteurs as $producteur) {
            $assignationsProducteur = $assignations->where('producteur_id', $producteur->id);
            $utilisationsProducteur = $utilisations->where('producteur', $producteur->id);
            
            // Détails des matières assignées
            $detailsMatieres = [];
            $coutTotalMatieresAssignees = 0;
            
            foreach ($assignationsProducteur as $assignation) {
                $matiere = $assignation->matiere;
                $quantiteAssigneeMinimale = $this->conversionService->convertir(
                    $assignation->quantite_assignee,
                    $assignation->unite_assignee,
                    $matiere->unite_minimale
                );
                
                $coutMatiere = $quantiteAssigneeMinimale * $matiere->prix_par_unite_minimale;
                $coutTotalMatieresAssignees += $coutMatiere;
                
                // Calculer la quantité utilisée pour cette matière
                $utilisationsMatiereProducteur = $utilisationsProducteur->where('matierep', $matiere->id);
                $quantiteUtilisee = 0;
                
                foreach ($utilisationsMatiereProducteur as $utilisation) {
                    // Conversion en unité minimale si nécessaire
                    if ($utilisation->unite_matiere !== $matiere->unite_minimale) {
                        $quantiteConvertie = $this->conversionService->convertir(
                            $utilisation->quantite_matiere,
                            $utilisation->unite_matiere,
                            $matiere->unite_minimale
                        );
                        $quantiteUtilisee += $quantiteConvertie;
                        
                        Log::info("Conversion: {$utilisation->quantite_matiere} {$utilisation->unite_matiere} = {$quantiteConvertie} {$matiere->unite_minimale->toString()}");
                    } else {
                        $quantiteUtilisee += $utilisation->quantite_matiere;
                    }
                }
                
                $coutUtilisation = $quantiteUtilisee * $matiere->prix_par_unite_minimale;
                $quantiteRestante = $quantiteAssigneeMinimale - $quantiteUtilisee;
                $coutRestant = $quantiteRestante * $matiere->prix_par_unite_minimale;
                
                $detailsMatieres[] = [
                    'matiere' => $matiere,
                    'quantite_assignee' => $assignation->quantite_assignee,
                    'unite_assignee' => $assignation->unite_assignee,
                    'quantite_assignee_minimale' => $quantiteAssigneeMinimale,
                    'quantite_utilisee' => $quantiteUtilisee,
                    'quantite_restante' => $quantiteRestante,
                    'cout_assignation' => $coutMatiere,
                    'cout_utilisation' => $coutUtilisation,
                    'cout_restant' => $coutRestant
                ];
            }
            
            // Calculer le coût total des produits fabriqués
            $coutTotalProduits = 0;
            $nombreProduitsCrees = 0;
            
            $utilisationsProducteur1 = $utilisations
            ->where('producteur', $producteur->id)
            ->unique('id_lot');
                foreach ($utilisationsProducteur1 as $utilisation) {
                $coutProduit = $utilisation->quantite_produit * $utilisation->produitFixe->prix;
                $coutTotalProduits += $coutProduit;
                $nombreProduitsCrees += $utilisation->quantite_produit;
            }
            
            $efficaciteCout = $coutTotalMatieresAssignees > 0 ? 
                ($coutTotalProduits / $coutTotalMatieresAssignees) * 100 : 0;
            
            // Déterminer la note du producteur (comme dans FIFA) sur 100
            $note = $this->calculerNoteProducteur($efficaciteCout, $coutTotalProduits);
            
            // Déterminer les compétences (comme dans FIFA)
            $competences = $this->determinerCompetences($efficaciteCout, $nombreProduitsCrees, $detailsMatieres);
            
            $donneesProducteurs[] = [
                'producteur' => $producteur,
                'details_matieres' => $detailsMatieres,
                'cout_total_matieres' => $coutTotalMatieresAssignees,
                'cout_total_produits' => $coutTotalProduits,
                'efficacite_cout' => $efficaciteCout,
                'note' => $note,
                'competences' => $competences,
                'nombre_produits_crees' => $nombreProduitsCrees
            ];
        }
        
        // Trier les producteurs par note décroissante
        usort($donneesProducteurs, function ($a, $b) {
            return $b['note'] <=> $a['note'];
        });
        
        Log::info("Données préparées pour " . count($donneesProducteurs) . " producteurs");
        
        return view('matieres.rapport-journalier', compact(
            'hier',
            'coutTotalAssignations',
            'coutTotalUtilisations',
            'coutTotalRestant',
            'alerteGaspillage',
            'donneesProducteurs'
        ));
    }
    
    /**
     * Calcule le coût total des assignations
     */
    private function calculerCoutTotalAssignations($assignations)
    {
        $coutTotal = 0;
        
        foreach ($assignations as $assignation) {
            $matiere = $assignation->matiere;
            
            try {
                // Convertir la quantité assignée en unité minimale
                $quantiteMinimale = $this->conversionService->convertir(
                    $assignation->quantite_assignee,
                    $assignation->unite_assignee,
                    $matiere->unite_minimale
                );
                
                // Calculer le coût
                $cout = $quantiteMinimale * $matiere->prix_par_unite_minimale;
                $coutTotal += $cout;
                
                Log::info("Coût assignation [{$assignation->id}]: {$cout} ({$quantiteMinimale} {$matiere->unite_minimale->toString()} × {$matiere->prix_par_unite_minimale})");
            } catch (\Exception $e) {
                Log::error("Erreur lors du calcul du coût d'assignation [{$assignation->id}]: " . $e->getMessage());
            }
        }
        
        Log::info("Coût total des assignations: {$coutTotal}");
        return $coutTotal;
    }
    
    /**
     * Calcule le coût total des utilisations
     */
    private function calculerCoutTotalUtilisations($utilisations)
    {
        $coutTotal = 0;
        
        foreach ($utilisations as $utilisation) {
            $matiere = Matiere::FindOrFail($utilisation->matierep);
            
            try {
                // Convertir la quantité utilisée en unité minimale si nécessaire
                $quantiteMinimale = $utilisation->unite_matiere !== $matiere->unite_minimale ?
                    $this->conversionService->convertir(
                        $utilisation->quantite_matiere,
                        $utilisation->unite_matiere,
                        $matiere->unite_minimale
                    ) : $utilisation->quantite_matiere;
                
                // Calculer le coût
                $cout = $quantiteMinimale * $matiere->prix_par_unite_minimale;
                Log::info("XXXXXXX-cout pour la matiere {$matiere->nom} : {$quantiteMinimale}x{$matiere->prix_par_unite_minimale}");
                $coutTotal += $cout;
                
                Log::info("Coût utilisation [{$utilisation->id}]: {$cout} ({$quantiteMinimale} {$matiere->unite_minimale->toString()} × {$matiere->prix_par_unite_minimale})");
            } catch (\Exception $e) {
                Log::error("Erreur lors du calcul du coût d'utilisation [{$utilisation->id}]: " . $e->getMessage());
            }
        }
        
        Log::info("Coût total des utilisations: {$coutTotal}");
        return $coutTotal;
    }
    
    /**
     * Calcule la note du producteur (comme dans FIFA)
     */
    private function calculerNoteProducteur($efficaciteCout, $coutTotalProduits)
    {
        // Base de notation: efficacité et volume de production
        $noteBase = 50; // Note minimale
        
        // Bonus pour l'efficacité (ratio valeur produite / coût matières)
        $bonusEfficacite = min(30, max(0, ($efficaciteCout - 100) / 2));
        
        // Bonus pour le volume de production (max 20 points)
        $bonusVolume = min(20, $coutTotalProduits / 1000);
        
        $note = $noteBase + $bonusEfficacite + $bonusVolume;
        
        // Limiter entre 0 et 99
        return min(99, max(0, round($note)));
    }
    
    /**
     * Détermine les compétences du producteur (comme dans FIFA)
     */
    private function determinerCompetences($efficaciteCout, $nombreProduits, $detailsMatieres)
    {
        $competences = [];
        
        // Efficacité
        if ($efficaciteCout >= 130) {
            $competences['Efficacité'] = 90;
        } elseif ($efficaciteCout >= 110) {
            $competences['Efficacité'] = 80;
        } elseif ($efficaciteCout >= 100) {
            $competences['Efficacité'] = 70;
        } else {
            $competences['Efficacité'] = 60;
        }
        
        // Productivité
        if ($nombreProduits >= 500) {
            $competences['Productivité'] = 90;
        } elseif ($nombreProduits >= 300) {
            $competences['Productivité'] = 80;
        } elseif ($nombreProduits >= 100) {
            $competences['Productivité'] = 70;
        } else {
            $competences['Productivité'] = 60;
        }
        
        // Gestion des ressources
        $totalRestant = 0;
        $totalAssigne = 0;
        
        foreach ($detailsMatieres as $detail) {
            $totalRestant += max(0, $detail['quantite_restante']);
            $totalAssigne += $detail['quantite_assignee_minimale'];
        }
        
        $tauxUtilisation = $totalAssigne > 0 ? (($totalAssigne - $totalRestant) / $totalAssigne) * 100 : 0;
        
        if ($tauxUtilisation >= 95) {
            $competences['Gestion'] = 90;
        } elseif ($tauxUtilisation >= 90) {
            $competences['Gestion'] = 80;
        } elseif ($tauxUtilisation >= 80) {
            $competences['Gestion'] = 70;
        } else {
            $competences['Gestion'] = 60;
        }
        
        return $competences;
    }
}
