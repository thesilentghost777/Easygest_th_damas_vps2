<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use App\Services\UniteConversionService;
use App\Enums\UniteMinimale;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MatiereRecommander extends Model
{
    use HasFactory;

    protected $table = 'Matiere_recommander';
    
    protected $fillable = [
        'produit',
        'matierep',
        'quantitep',
        'quantite',
        'unite',
    ];
    
    protected $casts = [
        'quantite' => 'decimal:3',
    ];
    public function Produit_fixes(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }
    
    public function produitFixe(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }
    
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matierep');
    }

    public function getQuantiteInMinimalUnit(): float
    {
        // Get the minimal unit from the related matiere
        $uniteMinimale = $this->matiere->unite_minimale;
        
        // Log conversion details for debugging
        Log::info("Converting from {$this->unite} to {$uniteMinimale->toString()}", [            'id' => $this->id,
            'matiere' => $this->matierep,
            'quantite_originale' => $this->quantite,
            'unite_originale' => $this->unite,
            'unite_minimale_cible' => $uniteMinimale
        ]);
        
        // Use the conversion service to convert from unite (unit stored) to minimal unit
        $conversionService = new UniteConversionService();
        $resultat = $conversionService->convertir($this->quantite, $this->unite, $uniteMinimale);
        
        Log::info("Conversion result: {$resultat} {$uniteMinimale->toString()}");
        
        return $resultat;
    }
    
    /**
     * Calculate recommended quantity for a specific product quantity
     * 
     * @param float $targetProductQuantity
     * @return float
     */
    public function getRecommendedQuantityFor(float $targetProductQuantity): float
    {
        $quantiteInMinimalUnit = $this->getQuantiteInMinimalUnit();
        
        // Log calculation details
        Log::info("Calculating recommended quantity", [
            'id' => $this->id,
            'matiere' => $this->matierep,
            'quantite_originale' => $this->quantite,
            'unite_originale' => $this->unite,
            'quantite_minimale' => $quantiteInMinimalUnit,
            'quantite_produit_base' => $this->quantitep,
            'quantite_produit_cible' => $targetProductQuantity,
            'rapport' => $targetProductQuantity / $this->quantitep
        ]);
        
        // Calculate proportional quantity based on product quantity ratio
        $resultat = ($quantiteInMinimalUnit * $targetProductQuantity) / $this->quantitep;
        
        Log::info("Recommended quantity result: {$resultat}");
        
        return $resultat;
    }
}
