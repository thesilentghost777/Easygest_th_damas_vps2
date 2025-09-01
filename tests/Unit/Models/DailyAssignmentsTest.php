<?php

namespace Tests\Unit\Models;

use App\Models\Daily_assignments;
use App\Models\User;
use App\Models\Produit_fixes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyAssignmentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $assignment = new Daily_assignments();
        $expectedFillable = [
            'chef_production',
            'producteur',
            'produit',
            'expected_quantity',
            'assignment_date',
            'status'
        ];
        
        $this->assertEquals($expectedFillable, $assignment->getFillable());
    }

    /** @test */
    public function it_casts_assignment_date_to_date()
    {
        $assignment = new Daily_assignments();
        $casts = $assignment->getCasts();
        
        $this->assertEquals('date', $casts['assignment_date']);
    }

    /** @test */
    public function it_can_be_created()
    {
        $assignment = Daily_assignments::factory()->create();
        
        $this->assertInstanceOf(Daily_assignments::class, $assignment);
        $this->assertDatabaseHas('Daily_assignments', [
            'id' => $assignment->id
        ]);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $assignment = Daily_assignments::factory()->create();
        
        $assignment->update(['status' => 'completed']);
        
        $this->assertEquals('completed', $assignment->fresh()->status);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $assignment = Daily_assignments::factory()->create();
        $id = $assignment->id;
        
        $assignment->delete();
        
        $this->assertDatabaseMissing('Daily_assignments', ['id' => $id]);
    }

    /** @test */
    public function produit_fixe_returns_belongs_to_relationship()
    {
        $assignment = new Daily_assignments();
        $relation = $assignment->produitFixe();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('produit', $relation->getForeignKeyName());
        $this->assertEquals('code_produit', $relation->getOwnerKeyName());
    }

    /** @test */
    public function producteur_returns_belongs_to_relationship()
    {
        $assignment = new Daily_assignments();
        $relation = $assignment->producteur();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('producteur', $relation->getForeignKeyName());
    }

    /** @test */
    public function chef_production_returns_belongs_to_relationship()
    {
        $assignment = new Daily_assignments();
        $relation = $assignment->chefProduction();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('chef_production', $relation->getForeignKeyName());
    }
}
