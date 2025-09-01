<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionVente extends Model
{
    use HasFactory;
    
    protected $table = 'transaction_ventes';
    
    protected $fillable = [
        'produit',
        'serveur',
        'quantite',
        'prix',
        'date_vente',
        'type',
        'monnaie',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'date_vente' => 'date',
    ];

    /**
     * Résoudre le conflit entre la colonne 'produit' et la méthode produit()
     */
    public function getProduitAttribute($value)
    {
        return $value;
    }

    /**
     * Obtenir le produit associé à cette transaction.
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }

    public function Produit_fixes(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }

    public function produits(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }

    /**
     * Relation vers l'utilisateur (compatibilité avec l'ancien code).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le vendeur (serveur) associé à cette transaction.
     */
    public function vendeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'serveur', 'id');
    }

    /**
     * Get the total amount of this transaction.
     */
    public function getTotalAmountAttribute()
    {
        return $this->quantite * $this->prix;
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->getTotalAmountAttribute(), 0, ',', ' ') . ' FCFA';
    }

    public function name($produit_id)
    {
        $p = Produit_fixes::find($produit_id);
        if ($p) {
            return $p->nom;
        } else {
            return 'Aucun nom associé';
        }
    }
}