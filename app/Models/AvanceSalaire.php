<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvanceSalaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_employe',
        'sommeAs',
        'flag',
        'retrait_demande',
        'retrait_valide',
        'mois_as'
    ];

    protected $casts = [
        'mois_as' => 'date',
        'flag' => 'boolean',
        'retrait_demande' => 'boolean',
        'retrait_valide' => 'boolean',
    ];

    public function employe()
    {
        return $this->belongsTo(User::class, 'id_employe');
    }

    public function peutDemanderAS()
    {
        $avanceExistante = $this->where('id_employe', auth()->id())
            ->whereMonth('mois_as', now()->month)
            ->whereYear('mois_as', now()->year)
            ->first();
        
        // L'employé peut demander une AS si :
        // 1. Il n'a pas encore d'enregistrement pour ce mois, OU
        // 2. Il a un enregistrement mais le flag est à false
        return is_null($avanceExistante) || $avanceExistante->flag === false;
    }

    public function estEnAttente()
    {
        return $this->retrait_demande && !$this->retrait_valide;
    }

    /**
     * Vérifie si l'avance a été validée
     */
    public function estValidee()
    {
        return $this->retrait_valide;
    }
}
