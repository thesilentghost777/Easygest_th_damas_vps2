<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\BagReception;
use App\Models\BagAssignment;
use App\Models\BagSale;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BagReceptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $reception = new BagReception();
        $expectedFillable = ['bag_assignment_id', 'quantity_received', 'notes'];
        $this->assertEquals($expectedFillable, $reception->getFillable());
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $assignment = BagAssignment::factory()->create();
        
        $receptionData = [
            'bag_assignment_id' => $assignment->id,
            'quantity_received' => 25,
            'notes' => 'Test reception'
        ];

        $reception = BagReception::create($receptionData);

        $this->assertInstanceOf(BagReception::class, $reception);
        $this->assertDatabaseHas('bag_receptions', $receptionData);
    }

    /** @test */
    public function it_belongs_to_an_assignment()
    {
        $reception = new BagReception();
        $relation = $reception->assignment();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('bag_assignment_id', $relation->getForeignKeyName());
        $this->assertInstanceOf(BagAssignment::class, $relation->getRelated());
    }

    /** @test */
    public function it_has_one_sale()
    {
        $reception = new BagReception();
        $relation = $reception->sale();
        
        $this->assertInstanceOf(HasOne::class, $relation);
        $this->assertEquals('bag_reception_id', $relation->getForeignKeyName());
        $this->assertInstanceOf(BagSale::class, $relation->getRelated());
    }

    /** @test */
    public function it_can_retrieve_related_assignment()
    {
        $assignment = BagAssignment::factory()->create();
        $reception = BagReception::factory()->create(['bag_assignment_id' => $assignment->id]);

        $this->assertInstanceOf(BagAssignment::class, $reception->assignment);
        $this->assertEquals($assignment->id, $reception->assignment->id);
    }

    /** @test */
    public function it_can_retrieve_related_sale()
    {
        $reception = BagReception::factory()->create();
        $sale = BagSale::factory()->create(['bag_reception_id' => $reception->id]);

        $this->assertInstanceOf(BagSale::class, $reception->sale);
        $this->assertEquals($sale->id, $reception->sale->id);
    }

    /** @test */
    public function has_sale_attribute_returns_false_when_no_sale()
    {
        $reception = BagReception::factory()->create();

        $this->assertFalse($reception->has_sale);
    }

    /** @test */
    public function has_sale_attribute_returns_true_when_sale_exists()
    {
        $reception = BagReception::factory()->create();
        BagSale::factory()->create(['bag_reception_id' => $reception->id]);

        $this->assertTrue($reception->has_sale);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $reception = BagReception::factory()->create(['quantity_received' => 10]);

        $reception->update(['quantity_received' => 20, 'notes' => 'Updated notes']);

        $this->assertEquals(20, $reception->quantity_received);
        $this->assertEquals('Updated notes', $reception->notes);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $reception = BagReception::factory()->create();
        $receptionId = $reception->id;

        $reception->delete();

        $this->assertDatabaseMissing('bag_receptions', ['id' => $receptionId]);
    }
}
