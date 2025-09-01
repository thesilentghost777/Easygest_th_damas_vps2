<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BagTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bag_id',
        'type',
        'quantity',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'date'
    ];

    public function bag(): BelongsTo
    {
        return $this->belongsTo(Bag::class);
    }
}
