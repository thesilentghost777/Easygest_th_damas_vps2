<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Matiere;
use App\Enums\UniteMinimale;
use App\Services\MatiereService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatiereTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_matiere()
    {
        $matiere = Matiere::factory()->create([
            'nom' => 'Test Matiere',
            'unite_minimale' => UniteMinimale::GRAMME,
            'unite_classique' => 'kg',
            'quantite_par_unite' => 1000,
            'quantite' => 50,
            'prix_unitaire' => 100,
            'quantite_seuil' => 10,
            'notification_active' => true
        ]);

        $this->assertDatabaseHas('Matiere', [
            'nom' => 'Test Matiere',
            'quantite' => 50
        ]);
    }

    /** @test */
    public function it_can_read_a_matiere()
    {
        $matiere = Matiere::factory()->create(['nom' => 'Test Read']);
        
        $found = Matiere::find($matiere->id);
        
        $this->assertEquals('Test Read', $found->nom);
    }

    /** @test */
    public function it_can_update_a_matiere()
    {
        $matiere = Matiere::factory()->create(['nom' => 'Original']);
        
        $matiere->update(['nom' => 'Updated']);
        
        $this->assertEquals('Updated', $matiere->fresh()->nom);
    }

    /** @test */
    public function it_can_delete_a_matiere()
    {
        $matiere = Matiere::factory()->create();
        
        $matiere->delete();
        
        $this->assertDatabaseMissing('Matiere', ['id' => $matiere->id]);
    }

    /** @test */
    public function it_calculates_prix_par_unite_minimale_on_create()
    {
        $matiere = Matiere::factory()->create([
            'prix_unitaire' => 100,
            'quantite_par_unite' => 1000,
            'unite_classique' => 'kg',
            'unite_minimale' => UniteMinimale::GRAMME
        ]);

        $this->assertNotNull($matiere->prix_par_unite_minimale);
    }

    /** @test */
    public function it_calculates_prix_par_unite_minimale_on_update()
    {
        $matiere = Matiere::factory()->create([
            'prix_unitaire' => 100,
            'quantite_par_unite' => 1000
        ]);

        $matiere->update(['prix_unitaire' => 200]);

        $this->assertNotNull($matiere->prix_par_unite_minimale);
    }

    /** @test */
    public function it_detects_when_under_threshold()
    {
        $matiere = Matiere::factory()->create([
            'quantite' => 5,
            'quantite_seuil' => 10
        ]);

        $this->assertTrue($matiere->isUnderThreshold());
    }

    /** @test */
    public function it_detects_when_not_under_threshold()
    {
        $matiere = Matiere::factory()->create([
            'quantite' => 15,
            'quantite_seuil' => 10
        ]);

        $this->assertFalse($matiere->isUnderThreshold());
    }

    /** @test */
    public function it_should_notify_when_active_and_under_threshold()
    {
        $matiere = Matiere::factory()->create([
            'notification_active' => true,
            'quantite' => 5,
            'quantite_seuil' => 10
        ]);

        $this->assertTrue($matiere->shouldNotify());
    }

    /** @test */
    public function it_should_not_notify_when_inactive()
    {
        $matiere = Matiere::factory()->create([
            'notification_active' => false,
            'quantite' => 5,
            'quantite_seuil' => 10
        ]);

        $this->assertFalse($matiere->shouldNotify());
    }

    /** @test */
    public function it_should_not_notify_when_above_threshold()
    {
        $matiere = Matiere::factory()->create([
            'notification_active' => true,
            'quantite' => 15,
            'quantite_seuil' => 10
        ]);

        $this->assertFalse($matiere->shouldNotify());
    }

    /** @test */
    public function it_casts_unite_minimale_to_enum()
    {
        $matiere = Matiere::factory()->create([
            'unite_minimale' => UniteMinimale::GRAMME
        ]);

        $this->assertInstanceOf(UniteMinimale::class, $matiere->unite_minimale);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'nom',
            'unite_minimale',
            'unite_classique',
            'quantite_par_unite',
            'quantite',
            'prix_unitaire',
            'prix_par_unite_minimale',
            'quantite_seuil',
            'notification_active'
        ];

        $matiere = new Matiere();
        
        $this->assertEquals($fillable, $matiere->getFillable());
    }
}
