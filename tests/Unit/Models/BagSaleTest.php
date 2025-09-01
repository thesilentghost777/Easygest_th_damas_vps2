<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\BagSale;
use App\Models\BagReception;
use App\Models\BagAssignment;
use App\Models\Bag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BagSaleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $sale = new BagSale();
        $expectedFillable = [
            'bag_reception_id',
            'quantity_sold',
            'quantity_unsold',
            'notes',
            'is_recovered'
        ];
        $this->assertEquals($expectedFillable, $sale->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $sale = new BagSale();
        $expectedCasts = ['is_recovered' => 'boolean'];
        
        $this->assertEquals('boolean', $sale->getCasts()['is_recovered']);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $reception = BagReception::factory()->create();
        
        $saleData = [
            'bag_reception_id' => $reception->id,
            'quantity_sold' => 15,
            'quantity_unsold' => 5,
            'notes' => 'Test sale',
            'is_recovered' => false
        ];

        $sale = BagSale::create($saleData);

        $this->assertInstanceOf(BagSale::class, $sale);
        $this->assertDatabaseHas('bag_sales', $saleData);
    }

    /** @test */
    public function it_belongs_to_a_reception()
    {
        $sale = new BagSale();
        $relation = $sale->reception();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('bag_reception_id', $relation->getForeignKeyName());
        $this->assertInstanceOf(BagReception::class, $relation->getRelated());
    }

    /** @test */
    public function it_can_retrieve_related_reception()
    {
        $reception = BagReception::factory()->create();
        $sale = BagSale::factory()->create(['bag_reception_id' => $reception->id]);

        $this->assertInstanceOf(BagReception::class, $sale->reception);
        $this->assertEquals($reception->id, $sale->reception->id);
    }

    /** @test */
    public function is_recovered_attribute_is_cast_to_boolean()
    {
        $sale = BagSale::factory()->create(['is_recovered' => 1]);
        $this->assertTrue($sale->is_recovered);
        $this->assertIsBool($sale->is_recovered);

        $sale = BagSale::factory()->create(['is_recovered' => 0]);
        $this->assertFalse($sale->is_recovered);
        $this->assertIsBool($sale->is_recovered);
    }

    /** @test */
    public function bag_attribute_returns_bag_through_relations()
    {
        $bag = Bag::factory()->create();
        $assignment = BagAssignment::factory()->create(['bag_id' => $bag->id]);
        $reception = BagReception::factory()->create(['bag_assignment_id' => $assignment->id]);
        $sale = BagSale::factory()->create(['bag_reception_id' => $reception->id]);

        $this->assertInstanceOf(Bag::class, $sale->bag);
        $this->assertEquals($bag->id, $sale->bag->id);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $sale = BagSale::factory()->create([
            'quantity_sold' => 10,
            'is_recovered' => false
        ]);

        $sale->update([
            'quantity_sold' => 15,
            'is_recovered' => true,
            'notes' => 'Updated notes'
        ]);

        $this->assertEquals(15, $sale->quantity_sold);
        $this->assertTrue($sale->is_recovered);
        $this->assertEquals('Updated notes', $sale->notes);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $sale = BagSale::factory()->create();
        $saleId = $sale->id;

        $sale->delete();

        $this->assertDatabaseMissing('bag_sales', ['id' => $saleId]);
    }
}
