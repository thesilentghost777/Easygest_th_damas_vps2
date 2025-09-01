<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_message()
    {
        $message = Message::create([
            'message' => 'Test content',
            'type' => 'info',
            'date_message' => now(),
            'name' => 'Admin',
            'read' => false,
        ]);

        $this->assertDatabaseHas('Message', [
            'id' => $message->id,
            'message' => 'Test content',
            'read' => false,
        ]);
    }

    /** @test */
    public function it_can_update_a_message()
    {
        $message = Message::factory()->create();

        $message->update(['read' => true]);

        $this->assertDatabaseHas('Message', [
            'id' => $message->id,
            'read' => true,
        ]);
    }

    /** @test */
    public function it_can_delete_a_message()
    {
        $message = Message::factory()->create();

        $message->delete();

        $this->assertDatabaseMissing('Message', [
            'id' => $message->id,
        ]);
    }
}
