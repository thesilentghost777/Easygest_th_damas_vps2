<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSuggererParJour extends Model
{
    use HasFactory;
    
    protected $table = 'Production_suggerer_par_jour';
    
    protected $fillable = [
        'produit',
        'quantity',
        'day',
    ];
    
    protected $casts = [
        'day' => 'date',
        'quantity' => 'integer',
    ];
    
    /**
     * Relation avec le produit fixe
     */
    public function Produit_fixes()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit', 'code_produit');
    }
}
