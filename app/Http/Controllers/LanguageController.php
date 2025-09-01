<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\HistorisableActions;

class LanguageController extends Controller
{
    use HistorisableActions;
    /**
     * Affiche la page de sélection de langue
     */
    public function index()
    {
        $currentLanguage = Auth::user()->language ?? 'fr';
        return view('language.selection', compact('currentLanguage'));
    }
    
    /**
     * Met à jour la langue de l'utilisateur
     */
    public function update(Request $request)
    {
        $request->validate([
            'language' => 'required|in:fr,en'
        ]);
        
        DB::table('users')
            ->where('id', Auth::id())
            ->update(['language' => $request->language]);
        // Historiser l'action
        $this->historiser("L'utilisateur " . Auth::user()->name . " a mis à jour sa langue en {$request->language}", 'update_language');
        return redirect()->back()->with('success', 
            $request->language === 'fr' 
                ? 'Langue mise à jour avec succès' 
                : 'Language updated successfully'
        );
    }
}
