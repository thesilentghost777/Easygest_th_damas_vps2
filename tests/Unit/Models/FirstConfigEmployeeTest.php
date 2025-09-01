<?php

namespace Tests\Unit\Models;

use App\Models\FirstConfigEmployee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FirstConfigEmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_first_config_employee()
    {
        $user = User::factory()->create();
        
        $config = FirstConfigEmployee::create([
            'user_id' => $user->id,
            'status' => true
        ]);

        $this->assertDatabaseHas('first_config_employee', [
            'user_id' => $user->id,
            'status' => true
        ]);
    }

    /** @test */
    public function it_can_read_a_first_config_employee()
    {
        $user = User::factory()->create();
        $config = FirstConfigEmployee::factory()->create([
            'user_id' => $user->id,
            'status' => false
        ]);

        $found = FirstConfigEmployee::find($config->id);
        
        $this->assertEquals($user->id, $found->user_id);
        $this->assertFalse($found->status);
    }

    /** @test */
    public function it_can_update_a_first_config_employee()
    {
        $config = FirstConfigEmployee::factory()->create(['status' => false]);

        $config->update(['status' => true]);

        $this->assertTrue($config->fresh()->status);
    }

    /** @test */
    public function it_can_delete_a_first_config_employee()
    {
        $config = FirstConfigEmployee::factory()->create();

        $config->delete();

        $this->assertDatabaseMissing('first_config_employee', ['id' => $config->id]);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $config = new FirstConfigEmployee();
        
        $this->assertEquals('first_config_employee', $config->getTable());
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $config = new FirstConfigEmployee();
        
        $this->assertEquals(['user_id', 'status'], $config->getFillable());
    }

    /** @test */
    public function it_casts_status_to_boolean()
    {
        $config = FirstConfigEmployee::factory()->create(['status' => 1]);

        $this->assertIsBool($config->status);
        $this->assertTrue($config->status);
    }

    /** @test */
    public function it_casts_timestamps_to_datetime()
    {
        $config = FirstConfigEmployee::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $config->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $config->updated_at);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $config = FirstConfigEmployee::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $config->user);
        $this->assertEquals($user->id, $config->user->id);
    }

  
    /** @test */
    public function it_can_create_with_boolean_false_status()
    {
        $user = User::factory()->create();
        
        $config = FirstConfigEmployee::create([
            'user_id' => $user->id,
            'status' => false
        ]);

        $this->assertFalse($config->status);
        $this->assertIsBool($config->status);
    }

    /** @test */
    public function it_can_find_by_user_id()
    {
        $user = User::factory()->create();
        $config = FirstConfigEmployee::factory()->create(['user_id' => $user->id]);

        $found = FirstConfigEmployee::where('user_id', $user->id)->first();

        $this->assertEquals($config->id, $found->id);
    }
}
