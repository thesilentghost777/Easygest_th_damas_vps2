<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Traits\HistorisableActions;

class IngredientController extends Controller
{
    use HistorisableActions;
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        return view('recipes.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('recipes.ingredients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
        ]);

        Ingredient::create($validated);
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a créé un nouvel ingrédient: {$validated['name']}", 'create_ingredient');
        return redirect()->route('recipe.ingredients.index')
            ->with('success', 'Ingrédient créé avec succès.');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('recipes.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
        ]);

        $ingredient->update($validated);
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a mis à jour l'ingrédient: {$ingredient->name}", 'update_ingredient');
        return redirect()->route('recipe.ingredients.index')
            ->with('success', 'Ingrédient mis à jour avec succès.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        // Historiser l'action
        $this->historiser("L'utilisateur " . auth()->user()->name . " a supprimé l'ingrédient: {$ingredient->name}", 'delete_ingredient');
        return redirect()->route('recipe.ingredients.index')
            ->with('success', 'Ingrédient supprimé avec succès.');
    }
}
