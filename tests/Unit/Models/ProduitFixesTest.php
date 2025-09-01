<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Produit_fixes;
use App\Models\Utilisation;
use App\Models\MatiereRecommander;
use App\Models\Production;
use App\Models\ProduitRecu;
use App\Models\TransactionVente;
use App\Models\ProduitStock;
use App\Models\ProductionSuggererParJour;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProduitFixesTest extends TestCase
{
    use RefreshDatabase;

    protected $produit;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->produit = Produit_fixes::factory()->create([
            'nom' => 'Pain complet',
            'prix' => 250.00,
            'categorie' => 'Boulangerie'
        ]);
    }

    /** @test */
    public function it_can_create_produit_fixes()
    {
        $data = [
            'nom' => 'Croissant',
            'prix' => 150.00,
            'categorie' => 'Viennoiserie'
        ];

        $produit = Produit_fixes::create($data);

        $this->assertInstanceOf(Produit_fixes::class, $produit);
        $this->assertEquals($data['nom'], $produit->nom);
        $this->assertEquals($data['prix'], $produit->prix);
        $this->assertEquals($data['categorie'], $produit->categorie);
        $this->assertNotNull($produit->code_produit);
    }

    /** @test */
    public function it_can_read_produit_fixes()
    {
        $found = Produit_fixes::find($this->produit->code_produit);

        $this->assertNotNull($found);
        $this->assertEquals($this->produit->nom, $found->nom);
        $this->assertEquals($this->produit->prix, $found->prix);
        $this->assertEquals($this->produit->categorie, $found->categorie);
    }

    /** @test */
    public function it_can_update_produit_fixes()
    {
        $newName = 'Pain aux céréales';
        $newPrice = 300.00;
        
        $this->produit->update([
            'nom' => $newName,
            'prix' => $newPrice
        ]);

        $this->assertEquals($newName, $this->produit->fresh()->nom);
        $this->assertEquals($newPrice, $this->produit->fresh()->prix);
    }

    /** @test */
    public function it_can_delete_produit_fixes()
    {
        $code = $this->produit->code_produit;
        
        $this->produit->delete();

        $this->assertNull(Produit_fixes::find($code));
    }

    /** @test */
    public function it_uses_custom_primary_key()
    {
        $this->assertEquals('code_produit', $this->produit->getKeyName());
        $this->assertNotNull($this->produit->code_produit);
    }

    /** @test */
    public function it_uses_custom_table_name()
    {
        $this->assertEquals('Produit_fixes', $this->produit->getTable());
    }

  

    /** @test */
    public function it_returns_zero_when_no_stock_exists()
    {
        $this->assertEquals(0, $this->produit->getCurrentStock());
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = $this->produit->getFillable();
        
        $this->assertContains('nom', $fillable);
        $this->assertContains('prix', $fillable);
        $this->assertContains('categorie', $fillable);
    }

    /** @test */
    public function it_can_be_filtered_by_category()
    {
        Produit_fixes::factory()->create(['categorie' => 'Patisserie']);
        Produit_fixes::factory()->create(['categorie' => 'Boulangerie']);
        Produit_fixes::factory()->create(['categorie' => 'Patisserie']);

        $boulangerie = Produit_fixes::where('categorie', 'Boulangerie')->get();
        $patisserie = Produit_fixes::where('categorie', 'Patisserie')->get();

        $this->assertCount(2, $boulangerie); // 1 du setUp + 1 créé
        $this->assertCount(2, $patisserie);
    }

    /** @test */
    public function it_can_be_searched_by_name()
    {
        Produit_fixes::factory()->create(['nom' => 'Pain de mie']);
        Produit_fixes::factory()->create(['nom' => 'Pain aux raisins']);
        Produit_fixes::factory()->create(['nom' => 'Croissant']);

        $painProducts = Produit_fixes::where('nom', 'like', '%Pain%')->get();

        $this->assertCount(3, $painProducts); // 1 du setUp + 2 créés
    }
}
