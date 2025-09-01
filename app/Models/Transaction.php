<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model

{
    use HasFactory;
    protected $fillable = [
        'type',
        'category_id',
        'amount',
        'date',
        'description'
    ];
    
    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
   
   public function getFormattedAmountAttribute()
   {
       return number_format($this->amount, 0, ',', ' ') . ' FCFA';
   }
   
   /**
    * Get the formatted date attribute.
    */
   public function getFormattedDateAttribute()
   {
       return $this->date->format('d/m/Y H:i');
   }
   

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }
    
    /**
     * Scope a query to only include outcome transactions.
     */
    public function scopeOutcome($query)
    {
        return $query->where('type', 'outcome');
    }
}
