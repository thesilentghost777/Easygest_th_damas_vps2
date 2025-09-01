<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SacConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'sac_id',
        'valeur_moyenne_fcfa',
        'notes',
        'actif'
    ];

    protected $casts = [
        'valeur_moyenne_fcfa' => 'decimal:2',
        'actif' => 'boolean'
    ];

    public function sac()
    {
        return $this->belongsTo(Sac::class);
    }
}