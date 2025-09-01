<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stagiaire extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'ecole',
        'niveau_etude',
        'filiere',
        'date_debut',
        'date_fin',
        'departement',
        'nature_travail',
        'remuneration',
        'appreciation',
        'type_stage',
        'rapport_genere',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'rapport_genere' => 'boolean',
    ];
}
