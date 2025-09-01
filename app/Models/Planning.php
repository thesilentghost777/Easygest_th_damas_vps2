<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Planning extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'employe',
        'type',
        'date',
        'heure_debut',
        'heure_fin'
    ];

    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employe');
    }
}
