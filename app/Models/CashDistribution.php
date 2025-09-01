<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Traits\HistorisableActions;

class CashDistribution extends Model
{
    use HasFactory;
    use HistorisableActions;


    protected $fillable = [
        'user_id',
        'date',
        'bill_amount',
        'initial_coin_amount',
        'final_coin_amount',
        'deposited_amount',
        'sales_amount',
        'missing_amount',
        'status',
        'notes',
        'closed_by',
        'closed_at'
    ];

    protected $casts = [
        'date' => 'date',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function calculateMissingAmount($user)
    {
        // (Somme ventes + Billets initiaux + (Monnaie initiale - Monnaie finale)) - Versement
        if ($this->final_coin_amount !== null && $this->deposited_amount !== null) {
            $expectedAmount = $this->sales_amount + $this->bill_amount +
                             ($this->initial_coin_amount - $this->final_coin_amount);

            $this->missing_amount = max(0, $expectedAmount - $this->deposited_amount);
            if ($this->missing_amount > 0) {
                $this->historiser("Le(la) vendeur(se) {$user->name} a enregistrer une vente avec : total vendu:{$this->sales_amount} monnaie initial : {$this->initial_coin_amount} somme initial rexu pour les ventes : {$this->bill_amount} monnaie final : {$this->final_coin_amount} : montant verser {$this->deposited_amount} : montant manquant inexpliquer : {$this->missing_amount}", 'detection_de_vol_vendeuse');
            }
            return $this->missing_amount;
        }

        return null;
    }
}
