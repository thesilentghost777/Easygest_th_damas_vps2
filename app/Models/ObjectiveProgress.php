<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjectiveProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'objective_id',
        'date',
        'current_amount',
        'expenses',
        'profit',
        'progress_percentage',
        'transactions'
    ];

    protected $casts = [
        'date' => 'date',
        'current_amount' => 'decimal:2',
        'expenses' => 'decimal:2',
        'profit' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'transactions' => 'json'
    ];

    /**
     * Get the objective that this progress belongs to.
     */
    public function objective(): BelongsTo
    {
        return $this->belongsTo(Objective::class);
    }

    /**
     * Get the formatted current amount.
     */
    public function getFormattedCurrentAmountAttribute()
    {
        return number_format($this->current_amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get the formatted expenses.
     */
    public function getFormattedExpensesAttribute()
    {
        return number_format($this->expenses, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get the formatted profit.
     */
    public function getFormattedProfitAttribute()
    {
        return number_format($this->profit, 0, ',', ' ') . ' FCFA';
    }
    
    /**
     * Get related transactions details.
     */
    public function getTransactionDetailsAttribute()
    {
        $transactionIds = $this->transactions ?? [];
        $details = [];
        
        // Traitement selon le secteur
        if ($this->objective->sector === 'alimentation') {
            $details = VersementChef::whereIn('code_vc', $transactionIds)
                ->where('status', true)
                ->get();
        } elseif ($this->objective->sector === 'boulangerie-patisserie') {
            $details = VersementChef::whereIn('code_vc', $transactionIds)
                ->where('status', true)
                ->get();
        } elseif ($this->objective->sector === 'glace') {
            $details = VersementChef::whereIn('code_vc', $transactionIds)
                ->where('status', true)
                ->get();
        } elseif ($this->objective->sector === 'global') {
            // Pour les objectifs globaux, récupérer les transactions
            $details = Transaction::whereIn('id', $transactionIds)
                ->get();
        }
        
        return $details;
    }
}
