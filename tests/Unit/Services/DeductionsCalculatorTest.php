<?php

namespace Tests\Unit\Services;

use App\Services\DeductionsCalculator;
use App\Models\ACouper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeductionsCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private DeductionsCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new DeductionsCalculator();
    }

    /** @test */
    public function it_calculates_deductions_for_employee_with_data()
    {
        // Créer un employé
        $employe = User::factory()->create();
        $mois = Carbon::create(2024, 3, 1);

        // Créer des données de déductions pour mars 2024
        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $mois->copy()->day(5),
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ]);

        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $mois->copy()->day(15),
            'manquants' => 500,
            'remboursement' => 1000,
            'pret' => 2000,
            'caisse_sociale' => 300
        ]);

        $result = $this->calculator->calculerDeductions($employe->id, $mois);

        $this->assertEquals([
            'manquants' => 1500,
            'remboursement' => 3000,
            'pret' => 5000,
            'caisse_sociale' => 800
        ], $result);
    }

    /** @test */
    public function it_returns_zero_values_when_no_data_exists()
    {
        $employe = User::factory()->create();
        $mois = Carbon::create(2024, 3, 1);

        $result = $this->calculator->calculerDeductions($employe->id, $mois);

        $this->assertEquals([
            'manquants' => 0,
            'remboursement' => 0,
            'pret' => 0,
            'caisse_sociale' => 0
        ], $result);
    }

    /** @test */
    public function it_filters_by_specific_month_and_year()
    {
        $employe = User::factory()->create();
        $moisCible = Carbon::create(2024, 3, 1);
        $autreMois = Carbon::create(2024, 4, 1);

        // Données pour mars 2024
        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $moisCible->copy()->day(10),
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ]);

        // Données pour avril 2024 (ne doivent pas être incluses)
        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $autreMois->copy()->day(10),
            'manquants' => 5000,
            'remboursement' => 6000,
            'pret' => 7000,
            'caisse_sociale' => 8000
        ]);

        $result = $this->calculator->calculerDeductions($employe->id, $moisCible);

        $this->assertEquals([
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ], $result);
    }

    /** @test */
    public function it_filters_by_specific_year()
    {
        $employe = User::factory()->create();
        $mois2024 = Carbon::create(2024, 3, 1);
        $mois2023 = Carbon::create(2023, 3, 1);

        // Données pour mars 2024
        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $mois2024->copy()->day(10),
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ]);

        // Données pour mars 2023 (ne doivent pas être incluses)
        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $mois2023->copy()->day(10),
            'manquants' => 9000,
            'remboursement' => 8000,
            'pret' => 7000,
            'caisse_sociale' => 6000
        ]);

        $result = $this->calculator->calculerDeductions($employe->id, $mois2024);

        $this->assertEquals([
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ], $result);
    }

    /** @test */
    public function it_filters_by_employee_id()
    {
        $employe1 = User::factory()->create();
        $employe2 = User::factory()->create();
        $mois = Carbon::create(2024, 3, 1);

        // Données pour l'employé 1
        ACouper::factory()->create([
            'id_employe' => $employe1->id,
            'date' => $mois->copy()->day(10),
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ]);

        // Données pour l'employé 2 (ne doivent pas être incluses)
        ACouper::factory()->create([
            'id_employe' => $employe2->id,
            'date' => $mois->copy()->day(10),
            'manquants' => 9000,
            'remboursement' => 8000,
            'pret' => 7000,
            'caisse_sociale' => 6000
        ]);

        $result = $this->calculator->calculerDeductions($employe1->id, $mois);

        $this->assertEquals([
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ], $result);
    }


    /** @test */
    public function it_handles_zero_values()
    {
        $employe = User::factory()->create();
        $mois = Carbon::create(2024, 3, 1);

        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $mois->copy()->day(10),
            'manquants' => 0,
            'remboursement' => 0,
            'pret' => 0,
            'caisse_sociale' => 0
        ]);

        $result = $this->calculator->calculerDeductions($employe->id, $mois);

        $this->assertEquals([
            'manquants' => 0,
            'remboursement' => 0,
            'pret' => 0,
            'caisse_sociale' => 0
        ], $result);
    }

    /** @test */
    public function it_returns_integers_for_all_values()
    {
        $employe = User::factory()->create();
        $mois = Carbon::create(2024, 3, 1);

        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $mois->copy()->day(10),
            'manquants' => 1500.75,
            'remboursement' => 2000.25,
            'pret' => 3000.50,
            'caisse_sociale' => 500.99
        ]);

        $result = $this->calculator->calculerDeductions($employe->id, $mois);

        $this->assertIsInt($result['manquants']);
        $this->assertIsInt($result['remboursement']);
        $this->assertIsInt($result['pret']);
        $this->assertIsInt($result['caisse_sociale']);
    }

    /** @test */
    public function it_handles_non_existent_employee_id()
    {
        $mois = Carbon::create(2024, 3, 1);
        $nonExistentId = 99999;

        $result = $this->calculator->calculerDeductions($nonExistentId, $mois);

        $this->assertEquals([
            'manquants' => 0,
            'remboursement' => 0,
            'pret' => 0,
            'caisse_sociale' => 0
        ], $result);
    }

    /** @test */
    public function it_handles_edge_case_months()
    {
        $employe = User::factory()->create();
        
        // Test avec janvier (mois 1)
        $janvier = Carbon::create(2024, 1, 1);
        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $janvier->copy()->day(15),
            'manquants' => 1000,
            'remboursement' => 2000,
            'pret' => 3000,
            'caisse_sociale' => 500
        ]);

        $result = $this->calculator->calculerDeductions($employe->id, $janvier);
        $this->assertEquals(1000, $result['manquants']);

        // Test avec décembre (mois 12)
        $decembre = Carbon::create(2024, 12, 1);
        ACouper::factory()->create([
            'id_employe' => $employe->id,
            'date' => $decembre->copy()->day(15),
            'manquants' => 5000,
            'remboursement' => 6000,
            'pret' => 7000,
            'caisse_sociale' => 8000
        ]);

        $result = $this->calculator->calculerDeductions($employe->id, $decembre);
        $this->assertEquals(5000, $result['manquants']);
    }
}
