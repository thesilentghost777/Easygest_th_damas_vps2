<?php

namespace Tests\Unit\Models;

use App\Models\EmployeeRation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeRationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_employee_ration()
    {
        $user = User::factory()->create();
        
        $data = [
            'employee_id' => $user->id,
            'montant' => 5000.00,
            'personnalise' => 1
        ];

        $ration = EmployeeRation::create($data);

        $this->assertDatabaseHas('employee_rations', $data);
        $this->assertEquals($user->id, $ration->employee_id);
        $this->assertEquals(5000.00, $ration->montant);
    }

    /** @test */
    public function it_can_read_employee_ration()
    {
        $ration = EmployeeRation::factory()->create();
        
        $found = EmployeeRation::find($ration->id);
        
        $this->assertEquals($ration->id, $found->id);
    }

    /** @test */
    public function it_can_update_employee_ration()
    {
        $ration = EmployeeRation::factory()->create();
        
        $ration->update(['montant' => 7500, 'personnalise' => 0]);
        
        $this->assertEquals(7500, $ration->fresh()->montant);
        $this->assertEquals(0, $ration->fresh()->personnalise);
    }

    /** @test */
    public function it_can_delete_employee_ration()
    {
        $ration = EmployeeRation::factory()->create();
        $id = $ration->id;
        
        $ration->delete();
        
        $this->assertDatabaseMissing('employee_rations', ['id' => $id]);
    }

    /** @test */
    public function it_belongs_to_employee()
    {
        $user = User::factory()->create();
        $ration = EmployeeRation::factory()->create(['employee_id' => $user->id]);
        
        $this->assertInstanceOf(User::class, $ration->employee);
        $this->assertEquals($user->id, $ration->employee->id);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = ['employee_id', 'montant', 'personnalise'];
        
        $this->assertEquals($fillable, (new EmployeeRation())->getFillable());
    }
}
