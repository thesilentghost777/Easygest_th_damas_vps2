<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TransactionVente;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionVenteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_transaction_vente()
    {
        $transactionVente = TransactionVente::factory()->create([
            'quantite' => 5,
            'prix' => 2500.00,
            'type' => 'vente',
            'monnaie' => 'FCFA'
        ]);

        $this->assertDatabaseHas('transaction_ventes', [
            'id' => $transactionVente->id,
            'quantite' => 5,
            'prix' => 2500.00,
            'type' => 'vente'
        ]);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'produit',
            'serveur',
            'quantite',
            'prix',
            'date_vente',
            'type',
            'monnaie',
            'created_at',
            'updated_at'
        ];

        $this->assertEquals($fillable, (new TransactionVente())->getFillable());
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $transactionVente = new TransactionVente();
        $this->assertEquals('transaction_ventes', $transactionVente->getTable());
    }

    /** @test */
    public function it_casts_date_vente_to_date()
    {
        $transactionVente = TransactionVente::factory()->create([
            'date_vente' => '2024-01-15'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $transactionVente->date_vente);
    }


    /** @test */
    public function it_belongs_to_produit_via_produit_fixes_method()
    {
        $produitFixe = Produit_fixes::factory()->create();
        $transactionVente = TransactionVente::factory()->create(['produit' => $produitFixe->code_produit]);

        $this->assertInstanceOf(Produit_fixes::class, $transactionVente->Produit_fixes);
        $this->assertEquals($produitFixe->code_produit, $transactionVente->Produit_fixes->code_produit);
    }

    /** @test */
    public function it_belongs_to_produit_via_produits_method()
    {
        $produitFixe = Produit_fixes::factory()->create();
        $transactionVente = TransactionVente::factory()->create(['produit' => $produitFixe->code_produit]);

        $this->assertInstanceOf(Produit_fixes::class, $transactionVente->produits);
        $this->assertEquals($produitFixe->code_produit, $transactionVente->produits->code_produit);
    }

    /** @test */
    public function it_belongs_to_vendeur()
    {
        $vendeur = User::factory()->create();
        $transactionVente = TransactionVente::factory()->create(['serveur' => $vendeur->id]);

        $this->assertInstanceOf(User::class, $transactionVente->vendeur);
        $this->assertEquals($vendeur->id, $transactionVente->vendeur->id);
    }

    /** @test */
    public function it_has_user_relation()
    {
        $user = User::factory()->create();
        $transactionVente = TransactionVente::factory()->create();
        // Assuming user_id field exists or we need to modify this test based on actual implementation
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $transactionVente->user());
    }

    /** @test */
    public function it_calculates_total_amount_correctly()
    {
        $transactionVente = TransactionVente::factory()->create([
            'quantite' => 10,
            'prix' => 2500.00
        ]);

        $this->assertEquals(25000.00, $transactionVente->total_amount);
    }

    /** @test */
    public function it_formats_total_amount_correctly()
    {
        $transactionVente = TransactionVente::factory()->create([
            'quantite' => 8,
            'prix' => 1500.75
        ]);

        $this->assertEquals('12 006 FCFA', $transactionVente->formatted_total_amount);
    }

    /** @test */
    public function it_handles_large_amount_formatting()
    {
        $transactionVente = TransactionVente::factory()->create([
            'quantite' => 100,
            'prix' => 15000.00
        ]);

        $this->assertEquals('1 500 000 FCFA', $transactionVente->formatted_total_amount);
    }

    /** @test */
    public function name_method_returns_product_name_when_exists()
    {
        $produitFixe = Produit_fixes::factory()->create(['nom' => 'Produit Test']);
        $transactionVente = new TransactionVente();

        $result = $transactionVente->name($produitFixe->code_produit);

        $this->assertEquals('Produit Test', $result);
    }

    /** @test */
    public function name_method_returns_default_message_when_product_not_found()
    {
        $transactionVente = new TransactionVente();

        $result = $transactionVente->name(999999);

        $this->assertEquals('Aucun nom associer', $result);
    }

    /** @test */
    public function it_can_update_transaction_vente()
    {
        $transactionVente = TransactionVente::factory()->create();
        
        $transactionVente->update([
            'quantite' => 15,
            'prix' => 3000.00,
            'type' => 'retour'
        ]);

        $this->assertDatabaseHas('transaction_ventes', [
            'id' => $transactionVente->id,
            'quantite' => 15,
            'prix' => 3000.00,
            'type' => 'retour'
        ]);
    }

    /** @test */
    public function it_can_delete_transaction_vente()
    {
        $transactionVente = TransactionVente::factory()->create();
        $id = $transactionVente->id;

        $transactionVente->delete();

        $this->assertDatabaseMissing('transaction_ventes', ['id' => $id]);
    }

    /** @test */
    public function it_handles_zero_quantity_total_amount()
    {
        $transactionVente = TransactionVente::factory()->create([
            'quantite' => 0,
            'prix' => 1500.00
        ]);

        $this->assertEquals(0.00, $transactionVente->total_amount);
        $this->assertEquals('0 FCFA', $transactionVente->formatted_total_amount);
    }

    /** @test */
    public function it_handles_zero_price_total_amount()
    {
        $transactionVente = TransactionVente::factory()->create([
            'quantite' => 5,
            'prix' => 0.00
        ]);

        $this->assertEquals(0.00, $transactionVente->total_amount);
        $this->assertEquals('0 FCFA', $transactionVente->formatted_total_amount);
    }
}
