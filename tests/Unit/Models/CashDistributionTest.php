<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\CashDistribution;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CashDistributionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $distribution = new CashDistribution();
        $expectedFillable = [
            'user_id',
            'date',
            'bill_amount',
            'initial_coin_amount',
            'final_coin_amount',
            'deposited_amount',
            'sales_amount',
            'missing_amount',
            'status',
            'notes',
            'closed_by',
            'closed_at'
        ];
        $this->assertEquals($expectedFillable, $distribution->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $distribution = new CashDistribution();
        $expectedCasts = [
            'date' => 'date',
            'closed_at' => 'datetime'
        ];
        
        $this->assertEquals('date', $distribution->getCasts()['date']);
        $this->assertEquals('datetime', $distribution->getCasts()['closed_at']);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $user = User::factory()->create();
        
        $distributionData = [
            'user_id' => $user->id,
            'date' => '2024-06-07',
            'bill_amount' => 100.00,
            'initial_coin_amount' => 50.00,
            'final_coin_amount' => 25.00,
            'deposited_amount' => 120.00,
            'sales_amount' => 95.00,
            'status' => 'en_cours'
        ];

        $distribution = CashDistribution::create($distributionData);

        $this->assertInstanceOf(CashDistribution::class, $distribution);
        $this->assertDatabaseHas('cash_distributions', $distributionData);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $distribution = new CashDistribution();
        $relation = $distribution->user();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    /** @test */
    public function it_belongs_to_a_closed_by_user()
    {
        $distribution = new CashDistribution();
        $relation = $distribution->closedByUser();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('closed_by', $relation->getForeignKeyName());
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    /** @test */
    public function it_can_retrieve_related_user()
    {
        $user = User::factory()->create();
        $distribution = CashDistribution::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $distribution->user);
        $this->assertEquals($user->id, $distribution->user->id);
    }

    /** @test */
    public function it_can_retrieve_closed_by_user()
    {
        $closedByUser = User::factory()->create();
        $distribution = CashDistribution::factory()->create(['closed_by' => $closedByUser->id]);

        $this->assertInstanceOf(User::class, $distribution->closedByUser);
        $this->assertEquals($closedByUser->id, $distribution->closedByUser->id);
    }

    /** @test */
    public function date_is_cast_to_date()
    {
        $date = '2024-06-07';
        $distribution = CashDistribution::factory()->create(['date' => $date]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $distribution->date);
        $this->assertEquals($date, $distribution->date->format('Y-m-d'));
    }

    /** @test */
    public function closed_at_is_cast_to_datetime()
    {
        $datetime = '2024-06-07 15:30:00';
        $distribution = CashDistribution::factory()->create(['closed_at' => $datetime]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $distribution->closed_at);
        $this->assertEquals($datetime, $distribution->closed_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function calculate_missing_amount_returns_null_when_final_coin_amount_is_null()
    {
        $user = User::factory()->create();
        $distribution = CashDistribution::factory()->create([
            'final_coin_amount' => null,
            'deposited_amount' => 100
        ]);

        $result = $distribution->calculateMissingAmount($user);

        $this->assertNull($result);
    }

    /** @test */
    public function calculate_missing_amount_returns_null_when_deposited_amount_is_null()
    {
        $user = User::factory()->create();
        $distribution = CashDistribution::factory()->create([
            'final_coin_amount' => 25,
            'deposited_amount' => null
        ]);

        $result = $distribution->calculateMissingAmount($user);

        $this->assertNull($result);
    }

    /** @test */
    public function calculate_missing_amount_returns_zero_when_no_shortage()
    {
        $user = User::factory()->create();
        $distribution = CashDistribution::factory()->create([
            'sales_amount' => 100,
            'bill_amount' => 50,
            'initial_coin_amount' => 30,
            'final_coin_amount' => 10,
            'deposited_amount' => 170
        ]);

        $result = $distribution->calculateMissingAmount($user);

        $this->assertEquals(0, $result);
        $this->assertEquals(0, $distribution->missing_amount);
    }

    /** @test */
    public function calculate_missing_amount_calculates_correctly_with_shortage()
    {
        $user = User::factory()->create();
        $distribution = CashDistribution::factory()->create([
            'sales_amount' => 100,
            'bill_amount' => 50,
            'initial_coin_amount' => 30,
            'final_coin_amount' => 10,
            'deposited_amount' => 160
        ]);

        $result = $distribution->calculateMissingAmount($user);

        // Expected: (100 + 50 + (30 - 10)) - 160 = 170 - 160 = 10
        $this->assertEquals(10, $result);
        $this->assertEquals(10, $distribution->missing_amount);
    }

    /** @test */
    public function calculate_missing_amount_handles_negative_coin_difference()
    {
        $user = User::factory()->create();
        $distribution = CashDistribution::factory()->create([
            'sales_amount' => 100,
            'bill_amount' => 50,
            'initial_coin_amount' => 10,
            'final_coin_amount' => 30,
            'deposited_amount' => 120
        ]);

        $result = $distribution->calculateMissingAmount($user);

        // Expected: (100 + 50 + (10 - 30)) - 120 = 130 - 120 = 10
        $this->assertEquals(10, $result);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $distribution = CashDistribution::factory()->create(['status' => 'en_cours']);

        $distribution->update(['status' => 'cloture', 'notes' => 'Updated notes']);

        $this->assertEquals('cloture', $distribution->status);
        $this->assertEquals('Updated notes', $distribution->notes);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $distribution = CashDistribution::factory()->create();
        $distributionId = $distribution->id;

        $distribution->delete();

        $this->assertDatabaseMissing('cash_distributions', ['id' => $distributionId]);
    }
}
