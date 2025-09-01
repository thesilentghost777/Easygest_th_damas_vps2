<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VersementChef extends Model
{
    use HasFactory;
    protected $table = 'Versement_chef';
    protected $primaryKey = 'code_vc';

    protected $fillable = [
        'verseur',
        'libelle',
        'montant',
        'date',
        'status' // 0: En attente, 1: Validé
    ];
    protected $casts = [
        'date' => 'date',
        'status' => 'boolean',
    ];


    public function verseur()
    {
        return $this->belongsTo(User::class, 'verseur');
    }
    public function verseur_name(int $x)
    {
        $name = User::where('id',$x)->first();
        return $name->name;
    }
}
