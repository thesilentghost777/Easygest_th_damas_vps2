<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_inventaire',
        'produit_id',
        'quantite_theorique',
        'quantite_physique',
        'valeur_manquant',
        'user_id',
        'commentaire'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
