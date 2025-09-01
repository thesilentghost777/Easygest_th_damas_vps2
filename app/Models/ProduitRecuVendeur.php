<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitRecuVendeur extends Model
{
    use HasFactory;
    
    protected $table = 'produits_recu_vendeur';
    
    protected $fillable = [
        'produit_recu_id',
        'vendeur_id',
        'quantite_recue',
        'quantite_confirmee',
        'status',
        'remarques'
    ];
    
    protected $casts = [
        'quantite_recue' => 'integer',
        'quantite_confirmee' => 'integer',
    ];
    
    public function produitRecu()
    {
        return $this->belongsTo(ProduitRecu1::class, 'produit_recu_id');
    }
    
    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }
    
    public function scopeNonConfirmes($query)
    {
        return $query->where('status', 'en_attente');
    }
    
    public function scopeConfirmes($query)
    {
        return $query->where('status', 'confirmé');
    }
    
    public function scopeRejetes($query)
    {
        return $query->where('status', 'rejeté');
    }
}
