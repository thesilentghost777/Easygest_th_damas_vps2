<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignationMatiere extends Model
{
    use HasFactory;
    protected $table = 'assignations_matiere';

    protected $fillable = [
        'producteur_id',
        'matiere_id',
        'quantite_assignee',
        'quantite_restante',
        'unite_assignee',
        'date_limite_utilisation'
    ];

    protected $casts = [
        'date_limite_utilisation' => 'datetime'
    ];

    public function producteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producteur_id');
    }

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }
}
