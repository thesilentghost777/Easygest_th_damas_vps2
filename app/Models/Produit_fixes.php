<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit_fixes extends Model
{
    use HasFactory;
    protected $table = 'Produit_fixes';
    protected $primaryKey = 'code_produit';

    protected $fillable = [
        'nom',
        'prix',
        'categorie'
    ];

    public function utilisations(): HasMany
    {
        return $this->hasMany(Utilisation::class, 'produit', 'code_produit');
    }

    public function matiereRecommandee(): HasMany
    {
        return $this->hasMany(MatiereRecommander::class, 'produit', 'code_produit');
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class, 'produit', 'code_produit');
    }

    public function receptions()
    {
        return $this->hasMany(ReceptionProduction::class, 'code_produit', 'code_produit');
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(TransactionVente::class, 'produit', 'code_produit');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(ProduitStock::class, 'id_produit', 'code_produit');
    }
    public function suggestions(): HasMany
    {
        return $this->hasMany(ProductionSuggererParJour::class, 'produit', 'code_produit');
    }
    public function getCurrentStock()
    {
        return $this->stock ? $this->stock->quantite_totale : 0;
    }
    /**
     * Relation avec les commandes
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'produit', 'code_produit');
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Formater le prix en fcfa
     */
    public function getPrixFormateAttribute()
    {
        return number_format($this->prix, 2) . ' FCFA';
    }

     public function receptionsPointeurs()
    {
        return $this->hasMany(ReceptionPointeur::class, 'produit_id', 'code_produit');
    }

    public function receptionsVendeurs()
    {
        return $this->hasMany(ReceptionVendeur::class, 'produit_id', 'code_produit');
    }

    public function fluxJournaliers()
    {
        return $this->hasMany(FluxJournalier::class, 'produit_id', 'code_produit');
    }

    public function manquants()
    {
        return $this->hasMany(Manquant::class, 'produit_id', 'code_produit');
    }

    // Relation avec les avaries
    public function avaries()
    {
        return $this->hasMany(Avarie::class, 'produit_id', 'code_produit');
    }
}
