<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionProduit extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_sac_id',
        'produit_id',
        'quantite',
        'valeur_unitaire',
        'valeur_totale'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'valeur_unitaire' => 'decimal:2',
        'valeur_totale' => 'decimal:2'
    ];

    public function production()
    {
        return $this->belongsTo(ProductionSac::class, 'production_sac_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit_id', 'code_produit');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($productionProduit) {
            $productionProduit->valeur_totale = $productionProduit->quantite * $productionProduit->valeur_unitaire;
        });
    }
}