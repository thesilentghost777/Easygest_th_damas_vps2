<?php

namespace App\Http\Controllers;

use App\Models\PaydayConfig;
use Illuminate\Http\Request;
use App\Traits\HistorisableActions;

class PaydayConfigController extends Controller
{
    use HistorisableActions;
    public function index()
    {
        $config = PaydayConfig::getCurrentOrCreate();
        return view('payday.config', compact('config'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'salary_day' => 'required|integer|between:1,31',
            'advance_day' => 'required|integer|between:1,31'
        ]);
        
        $config = PaydayConfig::getCurrentOrCreate();
        $config->update($validated);
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour la configuration des jours de paie", 'update_payday_config');
        
        return redirect()->route('payday.config')
            ->with('success', 'Configuration des jours de paie mise à jour avec succès.');
    }
}
