<?php

namespace Tests\Unit;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function fillable_attributes_are_correct()
    {
        $announcement = new Announcement();

        $this->assertEquals(
            ['title', 'content', 'user_id'],
            $announcement->getFillable()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function user_relationship_returns_belongsto()
    {
        $user = User::factory()->create();
        $announcement = Announcement::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $announcement->user);
        $this->assertEquals($user->id, $announcement->user->id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function reactions_relationship_returns_has_many()
    {
        $announcement = Announcement::factory()->create();
        $reaction1 = Reaction::factory()->create(['announcement_id' => $announcement->id]);
        $reaction2 = Reaction::factory()->create(['announcement_id' => $announcement->id]);

        $this->assertCount(2, $announcement->reactions);
        $this->assertTrue($announcement->reactions->contains($reaction1));
        $this->assertTrue($announcement->reactions->contains($reaction2));
    }
}
