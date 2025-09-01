<?php

namespace App\Services;

use App\Models\UserPin;
use Illuminate\Support\Facades\Hash;

class PinService
{
    /**
     * Vérifie si le code PIN fourni correspond à celui de l'utilisateur spécifié
     *
     * @param int $userId ID de l'utilisateur
     * @param string $pinCode Code PIN à vérifier
     * @return int 1 si le code correspond, 0 sinon
     */
    public function verifyPin(int $userId, string $pinCode): int
    {
        // Récupérer l'enregistrement du PIN pour l'utilisateur spécifié
        $userPin = UserPin::where('user_id', $userId)->first();
        
        // Si aucun PIN n'est trouvé pour cet utilisateur, retourner 0
        if (!$userPin) {
            return 0;
        }
        
        // Vérifier si le code PIN fourni correspond au hash stocké
        if (Hash::check($pinCode, $userPin->pin_code)) {
            return 1;
        }
        
        // Si le code ne correspond pas, retourner 0
        return 0;
    }
}