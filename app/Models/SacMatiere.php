<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SacMatiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'sac_id',
        'matiere_id',
        'quantite_utilisee'
    ];

    protected $casts = [
        'quantite_utilisee' => 'decimal:3'
    ];

    public function sac()
    {
        return $this->belongsTo(Sac::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}