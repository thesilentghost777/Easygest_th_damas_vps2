<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceptionPointeur extends Model
{
    protected $table = 'receptions_pointeurs';

    protected $fillable = [
        'pointeur_id',
        'produit_id',
        'quantite_recue',
        'date_reception'
    ];

    protected $casts = [
        'date_reception' => 'date',
        'quantite_recue' => 'decimal:2',
    ];

    public function pointeur()
    {
        return $this->belongsTo(User::class, 'pointeur_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produit_fixes::class, 'produit_id', 'code_produit');
    }
}