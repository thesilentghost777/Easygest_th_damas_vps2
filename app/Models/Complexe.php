<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complexe extends Model
{
    use HasFactory;

    protected $table = 'Complexe';
    protected $primaryKey = 'id_comp';
    public $incrementing = false;

    protected $fillable = [
        'nom',
        'localisation',
        'revenu_mensuel',
        'revenu_annuel',
        'solde',
        'caisse_sociale',
    ];
}
