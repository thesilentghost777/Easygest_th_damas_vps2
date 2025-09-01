<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produit;
use App\Models\MouvementStock;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MouvementStockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_mouvement_stock()
    {
        $user = User::factory()->create();
        $produit = Produit::factory()->create();

        $mouvement = MouvementStock::create([
            'produit_id' => $produit->id,
            'type' => 'entrée',
            'quantite' => 10,
            'user_id' => $user->id,
            'motif' => 'Réapprovisionnement',
        ]);

        $this->assertDatabaseHas('mouvements_stock', [
            'id' => $mouvement->id,
            'type' => 'entrée',
            'quantite' => 10,
        ]);
    }

    /** @test */
    public function it_can_update_mouvement_stock()
    {
        $mouvement = MouvementStock::factory()->create();

        $mouvement->update(['quantite' => 20]);

        $this->assertDatabaseHas('mouvements_stock', [
            'id' => $mouvement->id,
            'quantite' => 20,
        ]);
    }

    /** @test */
    public function it_can_delete_mouvement_stock()
    {
        $mouvement = MouvementStock::factory()->create();

        $mouvement->delete();

        $this->assertDatabaseMissing('mouvements_stock', [
            'id' => $mouvement->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_produit()
    {
        $mouvement = MouvementStock::factory()->create();
        $this->assertInstanceOf(Produit::class, $mouvement->produit);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $mouvement = MouvementStock::factory()->create();
        $this->assertInstanceOf(User::class, $mouvement->user);
    }
}
