<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionSac extends Model
{
    use HasFactory;

    protected $table = 'productions_sacs';

    protected $fillable = [
        'sac_id',
        'producteur_id',
        'valeur_totale_fcfa',
        'valide',
        'observations',
        'date_production'
    ];

    protected $casts = [
        'valeur_totale_fcfa' => 'decimal:2',
        'valide' => 'boolean',
        'date_production' => 'date'
    ];

    public function sac()
    {
        return $this->belongsTo(Sac::class);
    }

    public function producteur()
    {
        return $this->belongsTo(User::class, 'producteur_id');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit_fixes::class, 'production_produits', 'production_sac_id', 'produit_id', 'id', 'code_produit')
                    ->withPivot('quantite', 'valeur_unitaire', 'valeur_totale')
                    ->withTimestamps();
    }

    public function productionProduits()
    {
        return $this->hasMany(ProductionProduit::class);
    }

    public function calculerValeurTotale()
    {
        return $this->productionProduits()->sum('valeur_totale');
    }

    public function estSousLaMoyenne()
    {
        $configuration = $this->sac->configuration;
        if (!$configuration) return false;
        
        return $this->valeur_totale_fcfa < $configuration->valeur_moyenne_fcfa;
    }
}