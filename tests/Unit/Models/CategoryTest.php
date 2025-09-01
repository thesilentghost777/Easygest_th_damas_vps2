<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_name_fillable()
    {
        $category = new Category(['name' => 'Test Category']);
        $this->assertEquals('Test Category', $category->name);
    }

    /** @test */
    public function it_has_many_transactions()
    {
        $category = Category::factory()->create();
        $transaction = Transaction::factory()->create(['category_id' => $category->id]);

        $this->assertTrue($category->transactions->contains($transaction));
    }
}
