<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\BagAssignment;
use App\Models\Bag;
use App\Models\User;
use App\Models\BagReception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BagAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $assignment = new BagAssignment();
        $expectedFillable = ['bag_id', 'user_id', 'quantity_assigned', 'notes'];
        $this->assertEquals($expectedFillable, $assignment->getFillable());
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $bag = Bag::factory()->create();
        $user = User::factory()->create();
        
        $assignmentData = [
            'bag_id' => $bag->id,
            'user_id' => $user->id,
            'quantity_assigned' => 50,
            'notes' => 'Test assignment'
        ];

        $assignment = BagAssignment::create($assignmentData);

        $this->assertInstanceOf(BagAssignment::class, $assignment);
        $this->assertDatabaseHas('bag_assignments', $assignmentData);
    }

    /** @test */
    public function it_belongs_to_a_bag()
    {
        $assignment = new BagAssignment();
        $relation = $assignment->bag();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Bag::class, $relation->getRelated());
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $assignment = new BagAssignment();
        $relation = $assignment->user();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    /** @test */
    public function it_has_many_receptions()
    {
        $assignment = new BagAssignment();
        $relation = $assignment->receptions();
        
        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(BagReception::class, $relation->getRelated());
    }

    /** @test */
    public function it_can_retrieve_related_bag()
    {
        $bag = Bag::factory()->create();
        $assignment = BagAssignment::factory()->create(['bag_id' => $bag->id]);

        $this->assertInstanceOf(Bag::class, $assignment->bag);
        $this->assertEquals($bag->id, $assignment->bag->id);
    }

    /** @test */
    public function it_can_retrieve_related_user()
    {
        $user = User::factory()->create();
        $assignment = BagAssignment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $assignment->user);
        $this->assertEquals($user->id, $assignment->user->id);
    }

    /** @test */
    public function total_received_attribute_returns_zero_when_no_receptions()
    {
        $assignment = BagAssignment::factory()->create();

        $this->assertEquals(0, $assignment->total_received);
    }

    /** @test */
    public function total_received_attribute_sums_all_receptions()
    {
        $assignment = BagAssignment::factory()->create();
        
        BagReception::factory()->create([
            'bag_assignment_id' => $assignment->id,
            'quantity_received' => 10
        ]);
        BagReception::factory()->create([
            'bag_assignment_id' => $assignment->id,
            'quantity_received' => 15
        ]);

        $this->assertEquals(25, $assignment->total_received);
    }

    /** @test */
    public function discrepancy_attribute_calculates_correctly_with_no_receptions()
    {
        $assignment = BagAssignment::factory()->create(['quantity_assigned' => 100]);

        $this->assertEquals(100, $assignment->discrepancy);
    }

    /** @test */
    public function discrepancy_attribute_calculates_correctly_with_receptions()
    {
        $assignment = BagAssignment::factory()->create(['quantity_assigned' => 100]);
        
        BagReception::factory()->create([
            'bag_assignment_id' => $assignment->id,
            'quantity_received' => 30
        ]);

        $this->assertEquals(70, $assignment->discrepancy);
    }

    /** @test */
    public function discrepancy_attribute_handles_over_reception()
    {
        $assignment = BagAssignment::factory()->create(['quantity_assigned' => 50]);
        
        BagReception::factory()->create([
            'bag_assignment_id' => $assignment->id,
            'quantity_received' => 60
        ]);

        $this->assertEquals(-10, $assignment->discrepancy);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $assignment = BagAssignment::factory()->create(['quantity_assigned' => 50]);

        $assignment->update(['quantity_assigned' => 75, 'notes' => 'Updated notes']);

        $this->assertEquals(75, $assignment->quantity_assigned);
        $this->assertEquals('Updated notes', $assignment->notes);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $assignment = BagAssignment::factory()->create();
        $assignmentId = $assignment->id;

        $assignment->delete();

        $this->assertDatabaseMissing('bag_assignments', ['id' => $assignmentId]);
    }
}
