<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FluxJournalier extends Model
{
    protected $table = 'flux_journaliers';

    protected $fillable = [
        'produit_id',
        'date_flux',
        'total_production',
        'total_pointage',
        'total_reception_vendeur',
        'detail_productions',
        'detail_pointages',
        'detail_receptions'
    ];

    protected $casts = [
        'date_flux' => 'date',
        'total_production' => 'decimal:2',
        'total_pointage' => 'decimal:2',
        'total_reception_vendeur' => 'decimal:2',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit_id', 'code_produit');
    }

    public function manquant()
    {
        return $this->hasOne(Manquant::class, 'produit_id', 'produit_id')
                    ->where('date_calcul', $this->date_flux);
    }

    public function getManquantProducteurPointeurAttribute()
    {
        return max(0, $this->total_production - $this->total_pointage);
    }

    public function getManquantPointeurVendeurAttribute()
    {
        return max(0, $this->total_pointage - $this->total_reception_vendeur);
    }
}