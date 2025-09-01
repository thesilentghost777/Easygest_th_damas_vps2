<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HistorisableActions;

class ProductController extends Controller
{
    use HistorisableActions;
    //$this->historiser("description detailler explicite et complete de l'action", 'type');
    /**
     * Display a listing of the resource.
     */
    public function index(ProductGroup $group)
    {
        // Check if the user owns this group
        if ($group->user_id !== Auth::id()) {
            abort(403);
        }

        $products = $group->products()->get();
        return view('inventory.products.index', compact('group', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ProductGroup $group)
    {
        // Check if the user owns this group
        if ($group->user_id !== Auth::id()) {
            abort(403);
        }

        return view('inventory.products.create', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ProductGroup $group)
    {
        // Check if the user owns this group
        if ($group->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $validated['product_group_id'] = $group->id;

        $product = Product::create($validated);
        //historiser l'action
        $this->historiser("Ajout du produit {$product->name} au groupe {$group->name}", 'create_product');
        return redirect()->route('inventory.groups.show', $group)
            ->with('success', 'Produit ajouté avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $group = $product->productGroup;

        // Check if the user owns the group this product belongs to
        if ($group->user_id !== Auth::id()) {
            abort(403);
        }

        return view('inventory.products.show', compact('product', 'group'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $group = $product->productGroup;

        // Check if the user owns the group this product belongs to
        if ($group->user_id !== Auth::id()) {
            abort(403);
        }

        return view('inventory.products.edit', compact('product', 'group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $group = $product->productGroup;

        // Check if the user owns the group this product belongs to
        if ($group->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);

        $product->update($validated);
        //historiser l'action
        $this->historiser("Mise à jour du produit {$product->name} dans le groupe {$group->name}", 'update_product');
        return redirect()->route('inventory.groups.show', $group)
            ->with('success', 'Produit mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $group = $product->productGroup;

        // Check if the user owns the group this product belongs to
        if ($group->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete the product
        $product->delete();
        //historiser l'action
        $this->historiser("Suppression du produit {$product->name} du groupe {$group->name}", 'delete_product');
        return redirect()->route('inventory.groups.show', $group)
            ->with('success', 'Produit supprimé avec succès');
    }
}
