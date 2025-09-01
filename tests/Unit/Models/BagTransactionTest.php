<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\BagTransaction;
use App\Models\Bag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BagTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $transaction = new BagTransaction();
        $expectedFillable = ['bag_id', 'type', 'quantity', 'transaction_date'];
        $this->assertEquals($expectedFillable, $transaction->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $transaction = new BagTransaction();
        $this->assertEquals('date', $transaction->getCasts()['transaction_date']);
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $bag = Bag::factory()->create();
        
        $transactionData = [
            'bag_id' => $bag->id,
            'type' => 'received',
            'quantity' => 50,
            'transaction_date' => '2024-06-07'
        ];

        $transaction = BagTransaction::create($transactionData);

        $this->assertInstanceOf(BagTransaction::class, $transaction);
        $this->assertDatabaseHas('bag_transactions', $transactionData);
    }

    /** @test */
    public function it_belongs_to_a_bag()
    {
        $transaction = new BagTransaction();
        $relation = $transaction->bag();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertInstanceOf(Bag::class, $relation->getRelated());
    }

    /** @test */
    public function it_can_retrieve_related_bag()
    {
        $bag = Bag::factory()->create();
        $transaction = BagTransaction::factory()->create(['bag_id' => $bag->id]);

        $this->assertInstanceOf(Bag::class, $transaction->bag);
        $this->assertEquals($bag->id, $transaction->bag->id);
    }

    /** @test */
    public function transaction_date_is_cast_to_date()
    {
        $date = '2024-06-07';
        $transaction = BagTransaction::factory()->create(['transaction_date' => $date]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $transaction->transaction_date);
        $this->assertEquals($date, $transaction->transaction_date->format('Y-m-d'));
    }

    /** @test */
    public function it_can_be_updated()
    {
        $transaction = BagTransaction::factory()->create([
            'type' => 'sold',
            'quantity' => 25
        ]);

        $transaction->update([
            'type' => 'received',
            'quantity' => 40
        ]);

        $this->assertEquals('received', $transaction->type);
        $this->assertEquals(40, $transaction->quantity);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $transaction = BagTransaction::factory()->create();
        $transactionId = $transaction->id;

        $transaction->delete();

        $this->assertDatabaseMissing('bag_transactions', ['id' => $transactionId]);
    }
}
