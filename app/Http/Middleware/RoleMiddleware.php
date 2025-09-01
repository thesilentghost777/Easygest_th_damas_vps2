<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = $user->role;
        $userSecteur = $user->secteur;

        // Définition des rôles et leurs hiérarchies
        $roleHierarchy = [
            // PDG a accès à tout
            'pdg' => ['all'],
            
            // DG a accès à tout sauf PDG
            'dg' => ['all'],
            
            // Chef de production a accès à ses routes + producteurs + employés standard
            'chef_production' => [
                'chef_production', 'patissier', 'boulanger', 'pointeur', 'enfourneur', 
                'tech_surf', 'caissiere', 'calviste', 'magasinier', 'rayoniste', 
                'controleur', 'virgile', 'vendeur_boulangerie', 'vendeur_patisserie'
            ],
            
            // Gestionnaire alimentation a accès à ses routes + employés alimentation
            'gestionnaire_alimentation' => [
                'gestionnaire_alimentation', 'chef_rayoniste', 'caissiere', 'calviste', 
                'magasinier', 'rayoniste', 'controleur', 'tech_surf', 'virgile'
            ],
            
            // Chef rayoniste a accès à ses routes + employés alimentation
            'chef_rayoniste' => [
                'chef_rayoniste', 'caissiere', 'calviste', 'magasinier', 'rayoniste', 
                'controleur', 'tech_surf', 'virgile'
            ],
            
            // Producteurs ont accès à leurs routes + employés standard
            'patissier' => ['patissier', 'caissiere', 'calviste', 'magasinier', 'rayoniste', 'controleur', 'tech_surf', 'virgile'],
            'boulanger' => ['boulanger', 'caissiere', 'calviste', 'magasinier', 'rayoniste', 'controleur', 'tech_surf', 'virgile'],
            
            // Vendeur glace a accès aux routes producteur + vendeur + employés
            'glace' => [
                'glace', 'patissier', 'boulanger', 'vendeur_boulangerie', 'vendeur_patisserie',
                'caissiere', 'calviste', 'magasinier', 'rayoniste', 'controleur', 'tech_surf', 'virgile'
            ],
            
            // Vendeurs ont accès à leurs routes + employés standard
            'vendeur_boulangerie' => ['vendeur_boulangerie', 'caissiere', 'calviste', 'magasinier', 'rayoniste', 'controleur', 'tech_surf', 'virgile'],
            'vendeur_patisserie' => ['vendeur_patisserie', 'caissiere', 'calviste', 'magasinier', 'rayoniste', 'controleur', 'tech_surf', 'virgile'],
            
            // Pointeur n'a accès qu'à ses routes + employés standard
            'pointeur' => ['pointeur', 'caissiere', 'calviste', 'magasinier', 'rayoniste', 'controleur', 'tech_surf', 'virgile'],
            
            // DDG (même niveau que DG)
            'ddg' => ['all'],
            
            // Développeur a accès à tout
            'developper' => ['all'],
            
            // Employés standard n'ont accès qu'à leurs propres routes
            'caissiere' => ['caissiere'],
            'calviste' => ['calviste'],
            'magasinier' => ['magasinier'],
            'rayoniste' => ['rayoniste'],
            'controleur' => ['controleur'],
            'tech_surf' => ['tech_surf'],
            'virgile' => ['virgile'],
            'enfourneur' => ['enfourneur']
        ];

        // Vérifier si l'utilisateur a l'un des rôles autorisés
        foreach ($allowedRoles as $allowedRole) {
            // Si le rôle autorisé est 'all', tout le monde peut accéder
            if ($allowedRole === 'all') {
                return $next($request);
            }
            
            // Vérifier si l'utilisateur a directement ce rôle
            if ($userRole === $allowedRole) {
                return $next($request);
            }
            
            // Vérifier la hiérarchie des rôles
            if (isset($roleHierarchy[$userRole])) {
                $allowedForUser = $roleHierarchy[$userRole];
                
                // Si l'utilisateur a accès à 'all' ou au rôle spécifique
                if (in_array('all', $allowedForUser) || in_array($allowedRole, $allowedForUser)) {
                    return $next($request);
                }
            }
        }

      
        // Si aucune autorisation trouvée, rediriger vers une page d'erreur
        abort(403, 'Accès non autorisé. Votre rôle ne vous permet pas d\'accéder à cette ressource.');
    }
}
