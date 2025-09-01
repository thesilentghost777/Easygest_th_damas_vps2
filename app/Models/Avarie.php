<?php
// app/Models/Avarie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avarie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'produit_id', 
        'quantite',
        'montant_total',
        'description',
        'date_avarie'
    ];

    protected $casts = [
        'date_avarie' => 'date',
        'montant_total' => 'decimal:2'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit_id', 'code_produit');
    }

    // Scope pour filtrer par pointeur
    public function scopeByPointeur($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope pour filtrer par période
    public function scopeByPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_avarie', [$dateDebut, $dateFin]);
    }
}