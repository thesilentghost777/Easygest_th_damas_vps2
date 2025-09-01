<?php

namespace Tests\Unit\Models;

use App\Models\Complexe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplexeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uses_correct_table_name()
    {
        $complexe = new Complexe();
        $this->assertEquals('Complexe', $complexe->getTable());
    }

    /** @test */
    public function it_uses_custom_primary_key()
    {
        $complexe = new Complexe();
        $this->assertEquals('id_comp', $complexe->getKeyName());
    }

    /** @test */
    public function it_has_non_incrementing_primary_key()
    {
        $complexe = new Complexe();
        $this->assertFalse($complexe->getIncrementing());
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $complexe = new Complexe();
        $expectedFillable = [
            'nom',
            'localisation',
            'revenu_mensuel',
            'revenu_annuel',
            'solde',
            'caisse_sociale'
        ];
        
        $this->assertEquals($expectedFillable, $complexe->getFillable());
    }

    /** @test */
    public function it_can_be_created_using_factory()
    {
        $complexe = Complexe::factory()->create();
        
        $this->assertInstanceOf(Complexe::class, $complexe);
        $this->assertDatabaseHas('Complexe', [
            'id_comp' => $complexe->id_comp
        ]);
    }

    /** @test */
    public function it_can_be_made_using_factory()
    {
        $complexe = Complexe::factory()->make();
        
        $this->assertInstanceOf(Complexe::class, $complexe);
        $this->assertNotNull($complexe->nom);
        $this->assertNotNull($complexe->localisation);
    }

    /** @test */
    public function it_can_be_filled_with_valid_data()
    {
        $data = [
            'nom' => 'Complexe Test',
            'localisation' => 'Yaoundé, Cameroun',
            'revenu_mensuel' => 500000,
            'revenu_annuel' => 6000000,
            'solde' => 150000,
            'caisse_sociale' => 50000
        ];

        $complexe = new Complexe($data);

        $this->assertEquals('Complexe Test', $complexe->nom);
        $this->assertEquals('Yaoundé, Cameroun', $complexe->localisation);
        $this->assertEquals(500000, $complexe->revenu_mensuel);
        $this->assertEquals(6000000, $complexe->revenu_annuel);
        $this->assertEquals(150000, $complexe->solde);
        $this->assertEquals(50000, $complexe->caisse_sociale);
    }

    /** @test */
    public function it_can_create_multiple_complexes_with_factory()
    {
        $complexes = Complexe::factory()->count(3)->create();
        
        $this->assertCount(3, $complexes);
        $this->assertEquals(3, Complexe::count());
        
        foreach ($complexes as $complexe) {
            $this->assertInstanceOf(Complexe::class, $complexe);
            $this->assertNotNull($complexe->id_comp);
        }
    }

    /** @test */
    public function it_can_override_factory_attributes()
    {
        $complexe = Complexe::factory()->create([
            'nom' => 'Complexe Spécifique',
            'solde' => 1000000
        ]);
        
        $this->assertEquals('Complexe Spécifique', $complexe->nom);
        $this->assertEquals(1000000, $complexe->solde);
    }

    /** @test */
    public function it_has_timestamps()
    {
        $complexe = new Complexe();
        $this->assertTrue($complexe->usesTimestamps());
    }

    /** @test */
    public function factory_creates_valid_financial_data()
    {
        $complexe = Complexe::factory()->create();
        
        // Vérifier que les données financières sont cohérentes
        $this->assertIsNumeric($complexe->revenu_mensuel);
        $this->assertIsNumeric($complexe->revenu_annuel);
        $this->assertIsNumeric($complexe->solde);
        $this->assertIsNumeric($complexe->caisse_sociale);
        
        // Vérifier que les valeurs sont positives
        $this->assertGreaterThanOrEqual(0, $complexe->revenu_mensuel);
        $this->assertGreaterThanOrEqual(0, $complexe->revenu_annuel);
        $this->assertGreaterThanOrEqual(0, $complexe->solde);
        $this->assertGreaterThanOrEqual(0, $complexe->caisse_sociale);
    }

    /** @test */
    public function factory_creates_unique_primary_keys()
    {
        $complexe1 = Complexe::factory()->create();
        $complexe2 = Complexe::factory()->create();
        
        $this->assertNotEquals($complexe1->id_comp, $complexe2->id_comp);
    }
}
