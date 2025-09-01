<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'user_id',
        'action_type',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
       /**
     * Décoder les données JSON de description
     */
    public function getDecodedDescriptionAttribute()
    {
        return json_decode($this->description, true);
    }

    /**
     * Scope pour filtrer par type d'action
     */
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope pour les réductions de commandes
     */
    public function scopeCommandeReductions($query)
    {
        return $query->where('action_type', 'commande_reduction');
    }
}