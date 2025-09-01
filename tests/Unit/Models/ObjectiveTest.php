<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Objective;
use App\Models\User;
use App\Models\Category;
use App\Models\ObjectiveProgress;
use App\Models\SubObjective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ObjectiveTest extends TestCase
{
    use RefreshDatabase;

    private Objective $objective;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $this->objective = Objective::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Test Objective',
            'description' => 'Test Description',
            'target_amount' => 100000.50,
            'period_type' => 'monthly',
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->endOfMonth(),
            'sector' => 'alimentation',
            'goal_type' => 'revenue',
            'is_active' => true,
            'is_achieved' => false,
            'is_confirmed' => false,
            'use_standard_sources' => true,
            'expense_categories' => [1, 2, 3],
            'custom_users' => [1, 2],
            'custom_categories' => [4, 5]
        ]);
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(Objective::class, $this->objective);
        $this->assertDatabaseHas('objectives', [
            'id' => $this->objective->id,
            'title' => 'Test Objective'
        ]);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = [
            'user_id', 'title', 'description', 'target_amount', 'period_type',
            'start_date', 'end_date', 'sector', 'goal_type', 'expense_categories',
            'use_standard_sources', 'custom_users', 'custom_categories',
            'is_active', 'is_achieved', 'is_confirmed'
        ];
        
        $this->assertEquals($fillable, $this->objective->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $this->assertInstanceOf(Carbon::class, $this->objective->start_date);
        $this->assertInstanceOf(Carbon::class, $this->objective->end_date);
        $this->assertIsDecimal($this->objective->target_amount);
        $this->assertIsBool($this->objective->is_active);
        $this->assertIsBool($this->objective->is_achieved);
        $this->assertIsBool($this->objective->is_confirmed);
        $this->assertIsBool($this->objective->use_standard_sources);
        $this->assertIsArray($this->objective->expense_categories);
        $this->assertIsArray($this->objective->custom_users);
        $this->assertIsArray($this->objective->custom_categories);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $newData = [
            'title' => 'Updated Objective',
            'target_amount' => 200000.75,
            'is_active' => false
        ];

        $this->objective->update($newData);

        $this->assertDatabaseHas('objectives', [
            'id' => $this->objective->id,
            'title' => 'Updated Objective',
            'target_amount' => 200000.75,
            'is_active' => false
        ]);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $id = $this->objective->id;
        
        $this->objective->delete();
        
        $this->assertDatabaseMissing('objectives', ['id' => $id]);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $relation = $this->objective->user();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals($this->user->id, $this->objective->user->id);
    }

    /** @test */
    public function it_has_many_progress_records()
    {
        $relation = $this->objective->progress();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
    }

    /** @test */
    public function it_has_many_sub_objectives()
    {
        $relation = $this->objective->subObjectives();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $relation);
    }

    /** @test */
    public function it_returns_zero_current_progress_when_no_progress_records()
    {
        $this->assertEquals(0, $this->objective->current_progress);
    }

    /** @test */
    public function it_returns_zero_current_amount_when_no_progress_records()
    {
        $this->assertEquals(0, $this->objective->current_amount);
    }

    /** @test */
    public function it_calculates_remaining_amount_correctly()
    {
        $expectedRemaining = $this->objective->target_amount - $this->objective->current_amount;
        $this->assertEquals($expectedRemaining, $this->objective->remaining_amount);
    }

    /** @test */
    public function it_formats_target_amount_correctly()
    {
        $expected = number_format($this->objective->target_amount, 0, ',', ' ') . ' FCFA';
        $this->assertEquals($expected, $this->objective->formatted_target_amount);
    }

    /** @test */
    public function it_formats_current_amount_correctly()
    {
        $expected = number_format($this->objective->current_amount, 0, ',', ' ') . ' FCFA';
        $this->assertEquals($expected, $this->objective->formatted_current_amount);
    }

    /** @test */
    public function it_formats_remaining_amount_correctly()
    {
        $expected = number_format($this->objective->remaining_amount, 0, ',', ' ') . ' FCFA';
        $this->assertEquals($expected, $this->objective->formatted_remaining_amount);
    }

    /** @test */
    public function it_formats_period_type_in_french()
    {
        $testCases = [
            'daily' => 'Journalier',
            'weekly' => 'Hebdomadaire',
            'monthly' => 'Mensuel',
            'yearly' => 'Annuel'
        ];

        foreach ($testCases as $type => $expected) {
            $this->objective->period_type = $type;
            $this->assertEquals($expected, $this->objective->formatted_period_type);
        }
    }

    /** @test */
    public function it_formats_sector_in_french()
    {
        $testCases = [
            'alimentation' => 'Alimentation',
            'boulangerie-patisserie' => 'Boulangerie-Pâtisserie',
            'glace' => 'Glaces',
            'global' => 'Global (Toute entreprise)'
        ];

        foreach ($testCases as $sector => $expected) {
            $this->objective->sector = $sector;
            $this->assertEquals($expected, $this->objective->formatted_sector);
        }
    }

    /** @test */
    public function it_formats_goal_type_in_french()
    {
        $this->objective->goal_type = 'revenue';
        $this->assertEquals('Chiffre d\'affaires', $this->objective->formatted_goal_type);
        
        $this->objective->goal_type = 'profit';
        $this->assertEquals('Bénéfice', $this->objective->formatted_goal_type);
    }

    /** @test */
    public function it_returns_correct_sector_color()
    {
        $testCases = [
            'alimentation' => 'bg-blue-100 text-blue-800',
            'boulangerie-patisserie' => 'bg-yellow-100 text-yellow-800',
            'glace' => 'bg-purple-100 text-purple-800',
            'global' => 'bg-green-100 text-green-800'
        ];

        foreach ($testCases as $sector => $expected) {
            $this->objective->sector = $sector;
            $this->assertEquals($expected, $this->objective->sector_color);
        }
    }

    /** @test */
    public function it_returns_correct_progress_color()
    {
        // Mock progress records to test different scenarios
        $progress = ObjectiveProgress::factory()->create([
            'objective_id' => $this->objective->id,
            'progress_percentage' => 50
        ]);

        $this->objective->refresh();
        $this->assertEquals('bg-yellow-500', $this->objective->progress_color);
    }

    /** @test */
    public function it_calculates_total_sub_objectives_amount()
    {
        // Create sub-objectives
        SubObjective::factory()->create([
            'objective_id' => $this->objective->id,
            'target_amount' => 30000
        ]);
        
        SubObjective::factory()->create([
            'objective_id' => $this->objective->id,
            'target_amount' => 20000
        ]);

        $this->assertEquals(50000, $this->objective->total_sub_objectives_amount);
    }

    /** @test */
    public function it_detects_when_sub_objectives_exceed_limit()
    {
        // Create sub-objectives that exceed the main objective
        SubObjective::factory()->create([
            'objective_id' => $this->objective->id,
            'target_amount' => 120000 // More than the main objective's 100000.50
        ]);

        $this->assertTrue($this->objective->sub_objectives_exceed_limit);
    }

    /** @test */
    public function it_calculates_sub_objectives_remaining_allocation()
    {
        SubObjective::factory()->create([
            'objective_id' => $this->objective->id,
            'target_amount' => 30000
        ]);

        $expected = $this->objective->target_amount - 30000;
        $this->assertEquals($expected, $this->objective->sub_objectives_remaining_allocation);
    }

    /** @test */
    public function it_detects_inconsistency_for_boulangerie_patisserie()
    {
        // Set sector to boulangerie-patisserie
        $this->objective->update(['sector' => 'boulangerie-patisserie']);
        
        // Create sub-objectives
        SubObjective::factory()->create([
            'objective_id' => $this->objective->id,
            'current_amount' => 5000
        ]);

        // Create progress with different amount (difference > 1000)
        ObjectiveProgress::factory()->create([
            'objective_id' => $this->objective->id,
            'current_amount' => 8000
        ]);

        $this->assertTrue($this->objective->has_inconsistency);
        $this->assertEquals(3000, $this->objective->inconsistency_amount);
    }

    /** @test */
    public function it_formats_inconsistency_amount()
    {
        $this->objective->update(['sector' => 'boulangerie-patisserie']);
        
        SubObjective::factory()->create([
            'objective_id' => $this->objective->id,
            'current_amount' => 5000
        ]);

        ObjectiveProgress::factory()->create([
            'objective_id' => $this->objective->id,
            'current_amount' => 8000
        ]);

        $expected = number_format(3000, 0, ',', ' ') . ' FCFA';
        $this->assertEquals($expected, $this->objective->formatted_inconsistency_amount);
    }

    /** @test */
    public function it_returns_standard_sources_description()
    {
        $descriptions = [
            'alimentation' => 'Versements faits par les caissier(ère)s (rôle "caissiere")',
            'boulangerie-patisserie' => 'Versements faits par les chefs de production (rôle "chef_production") et vendeurs (secteur "vente")',
            'glace' => 'Versements faits par les responsables glace (rôle "glace")',
            'global' => 'Toutes les transactions de type "income" (entrée d\'argent)'
        ];

        foreach ($descriptions as $sector => $expected) {
            $this->objective->sector = $sector;
            $this->assertEquals($expected, $this->objective->standard_sources_description);
        }
    }
}
