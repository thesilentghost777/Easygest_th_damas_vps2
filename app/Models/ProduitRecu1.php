<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProduitRecu1 extends Model
{
    use HasFactory;

    protected $table = 'produits_recu_1';

    protected $fillable = [
        'produit_id',
        'quantite',
        'producteur_id',
        'pointeur_id',
        'vendeur_id',
        'date_reception',
        'remarques',
    ];

    protected $dates = [
        'date_reception',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit_id', 'code_produit');
    }

    public function producteur()
    {
        return $this->belongsTo(User::class, 'producteur_id');
    }

    public function pointeur()
    {
        return $this->belongsTo(User::class, 'pointeur_id');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function receptionsVendeurs()
    {
        return $this->hasMany(ProduitRecuVendeur::class, 'produit_recu_id');
    }

    /**
     * Scope pour les produits disponibles
     */
    public function scopeDisponible($query)
    {
        return $query->where('quantite', '>', 0);
    }

    /**
     * Quantité totale confirmée reçue par les vendeurs
     */
    public function getTotalRecuVendeursAttribute()
    {
        return $this->receptionsVendeurs()
            ->where('status', 'confirmé')
            ->sum('quantite_confirmee');
    }

    /**
     * Quantité restante disponible
     */
    public function getQuantiteRestanteAttribute()
    {
        return $this->quantite - $this->total_recu_vendeurs;
    }
}
