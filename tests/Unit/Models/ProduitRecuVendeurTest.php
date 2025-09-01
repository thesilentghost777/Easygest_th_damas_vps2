<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ProduitRecuVendeur;
use App\Models\ProduitRecu1;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProduitRecuVendeurTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_produit_recu_vendeur()
    {
        $produitRecuVendeur = ProduitRecuVendeur::factory()->create([
            'quantite_recue' => 50,
            'quantite_confirmee' => 45,
            'status' => 'confirmé',
            'remarques' => 'Test remarque'
        ]);

        $this->assertDatabaseHas('produits_recu_vendeur', [
            'id' => $produitRecuVendeur->id,
            'quantite_recue' => 50,
            'quantite_confirmee' => 45,
            'status' => 'confirmé'
        ]);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'produit_recu_id',
            'vendeur_id',
            'quantite_recue',
            'quantite_confirmee',
            'status',
            'remarques'
        ];

        $this->assertEquals($fillable, (new ProduitRecuVendeur())->getFillable());
    }

    /** @test */
    public function it_casts_quantities_to_integer()
    {
        $produitRecuVendeur = ProduitRecuVendeur::factory()->create([
            'quantite_recue' => '100',
            'quantite_confirmee' => '95'
        ]);

        $this->assertIsInt($produitRecuVendeur->quantite_recue);
        $this->assertIsInt($produitRecuVendeur->quantite_confirmee);
        $this->assertEquals(100, $produitRecuVendeur->quantite_recue);
        $this->assertEquals(95, $produitRecuVendeur->quantite_confirmee);
    }

    /** @test */
    public function it_belongs_to_produit_recu()
    {
        $produitRecu = ProduitRecu1::factory()->create();
        $produitRecuVendeur = ProduitRecuVendeur::factory()->create(['produit_recu_id' => $produitRecu->id]);

        $this->assertInstanceOf(ProduitRecu1::class, $produitRecuVendeur->produitRecu);
        $this->assertEquals($produitRecu->id, $produitRecuVendeur->produitRecu->id);
    }

    /** @test */
    public function it_belongs_to_vendeur()
    {
        $vendeur = User::factory()->create();
        $produitRecuVendeur = ProduitRecuVendeur::factory()->create(['vendeur_id' => $vendeur->id]);

        $this->assertInstanceOf(User::class, $produitRecuVendeur->vendeur);
        $this->assertEquals($vendeur->id, $produitRecuVendeur->vendeur->id);
    }

    /** @test */
    public function it_can_scope_non_confirmes()
    {
        ProduitRecuVendeur::factory()->create(['status' => 'en_attente']);
        ProduitRecuVendeur::factory()->create(['status' => 'confirmé']);
        ProduitRecuVendeur::factory()->create(['status' => 'en_attente']);

        $nonConfirmes = ProduitRecuVendeur::nonConfirmes()->get();

        $this->assertCount(2, $nonConfirmes);
        $this->assertTrue($nonConfirmes->every(fn($item) => $item->status === 'en_attente'));
    }

    /** @test */
    public function it_can_scope_confirmes()
    {
        ProduitRecuVendeur::factory()->create(['status' => 'confirmé']);
        ProduitRecuVendeur::factory()->create(['status' => 'en_attente']);
        ProduitRecuVendeur::factory()->create(['status' => 'confirmé']);

        $confirmes = ProduitRecuVendeur::confirmes()->get();

        $this->assertCount(2, $confirmes);
        $this->assertTrue($confirmes->every(fn($item) => $item->status === 'confirmé'));
    }

    /** @test */
    public function it_can_scope_rejetes()
    {
        ProduitRecuVendeur::factory()->create(['status' => 'rejeté']);
        ProduitRecuVendeur::factory()->create(['status' => 'confirmé']);
        ProduitRecuVendeur::factory()->create(['status' => 'rejeté']);

        $rejetes = ProduitRecuVendeur::rejetes()->get();

        $this->assertCount(2, $rejetes);
        $this->assertTrue($rejetes->every(fn($item) => $item->status === 'rejeté'));
    }

    /** @test */
    public function it_can_update_produit_recu_vendeur()
    {
        $produitRecuVendeur = ProduitRecuVendeur::factory()->create();
        
        $produitRecuVendeur->update([
            'quantite_confirmee' => 80,
            'status' => 'confirmé',
            'remarques' => 'Mis à jour'
        ]);

        $this->assertDatabaseHas('produits_recu_vendeur', [
            'id' => $produitRecuVendeur->id,
            'quantite_confirmee' => 80,
            'status' => 'confirmé',
            'remarques' => 'Mis à jour'
        ]);
    }

    /** @test */
    public function it_can_delete_produit_recu_vendeur()
    {
        $produitRecuVendeur = ProduitRecuVendeur::factory()->create();
        $id = $produitRecuVendeur->id;

        $produitRecuVendeur->delete();

        $this->assertDatabaseMissing('produits_recu_vendeur', ['id' => $id]);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $produitRecuVendeur = new ProduitRecuVendeur();
        $this->assertEquals('produits_recu_vendeur', $produitRecuVendeur->getTable());
    }
}
