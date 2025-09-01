<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sac extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean'
    ];

    public function configuration()
    {
        return $this->hasOne(SacConfiguration::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'sac_matieres')
                    ->withPivot('quantite_utilisee')
                    ->withTimestamps();
    }

    public function productions()
    {
        return $this->hasMany(ProductionSac::class);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}