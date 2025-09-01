<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReposConge extends Model
{
    use HasFactory;
    protected $fillable = [
        'employe_id',
        'jour',
        'conges',
        'debut_c',
        'raison_c',
        'autre_raison'
    ];

    protected $casts = [
        'debut_c' => 'date',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employe_id');
    }

}
