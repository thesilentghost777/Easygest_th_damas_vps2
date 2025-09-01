<x-guest-layout>
    <head>
        <style>
             .important-red {
            color: red;
            font-weight: bold;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
            input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dadce0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.2s;
}
        </style>
    </head>
    <div id="form">
        <!-- Affichage des messages d'erreur généraux -->
        @if ($errors->has('message'))
            <div class="alert alert-error">
                {{ $errors->first('message') }}
            </div>
        @endif

        <!-- Affichage des messages de succès -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

         <!-- Message d'avertissement pour le code secret -->
         <div class="secret-code-warning">
            ⚠️ <span class="important-red">IMPORTANT</span> : Pour obtenir le code secret de votre poste, veuillez contacter directement le Directeur Général (DG).
            Ce code est strictement confidentiel et personnel.
        </div>
        <br>
    <form method="POST" action="{{ route('sign_up') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom')"/>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- numero de telephone -->
         <div class="mt-4">
            <x-input-label for="num_tel" :value="__('Telephone')" />
            <x-text-input id="num_tel" class="block mt-1 w-full" type="tel" name="num_tel" :value="old('num_tel')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('num_tel')" class="mt-2" />
        </div>

        <!-- date_naissance -->
        <div class="mt-4">
            <x-input-label for="date_naissance" :value="__('Date de Naissance')" />
            <x-text-input id="date_naissance" class="block mt-1 w-full" type="date" name="date_naissance" :value="old('date_naissance')" required autocomplete="bday" />
            <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
        </div>

        <!-- annee debut service-->
        <div class="mt-4">
            <x-input-label for="annee_debut_service" :value="__('Annee de debut du service')" />
            <x-text-input id="annee_debut_service" class="block mt-1 w-full" type="number" name="annee_debut_service" :value="old('annee_debut_service')" required />
            <x-input-error :messages="$errors->get('annee_debut_service')" class="mt-2" />
        </div>

        <div class="mt-4">
        <x-input-label for="secteur" :value="__('Département')"/>
        <select id="secteur" name="secteur" class="block mt-1 w-full">
            <option value="">Sélectionnez un département</option>
            <option value="alimentation" {{ old('secteur') == 'alimentation' ? 'selected' : '' }}>Alimentation</option>
            <option value="vente" {{ old('secteur') == 'vente' ? 'selected' : '' }}>Vente</option>
            <option value="production" {{ old('secteur') == 'production' ? 'selected' : '' }}>Production</option>
            <option value="glace" {{ old('secteur') == 'glace' ? 'selected' : '' }}>Glace</option>
            <option value="administration" {{ old('secteur') == 'administration' ? 'selected' : '' }}>Administration</option>
        </select>
        <x-input-error :messages="$errors->get('secteur')" class="mt-2" />
        </div>

        <div class="form-group mt-4">
        <x-input-label for="role" :value="__('Role')"/>
        <select id="role" name="role" required class="block mt-1 w-full">
            <option value="">Sélectionnez un rôle</option>
            <optgroup label="Alimentation">
                <option value="caissiere" data-department="alimentation" {{ old('role') == 'caissiere' ? 'selected' : '' }}>Caissier(e)</option>
                <option value="calviste" data-department="alimentation" {{ old('role') == 'calviste' ? 'selected' : '' }}>Calviste</option>
                <option value="magasinier" data-department="alimentation" {{ old('role') == 'magasinier' ? 'selected' : '' }}>Magasinier</option>
                <option value="rayoniste" data-department="alimentation" {{ old('role') == 'rayoniste' ? 'selected' : '' }}>Rayoniste</option>
                <option value="controleur" data-department="alimentation" {{ old('role') == 'controleur' ? 'selected' : '' }}>Contrôleur</option>
                <option value="tech_surf" data-department="alimentation" {{ old('role') == 'tech_surf' ? 'selected' : '' }}>Technicien de Surface</option>
                <option value="virgile" data-department="alimentation" {{ old('role') == 'virgile' ? 'selected' : '' }}>Virgil</option>
                <option value="chef_rayoniste" data-department="alimentation" {{ old('role') == 'chef_rayoniste' ? 'selected' : '' }}>Chef rayonniste</option>
            </optgroup>
            <optgroup label="Production">
                <option value="patissier" data-department="production" {{ old('role') == 'patissier' ? 'selected' : '' }}>Patissier(e)</option>
                <option value="boulanger" data-department="production" {{ old('role') == 'boulanger' ? 'selected' : '' }}>Boulanger(e)</option>
                <option value="pointeur" data-department="production" {{ old('role') == 'pointeur' ? 'selected' : '' }}>Pointeur</option>
                <option value="enfourneur" data-department="production" {{ old('role') == 'enfourneur' ? 'selected' : '' }}>Enfourneur</option>
                <option value="tech_surf" data-department="production" {{ old('role') == 'tech_surf' ? 'selected' : '' }}>Technicien de Surface</option>
            </optgroup>
            <optgroup label="Glace">
                <option value="glace" data-department="glace" {{ old('role') == 'glace' ? 'selected' : '' }}>Glace</option>
            </optgroup>
            <optgroup label="Administration">
                <option value="chef_production" data-department="administration" {{ old('role') == 'chef_production' ? 'selected' : '' }}>Chef Production</option>
                <option value="gestionnaire_alimentation" data-department="administration" {{ old('role') == 'gestionnaire_alimentation' ? 'selected' : '' }}>Gestionnaire alimentation</option>
                <option value="dg" data-department="administration" {{ old('role') == 'dg' ? 'selected' : '' }}>DG</option>
                <option value="pdg" data-department="administration" {{ old('role') == 'pdg' ? 'selected' : '' }}>PDG</option>
            </optgroup>
            <optgroup label="Vente">
                <option value="vendeur_boulangerie" data-department="vente" {{ old('role') == 'vendeur_boulangerie' ? 'selected' : '' }}>Vendeur(se) boulangerie</option>
                <option value="vendeur_patisserie" data-department="vente" {{ old('role') == 'vendeur_patisserie' ? 'selected' : '' }}>Vendeur(se) patisserie</option>
            </optgroup>
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
        <div class="error-message" id="roleError"></div>
    </div>

        <!--code secret du poste -->
        <div class="mt-4">
            <x-input-label for="code_secret" :value="__('Code secret du poste')" />
            <x-text-input id="code_secret" class="block mt-1 w-full"
                            type="number"
                            name="code_secret"
                            :value="old('code_secret')"
                            required autocomplete="off" />
            <x-input-error :messages="$errors->get('code_secret')" class="mt-2" />
        </div>
        
        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmation de Mot de passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Conditions d'utilisation -->
        <div class="mt-4">
            <div class="flex items-center">
                <input id="accept_terms" name="accept_terms" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('accept_terms') ? 'checked' : '' }} required>
                <label for="accept_terms" class="ml-2 block text-sm text-gray-900">
                    J'accepte les <a href="{{ route('about') }}" class="text-indigo-600 hover:text-indigo-500 underline" target="_blank">conditions d'utilisation</a>
                </label>
            </div>
            <x-input-error :messages="$errors->get('accept_terms')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Déjà inscrit ?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('S\'inscrire') }}
            </x-primary-button>
        </div>
    </form>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const secteurSelect = document.getElementById('secteur');
            const roleSelect = document.getElementById('role');

            // Fonction pour filtrer les rôles en fonction du département
            function filterRoles() {
                const selectedDepartment = secteurSelect.value;
                const options = roleSelect.querySelectorAll('option');

                options.forEach(option => {
                    const department = option.getAttribute('data-department');
                    if (!department || department === selectedDepartment) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Réinitialiser la sélection si le rôle actuel n'appartient pas au département
                const currentRole = roleSelect.value;
                const currentOption = roleSelect.querySelector(`option[value="${currentRole}"]`);
                if (currentOption && currentOption.style.display === 'none') {
                    roleSelect.value = '';
                }
            }

            secteurSelect.addEventListener('change', filterRoles);
            filterRoles(); // Appliquer le filtre au chargement
        });
    </script>
</x-guest-layout>