<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horaire extends Model
{
    use HasFactory;

    protected $table = 'Horaire';

    protected $fillable = [
        'employe',
        'arrive',
        'depart'
    ];

    protected $casts = [
        'arrive' => 'datetime',
        'depart' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employe');
    }
}
