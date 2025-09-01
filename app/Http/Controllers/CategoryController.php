<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Http\Request;
use App\Traits\HistorisableActions;
class CategoryController extends Controller

{
    use HistorisableActions;

    public function index()

    {

        $categories = Category::all();

        return view('categories.index', compact('categories'));

    }

    public function store(Request $request)

    {

        $request->validate([

            'name' => 'required|string|max:255|unique:categories'

        ]);

        Category::create($request->all());

        // Historiser l'action
        $this->historiser('Création de la catégorie ' . $request->name . ' par ' . auth()->user()->name, 'categories_create');
        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès');

    }

    public function update(Request $request, Category $category)

    {

        $request->validate([

            'name' => 'required|string|max:255|unique:categories,name,' . $category->id

        ]);

        $category->update($request->all());

        // Historiser l'action
        $this->historiser('Mise à jour de la catégorie ' . $category->name . ' par ' . auth()->user()->name, 'categories_update');
        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès');

    }

    public function destroy(Category $category)

    {

        $category->delete();

        // Historiser l'action
        $this->historiser('Suppression de la catégorie ' . $category->name . ' par ' . auth()->user()->name, 'categories_delete');
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès');

    }

}
