<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\FirstConfigEmployee;
use App\Models\UserPin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PinConfigController extends Controller
{
    /**
     * Display the initial setup form for Complexe
     */

    

    public function showSetupForm()
    {
        return view('setup2.create');
    }

    /**
     * Process the initial setup form submission
     */
    public function processSetup(Request $request)
    {
        $request->validate([
            'pin_code' => [
                'required',
                'string',
                'size:6',
                'regex:/^[0-9]+$/',
                'confirmed',
            ],
        ], [
            'pin_code.required' => 'Le code PIN est obligatoire.',
            'pin_code.size' => 'Le code PIN doit contenir exactement 6 caractères.',
            'pin_code.regex' => 'Le code PIN doit contenir uniquement des chiffres.',
            'pin_code.confirmed' => 'La confirmation du code PIN ne correspond pas.',
        ]);

        // Vérifier si l'utilisateur a déjà un PIN
        $existingPin = UserPin::where('user_id', Auth::id())->first();

        if ($existingPin) {
            // Mise à jour du PIN existant
            $existingPin->update([
                'pin_code' => Hash::make($request->pin_code),
            ]);
        } else {
            // Création d'un nouveau PIN
            UserPin::create([
                'user_id' => Auth::id(),
                'pin_code' => Hash::make($request->pin_code),
            ]);
            //declaration de la config
            $user = Auth::user();
            $config3 = FirstConfigEmployee::where('user_id', $user->id)->first();
            
            if ($config3) {
                $config3->status = true;
                $config3->save();
                Log::info("config_sauvegarder");

            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Votre code PIN a été enregistré avec succès!');
    }


}
