<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceptionVendeur extends Model
{
    protected $table = 'receptions_vendeurs';

    protected $fillable = [
        'vendeur_id',
        'produit_id',
        'quantite_entree_matin',
        'quantite_entree_journee',
        'quantite_invendue',
        'quantite_reste_hier',
        'date_reception',
        'quantite_avarie', // Nouveau champ ajouté

    ];

    protected $casts = [
        'date_reception' => 'date',
        'quantite_entree_matin' => 'decimal:2',
        'quantite_entree_journee' => 'decimal:2',
        'quantite_avarie' => 'decimal:2', // Nouveau champ ajouté
        'quantite_invendue' => 'decimal:2',
        'quantite_reste_hier' => 'decimal:2',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit_id', 'code_produit');
    }

    public function getTotalEntreeAttribute()
    {
        return $this->quantite_entree_matin + $this->quantite_entree_journee;
    }
}