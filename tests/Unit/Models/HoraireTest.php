<?php

namespace Tests\Unit\Models;

use App\Models\Horaire;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HoraireTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_horaire()
    {
        $user = User::factory()->create();
        $arrive = Carbon::now()->setTime(8, 0);
        $depart = Carbon::now()->setTime(17, 0);
        
        $horaire = Horaire::create([
            'employe' => $user->id,
            'arrive' => $arrive,
            'depart' => $depart
        ]);

        $this->assertDatabaseHas('Horaire', [
            'employe' => $user->id,
            'arrive' => $arrive->format('Y-m-d H:i:s'),
            'depart' => $depart->format('Y-m-d H:i:s')
        ]);
    }

    /** @test */
    public function it_can_read_an_horaire()
    {
        $user = User::factory()->create();
        $horaire = Horaire::factory()->create([
            'employe' => $user->id
        ]);

        $found = Horaire::find($horaire->id);
        
        $this->assertEquals($user->id, $found->employe);
        $this->assertNotNull($found->arrive);
        $this->assertNotNull($found->depart);
    }

    /** @test */
    public function it_can_update_an_horaire()
    {
        $newDepart = Carbon::now()->setTime(18, 30);
        $horaire = Horaire::factory()->create();

        $horaire->update(['depart' => $newDepart]);

        $this->assertEquals($newDepart->format('Y-m-d H:i:s'), $horaire->fresh()->depart->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_can_delete_an_horaire()
    {
        $horaire = Horaire::factory()->create();

        $horaire->delete();

        $this->assertDatabaseMissing('Horaire', ['id' => $horaire->id]);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $horaire = new Horaire();
        
        $this->assertEquals('Horaire', $horaire->getTable());
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $horaire = new Horaire();
        
        $expected = ['employe', 'arrive', 'depart'];
        
        $this->assertEquals($expected, $horaire->getFillable());
    }

    /** @test */
    public function it_casts_arrive_to_datetime()
    {
        $horaire = Horaire::factory()->create([
            'arrive' => '2024-01-15 08:30:00'
        ]);

        $this->assertInstanceOf(Carbon::class, $horaire->arrive);
        $this->assertEquals('08:30:00', $horaire->arrive->format('H:i:s'));
    }

    /** @test */
    public function it_casts_depart_to_datetime()
    {
        $horaire = Horaire::factory()->create([
            'depart' => '2024-01-15 17:45:00'
        ]);

        $this->assertInstanceOf(Carbon::class, $horaire->depart);
        $this->assertEquals('17:45:00', $horaire->depart->format('H:i:s'));
    }

    /** @test */
    public function it_belongs_to_user_with_custom_foreign_key()
    {
        $user = User::factory()->create();
        $horaire = Horaire::factory()->create(['employe' => $user->id]);

        $this->assertInstanceOf(User::class, $horaire->user);
        $this->assertEquals($user->id, $horaire->user->id);
        $this->assertEquals($user->name, $horaire->user->name);
    }

   
    /** @test */
    public function it_can_create_with_string_datetime()
    {
        $user = User::factory()->create();
        
        $horaire = Horaire::create([
            'employe' => $user->id,
            'arrive' => '2024-01-15 09:00:00',
            'depart' => '2024-01-15 18:00:00'
        ]);

        $this->assertInstanceOf(Carbon::class, $horaire->arrive);
        $this->assertInstanceOf(Carbon::class, $horaire->depart);
    }

    /** @test */
    public function it_can_filter_by_employe()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Horaire::factory()->create(['employe' => $user1->id]);
        Horaire::factory()->create(['employe' => $user1->id]);
        Horaire::factory()->create(['employe' => $user2->id]);

        $user1Horaires = Horaire::where('employe', $user1->id)->get();
        
        $this->assertCount(2, $user1Horaires);
    }

    /** @test */
    public function it_can_filter_by_date_range()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        Horaire::factory()->create([
            'arrive' => $today->copy()->setTime(8, 0),
            'depart' => $today->copy()->setTime(17, 0)
        ]);
        
        Horaire::factory()->create([
            'arrive' => $yesterday->copy()->setTime(8, 0),
            'depart' => $yesterday->copy()->setTime(17, 0)
        ]);

        $todayHoraires = Horaire::whereDate('arrive', $today)->get();
        
        $this->assertCount(1, $todayHoraires);
    }


    /** @test */
    public function it_can_find_horaires_for_specific_time_range()
    {
        $morningStart = Carbon::now()->setTime(8, 0);
        $morningEnd = Carbon::now()->setTime(12, 0);
        
        Horaire::factory()->create(['arrive' => Carbon::now()->setTime(7, 30)]);
        Horaire::factory()->create(['arrive' => Carbon::now()->setTime(9, 0)]);
        Horaire::factory()->create(['arrive' => Carbon::now()->setTime(13, 0)]);

        $morningHoraires = Horaire::whereTime('arrive', '>=', $morningStart->format('H:i:s'))
                                 ->whereTime('arrive', '<=', $morningEnd->format('H:i:s'))
                                 ->get();
        
        $this->assertCount(1, $morningHoraires);
    }

    /** @test */
    public function it_handles_null_arrive_or_depart()
    {
        $user = User::factory()->create();
        
        $horaire = Horaire::create([
            'employe' => $user->id,
            'arrive' => Carbon::now(),
            'depart' => null
        ]);

        $this->assertNotNull($horaire->arrive);
        $this->assertNull($horaire->depart);
    }

    /** @test */
    public function it_can_order_by_arrive_time()
    {
        Horaire::factory()->create(['arrive' => Carbon::now()->setTime(9, 0)]);
        Horaire::factory()->create(['arrive' => Carbon::now()->setTime(8, 0)]);
        Horaire::factory()->create(['arrive' => Carbon::now()->setTime(10, 0)]);

        $orderedHoraires = Horaire::orderBy('arrive')->get();
        
        $this->assertEquals('08:00:00', $orderedHoraires->first()->arrive->format('H:i:s'));
        $this->assertEquals('10:00:00', $orderedHoraires->last()->arrive->format('H:i:s'));
    }
}
