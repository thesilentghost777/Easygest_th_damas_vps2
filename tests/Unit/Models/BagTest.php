<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Bag;
use App\Models\BagAssignment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $bag = new Bag();
        $expectedFillable = ['name', 'price', 'stock_quantity', 'alert_threshold'];
        $this->assertEquals($expectedFillable, $bag->getFillable());
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $bagData = [
            'name' => 'Sac Premium',
            'price' => 15.99,
            'stock_quantity' => 100,
            'alert_threshold' => 10
        ];

        $bag = Bag::create($bagData);

        $this->assertInstanceOf(Bag::class, $bag);
        $this->assertDatabaseHas('bags', $bagData);
    }

    /** @test */
    public function it_has_many_assignments()
    {
        $bag = new Bag();
        $relation = $bag->assignments();
        
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(BagAssignment::class, $relation->getRelated());
    }

    /** @test */
    public function it_can_retrieve_assignments()
    {
        $bag = Bag::factory()->create();
        $assignment = BagAssignment::factory()->create(['bag_id' => $bag->id]);

        $this->assertTrue($bag->assignments->contains($assignment));
    }

    /** @test */
    public function is_low_stock_returns_true_when_stock_equals_threshold()
    {
        $bag = Bag::factory()->create([
            'stock_quantity' => 10,
            'alert_threshold' => 10
        ]);

        $this->assertTrue($bag->isLowStock());
    }

    /** @test */
    public function is_low_stock_returns_true_when_stock_below_threshold()
    {
        $bag = Bag::factory()->create([
            'stock_quantity' => 5,
            'alert_threshold' => 10
        ]);

        $this->assertTrue($bag->isLowStock());
    }

    /** @test */
    public function is_low_stock_returns_false_when_stock_above_threshold()
    {
        $bag = Bag::factory()->create([
            'stock_quantity' => 15,
            'alert_threshold' => 10
        ]);

        $this->assertFalse($bag->isLowStock());
    }

    /** @test */
    public function increase_stock_adds_quantity_and_saves()
    {
        $bag = Bag::factory()->create(['stock_quantity' => 50]);
        $initialStock = $bag->stock_quantity;

        $bag->increaseStock(20);

        $this->assertEquals($initialStock + 20, $bag->stock_quantity);
        $this->assertDatabaseHas('bags', [
            'id' => $bag->id,
            'stock_quantity' => $initialStock + 20
        ]);
    }

    /** @test */
    public function increase_stock_handles_large_quantities()
    {
        $bag = Bag::factory()->create(['stock_quantity' => 0]);

        $bag->increaseStock(1000);

        $this->assertEquals(1000, $bag->stock_quantity);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $bag = Bag::factory()->create(['name' => 'Old Name']);

        $bag->update(['name' => 'New Name', 'price' => 25.50]);

        $this->assertEquals('New Name', $bag->name);
        $this->assertEquals(25.50, $bag->price);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $bag = Bag::factory()->create();
        $bagId = $bag->id;

        $bag->delete();

        $this->assertDatabaseMissing('bags', ['id' => $bagId]);
    }
}
