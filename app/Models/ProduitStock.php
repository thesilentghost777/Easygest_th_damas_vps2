<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProduitStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_produit',
        'quantite_en_stock',
        'quantite_invendu',
        'quantite_avarie'
    ];

    public function produitFixe(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'id_produit', 'code_produit');
    }
    public static function updateStock(int $produitId, int $quantite, string $type = 'en_stock'): bool
    {
        try {
            // Détermine la colonne à mettre à jour selon le type
            $column = match ($type) {
                'en_stock' => 'quantite_en_stock',
                'invendu' => 'quantite_invendu',
                'avarie' => 'quantite_avarie',
                default => 'quantite_en_stock',
            };
            
            // Trouver l'enregistrement ou le créer s'il n'existe pas
            $stock = self::firstOrNew(['id_produit' => $produitId]);
            
            // Si c'est un nouveau record, initialiser les valeurs
            if (!$stock->exists) {
                $stock->quantite_en_stock = 0;
                $stock->quantite_invendu = 0;
                $stock->quantite_avarie = 0;
            }
            
            // Calculer la nouvelle valeur
            $newValue = $stock->$column + $quantite;
            
            // Vérifier que le stock ne devient pas négatif (pour quantite_en_stock)
            if ($column === 'quantite_en_stock' && $newValue < 0) {
                return false; // Stock insuffisant
            }
            
            // Mise à jour du stock
            $stock->$column = $newValue;
            return $stock->save();
            
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la mise à jour du stock: " . $e->getMessage());
            return false;
        }
    }
    public function getQuantiteTotaleAttribute()
    {
        return $this->quantite_en_stock - $this->quantite_invendu - $this->quantite_avarie;
    }
}
