<?php

namespace Tests\Unit;

use App\Models\CashierSession;
use App\Models\User;
use App\Models\CashWithdrawal;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class CashierSessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur pour les tests
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_a_cashier_session()
    {
        $sessionData = [
            'user_id' => $this->user->id,
            'start_time' => now(),
            'initial_cash' => 1000.00,
            'initial_change' => 200.00,
            'initial_mobile_balance' => 500.00,
            'notes' => 'Session de test'
        ];

        $session = CashierSession::create($sessionData);

        $this->assertInstanceOf(CashierSession::class, $session);
        $this->assertEquals($this->user->id, $session->user_id);
        $this->assertEquals(1000.00, $session->initial_cash);
        $this->assertEquals(200.00, $session->initial_change);
        $this->assertEquals(500.00, $session->initial_mobile_balance);
        $this->assertEquals('Session de test', $session->notes);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $session = CashierSession::factory()->create(['user_id' => $this->user->id]);

        $this->assertInstanceOf(User::class, $session->user);
        $this->assertEquals($this->user->id, $session->user->id);
    }

    /** @test */
    public function it_has_many_withdrawals()
    {
        $session = CashierSession::factory()->create(['user_id' => $this->user->id]);
        
        $withdrawal1 = CashWithdrawal::factory()->create(['cashier_session_id' => $session->id]);
        $withdrawal2 = CashWithdrawal::factory()->create(['cashier_session_id' => $session->id]);

        $this->assertCount(2, $session->withdrawals);
        $this->assertTrue($session->withdrawals->contains($withdrawal1));
        $this->assertTrue($session->withdrawals->contains($withdrawal2));
    }

    /** @test */
    public function it_can_check_if_session_is_active()
    {
        // Session active (sans end_time)
        $activeSession = CashierSession::factory()->create([
            'user_id' => $this->user->id,
            'start_time' => now(),
            'end_time' => null
        ]);

        $this->assertTrue($activeSession->isActive());

        // Session inactive (avec end_time)
        $inactiveSession = CashierSession::factory()->create([
            'user_id' => $this->user->id,
            'start_time' => now()->subHours(2),
            'end_time' => now()
        ]);

        $this->assertFalse($inactiveSession->isActive());
    }

    /** @test */
    public function it_calculates_duration_for_completed_session()
    {
        $startTime = Carbon::now()->subHours(2);
        $endTime = Carbon::now();

        $session = CashierSession::factory()->create([
            'user_id' => $this->user->id,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        $duration = $session->getDurationAttribute();
        $this->assertStringContainsString('2 hours', $duration);
    }

    /** @test */
    public function it_calculates_duration_for_active_session()
    {
        $startTime = Carbon::now()->subHour();

        $session = CashierSession::factory()->create([
            'user_id' => $this->user->id,
            'start_time' => $startTime,
            'end_time' => null
        ]);

        $duration = $session->getDurationAttribute();
        $this->assertStringContainsString('1 hour', $duration);
    }

    /** @test */
    public function it_can_get_total_withdrawals()
    {
        $session = CashierSession::factory()->create(['user_id' => $this->user->id]);
        
        CashWithdrawal::factory()->create([
            'cashier_session_id' => $session->id,
            'amount' => 100.00
        ]);
        
        CashWithdrawal::factory()->create([
            'cashier_session_id' => $session->id,
            'amount' => 150.00
        ]);

        $totalWithdrawals = $session->getTotalWithdrawals();
        
        $this->assertEquals(250.00, $totalWithdrawals);
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $session = CashierSession::factory()->create([
            'user_id' => $this->user->id,
            'start_time' => '2024-01-01 10:00:00',
            'end_time' => '2024-01-01 18:00:00',
            'initial_cash' => '1000.50',
            'final_cash' => '950.25'
        ]);

        $this->assertInstanceOf(Carbon::class, $session->start_time);
        $this->assertInstanceOf(Carbon::class, $session->end_time);
        $this->assertIsFloat($session->initial_cash);
        $this->assertIsFloat($session->final_cash);
        $this->assertEquals(1000.50, $session->initial_cash);
        $this->assertEquals(950.25, $session->final_cash);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = [
            'user_id',
            'start_time',
            'end_time',
            'initial_cash',
            'initial_change',
            'initial_mobile_balance',
            'final_cash',
            'final_change',
            'final_mobile_balance',
            'cash_remitted',
            'total_withdrawals',
            'discrepancy',
            'notes',
            'end_notes',
        ];

        $session = new CashierSession();
        
        $this->assertEquals($fillable, $session->getFillable());
    }

    /** @test */
    public function it_calculates_session_duration_with_get_duration_method()
    {
        $startTime = Carbon::now()->subHours(3);
        $endTime = Carbon::now();

        $session = CashierSession::factory()->create([
            'user_id' => $this->user->id,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        $duration = $session->getDuration();
        $this->assertStringContainsString('3 hours', $duration);
    }

    /** @test */
    public function it_calculates_duration_for_active_session_with_get_duration_method()
    {
        $startTime = Carbon::now()->subMinutes(30);

        $session = CashierSession::factory()->create([
            'user_id' => $this->user->id,
            'start_time' => $startTime,
            'end_time' => null
        ]);

        $duration = $session->getDuration();
        $this->assertStringContainsString('30 minutes', $duration);
    }
}
