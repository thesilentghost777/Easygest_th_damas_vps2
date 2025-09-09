<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceptionProduction extends Model
{
    use HasFactory;

    protected $table = 'reception_production';

    protected $fillable = [
        'code_produit',
        'quantite',
        'date_reception',
        'user_id'
    ];

    protected $casts = [
        'date_reception' => 'date',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'code_produit', 'code_produit');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}