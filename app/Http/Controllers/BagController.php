<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use App\Models\BagTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\HistorisableActions;

class BagController extends Controller
{
    use HistorisableActions;
     public function index()
    {
        $bags = Bag::orderBy('name')->get();
        return view('bags.index', compact('bags'));
    }

    /**
     * Ajouter du stock à un sac
     */
    public function addStock(Request $request, Bag $bag)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10000'
        ]);

        $bag->increaseStock($request->quantity);
        //historiser l'action
        $this->historiser('Ajout de stock par '.Auth()->user()->name.' pour le sac '.$bag->id.' de la quantité '.$request->quantity, 'bags_add_stock');
        return redirect()->back()->with('success', 
            "Stock ajouté avec succès. Nouveau stock: {$bag->stock_quantity}");
    }

    /**
     * Retirer du stock d'un sac
     */
    public function removeStock(Request $request, Bag $bag)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($request->quantity > $bag->stock_quantity) {
            return redirect()->back()->with('error', 
                'Quantité insuffisante en stock. Stock actuel: ' . $bag->stock_quantity);
        }

        $bag->stock_quantity -= $request->quantity;
        $bag->save();
        //historiser l'action
        $this->historiser('Retrait de stock par '.Auth()->user()->name.' pour le sac '.$bag->id.' de la quantité '.$request->quantity, 'bags_remove_stock');
        return redirect()->back()->with('success', 
            "Stock retiré avec succès. Nouveau stock: {$bag->stock_quantity}");
    }

    /**
     * Mettre à jour le seuil d'alerte
     */
    public function updateAlertThreshold(Request $request, Bag $bag)
    {
        $request->validate([
            'alert_threshold' => 'required|integer|min:0|max:1000'
        ]);

        $bag->alert_threshold = $request->alert_threshold;
        $bag->save();
        //historiser l'action
        $this->historiser('Mise à jour du seuil d\'alerte par '.Auth()->user()->name.' pour le sac '.$bag->id.' avec le nouveau seuil '.$bag->alert_threshold, 'bags_update_alert_threshold');
        return redirect()->back()->with('success', 
            "Seuil d'alerte mis à jour: {$bag->alert_threshold}");
    }

    /**
     * Obtenir les statistiques de stock
     */
    public function getStockStats()
    {
        $totalBags = Bag::count();
        $lowStockBags = Bag::whereColumn('stock_quantity', '<=', 'alert_threshold')->count();
        $outOfStockBags = Bag::where('stock_quantity', 0)->count();
        $totalStock = Bag::sum('stock_quantity');

        return [
            'total_bags' => $totalBags,
            'low_stock_bags' => $lowStockBags,
            'out_of_stock_bags' => $outOfStockBags,
            'total_stock' => $totalStock
        ];
    }

    public function create()
    {
        return view('bags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0'
        ]);

        Bag::create($validated);
        //$this->historiser("", 'type');
        // Enregistrer l'historique de l'action
        $this->historiser('Création d\'un sac par '.Auth()->user()->name.' avec le nom '.$validated['name'].' et le prix '.$validated['price'], 'bags_create');
        return redirect()->route('bags.index2')
            ->with('success', 'Sac créé avec succès.');
    }

    public function receive()
    {
        $nom = auth()->user()->name;
        $bags = Bag::all();
        return view('bags.receive', compact('bags','nom'));
    }

    public function storeReceived(Request $request)
    {
        $validated = $request->validate([
            'bag_id' => 'required|exists:bags,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date'
        ]);

        $bag = Bag::findOrFail($validated['bag_id']);

        BagTransaction::create([
            'bag_id' => $validated['bag_id'],
            'type' => 'received',
            'quantity' => $validated['quantity'],
            'transaction_date' => $validated['transaction_date']
        ]);

        $bag->increment('stock_quantity', $validated['quantity']);

        //historiser l'action
        $this->historiser('Réception de sacs par '.Auth()->user()->name.' pour le sac '.$bag->id.' de la quantité '.$validated['quantity'], 'bags_receive');
        return redirect()->route('bags.receive')
            ->with('success', 'Réception enregistrée avec succès.');
    }

    public function sell()
    {
        $nom = auth()->user()->name;
        $bags = Bag::all();
        return view('bags.sell', compact('bags','nom'));
    }

    public function storeSold(Request $request)
    {
        $validated = $request->validate([
            'bag_id' => 'required|exists:bags,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date'
        ]);

        $bag = Bag::findOrFail($validated['bag_id']);

        if ($bag->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stock insuffisant.']);
        }

        BagTransaction::create([
            'bag_id' => $validated['bag_id'],
            'type' => 'sold',
            'quantity' => $validated['quantity'],
            'transaction_date' => $validated['transaction_date']
        ]);

        $bag->decrement('stock_quantity', $validated['quantity']);
        //historiser l'action
        $this->historiser('Vente de sacs par '.Auth()->user()->name.' pour le sac '.$bag->id.' de la quantité '.$validated['quantity'], 'bags_sell');
        return redirect()->route('bags.sell')
            ->with('success', 'Vente enregistrée avec succès.');
    }
    public function index2()
    {
        $nom = auth()->user()->name;
        $bags = Bag::latest()->get();

        return view('bags.index2', compact('bags','nom'));

    }

    /**

     * Afficher le formulaire de création d'un sac.

     */

    public function create2()

    {

        return view('bags.create2');

    }

    /**

     * Stocker un nouveau sac dans la base de données.

     */

    public function store2(Request $request)

    {

        $validated = $request->validate([

            'name' => ['required', 'string', 'max:255', 'unique:bags,name'],

            'price' => ['required', 'numeric', 'min:0'],

            'stock_quantity' => ['required', 'integer', 'min:0'],

            'alert_threshold' => ['required', 'integer', 'min:1'],

        ]);

        Bag::create($validated);

        // Enregistrer l'historique de l'action
        $this->historiser('Création d\'un sac par '.Auth()->user()->name.' avec le nom '.$validated['name'].' et le prix '.$validated['price'], 'bags_create');
        return redirect()->route('bags.index')

            ->with('success', 'Sac créé avec succès.');

    }

    /**

     * Afficher le formulaire d'édition d'un sac.

     */

    public function edit(Bag $bag)

    {

        return view('bags.edit', compact('bag'));

    }

    /**

     * Mettre à jour le sac dans la base de données.

     */

    public function update(Request $request, Bag $bag)

    {

        $validated = $request->validate([

            'name' => ['required', 'string', 'max:255', Rule::unique('bags')->ignore($bag)],

            'price' => ['required', 'numeric', 'min:0'],

            'stock_quantity' => ['required', 'integer', 'min:0'],

            'alert_threshold' => ['required', 'integer', 'min:1'],

        ]);

        $bag->update($validated);
        // Enregistrer l'historique de l'action
        $this->historiser('Modification d\'un sac par '.Auth()->user()->name.' avec le nom '.$validated['name'].' et le prix '.$validated['price'], 'bags_update');
        return redirect()->route('bags.index2')

            ->with('success', 'Sac mis à jour avec succès.');

    }

    /**

     * Supprimer le sac de la base de données.

     */

    public function destroy(Bag $bag)

    {

        $bag->delete();

        // Enregistrer l'historique de l'action
        $this->historiser('Suppression d\'un sac par '.Auth()->user()->name.' avec le nom '.$bag->name, 'bags_delete');
        return redirect()->route('bags.index2')

            ->with('success', 'Sac supprimé avec succès.');

    }

    public function show(Bag $bag)
    {
        return view('bags.show', compact('bag'));
    }
}
