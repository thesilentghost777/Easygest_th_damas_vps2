<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignationRetour extends Model
{
    use HasFactory;

    protected $table = 'assignation_retours';

    protected $fillable = [
        'assignation_id',
        'producteur_id',
        'matiere_id',
        'quantite_retournee',
        'unite_retour',
        'quantite_stock_incrementee',
        'motif_retour',
        'statut',
        'validee_par',
        'date_validation',
        'commentaire_validation'
    ];

    protected $casts = [
        'quantite_retournee' => 'decimal:3',
        'quantite_stock_incrementee' => 'decimal:3',
        'date_validation' => 'datetime'
    ];

    public function assignation()
    {
        return $this->belongsTo(AssignationMatiere::class, 'assignation_id');
    }

    public function producteur()
    {
        return $this->belongsTo(User::class, 'producteur_id');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopeRefusees($query)
    {
        return $query->where('statut', 'refusee');
    }
}
