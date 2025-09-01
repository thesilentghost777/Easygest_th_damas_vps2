<!DOCTYPE html>
<html lang="{{ $isFrench ? 'fr' : 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isFrench ? 'Codes des Secteurs' : 'Sector Codes' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .mobile-card {
                animation: slideInUp 0.4s ease;
                border-left: 0.25rem solid #2563eb;
            }

            @keyframes slideInUp {
                0% {
                    transform: translateY(30px);
                    opacity: 0;
                }
                100% {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen py-6">
    <div class="container mx-auto px-4 max-w-5xl">
        @include('buttons')

        <br><br>
        <div class="bg-red-900 bg-opacity-30 border border-red-600 rounded p-3 mb-6 text-center">
            <p class="text-red-400 font-bold">
                {{ $isFrench ? 'ATTENTION : Ces codes sont strictement confidentiels et ne doivent pas être divulgués à des personnes non autorisées.' : 'WARNING: These codes are strictly confidential and must not be disclosed to unauthorized persons.' }}
            </p>
        </div>

        <h1 class="text-3xl font-bold text-center mb-8">
            {{ $isFrench ? "Codes secrets pour l'enregistrement du personnel" : "Secret codes for staff registration" }}
        </h1>

        @php
            $sections = [
                'Alimentation' => [
                    ['Caissière', '75804'], ['Calviste', '75804'], ['Rayoniste', '75804'],
                    ['Contrôleur', '75804'], ['Technicien Surface', '75804'], ['Magasinier', '75804'],
                    ['Chef Rayoniste', '75804'], ['Virgil', '75804']
                ],
                'Production' => [
                    ['Pâtissier', '182736'], ['Boulanger', '394857'], ['Pointeur', '527194'],
                    ['Enfourneur', '639285'], ['Technicien Surface', '748196']
                ],
                'Glace' => [
                    ['Glacière', '583492']
                ],
                'Administration' => [
                    ['Chef Production', '948371'], ['DG', '217634'], ['DDG', '365982'],
                    ['Gestionnaire Alimentation', '365982'], ['PDG', '592483']
                ],
                'Vente' => [
                    ['Vendeur Boulangerie', '748596'], ['Vendeur Pâtisserie', '983214']
                ]
            ];

            $translations = [
                'Alimentation' => 'Food',
                'Production' => 'Production',
                'Glace' => 'Ice Cream',
                'Administration' => 'Administration',
                'Vente' => 'Sales',
                'Rôle' => 'Role',
                'Code' => 'Code'
            ];
        @endphp

        @foreach ($sections as $secteur => $roles)
        <div class="mb-8 bg-gray-800 rounded-lg shadow p-4 mobile-card">
            <h2 class="text-xl font-semibold text-blue-400 mb-3">
                {{ $isFrench ? "Secteur $secteur" : "$translations[$secteur] Sector" }}
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-800">
                        <tr>
                            <th class="px-4 py-2 text-left">{{ $isFrench ? 'Rôle' : $translations['Rôle'] }}</th>
                            <th class="px-4 py-2 text-left">{{ $isFrench ? 'Code' : $translations['Code'] }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $role[0] }}</td>
                            <td class="px-4 py-2">{{ $role[1] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
