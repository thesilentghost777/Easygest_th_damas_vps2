<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManquantTemporaire extends Model
{
    use HasFactory;

    protected $table = 'manquant_temporaire';

    protected $fillable = [
        'employe_id',
        'montant',
        'explication',
        'statut',
        'commentaire_dg',
        'valide_par'
    ];

    protected $casts = [
        'montant' => 'integer',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employe_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
    /**
     * Relation avec le validateur
     */
    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

     public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
    
    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }
    
    public function scopeAjustes($query)
    {
        return $query->where('statut', 'ajuste');
    }
}
