<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaire extends Model
{
    use HasFactory;

    protected $table = 'salaires';

    protected $fillable = [
        'id_employe',
        'somme',
        'flag',
        'retrait_demande',
        'retrait_valide',
        'mois_salaire',
    ];

    // Casting des colonnes pour faciliter leur manipulation
    protected $casts = [
        'somme' => 'decimal:2',
        'flag' => 'boolean',
        'retrait_demande' => 'boolean',
        'retrait_valide' => 'boolean',
        'mois_salaire' => 'date',
    ];

    /**
     * Relation vers l'employé (user).
     */
    public function employe()
    {
        return $this->belongsTo(User::class, 'id_employe');
    }
}
