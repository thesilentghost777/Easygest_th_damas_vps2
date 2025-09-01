<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ObjectiveProgress;
use App\Models\Objective;
use App\Models\VersementChef;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObjectiveProgressTest extends TestCase
{
    use RefreshDatabase;

    protected $objectiveProgress;
    protected $objective;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->objective = Objective::factory()->create([
            'sector' => 'alimentation'
        ]);
        
        $this->objectiveProgress = ObjectiveProgress::factory()->create([
            'objective_id' => $this->objective->id,
            'current_amount' => 50000.50,
            'expenses' => 15000.25,
            'profit' => 35000.25,
            'progress_percentage' => 75.50,
            'transactions' => ['TXN001', 'TXN002']
        ]);
    }

    /** @test */
    public function it_can_create_objective_progress()
    {
        $data = [
            'objective_id' => $this->objective->id,
            'date' => now(),
            'current_amount' => 25000.00,
            'expenses' => 8000.00,
            'profit' => 17000.00,
            'progress_percentage' => 50.00,
            'transactions' => ['TXN003']
        ];

        $progress = ObjectiveProgress::create($data);

        $this->assertInstanceOf(ObjectiveProgress::class, $progress);
        $this->assertEquals($data['current_amount'], $progress->current_amount);
        $this->assertEquals($data['expenses'], $progress->expenses);
        $this->assertEquals($data['profit'], $progress->profit);
        $this->assertEquals($data['progress_percentage'], $progress->progress_percentage);
        $this->assertEquals($data['transactions'], $progress->transactions);
    }

    /** @test */
    public function it_can_read_objective_progress()
    {
        $found = ObjectiveProgress::find($this->objectiveProgress->id);

        $this->assertNotNull($found);
        $this->assertEquals($this->objectiveProgress->current_amount, $found->current_amount);
        $this->assertEquals($this->objectiveProgress->expenses, $found->expenses);
        $this->assertEquals($this->objectiveProgress->profit, $found->profit);
    }

    /** @test */
    public function it_can_update_objective_progress()
    {
        $newAmount = 75000.00;
        $newExpenses = 20000.00;
        
        $this->objectiveProgress->update([
            'current_amount' => $newAmount,
            'expenses' => $newExpenses
        ]);

        $this->assertEquals($newAmount, $this->objectiveProgress->fresh()->current_amount);
        $this->assertEquals($newExpenses, $this->objectiveProgress->fresh()->expenses);
    }

    /** @test */
    public function it_can_delete_objective_progress()
    {
        $id = $this->objectiveProgress->id;
        
        $this->objectiveProgress->delete();

        $this->assertNull(ObjectiveProgress::find($id));
    }

    /** @test */
    public function it_belongs_to_objective()
    {
        $this->assertInstanceOf(Objective::class, $this->objectiveProgress->objective);
        $this->assertEquals($this->objective->id, $this->objectiveProgress->objective->id);
    }

    /** @test */
    public function it_formats_current_amount_correctly()
    {
        $expected = number_format($this->objectiveProgress->current_amount, 0, ',', ' ') . ' FCFA';
        $this->assertEquals($expected, $this->objectiveProgress->formatted_current_amount);
    }

    /** @test */
    public function it_formats_expenses_correctly()
    {
        $expected = number_format($this->objectiveProgress->expenses, 0, ',', ' ') . ' FCFA';
        $this->assertEquals($expected, $this->objectiveProgress->formatted_expenses);
    }

    /** @test */
    public function it_formats_profit_correctly()
    {
        $expected = number_format($this->objectiveProgress->profit, 0, ',', ' ') . ' FCFA';
        $this->assertEquals($expected, $this->objectiveProgress->formatted_profit);
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $this->assertIsFloat($this->objectiveProgress->current_amount);
        $this->assertIsFloat($this->objectiveProgress->expenses);
        $this->assertIsFloat($this->objectiveProgress->profit);
        $this->assertIsFloat($this->objectiveProgress->progress_percentage);
        $this->assertIsArray($this->objectiveProgress->transactions);
    }

    /** @test */
    public function it_handles_transaction_details_for_alimentation_sector()
    {
        VersementChef::factory()->create([
            'code_vc' => 'TXN001',
            'status' => true
        ]);

        $details = $this->objectiveProgress->transaction_details;
        
        $this->assertNotEmpty($details);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $details);
    }

    /** @test */
    public function it_handles_transaction_details_for_global_sector()
    {
        $globalObjective = Objective::factory()->create(['sector' => 'global']);
        $globalProgress = ObjectiveProgress::factory()->create([
            'objective_id' => $globalObjective->id,
            'transactions' => [1, 2]
        ]);

        Transaction::factory()->create(['id' => 1]);
        Transaction::factory()->create(['id' => 2]);

        $details = $globalProgress->transaction_details;
        
        $this->assertNotEmpty($details);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $details);
    }

    /** @test */
    public function it_returns_empty_collection_when_no_transactions()
    {
        $this->objectiveProgress->update(['transactions' => []]);
        
        $details = $this->objectiveProgress->transaction_details;
        
        $this->assertEmpty($details);
    }
}
