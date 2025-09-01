<?php

namespace Tests\Unit\Models;

use App\Models\DeliUser;
use App\Models\Deli;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeliUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_deli_user()
    {
        $user = User::factory()->create();
        $deli = Deli::factory()->create();
        
        $data = [
            'deli_id' => $deli->id,
            'user_id' => $user->id,
            'date_incident' => now()
        ];

        $deliUser = DeliUser::create($data);

        $this->assertEquals($deli->id, $deliUser->deli_id);
        $this->assertEquals($user->id, $deliUser->user_id);
    }

    /** @test */
    public function it_can_read_deli_user()
    {
        $deliUser = DeliUser::factory()->create();
        
        $found = DeliUser::find($deliUser->id);
        
        $this->assertEquals($deliUser->id, $found->id);
    }

    /** @test */
  

    /** @test */
    public function it_can_delete_deli_user()
    {
        $deliUser = DeliUser::factory()->create();
        $id = $deliUser->id;
        
        $deliUser->delete();
        
        $this->assertDatabaseMissing('deli_user', ['id' => $id]);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $deliUser = DeliUser::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(User::class, $deliUser->user);
        $this->assertEquals($user->id, $deliUser->user->id);
    }

    /** @test */
    public function it_belongs_to_deli()
    {
        $deli = Deli::factory()->create();
        $deliUser = DeliUser::factory()->create(['deli_id' => $deli->id]);
        
        $this->assertInstanceOf(Deli::class, $deliUser->deli);
        $this->assertEquals($deli->id, $deliUser->deli->id);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = ['deli_id', 'user_id', 'date_incident'];
        
        $this->assertEquals($fillable, (new DeliUser())->getFillable());
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $deliUser = new DeliUser();
        
        $this->assertEquals('deli_user', $deliUser->getTable());
    }
}
