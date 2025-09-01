<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'auteur',
        'nom',
        'prix',
        'type',
        'idm',
        'date',
        'valider',
        'validated_at'
    ];

    protected $casts = [
        'date' => 'date',
        'valider' => 'boolean',
        'prix' => 'decimal:2'
    ];
    public function auteurRelation()
    {
        return $this->belongsTo(User::class, 'auteur');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur');
    }

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'idm');
    }
}
