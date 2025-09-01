<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubObjective extends Model
{
    use HasFactory;

    protected $fillable = [
        'objective_id',
        'product_id',
        'title',
        'target_amount',
        'current_amount',
        'progress_percentage'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2'
    ];

    /**
     * Get the objective that owns this sub-objective.
     */
    public function objective(): BelongsTo
    {
        return $this->belongsTo(Objective::class);
    }

    /**
     * Get the product associated with this sub-objective.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Produit_fixes::class, 'product_id', 'code_produit');
    }
    
    /**
     * Get the transactions de vente associated with this sub-objective.
     */
    public function transactionVentes()
    {
        return TransactionVente::where('produit', $this->product_id)
            ->whereBetween('date_vente', [
                $this->objective->start_date,
                $this->objective->end_date
            ])
            ->get();
    }

    /**
     * Get the formatted target amount.
     */
    public function getFormattedTargetAmountAttribute()
    {
        return number_format($this->target_amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get the formatted current amount.
     */
    public function getFormattedCurrentAmountAttribute()
    {
        return number_format($this->current_amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Calculate the remaining amount to reach the sub-objective.
     */
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    /**
     * Get the formatted remaining amount.
     */
    public function getFormattedRemainingAmountAttribute()
    {
        return number_format($this->getRemainingAmountAttribute(), 0, ',', ' ') . ' FCFA';
    }
}
