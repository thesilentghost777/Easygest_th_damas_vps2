<?php

namespace Tests\Unit;

use App\Models\CashWithdrawal;
use App\Models\CashierSession;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class CashWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur et une session de caissier pour les tests
        $this->user = User::factory()->create();
        $this->cashierSession = CashierSession::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_can_create_a_cash_withdrawal()
    {
        $withdrawalData = [
            'cashier_session_id' => $this->cashierSession->id,
            'amount' => 100.50,
            'reason' => 'Remboursement client',
            'withdrawn_by' => 'Jean Dupont',
            'created_at' => Carbon::now()
        ];

        $withdrawal = CashWithdrawal::create($withdrawalData);

        $this->assertInstanceOf(CashWithdrawal::class, $withdrawal);
        $this->assertEquals($this->cashierSession->id, $withdrawal->cashier_session_id);
        $this->assertEquals(100.50, $withdrawal->amount);
        $this->assertEquals('Remboursement client', $withdrawal->reason);
        $this->assertEquals('Jean Dupont', $withdrawal->withdrawn_by);
        $this->assertInstanceOf(Carbon::class, $withdrawal->created_at);
    }

    /** @test */
    public function it_belongs_to_a_cashier_session_via_session_method()
    {
        $withdrawal = CashWithdrawal::factory()->create([
            'cashier_session_id' => $this->cashierSession->id
        ]);

        $this->assertInstanceOf(CashierSession::class, $withdrawal->session);
        $this->assertEquals($this->cashierSession->id, $withdrawal->session->id);
    }

    /** @test */
    public function it_belongs_to_a_cashier_session_via_cashier_session_method()
    {
        $withdrawal = CashWithdrawal::factory()->create([
            'cashier_session_id' => $this->cashierSession->id
        ]);

        $this->assertInstanceOf(CashierSession::class, $withdrawal->cashierSession);
        $this->assertEquals($this->cashierSession->id, $withdrawal->cashierSession->id);
    }

    /** @test */
    public function it_casts_amount_to_float()
    {
        $withdrawal = CashWithdrawal::factory()->create([
            'cashier_session_id' => $this->cashierSession->id,
            'amount' => '250.75'
        ]);

        $this->assertIsFloat($withdrawal->amount);
        $this->assertEquals(250.75, $withdrawal->amount);
    }

    /** @test */
    public function it_casts_created_at_to_datetime()
    {
        $withdrawal = CashWithdrawal::factory()->create([
            'cashier_session_id' => $this->cashierSession->id,
            'created_at' => '2024-01-15 14:30:00'
        ]);

        $this->assertInstanceOf(Carbon::class, $withdrawal->created_at);
        $this->assertEquals('2024-01-15 14:30:00', $withdrawal->created_at->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = [
            'cashier_session_id',
            'amount',
            'reason',
            'withdrawn_by',
            'created_at'
        ];

        $withdrawal = new CashWithdrawal();
        
        $this->assertEquals($fillable, $withdrawal->getFillable());
    }

    /** @test */
    public function timestamps_are_disabled()
    {
        $withdrawal = new CashWithdrawal();
        
        $this->assertFalse($withdrawal->timestamps);
    }

    

    /** @test */
    public function it_belongs_to_session_with_correct_foreign_key()
    {
        $withdrawal = CashWithdrawal::factory()->create([
            'cashier_session_id' => $this->cashierSession->id
        ]);

        // Vérifier que la relation utilise la bonne clé étrangère
        $relation = $withdrawal->session();
        $this->assertEquals('cashier_session_id', $relation->getForeignKeyName());
    }

    /** @test */
    public function it_can_store_decimal_amounts_correctly()
    {
        $testAmounts = [
            0.01,
            0.99,
            10.50,
            100.00,
            999.99,
            1000.00
        ];

        foreach ($testAmounts as $amount) {
            $withdrawal = CashWithdrawal::factory()->create([
                'cashier_session_id' => $this->cashierSession->id,
                'amount' => $amount
            ]);

            $this->assertEquals($amount, $withdrawal->amount);
            $this->assertIsFloat($withdrawal->amount);
        }
    }

    /** @test */
    public function it_can_store_long_reason_text()
    {
        $longReason = str_repeat('Test de raison très longue pour vérifier la capacité de stockage. ', 3);
        
        $withdrawal = CashWithdrawal::factory()->create([
            'cashier_session_id' => $this->cashierSession->id,
            'reason' => $longReason
        ]);

        $this->assertEquals($longReason, $withdrawal->reason);
    }

    /** @test */
    public function both_relation_methods_return_same_session()
    {
        $withdrawal = CashWithdrawal::factory()->create([
            'cashier_session_id' => $this->cashierSession->id
        ]);

        $sessionViaSession = $withdrawal->session;
        $sessionViaCashierSession = $withdrawal->cashierSession;

        $this->assertEquals($sessionViaSession->id, $sessionViaCashierSession->id);
        $this->assertEquals($this->cashierSession->id, $sessionViaSession->id);
        $this->assertEquals($this->cashierSession->id, $sessionViaCashierSession->id);
    }


}
