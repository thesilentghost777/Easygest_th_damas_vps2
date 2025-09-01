<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\FactureComplexe;
use App\Models\FactureComplexeDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class FactureComplexeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_facture_complexe()
    {
        $user = User::factory()->create();
        
        $facture = FactureComplexe::create([
            'reference' => 'FC-20240101-001',
            'producteur_id' => $user->id,
            'id_lot' => 'LOT001',
            'montant_total' => 1500.50,
            'statut' => 'en_attente',
            'date_creation' => '2024-01-01',
            'notes' => 'Test facture'
        ]);

        $this->assertInstanceOf(FactureComplexe::class, $facture);
        $this->assertEquals('FC-20240101-001', $facture->reference);
        $this->assertDatabaseHas('factures_complexe', ['reference' => 'FC-20240101-001']);
    }

    /** @test */
    public function it_can_read_facture_complexe()
    {
        $facture = FactureComplexe::factory()->create(['reference' => 'FC-TEST-001']);
        
        $found = FactureComplexe::find($facture->id);
        
        $this->assertEquals($facture->id, $found->id);
        $this->assertEquals('FC-TEST-001', $found->reference);
    }

    /** @test */
    public function it_can_update_facture_complexe()
    {
        $facture = FactureComplexe::factory()->create();
        
        $facture->update(['statut' => 'validee']);
        
        $this->assertEquals('validee', $facture->fresh()->statut);
    }

    /** @test */
    public function it_can_delete_facture_complexe()
    {
        $facture = FactureComplexe::factory()->create();
        $id = $facture->id;
        
        $facture->delete();
        
        $this->assertDatabaseMissing('factures_complexe', ['id' => $id]);
    }

    /** @test */
    public function it_belongs_to_producteur()
    {
        $user = User::factory()->create();
        $facture = FactureComplexe::factory()->create(['producteur_id' => $user->id]);

        $this->assertInstanceOf(User::class, $facture->producteur);
        $this->assertEquals($user->id, $facture->producteur->id);
    }

    /** @test */
    public function it_has_many_details()
    {
        $facture = FactureComplexe::factory()->create();
        FactureComplexeDetail::factory(3)->create(['facture_id' => $facture->id]);

        $this->assertCount(3, $facture->details);
        $this->assertInstanceOf(FactureComplexeDetail::class, $facture->details->first());
    }

    /** @test */
    public function it_generates_unique_reference()
    {
        $reference1 = FactureComplexe::genererReference();
        $reference2 = FactureComplexe::genererReference();

        $this->assertStringStartsWith('FC-', $reference1);
        $this->assertStringStartsWith('FC-', $reference2);
        $this->assertStringContains(date('Ymd'), $reference1);
        $this->assertNotEquals($reference1, $reference2);
    }

    /** @test */
    public function it_generates_sequential_references()
    {
        // Créer une facture avec référence du jour
        $dateCode = date('Ymd');
        FactureComplexe::factory()->create([
            'reference' => "FC-{$dateCode}-001"
        ]);

        $newReference = FactureComplexe::genererReference();
        
        $this->assertEquals("FC-{$dateCode}-002", $newReference);
    }

    /** @test */
    public function it_handles_first_reference_of_day()
    {
        $reference = FactureComplexe::genererReference();
        $dateCode = date('Ymd');
        
        $this->assertEquals("FC-{$dateCode}-001", $reference);
    }

    /** @test */
    public function it_casts_dates_correctly()
    {
        $facture = FactureComplexe::factory()->create([
            'date_creation' => '2024-01-15',
            'date_validation' => '2024-01-20'
        ]);

        $this->assertInstanceOf(Carbon::class, $facture->date_creation);
        $this->assertInstanceOf(Carbon::class, $facture->date_validation);
    }

    /** @test */
    public function it_casts_montant_total_as_decimal()
    {
        $facture = FactureComplexe::factory()->create(['montant_total' => 1234.56]);

        $this->assertEquals('1234.56', $facture->montant_total);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $facture = new FactureComplexe();
        $fillable = $facture->getFillable();

        $expected = [
            'reference',
            'producteur_id',
            'id_lot',
            'montant_total',
            'statut',
            'date_creation',
            'date_validation',
            'notes'
        ];

        $this->assertEquals($expected, $fillable);
    }

    /** @test */
    public function it_has_correct_table_name()
    {
        $facture = new FactureComplexe();
        
        $this->assertEquals('factures_complexe', $facture->getTable());
    }

    /** @test */
    public function it_has_correct_casts()
    {
        $facture = new FactureComplexe();
        $casts = $facture->getCasts();

        $this->assertEquals('date', $casts['date_creation']);
        $this->assertEquals('date', $casts['date_validation']);
        $this->assertEquals('decimal:2', $casts['montant_total']);
    }
}
