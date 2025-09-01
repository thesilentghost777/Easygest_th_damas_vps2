<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Planning;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanningTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function it_can_update_a_planning()
    {
        $planning = Planning::factory()->create(['libelle' => 'Initial']);

        $planning->update(['libelle' => 'Updated']);

        $this->assertEquals('Updated', $planning->fresh()->libelle);
    }

    /** @test */
    public function it_can_delete_a_planning()
    {
        $planning = Planning::factory()->create();

        $planning->delete();

        $this->assertModelMissing($planning);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $planning = Planning::factory()->create(['employe' => $user->id]);

        $this->assertInstanceOf(User::class, $planning->user);
        $this->assertEquals($user->id, $planning->user->id);
    }
}
