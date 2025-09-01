<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use App\Services\UniteConversionService;
use App\Enums\UniteMinimale;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisation extends Model
{
    use HasFactory;
    protected $table = 'Utilisation';

    protected $fillable = [
        'id_lot',
        'produit',
        'matierep',
        'producteur',
        'quantite_produit',
        'quantite_matiere',
        'unite_matiere'
    ];

    public function produitFixe(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }
    public function produit_fixes(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }


    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matierep', 'id');
    }
    public function matierePremiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matierep', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producteur', 'id');
    }
    
    public function producteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producteur', 'id');
    }
     /**
     * Get the amount of wasted material for this utilization
     * 
     * @return float|null
     */
    public function getWastedQuantity(): ?float
    {
        // Find the recommended quantity for this product-material combination
        $recommandation = MatiereRecommander::where('produit', $this->produit)
            ->where('matierep', $this->matierep)
            ->first();
        
        if (!$recommandation) {
            Log::warning("No recommendation exists for product {$this->produit} and material {$this->matierep}");
            return null; // No recommendation exists for this combination
        }
        
        // Log the raw values for debugging
        Log::info("Calculating waste for utilisation ID {$this->id}", [
            'id_lot' => $this->id_lot,
            'produit' => $this->produit,
            'matierep' => $this->matierep,
            'unite_matiere' => $this->unite_matiere,
            'quantite_matiere' => $this->quantite_matiere,
            'quantite_produit' => $this->quantite_produit,
            'recommandation_unite' => $recommandation->unite,
            'recommandation_quantite' => $recommandation->quantite,
            'recommandation_quantitep' => $recommandation->quantitep
        ]);
        
        // Calculate recommended quantity for the specific product quantity used
        $quantiteRecommandee = $recommandation->getRecommendedQuantityFor($this->quantite_produit);
        
        // Ensure units are comparable by checking they are in the same minimal unit
        Log::info("Comparison values:", [
            'quantite_utilisee' => "{$this->quantite_matiere} {$this->unite_matiere}",
            'quantite_recommandee' => "{$quantiteRecommandee} {$this->matierePremiere->unite_minimale->toString()}"
        ]);
        
        // Compare with actual quantity used
        $wastedQuantity = $this->quantite_matiere - $quantiteRecommandee;
        
        Log::info("Wasted quantity calculation: {$this->quantite_matiere} - {$quantiteRecommandee} = {$wastedQuantity}");
        
        return $wastedQuantity;
    }
    
    /**
     * Get the waste value in currency
     * 
     * @return float|null
     */
    public function getWastedValue(): ?float
    {
        $wastedQuantity = $this->getWastedQuantity();
        
        if ($wastedQuantity === null || $wastedQuantity <= 0) {
            return null;
        }
        
        // Get the price per minimal unit from the related material
        $valeur = $wastedQuantity * $this->matierePremiere->prix_par_unite_minimale;
        
        Log::info("Wasted value: {$wastedQuantity} × {$this->matierePremiere->prix_par_unite_minimale} = {$valeur}");
        
        return $valeur;
    }
}
