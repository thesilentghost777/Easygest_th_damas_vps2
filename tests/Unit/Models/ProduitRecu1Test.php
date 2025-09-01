<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ProduitRecu1;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProduitRecu1Test extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_produit_recu1()
    {
        $produitRecu = ProduitRecu1::factory()->create([
            'quantite' => 100,
            'remarques' => 'Test remarque'
        ]);

        $this->assertDatabaseHas('produits_recu_1', [
            'id' => $produitRecu->id,
            'quantite' => 100,
            'remarques' => 'Test remarque'
        ]);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'produit_id',
            'quantite',
            'producteur_id',
            'pointeur_id',
            'vendeur_id',
            'date_reception',
            'remarques'
        ];

        $this->assertEquals($fillable, (new ProduitRecu1())->getFillable());
    }

    /** @test */
    public function it_belongs_to_produit()
    {
        $produit = Produit_fixes::factory()->create();
        $produitRecu = ProduitRecu1::factory()->create(['produit_id' => $produit->code_produit]);

        $this->assertInstanceOf(Produit_fixes::class, $produitRecu->produit);
        $this->assertEquals($produit->code_produit, $produitRecu->produit->code_produit);
    }

    /** @test */
    public function it_belongs_to_producteur()
    {
        $producteur = User::factory()->create();
        $produitRecu = ProduitRecu1::factory()->create(['producteur_id' => $producteur->id]);

        $this->assertInstanceOf(User::class, $produitRecu->producteur);
        $this->assertEquals($producteur->id, $produitRecu->producteur->id);
    }

    /** @test */
    public function it_belongs_to_pointeur()
    {
        $pointeur = User::factory()->create();
        $produitRecu = ProduitRecu1::factory()->create(['pointeur_id' => $pointeur->id]);

        $this->assertInstanceOf(User::class, $produitRecu->pointeur);
        $this->assertEquals($pointeur->id, $produitRecu->pointeur->id);
    }

    /** @test */
    public function it_belongs_to_vendeur()
    {
        $vendeur = User::factory()->create();
        $produitRecu = ProduitRecu1::factory()->create(['vendeur_id' => $vendeur->id]);

        $this->assertInstanceOf(User::class, $produitRecu->vendeur);
        $this->assertEquals($vendeur->id, $produitRecu->vendeur->id);
    }

    /** @test */
    public function it_can_update_produit_recu1()
    {
        $produitRecu = ProduitRecu1::factory()->create();
        
        $produitRecu->update([
            'quantite' => 200,
            'remarques' => 'Remarque mise à jour'
        ]);

        $this->assertDatabaseHas('produits_recu_1', [
            'id' => $produitRecu->id,
            'quantite' => 200,
            'remarques' => 'Remarque mise à jour'
        ]);
    }

    /** @test */
    public function it_can_delete_produit_recu1()
    {
        $produitRecu = ProduitRecu1::factory()->create();
        $id = $produitRecu->id;

        $produitRecu->delete();

        $this->assertDatabaseMissing('produits_recu_1', ['id' => $id]);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $produitRecu = new ProduitRecu1();
        $this->assertEquals('produits_recu_1', $produitRecu->getTable());
    }


}
