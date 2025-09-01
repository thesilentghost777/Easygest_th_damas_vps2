<div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 md:p-6 lg:p-6">
    <h3 class="text-lg md:text-xl font-semibold text-red-700 mb-2 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        {{ $isFrench ? 'Incohérence détectée' : 'Inconsistency Detected' }}
    </h3>
    <p class="text-sm text-red-600 mb-2">
        {{ $isFrench ?
            "Il existe une différence de" :
            "There is a discrepancy of" }}
        <strong>{{ $objective->formatted_inconsistency_amount }}</strong>
        {{ $isFrench ?
            "entre la somme des ventes des sous-objectifs (" :
            "between the sum of sub-objective sales (" }}
        {{ number_format($objective->subObjectives->sum('current_amount'), 0, ',', ' ') }} FCFA
        {{ $isFrench ?
            ") et le montant total des entrées de l'objectif principal (" :
            ") and the total amount of the main objective (" }}
        {{ $objective->formatted_current_amount }}).
    </p>
    <p class="text-sm text-red-700">
        {{ $isFrench ? 'Cela peut être dû à :' : 'Possible reasons include:' }}
        <ul class="list-disc list-inside mt-1 space-y-1">
            <li>
                {{ $isFrench ?
                    "Des ventes qui n'ont pas été correctement associées à un produit" :
                    "Sales not correctly linked to a product" }}
            </li>
            <li>
                {{ $isFrench ?
                    "Des transactions manquantes dans le système" :
                    "Missing transactions in the system" }}
            </li>
            <li>
                {{ $isFrench ?
                    "Des produits vendus qui ne sont pas liés à des sous-objectifs" :
                    "Sold products not linked to sub-objectives" }}
            </li>
        </ul>
    </p>
</div>

@include('buttons')

<style scoped>
@media (max-width: 768px) {
    div {
        animation: fadeInUp 0.6s ease;
        border-left-width: 6px;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
}
</style>
