<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Utilisation;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use App\Models\User;
use App\Models\MatiereRecommander;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class UtilisationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_utilisation()
    {
        $utilisation = Utilisation::factory()->create([
            'quantite_produit' => 100,
            'quantite_matiere' => 50,
            'unite_matiere' => 'kg'
        ]);

        $this->assertDatabaseHas('Utilisation', [
            'id' => $utilisation->id,
            'quantite_produit' => 100,
            'quantite_matiere' => 50,
            'unite_matiere' => 'kg'
        ]);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'id_lot',
            'produit',
            'matierep',
            'producteur',
            'quantite_produit',
            'quantite_matiere',
            'unite_matiere'
        ];

        $this->assertEquals($fillable, (new Utilisation())->getFillable());
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $utilisation = new Utilisation();
        $this->assertEquals('Utilisation', $utilisation->getTable());
    }

    /** @test */
    public function it_belongs_to_produit_fixe()
    {
        $produitFixe = Produit_fixes::factory()->create();
        $utilisation = Utilisation::factory()->create(['produit' => $produitFixe->code_produit]);

        $this->assertInstanceOf(Produit_fixes::class, $utilisation->produitFixe);
        $this->assertEquals($produitFixe->code_produit, $utilisation->produitFixe->code_produit);
    }

    /** @test */
    public function it_belongs_to_produit_via_produit_fixes_method()
    {
        $produitFixe = Produit_fixes::factory()->create();
        $utilisation = Utilisation::factory()->create(['produit' => $produitFixe->code_produit]);

        $this->assertInstanceOf(Produit_fixes::class, $utilisation->produit_fixes);
        $this->assertEquals($produitFixe->code_produit, $utilisation->produit_fixes->code_produit);
    }

    /** @test */
    public function it_belongs_to_matiere()
    {
        $matiere = Matiere::factory()->create();
        $utilisation = Utilisation::factory()->create(['matierep' => $matiere->id]);

        $this->assertInstanceOf(Matiere::class, $utilisation->matiere);
        $this->assertEquals($matiere->id, $utilisation->matiere->id);
    }

    /** @test */
    public function it_belongs_to_matiere_premiere()
    {
        $matiere = Matiere::factory()->create();
        $utilisation = Utilisation::factory()->create(['matierep' => $matiere->id]);

        $this->assertInstanceOf(Matiere::class, $utilisation->matierePremiere);
        $this->assertEquals($matiere->id, $utilisation->matierePremiere->id);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $utilisation = Utilisation::factory()->create(['producteur' => $user->id]);

        $this->assertInstanceOf(User::class, $utilisation->user);
        $this->assertEquals($user->id, $utilisation->user->id);
    }

    /** @test */
    public function it_belongs_to_producteur()
    {
        $producteur = User::factory()->create();
        $utilisation = Utilisation::factory()->create(['producteur' => $producteur->id]);

        $this->assertInstanceOf(User::class, $utilisation->producteur);
        $this->assertEquals($producteur->id, $utilisation->producteur->id);
    }

    /** @test */
    public function get_wasted_quantity_returns_null_when_no_recommendation()
    {
        $utilisation = Utilisation::factory()->create();

        $result = $utilisation->getWastedQuantity();

        $this->assertNull($result);
    }

    /** @test */
    public function get_wasted_quantity_calculates_correctly_with_recommendation()
    {
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();
        
        // Create recommendation
        $recommandation = MatiereRecommander::factory()->create([
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'quantite' => 10, // 10 units of material
            'quantitep' => 1   // for 1 unit of product
        ]);

        // Create utilisation with more material than recommended
        $utilisation = Utilisation::factory()->create([
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'quantite_produit' => 5,  // 5 units of product
            'quantite_matiere' => 60  // 60 units of material used (recommended: 50)
        ]);

        // Mock the getRecommendedQuantityFor method
        $this->mock(MatiereRecommander::class, function ($mock) {
            $mock->shouldReceive('getRecommendedQuantityFor')
                 ->with(5)
                 ->andReturn(50);
        });

        Log::shouldReceive('warning')->never();
        Log::shouldReceive('info')->andReturn(true);

        $wastedQuantity = $utilisation->getWastedQuantity();

        $this->assertEquals(10, $wastedQuantity); // 60 - 50 = 10
    }

    /** @test */
    public function get_wasted_value_returns_null_when_no_waste()
    {
        $utilisation = Utilisation::factory()->create();
        
        // Mock getWastedQuantity to return null
        $utilisation = $this->partialMock(Utilisation::class, function ($mock) {
            $mock->shouldReceive('getWastedQuantity')->andReturn(null);
        });

        $result = $utilisation->getWastedValue();

        $this->assertNull($result);
    }

    /** @test */
    public function get_wasted_value_calculates_correctly()
    {
        $matiere = Matiere::factory()->create(['prix_par_unite_minimale' => 100]);
        $utilisation = Utilisation::factory()->create(['matierep' => $matiere->id]);
        
        // Mock getWastedQuantity to return 5
        $utilisation = $this->partialMock(Utilisation::class, function ($mock) {
            $mock->shouldReceive('getWastedQuantity')->andReturn(5);
        });

        Log::shouldReceive('info')->andReturn(true);

        $wastedValue = $utilisation->getWastedValue();

        $this->assertEquals(500, $wastedValue); // 5 * 100 = 500
    }

    /** @test */
    public function get_wasted_value_returns_null_for_negative_waste()
    {
        $utilisation = Utilisation::factory()->create();
        
        // Mock getWastedQuantity to return negative value
        $utilisation = $this->partialMock(Utilisation::class, function ($mock) {
            $mock->shouldReceive('getWastedQuantity')->andReturn(-5);
        });

        $result = $utilisation->getWastedValue();

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_update_utilisation()
    {
        $utilisation = Utilisation::factory()->create();
        
        $utilisation->update([
            'quantite_produit' => 200,
            'quantite_matiere' => 80,
            'unite_matiere' => 'g'
        ]);

        $this->assertDatabaseHas('Utilisation', [
            'id' => $utilisation->id,
            'quantite_produit' => 200,
            'quantite_matiere' => 80,
            'unite_matiere' => 'g'
        ]);
    }

    /** @test */
    public function it_can_delete_utilisation()
    {
        $utilisation = Utilisation::factory()->create();
        $id = $utilisation->id;

        $utilisation->delete();

        $this->assertDatabaseMissing('Utilisation', ['id' => $id]);
    }
}
