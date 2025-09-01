<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\MatiereRecommander;
use App\Models\Produit_fixes;
use App\Models\Utilisation;
use App\Enums\UniteMinimale;
use App\Services\UniteConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GaspillageController extends Controller
{
    protected $uniteConversionService;
    
    public function __construct(UniteConversionService $uniteConversionService)
    {
        $this->uniteConversionService = $uniteConversionService;
    }
    
    /**
     * Affiche la page principale du module de gaspillage
     */
    public function choose_produit(){
        $produits = Produit_fixes::all();
        return view('gaspillage.details-produit-choose',compact('produits'));
    }

    public function choose_matiere(){
        $matieres = Matiere::whereRaw("LOWER(nom) NOT LIKE 'taules%'")
        ->whereRaw("LOWER(nom) NOT LIKE 'produit avarier%'")
        ->get();
        return view('gaspillage.details-matiere-choose',compact('matieres'));
    }
    public function index()
    {
        Log::info("Loading gaspillage index page");
        
        // Statistiques globales de gaspillage
        $statsGlobales = $this->getStatsGlobales();
        
        // Top 5 des productions avec le plus de gaspillage (les plus récentes)
        $topProductionsGaspillage = $this->getTopProductionsGaspillage();
        
        // Top 5 des produits avec le plus de gaspillage
        $topProduitsGaspillage = $this->getTopProduitsGaspillage();
        
        // Top 5 des matières avec le plus de gaspillage
        $topMatieresGaspillage = $this->getTopMatieresGaspillage();
        
        // Données pour le graphique d'évolution du gaspillage
        $evolutionGaspillage = $this->getEvolutionGaspillage();
        
        return view('gaspillage.index', compact(
            'statsGlobales',
            'topProductionsGaspillage',
            'topProduitsGaspillage',
            'topMatieresGaspillage',
            'evolutionGaspillage'
        ));
    }
    
    /**
     * Affiche les détails du gaspillage pour une production spécifique
     */
    public function detailsProduction($idLot)
    {
        Log::info("Viewing production details for lot: {$idLot}");
        
        $production = Utilisation::where('id_lot', $idLot)
            ->with(['produitFixe', 'matierePremiere', 'producteur'])
            ->get();
        
        if ($production->isEmpty()) {
            Log::warning("Production not found for lot: {$idLot}");
            return redirect()->route('gaspillage.index')->with('error', 'Production introuvable');
        }
        
        $detailsGaspillage = $this->calculerGaspillageProduction($idLot);
        
        return view('gaspillage.details-production', compact('production', 'detailsGaspillage'));
    }
    
    /**
     * Affiche les détails du gaspillage par produit
     */
    public function detailsProduit($codeProduit)
    {
        Log::info("Viewing waste details for product: {$codeProduit}");
        
        $produit = Produit_fixes::findOrFail($codeProduit);
        $detailsGaspillage = $this->calculerGaspillageProduit($codeProduit);
        
        return view('gaspillage.details-produit', compact('produit', 'detailsGaspillage'));
    }
    
    /**
     * Affiche les détails du gaspillage par matière
     */
    public function detailsMatiere($idMatiere)
    {
        Log::info("Viewing waste details for material: {$idMatiere}");
        
        $matiere = Matiere::findOrFail($idMatiere);
        $detailsGaspillage = $this->calculerGaspillageMatiere($idMatiere);
        
        return view('gaspillage.details-matiere', compact('matiere', 'detailsGaspillage'));
    }
    
    /**
     * Récupère les statistiques globales du gaspillage
     */
    public function getStatsGlobales()
    {
        Log::info("Calculating global waste statistics");
        
        // Utiliser les modèles avec les nouvelles méthodes pour le calcul du gaspillage
        $utilisations = Utilisation::with(['matierePremiere', 'produitFixe'])->get();
        
        $nbProductionsAvecGaspillage = 0;
        $valeurTotaleGaspillage = 0;
        $pourcentagesGaspillage = [];
        
        // Grouper les utilisations par lot de production
        $utilisationsParLot = $utilisations->groupBy('id_lot');
        
        foreach ($utilisationsParLot as $idLot => $lotUtilisations) {
            $hasWaste = false;
            $totalWasteValue = 0;
            $totalWastePercentage = 0;
            $count = 0;
            
            foreach ($lotUtilisations as $utilisation) {
                $wastedQuantity = $utilisation->getWastedQuantity();
                
                // Vérifier s'il y a une recommandation pour cette matière
                $recommandation = MatiereRecommander::where('produit', $utilisation->produit)
                    ->where('matierep', $utilisation->matierep)
                    ->first();
                
                if (!$recommandation) {
                    // Pas de recommandation = gaspillage total
                    $wastedQuantity = $utilisation->quantite_matiere;
                    Log::info("No recommendation found for material {$utilisation->matierep} in product {$utilisation->produit} - considering total quantity as waste: {$wastedQuantity}");
                }
                
                if ($wastedQuantity !== null && $wastedQuantity > 0) {
                    $hasWaste = true;
                    
                    if (!$recommandation) {
                        // Calcul de la valeur gaspillée pour matière non recommandée
                        $wastedValue = $wastedQuantity * $utilisation->matierePremiere->prix_par_unite_minimale;
                    } else {
                        $wastedValue = $utilisation->getWastedValue();
                    }
                    
                    if ($wastedValue !== null) {
                        $totalWasteValue += $wastedValue;
                    }
                    
                    if ($utilisation->quantite_matiere > 0) {
                        $percentage = ($wastedQuantity / $utilisation->quantite_matiere) * 100;
                        $totalWastePercentage += $percentage;
                        $count++;
                    }
                }
            }
            
            if ($hasWaste) {
                $nbProductionsAvecGaspillage++;
                $valeurTotaleGaspillage += $totalWasteValue;
                
                if ($count > 0) {
                    $pourcentagesGaspillage[] = $totalWastePercentage / $count;
                }
            }
        }
        
        $pourcentageMoyenGaspillage = !empty($pourcentagesGaspillage) 
            ? array_sum($pourcentagesGaspillage) / count($pourcentagesGaspillage)
            : 0;
        
        $stats = [
            'nbProductionsAvecGaspillage' => $nbProductionsAvecGaspillage,
            'valeurTotaleGaspillage' => $valeurTotaleGaspillage,
            'pourcentageMoyenGaspillage' => $pourcentageMoyenGaspillage
        ];
        
        Log::info("Global waste statistics calculated", $stats);
        
        return $stats;
    }
    
    /**
     * Récupère le top des productions avec le plus de gaspillage
     */
    public function getTopProductionsGaspillage($limit = 35)
    {
        Log::info("Getting top waste productions, limit: {$limit}");
        
        $result = [];
        
        // Récupérer toutes les utilisations avec leurs relations nécessaires
        $utilisations = Utilisation::with(['produitFixe', 'matierePremiere'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Grouper les utilisations par lot
        $lotGrouped = $utilisations->groupBy('id_lot');
        
        foreach ($lotGrouped as $idLot => $lotUtilisations) {
            $valeurGaspillage = 0;
            $quantiteGaspillage = 0;
            $nomProduit = $lotUtilisations->first()->produitFixe->nom ?? 'Inconnu';
            $dateProduction = $lotUtilisations->max('created_at');
            
            Log::info("Processing lot: {$idLot}, product: {$nomProduit}");
            
            foreach ($lotUtilisations as $utilisation) {
                // Vérifier s'il y a une recommandation pour cette matière
                $recommandation = MatiereRecommander::where('produit', $utilisation->produit)
                    ->where('matierep', $utilisation->matierep)
                    ->first();
                
                if (!$recommandation) {
                    #verifier si la matiere est 'Taule%'
                    if (str_starts_with(strtolower($utilisation->matierePremiere->nom), 'taules')) {
                        Log::info("Material {$utilisation->matierePremiere->nom} is a sheet metal - not considering as waste");
                        #skip
                        continue;
                    }
                    // Pas de recommandation = gaspillage total
                    $wastedQuantity = $utilisation->quantite_matiere;
                    $wastedValue = $wastedQuantity * $utilisation->matierePremiere->prix_par_unite_minimale;
                    Log::info("No recommendation - total waste for utilization ID {$utilisation->id}: {$wastedQuantity} {$utilisation->matierePremiere->unite_minimale->toString()}");
                } else {
                    $wastedQuantity = $utilisation->getWastedQuantity();
                    $wastedValue = $utilisation->getWastedValue();
                }
                
                if ($wastedQuantity !== null && $wastedQuantity > 0) {
                    Log::info("Waste found for utilization ID {$utilisation->id}: {$wastedQuantity}");
                    $quantiteGaspillage += $wastedQuantity;
                    
                    if ($wastedValue !== null) {
                        $valeurGaspillage += $wastedValue;
                    }
                }
            }
            
            if ($quantiteGaspillage > 0) {
                $result[] = (object) [
                    'id_lot' => $idLot,
                    'nom_produit' => $nomProduit,
                    'date_production' => $dateProduction,
                    'valeur_gaspillage' => $valeurGaspillage,
                    'quantite_gaspillage' => $quantiteGaspillage
                ];
                
                Log::info("Added lot to results with waste value: {$valeurGaspillage}");
            }
        }
        
        // Trier par date de production (la plus récente d'abord)
        usort($result, function ($a, $b) {
            return $b->date_production <=> $a->date_production;
        });
        
        return array_slice($result, 0, $limit);
    }
    
    /**
     * Récupère le top des produits avec le plus de gaspillage
     */
    public function getTopProduitsGaspillage($limit = 5)
    {
        Log::info("Getting top waste products, limit: {$limit}");
        
        $result = [];
        
        // Récupérer tous les produits fixes
        $produits = Produit_fixes::all();
        
        foreach ($produits as $produit) {
            $utilisations = Utilisation::with(['matierePremiere'])
                ->where('produit', $produit->code_produit)
                ->get();
            
            if ($utilisations->isEmpty()) {
                continue;
            }
            
            $valeurGaspillage = 0;
            $quantiteGaspillage = 0;
            $nbProductions = $utilisations->groupBy('id_lot')->count();
            
            Log::info("Processing product: {$produit->nom}, code: {$produit->code_produit}");
            
            foreach ($utilisations as $utilisation) {
                // Vérifier s'il y a une recommandation pour cette matière
                $recommandation = MatiereRecommander::where('produit', $utilisation->produit)
                    ->where('matierep', $utilisation->matierep)
                    ->first();
                
                if (!$recommandation) {
                    #verifier si la matiere est 'Taule%'
                    if (str_starts_with(strtolower($utilisation->matierePremiere->nom), 'taules')) {
                        Log::info("Material {$utilisation->matierePremiere->nom} is a sheet metal - not considering as waste");
                        #skip
                        continue;
                    }
                    // Pas de recommandation = gaspillage total
                    $wastedQuantity = $utilisation->quantite_matiere;
                    $wastedValue = $wastedQuantity * $utilisation->matierePremiere->prix_par_unite_minimale;
                } else {
                    $wastedQuantity = $utilisation->getWastedQuantity();
                    $wastedValue = $utilisation->getWastedValue();
                }
                
                if ($wastedQuantity !== null && $wastedQuantity > 0) {
                    $quantiteGaspillage += $wastedQuantity;
                    
                    if ($wastedValue !== null) {
                        $valeurGaspillage += $wastedValue;
                    }
                }
            }
            
            if ($quantiteGaspillage > 0) {
                $result[] = (object) [
                    'code_produit' => $produit->code_produit,
                    'nom_produit' => $produit->nom,
                    'nb_productions' => $nbProductions,
                    'valeur_gaspillage' => $valeurGaspillage,
                    'quantite_gaspillage' => $quantiteGaspillage
                ];
                
                Log::info("Added product to results with waste value: {$valeurGaspillage}");
            }
        }
        
        // Trier par valeur de gaspillage (la plus élevée d'abord)
        usort($result, function ($a, $b) {
            return $b->valeur_gaspillage <=> $a->valeur_gaspillage;
        });
        
        return array_slice($result, 0, $limit);
    }
    
    /**
     * Récupère le top des matières avec le plus de gaspillage
     */
    public function getTopMatieresGaspillage($limit = 5)
    {
        Log::info("Getting top waste materials, limit: {$limit}");
        
        $result = [];
        
        // Récupérer toutes les matières
        $matieres = Matiere::all();
        
        foreach ($matieres as $matiere) {
            $utilisations = Utilisation::where('matierep', $matiere->id)->get();
            
            if ($utilisations->isEmpty()) {
                continue;
            }
            
            $valeurGaspillage = 0;
            $quantiteGaspillage = 0;
            $nbProductions = $utilisations->groupBy('id_lot')->count();
            
            Log::info("Processing material: {$matiere->nom}, id: {$matiere->id}");
            
            foreach ($utilisations as $utilisation) {
                // Vérifier s'il y a une recommandation pour cette matière
                $recommandation = MatiereRecommander::where('produit', $utilisation->produit)
                    ->where('matierep', $utilisation->matierep)
                    ->first();
                
                if (!$recommandation) {
                    #verifier si la matiere est 'Taule%'
                    if (str_starts_with(strtolower($matiere->nom), 'taules')) {
                        Log::info("Material {$matiere->nom} is a sheet metal - not considering as waste");
                        #skip
                        continue;
                    }
                    // Pas de recommandation = gaspillage total
                    $wastedQuantity = $utilisation->quantite_matiere;
                    $wastedValue = $wastedQuantity * $matiere->prix_par_unite_minimale;
                } else {
                    $wastedQuantity = $utilisation->getWastedQuantity();
                    $wastedValue = $utilisation->getWastedValue();
                }
                
                if ($wastedQuantity !== null && $wastedQuantity > 0) {
                    $quantiteGaspillage += $wastedQuantity;
                    
                    if ($wastedValue !== null) {
                        $valeurGaspillage += $wastedValue;
                    }
                }
            }
            
            if ($quantiteGaspillage > 0) {
                $result[] = (object) [
                    'id' => $matiere->id,
                    'nom_matiere' => $matiere->nom,
                    'unite_minimale' => $matiere->unite_minimale,
                    'nb_productions' => $nbProductions,
                    'valeur_gaspillage' => $valeurGaspillage,
                    'quantite_gaspillage' => $quantiteGaspillage
                ];
                
                Log::info("Added material to results with waste value: {$valeurGaspillage}");
            }
        }
        
        // Trier par valeur de gaspillage (la plus élevée d'abord)
        usort($result, function ($a, $b) {
            return $b->valeur_gaspillage <=> $a->valeur_gaspillage;
        });
        
        return array_slice($result, 0, $limit);
    }
    
    /**
     * Récupère l'évolution du gaspillage sur les 30 derniers jours
     */
    public function getEvolutionGaspillage($jours = 30)
    {
        $dateDebut = Carbon::now()->subDays($jours)->startOfDay();
        $dateFin = Carbon::now()->endOfDay();
        
        Log::info("Getting waste evolution from {$dateDebut} to {$dateFin}");
        
        $result = [];
        
        // Générer toutes les dates dans la plage (format Y-m-d seulement)
        $currentDate = clone $dateDebut;
        while ($currentDate <= $dateFin) {
            $formattedDate = $currentDate->format('Y-m-d'); // Changé ici
            $result[$formattedDate] = [
                'date' => $formattedDate,
                'valeur_gaspillage' => 0,
                'nb_productions' => 0
            ];
            $currentDate->addDay();
        }
        
        // Récupérer les utilisations dans la plage de dates
        $utilisations = Utilisation::with(['matierePremiere'])
            ->whereDate('created_at', '>=', $dateDebut)
            ->whereDate('created_at', '<=', $dateFin)
            ->get();
        
        Log::info("Found " . $utilisations->count() . " utilisations");
        
        // Grouper par date (Y-m-d) et par lot
        $utilisationsParDateEtLot = $utilisations->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d') . '|' . $item->id_lot; // Utiliser | au lieu de -
        });
        
        Log::info("Grouped into " . count($utilisationsParDateEtLot) . " date-lot combinations");
        
        foreach ($utilisationsParDateEtLot as $key => $lotUtilisations) {
            // Séparer la date (Y-m-d) du lot en utilisant le séparateur |
            list($date, $idLot) = explode('|', $key);
            
            $hasWaste = false;
            $valeurGaspillage = 0;
            
            Log::info("Processing date-lot: {$key}");
            
            foreach ($lotUtilisations as $utilisation) {
                // Vérifier s'il y a une recommandation pour cette matière
                $recommandation = MatiereRecommander::where('produit', $utilisation->produit)
                    ->where('matierep', $utilisation->matierep)
                    ->first();
                
                if (!$recommandation) {
                    // Pas de recommandation = gaspillage total
                    $wastedValue = $utilisation->quantite_matiere * $utilisation->matierePremiere->prix_par_unite_minimale;
                } else {
                    $wastedValue = $utilisation->getWastedValue();
                }
                
                Log::info("Utilization ID {$utilisation->id}: waste value = " . ($wastedValue ?? 'null'));
                
                if ($wastedValue !== null && $wastedValue > 0) {
                    $hasWaste = true;
                    $valeurGaspillage += $wastedValue;
                    Log::info("Added waste value: {$wastedValue} for utilization ID {$utilisation->id}");
                }
            }
            
            // Vérifier si la clé existe dans $result
            if ($hasWaste) {
                if (isset($result[$date])) {
                    $result[$date]['valeur_gaspillage'] += $valeurGaspillage;
                    $result[$date]['nb_productions']++;
                    Log::info("Updated date {$date} with waste value: {$valeurGaspillage}");
                } else {
                    Log::warning("Date key {$date} not found in result array. Available keys: " . implode(', ', array_keys($result)));
                }
            }
        }
        
        // Convertir en tableau indexé
        $output = array_values($result);
        
        Log::info("Final output: " . json_encode($output));
        
        return $output;
    }
    
    /**
     * Calcule le détail du gaspillage pour une production spécifique
     */
    public function calculerGaspillageProduction($idLot)
    {
        Log::info("Calculating waste details for production: {$idLot}");
        
        $result = [];
        
        // Récupérer toutes les utilisations pour ce lot
        $utilisations = Utilisation::where('id_lot', $idLot)
            ->with(['produitFixe', 'matierePremiere'])
            ->get();
        
        foreach ($utilisations as $utilisation) {
            Log::info("Processing utilization ID {$utilisation->id}, material: {$utilisation->matierep}");
            
            // Trouver la recommandation correspondante
            $recommandation = MatiereRecommander::where('produit', $utilisation->produit)
                ->where('matierep', $utilisation->matierep)
                ->first();
            
            if (!$recommandation) {
                // Pas de recommandation = gaspillage total
                # vérifier si la matière est 'Taule%'
                if (str_starts_with(strtolower($utilisation->matierePremiere->nom), 'taules')) {
                    Log::info("Material {$utilisation->matierePremiere->nom} is a sheet metal - not considering as waste");
                    continue; // Skip this material
                }
                $quantiteRecommandee = 0;
                $quantiteGaspillee = $utilisation->quantite_matiere;
                $pourcentageGaspillage = 100;
                $valeurGaspillage = $quantiteGaspillee * $utilisation->matierePremiere->prix_par_unite_minimale;
                
                Log::info("No recommendation found - considering total quantity as waste", [
                    'quantite_utilisee' => "{$utilisation->quantite_matiere} {$utilisation->unite_matiere}",
                    'quantite_gaspillee' => $quantiteGaspillee,
                    'valeur_gaspillage' => $valeurGaspillage
                ]);
            } else {
                // Calculer la quantité recommandée (déjà convertie en unité minimale)
                $quantiteRecommandee = $recommandation->getRecommendedQuantityFor($utilisation->quantite_produit);
                
                // Calculer la quantité gaspillée
                $quantiteGaspillee = $utilisation->quantite_matiere - $quantiteRecommandee;
                
                Log::info("Waste calculation", [
                    'quantite_utilisee' => "{$utilisation->quantite_matiere} {$utilisation->unite_matiere}",
                    'quantite_recommandee' => "{$quantiteRecommandee} {$utilisation->matierePremiere->unite_minimale->toString()}",
                    'quantite_gaspillee' => $quantiteGaspillee
                ]);
                
                // Ne considérer que les gaspillages positifs (utilisation > recommandation)
                if ($quantiteGaspillee <= 0) {
                    Log::info("No waste detected: {$quantiteGaspillee} <= 0");
                    continue;
                }
                
                // Calculer le pourcentage de gaspillage
                $pourcentageGaspillage = ($quantiteGaspillee / $utilisation->quantite_matiere) * 100;
                
                // Calculer la valeur du gaspillage
                $valeurGaspillage = $quantiteGaspillee * $utilisation->matierePremiere->prix_par_unite_minimale;
            }
            
            Log::info("Waste value: {$valeurGaspillage}");
            
            $result[] = (object) [
                'id' => $utilisation->id,
                'nom_produit' => $utilisation->produitFixe->nom ?? 'Inconnu',
                'nom_matiere' => $utilisation->matierePremiere->nom ?? 'Inconnue',
                'quantite_utilisee' => $utilisation->quantite_matiere,
                'quantite_recommandee' => $quantiteRecommandee,
                'quantite_gaspillee' => $quantiteGaspillee,
                'pourcentage_gaspillage' => $pourcentageGaspillage,
                'valeur_gaspillage' => $valeurGaspillage,
                'unite_matiere' => $utilisation->unite_matiere,
                'unite_minimale' => $utilisation->matierePremiere->unite_minimale ?? '',
                'prix_par_unite_minimale' => $utilisation->matierePremiere->prix_par_unite_minimale ?? 0
            ];
        }
        
        // Trier par valeur de gaspillage (la plus élevée d'abord)
        usort($result, function ($a, $b) {
            return $b->valeur_gaspillage <=> $a->valeur_gaspillage;
        });
        
        return $result;
    }
    
  
}
