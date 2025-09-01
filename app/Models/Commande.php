<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model {
    use HasFactory;

    protected $table = 'Commande';  // Spécifiez le nom de la table en minuscules

    protected $fillable = [
        'libelle',
        'produit',
        'quantite',
        'date_commande',
        'categorie',
        'valider'
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'valider' => 'boolean'
    ];
    public function produitRelation()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }

    public function produit_fixe()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }

     /**
     * Scope pour les commandes non validées
     */
    public function scopeNonValidees($query)
    {
        return $query->where('valider', false);
    }

    /**
     * Scope pour les commandes validées
     */
    public function scopeValidees($query)
    {
        return $query->where('valider', true);
    }

    /**
     * Calculer le sous-total de la commande
     */
    public function getSousTotalAttribute()
    {
        if ($this->produitFixe) {
            return $this->produitFixe->prix * $this->quantite;
        }
        return 0;
    }

}
