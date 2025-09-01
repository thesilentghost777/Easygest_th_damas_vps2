<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'date_naissance',
        'code_secret',
        'secteur',
        'role',
        'num_tel',
        'annee_debut_service'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_naissance' => 'date',
        'password' => 'hashed',
        'code_secret' => 'integer',
        'annee_debut_service' => 'integer',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function utilisations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Utilisation::class, 'producteur', 'id');
    }



    public function salaires()
    {
        return $this->hasMany(Salaire::class, 'id_employe', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction_vente::class);
    }

    public function evaluation()
    {
        // Assuming evaluations table has a foreign key `user_id`
        return $this->hasMany(Evaluation::class);
    }
    public function delis()
{
    return $this->belongsToMany(Deli::class, 'deli_user')
        ->withPivot('date_incident')
        ->withTimestamps();
}
public function roles(): BelongsToMany
{
    return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
}


    /**
     * Vérifie si l'utilisateur actuel peut accéder au compte d'un autre utilisateur
     *
     * @param User $targetUser L'utilisateur cible
     * @return bool
     */
    public function canAccessUser(User $targetUser)
    {
        // Définition des droits d'accès basés sur le secteur et le rôle
        $accessRights = [
            'administration' => [
                'chef_production' => [
                    'production' => ['patissier', 'boulanger', 'pointeur'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace']
                ],
                'dg' => [
                    'administration' => ['chef_production'],
                    'production' => ['patissier', 'boulanger', 'pointeur'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace']
                ],
                'ddg' => [
                    'administration' => ['dg', 'chef_production'],
                    'production' => ['patissier', 'boulanger', 'pointeur'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace']
                ],
                'pdg' => [
                    'administration' => ['dg', 'chef_production'],
                    'production' => ['patissier', 'boulanger', 'pointeur'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace']
                ],
            ]
        ];

        // Vérification si l'utilisateur actuel est un administrateur avec des droits d'accès
        if (isset($accessRights[$this->secteur][$this->role])) {
            $allowedSectors = $accessRights[$this->secteur][$this->role];

            // Vérification si le secteur de l'utilisateur cible est dans la liste des secteurs autorisés
            if (isset($allowedSectors[$targetUser->secteur])) {
                $allowedRoles = $allowedSectors[$targetUser->secteur];

                // Vérification si le rôle de l'utilisateur cible est dans la liste des rôles autorisés
                return in_array($targetUser->role, $allowedRoles);
            }
        }

        return false;
    }

    /**
     * Récupère la liste des utilisateurs auxquels l'utilisateur actuel peut accéder
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAccessibleUsers()
    {
        // Définition des droits d'accès basés sur le secteur et le rôle
        $accessRights = [
            'administration' => [
                'chef_production' => [
                    'production' => ['patissier', 'boulanger', 'pointeur','enfourneur','tech_surf'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace'],
                    'alimentation' => ['rayonniste', 'caissiere', 'calviste', 'tech_surf', 'controlleur','virgile']
                ],
                'dg' => [
                    'administration' => ['chef_production'],
                    'production' => ['patissier', 'boulanger', 'pointeur'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace'],
                    'alimentation' => ['rayonniste', 'caissiere', 'calviste', 'tech_surf', 'controlleur','virgile']
                ],
                'ddg' => [
                    'administration' => ['dg', 'chef_production'],
                    'production' => ['patissier', 'boulanger', 'pointeur'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace']
                ],
                'pdg' => [
                    'administration' => ['dg', 'chef_production'],
                    'production' => ['patissier', 'boulanger', 'pointeur'],
                    'vente' => ['vendeur_boulangerie', 'vendeur_patisserie'],
                    'glace' => ['glace']
                ],
            ]
        ];

        // Si l'utilisateur n'a pas de droits d'accès, retourner une collection vide
        if (!isset($accessRights[$this->secteur][$this->role])) {
            return collect();
        }

        $query = User::where('id', '!=', $this->id);
        $allowedSectors = $accessRights[$this->secteur][$this->role];

        $query->where(function($q) use ($allowedSectors) {
            foreach ($allowedSectors as $sector => $roles) {
                $q->orWhere(function($subQ) use ($sector, $roles) {
                    $subQ->where('secteur', $sector)
                          ->whereIn('role', $roles);
                });
            }
        });

        return $query->get();
    }
    public function horaires()
    {
        return $this->hasMany(Horaire::class, 'employe');
    }

    /**
     * Get the employee ration for the user.
     */
    public function employeeRation()
    {
        return $this->hasOne(EmployeeRation::class, 'employee_id');
    }

    /**
     * Get the ration claims for the user.
     */
    public function rationClaims()
    {
        return $this->hasMany(RationClaim::class, 'employee_id');
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class, 'user_id');
    }
    
    /**
     * Get the active objectives defined by this user.
     */
    public function activeObjectives()
    {
        return $this->objectives()->where('is_active', true);
    }
    
    /**
     * Get the achieved objectives defined by this user.
     */
    public function achievedObjectives()
    {
        return $this->objectives()->where('is_achieved', true);
    }
    public static function getDG(){
    return self::where('role', 'dg')->first();
}

public function primes()
{
    return $this->hasMany(Prime::class, 'id_employe');
}


    public function receptionsPointeur()
    {
        return $this->hasMany(ReceptionPointeur::class, 'pointeur_id');
    }

    public function receptionsVendeur()
    {
        return $this->hasMany(ReceptionVendeur::class, 'vendeur_id');
    }

    public function scopeProducteurs($query)
    {
        return $query->where('secteur', 'production')
                    ->whereIn('role', ['boulanger', 'patissier']);
    }

    public function scopePointeurs($query)
    {
        return $query->where('role', 'pointeur');
    }

    public function scopeVendeurs($query)
    {
        return $query->where('secteur', 'vente');
    }
    public function avaries()
{
    return $this->hasMany(Avarie::class);
}

// Méthode pour calculer le total des avaries d'un pointeur
public function getTotalAvariesAttribute()
{
    return $this->avaries()->sum('montant_total');
}

// Méthode pour compter le nombre d'avaries
public function getNombreAvariesAttribute()
{
    return $this->avaries()->count();
}


}
