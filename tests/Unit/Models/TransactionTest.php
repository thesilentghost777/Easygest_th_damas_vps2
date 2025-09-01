<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_transaction()
    {
        $transaction = Transaction::factory()->create([
            'type' => 'income',
            'amount' => 25000.50,
            'description' => 'Test transaction'
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'type' => 'income',
            'amount' => 25000.50,
            'description' => 'Test transaction'
        ]);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'type',
            'category_id',
            'amount',
            'date',
            'description'
        ];

        $this->assertEquals($fillable, (new Transaction())->getFillable());
    }

    /** @test */
    public function it_casts_amount_to_decimal()
    {
        $transaction = Transaction::factory()->create([
            'amount' => '15000.75'
        ]);

        $this->assertEquals('15000.75', $transaction->amount);
        $this->assertIsString($transaction->amount);
    }

    /** @test */
    public function it_casts_date_to_datetime()
    {
        $transaction = Transaction::factory()->create([
            'date' => '2024-01-15 14:30:00'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $transaction->date);
    }

    /** @test */
    public function it_belongs_to_category()
    {
        $category = Category::factory()->create();
        $transaction = Transaction::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $transaction->category);
        $this->assertEquals($category->id, $transaction->category->id);
    }

    /** @test */
    public function it_formats_amount_correctly()
    {
        $transaction = Transaction::factory()->create([
            'amount' => 1500000.75
        ]);

        $this->assertEquals('1 500 001 FCFA', $transaction->formatted_amount);
    }

    /** @test */
    public function it_formats_amount_without_decimals()
    {
        $transaction = Transaction::factory()->create([
            'amount' => 25000.00
        ]);

        $this->assertEquals('25 000 FCFA', $transaction->formatted_amount);
    }

    /** @test */
    public function it_formats_date_correctly()
    {
        $transaction = Transaction::factory()->create([
            'date' => '2024-01-15 14:30:00'
        ]);

        $this->assertEquals('15/01/2024 14:30', $transaction->formatted_date);
    }

    /** @test */
    public function it_can_scope_income_transactions()
    {
        Transaction::factory()->create(['type' => 'income']);
        Transaction::factory()->create(['type' => 'outcome']);
        Transaction::factory()->create(['type' => 'income']);

        $incomeTransactions = Transaction::income()->get();

        $this->assertCount(2, $incomeTransactions);
        $this->assertTrue($incomeTransactions->every(fn($transaction) => $transaction->type === 'income'));
    }

    /** @test */
    public function it_can_scope_outcome_transactions()
    {
        Transaction::factory()->create(['type' => 'outcome']);
        Transaction::factory()->create(['type' => 'income']);
        Transaction::factory()->create(['type' => 'outcome']);

        $outcomeTransactions = Transaction::outcome()->get();

        $this->assertCount(2, $outcomeTransactions);
        $this->assertTrue($outcomeTransactions->every(fn($transaction) => $transaction->type === 'outcome'));
    }

    /** @test */
    public function it_can_update_transaction()
    {
        $transaction = Transaction::factory()->create();
        
        $transaction->update([
            'type' => 'outcome',
            'amount' => 75000.00,
            'description' => 'Updated description'
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'type' => 'outcome',
            'amount' => 75000.00,
            'description' => 'Updated description'
        ]);
    }

    /** @test */
    public function it_can_delete_transaction()
    {
        $transaction = Transaction::factory()->create();
        $id = $transaction->id;

        $transaction->delete();

        $this->assertDatabaseMissing('transactions', ['id' => $id]);
    }

    /** @test */
    public function it_handles_zero_amount_formatting()
    {
        $transaction = Transaction::factory()->create([
            'amount' => 0.00
        ]);

        $this->assertEquals('0 FCFA', $transaction->formatted_amount);
    }

    /** @test */
    public function it_handles_small_amount_formatting()
    {
        $transaction = Transaction::factory()->create([
            'amount' => 150.25
        ]);

        $this->assertEquals('150 FCFA', $transaction->formatted_amount);
    }
}
