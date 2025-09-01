<?php

namespace Tests\Unit\Models;

use App\Models\ManquantTemporaire;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManquantTemporaireTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_manquant_temporaire()
    {
        $employe = User::factory()->create();
        $validateur = User::factory()->create();
        
        $manquant = ManquantTemporaire::create([
            'employe_id' => $employe->id,
            'montant' => 50000,
            'explication' => 'Avance sur salaire',
            'statut' => 'en_attente',
            'commentaire_dg' => 'En cours de validation',
            'valide_par' => $validateur->id
        ]);

        $this->assertDatabaseHas('manquant_temporaire', [
            'employe_id' => $employe->id,
            'montant' => 50000,
            'explication' => 'Avance sur salaire',
            'statut' => 'en_attente'
        ]);
    }

    /** @test */
    public function it_can_read_a_manquant_temporaire()
    {
        $employe = User::factory()->create();
        $manquant = ManquantTemporaire::factory()->create([
            'employe_id' => $employe->id,
            'montant' => 75000,
            'statut' => 'valide'
        ]);

        $found = ManquantTemporaire::find($manquant->id);
        
        $this->assertEquals($employe->id, $found->employe_id);
        $this->assertEquals(75000, $found->montant);
        $this->assertEquals('valide', $found->statut);
    }

    /** @test */
    public function it_can_update_a_manquant_temporaire()
    {
        $manquant = ManquantTemporaire::factory()->create([
            'statut' => 'en_attente',
            'commentaire_dg' => 'En cours'
        ]);

        $manquant->update([
            'statut' => 'valide',
            'commentaire_dg' => 'Approuvé par la direction'
        ]);

        $fresh = $manquant->fresh();
        $this->assertEquals('valide', $fresh->statut);
        $this->assertEquals('Approuvé par la direction', $fresh->commentaire_dg);
    }

    /** @test */
    public function it_can_delete_a_manquant_temporaire()
    {
        $manquant = ManquantTemporaire::factory()->create();

        $manquant->delete();

        $this->assertDatabaseMissing('manquant_temporaire', ['id' => $manquant->id]);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $manquant = new ManquantTemporaire();
        
        $this->assertEquals('manquant_temporaire', $manquant->getTable());
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $manquant = new ManquantTemporaire();
        
        $expected = [
            'employe_id',
            'montant',
            'explication',
            'statut',
            'commentaire_dg',
            'valide_par'
        ];
        
        $this->assertEquals($expected, $manquant->getFillable());
    }

    /** @test */
    public function it_casts_montant_to_integer()
    {
        $manquant = ManquantTemporaire::factory()->create(['montant' => '50000']);

        $this->assertIsInt($manquant->montant);
        $this->assertEquals(50000, $manquant->montant);
    }

    /** @test */
    public function it_belongs_to_employe()
    {
        $employe = User::factory()->create();
        $manquant = ManquantTemporaire::factory()->create(['employe_id' => $employe->id]);

        $this->assertInstanceOf(User::class, $manquant->employe);
        $this->assertEquals($employe->id, $manquant->employe->id);
        $this->assertEquals($employe->name, $manquant->employe->name);
    }

    /** @test */
    public function it_belongs_to_validateur()
    {
        $validateur = User::factory()->create();
        $manquant = ManquantTemporaire::factory()->create(['valide_par' => $validateur->id]);

        $this->assertInstanceOf(User::class, $manquant->validateur);
        $this->assertEquals($validateur->id, $manquant->validateur->id);
    }

    /** @test */
    public function it_has_valide_par_relationship()
    {
        $validateur = User::factory()->create();
        $manquant = ManquantTemporaire::factory()->create(['valide_par' => $validateur->id]);

        $this->assertInstanceOf(User::class, $manquant->validePar);
        $this->assertEquals($validateur->id, $manquant->validePar->id);

    }

  
    /** @test */
    public function it_can_create_without_validateur()
    {
        $employe = User::factory()->create();
        
        $manquant = ManquantTemporaire::create([
            'employe_id' => $employe->id,
            'montant' => 25000,
            'explication' => 'Demande en attente',
            'statut' => 'en_attente',
            'valide_par' => null
        ]);

        $this->assertNull($manquant->valide_par);
        $this->assertNull($manquant->validateur);
    }

  

    /** @test */
    public function it_can_filter_by_employe()
    {
        $employe1 = User::factory()->create();
        $employe2 = User::factory()->create();
        
        ManquantTemporaire::factory()->create(['employe_id' => $employe1->id]);
        ManquantTemporaire::factory()->create(['employe_id' => $employe1->id]);
        ManquantTemporaire::factory()->create(['employe_id' => $employe2->id]);

        $employe1Manquants = ManquantTemporaire::where('employe_id', $employe1->id)->get();
        
        $this->assertCount(2, $employe1Manquants);
    }

    /** @test */
    public function it_can_filter_by_montant_range()
    {
        ManquantTemporaire::factory()->create(['montant' => 10000]);
        ManquantTemporaire::factory()->create(['montant' => 50000]);
        ManquantTemporaire::factory()->create(['montant' => 100000]);

        $petitsMontants = ManquantTemporaire::where('montant', '<=', 50000)->get();
        $grandsMontants = ManquantTemporaire::where('montant', '>', 50000)->get();

        $this->assertCount(2, $petitsMontants);
        $this->assertCount(1, $grandsMontants);
    }

    /** @test */
    public function it_can_search_by_explication()
    {
        ManquantTemporaire::factory()->create(['explication' => 'Avance sur salaire']);
        ManquantTemporaire::factory()->create(['explication' => 'Frais médicaux urgents']);
        ManquantTemporaire::factory()->create(['explication' => 'Avance pour formation']);

        $avanceResults = ManquantTemporaire::where('explication', 'LIKE', '%avance%')->get();
        
        $this->assertCount(2, $avanceResults);
    }

    /** @test */
    public function it_can_get_pending_requests()
    {
        ManquantTemporaire::factory()->create(['statut' => 'en_attente']);
        ManquantTemporaire::factory()->create(['statut' => 'valide']);
        ManquantTemporaire::factory()->create(['statut' => 'en_attente']);

        $pending = ManquantTemporaire::where('statut', 'en_attente')->get();
        
        $this->assertCount(2, $pending);
    }

    /** @test */
    public function it_can_order_by_montant()
    {
        ManquantTemporaire::factory()->create(['montant' => 75000]);
        ManquantTemporaire::factory()->create(['montant' => 25000]);
        ManquantTemporaire::factory()->create(['montant' => 50000]);

        $orderedAsc = ManquantTemporaire::orderBy('montant')->get();
        $orderedDesc = ManquantTemporaire::orderByDesc('montant')->get();

        $this->assertEquals(25000, $orderedAsc->first()->montant);
        $this->assertEquals(75000, $orderedDesc->first()->montant);
    }

 
}
