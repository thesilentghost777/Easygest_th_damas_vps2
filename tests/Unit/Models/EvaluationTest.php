<?php

namespace Tests\Unit\Models;

use App\Models\Evaluation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_evaluation()
    {
        $user = User::factory()->create();
        
        $data = [
            'user_id' => $user->id,
            'note' => 15.75,
            'appreciation' => 'Très bon travail'
        ];

        $evaluation = Evaluation::create($data);

        $this->assertDatabaseHas('evaluations', $data);
        $this->assertEquals($user->id, $evaluation->user_id);
        $this->assertEquals('15.75', $evaluation->note);
    }

    /** @test */
    public function it_can_read_evaluation()
    {
        $evaluation = Evaluation::factory()->create();
        
        $found = Evaluation::find($evaluation->id);
        
        $this->assertEquals($evaluation->id, $found->id);
    }

    /** @test */
    public function it_can_update_evaluation()
    {
        $evaluation = Evaluation::factory()->create();
        
        $evaluation->update(['note' => 18.50, 'appreciation' => 'Excellent']);
        
        $this->assertEquals('18.50', $evaluation->fresh()->note);
        $this->assertEquals('Excellent', $evaluation->fresh()->appreciation);
    }

    /** @test */
    public function it_can_delete_evaluation()
    {
        $evaluation = Evaluation::factory()->create();
        $id = $evaluation->id;
        
        $evaluation->delete();
        
        $this->assertDatabaseMissing('evaluations', ['id' => $id]);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $evaluation = Evaluation::factory()->create(['user_id' => $user->id]);
        
        $this->assertInstanceOf(User::class, $evaluation->user);
        $this->assertEquals($user->id, $evaluation->user->id);
    }

    /** @test */
    public function it_casts_note_to_decimal()
    {
        $evaluation = Evaluation::factory()->create(['note' => '16.50']);
        
        $this->assertEquals('16.50', $evaluation->note);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = ['user_id', 'note', 'appreciation'];
        
        $this->assertEquals($fillable, (new Evaluation())->getFillable());
    }
}
