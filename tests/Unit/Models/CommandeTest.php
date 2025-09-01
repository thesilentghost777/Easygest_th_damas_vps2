<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Commande;
use App\Models\Produit_fixes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommandeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $commande = new Commande([
            'libelle' => 'Commande Test',
            'produit' => 1,
            'quantite' => 10,
            'date_commande' => '2024-06-01 10:00:00',
            'categorie' => 'Catégorie A',
            'valider' => true
        ]);

        $this->assertEquals('Commande Test', $commande->libelle);
        $this->assertEquals(1, $commande->produit);
        $this->assertEquals(10, $commande->quantite);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $commande->date_commande);
        $this->assertEquals('Catégorie A', $commande->categorie);
        $this->assertTrue($commande->valider);
    }


    /** @test */
    public function it_belongs_to_produit_fixe()
    {
        $produit = Produit_fixes::factory()->create(['code_produit' => 1]);
        $commande = Commande::factory()->create(['produit' => 1]);

        $this->assertInstanceOf(Produit_fixes::class, $commande->produit_fixe);
        $this->assertEquals($produit->code_produit, $commande->produit_fixe->code_produit);
    }
}
