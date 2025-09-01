<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manquant extends Model
{
    protected $table = 'manquants';

    protected $fillable = [
        'produit_id',
        'date_calcul',
        'manquant_producteur_pointeur',
        'manquant_pointeur_vendeur',
        'manquant_vendeur_invendu',
        'incoherence_producteur_pointeur',  // Nouvelle colonne
        'incoherence_pointeur_vendeur',     // Nouvelle colonne
        'montant_producteur',
        'montant_pointeur',
        'montant_vendeur',
        'details_producteurs',
        'details_pointeurs',
        'details_vendeurs',
    ];
    protected $casts = [
        'date_calcul' => 'date',
        'manquant_producteur_pointeur' => 'decimal:2',
        'manquant_pointeur_vendeur' => 'decimal:2',
        'manquant_vendeur_invendu' => 'decimal:2',
        'montant_producteur' => 'decimal:2',
        'montant_pointeur' => 'decimal:2',
        'montant_vendeur' => 'decimal:2',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit_id', 'code_produit');
    }

    public function getDetailsProducteursDecodedAttribute()
    {
        return json_decode($this->details_producteurs, true) ?? [];
    }

    public function getDetailsPointeursDecodedAttribute()
    {
        return json_decode($this->details_pointeurs, true) ?? [];
    }

    public function getDetailsVendeursDecodedAttribute()
    {
        return json_decode($this->details_vendeurs, true) ?? [];
    }

    public function getTotalMontantAttribute()
    {
        return $this->montant_producteur + $this->montant_pointeur + $this->montant_vendeur;
    }
}
