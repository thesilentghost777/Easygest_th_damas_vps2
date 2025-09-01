<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ProduitStock;
use App\Models\Produit_fixes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProduitStockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_produit_stock()
    {
        $produitStock = ProduitStock::factory()->create([
            'quantite_en_stock' => 100,
            'quantite_invendu' => 10,
            'quantite_avarie' => 5
        ]);

        $this->assertDatabaseHas('produit_stocks', [
            'id' => $produitStock->id,
            'quantite_en_stock' => 100,
            'quantite_invendu' => 10,
            'quantite_avarie' => 5
        ]);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'id_produit',
            'quantite_en_stock',
            'quantite_invendu',
            'quantite_avarie'
        ];

        $this->assertEquals($fillable, (new ProduitStock())->getFillable());
    }

    /** @test */
    public function it_belongs_to_produit_fixe()
    {
        $produitFixe = Produit_fixes::factory()->create();
        $produitStock = ProduitStock::factory()->create(['id_produit' => $produitFixe->code_produit]);

        $this->assertInstanceOf(Produit_fixes::class, $produitStock->produitFixe);
        $this->assertEquals($produitFixe->code_produit, $produitStock->produitFixe->code_produit);
    }

    /** @test */
    public function it_can_update_stock_en_stock()
    {
        $produitFixe = Produit_fixes::factory()->create();
        
        $result = ProduitStock::updateStock($produitFixe->code_produit, 50, 'en_stock');
        
        $this->assertTrue($result);
        $this->assertDatabaseHas('produit_stocks', [
            'id_produit' => $produitFixe->code_produit,
            'quantite_en_stock' => 50
        ]);
    }

    /** @test */
    public function it_can_update_stock_invendu()
    {
        $produitFixe = Produit_fixes::factory()->create();
        
        $result = ProduitStock::updateStock($produitFixe->code_produit, 20, 'invendu');
        
        $this->assertTrue($result);
        $this->assertDatabaseHas('produit_stocks', [
            'id_produit' => $produitFixe->code_produit,
            'quantite_invendu' => 20
        ]);
    }

    /** @test */
    public function it_can_update_stock_avarie()
    {
        $produitFixe = Produit_fixes::factory()->create();
        
        $result = ProduitStock::updateStock($produitFixe->code_produit, 15, 'avarie');
        
        $this->assertTrue($result);
        $this->assertDatabaseHas('produit_stocks', [
            'id_produit' => $produitFixe->code_produit,
            'quantite_avarie' => 15
        ]);
    }

    /** @test */
    public function it_creates_new_stock_if_not_exists()
    {
        $produitFixe = Produit_fixes::factory()->create();
        
        $this->assertDatabaseMissing('produit_stocks', ['id_produit' => $produitFixe->code_produit]);
        
        ProduitStock::updateStock($produitFixe->code_produit, 30);
        
        $this->assertDatabaseHas('produit_stocks', [
            'id_produit' => $produitFixe->code_produit,
            'quantite_en_stock' => 30,
            'quantite_invendu' => 0,
            'quantite_avarie' => 0
        ]);
    }

    /** @test */
    public function it_prevents_negative_stock()
    {
        $produitFixe = Produit_fixes::factory()->create();
        ProduitStock::factory()->create([
            'id_produit' => $produitFixe->code_produit,
            'quantite_en_stock' => 10
        ]);
        
        $result = ProduitStock::updateStock($produitFixe->code_produit, -20, 'en_stock');
        
        $this->assertFalse($result);
        $this->assertDatabaseHas('produit_stocks', [
            'id_produit' => $produitFixe->code_produit,
            'quantite_en_stock' => 10
        ]);
    }

    /** @test */
    public function it_can_increment_existing_stock()
    {
        $produitFixe = Produit_fixes::factory()->create();
        ProduitStock::factory()->create([
            'id_produit' => $produitFixe->code_produit,
            'quantite_en_stock' => 50
        ]);
        
        ProduitStock::updateStock($produitFixe->code_produit, 25, 'en_stock');
        
        $this->assertDatabaseHas('produit_stocks', [
            'id_produit' => $produitFixe->code_produit,
            'quantite_en_stock' => 75
        ]);
    }

    /** @test */
    public function it_calculates_quantite_totale_attribute()
    {
        $produitStock = ProduitStock::factory()->create([
            'quantite_en_stock' => 100,
            'quantite_invendu' => 15,
            'quantite_avarie' => 10
        ]);

        $this->assertEquals(75, $produitStock->quantite_totale);
    }

    /** @test */
    public function it_can_update_produit_stock()
    {
        $produitStock = ProduitStock::factory()->create();
        
        $produitStock->update([
            'quantite_en_stock' => 200,
            'quantite_invendu' => 20
        ]);

        $this->assertDatabaseHas('produit_stocks', [
            'id' => $produitStock->id,
            'quantite_en_stock' => 200,
            'quantite_invendu' => 20
        ]);
    }

    /** @test */
    public function it_can_delete_produit_stock()
    {
        $produitStock = ProduitStock::factory()->create();
        $id = $produitStock->id;

        $produitStock->delete();

        $this->assertDatabaseMissing('produit_stocks', ['id' => $id]);
    }

    /** @test */
    public function update_stock_uses_default_type_for_invalid_type()
    {
        $produitFixe = Produit_fixes::factory()->create();
        
        ProduitStock::updateStock($produitFixe->code_produit, 40, 'invalid_type');
        
        $this->assertDatabaseHas('produit_stocks', [
            'id_produit' => $produitFixe->code_produit,
            'quantite_en_stock' => 40
        ]);
    }
}
