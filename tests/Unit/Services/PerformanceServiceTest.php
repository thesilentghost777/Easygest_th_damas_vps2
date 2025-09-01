<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PerformanceService;
use App\Models\Utilisation;
use App\Models\User;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class PerformanceServiceTest extends TestCase
{
    use RefreshDatabase;

    private $performanceService;
    private $user;
    private $produit;
    private $matiere1;
    private $matiere2;
    private $matiere3;

    protected function setUp(): void
    {
        parent::setUp();
        $this->performanceService = new PerformanceService();
        
        // Créer les données de base pour les tests
        $this->user = User::factory()->create();
        $this->produit = Produit_fixes::factory()->create();
        
        // Créer plusieurs matières pour simuler des productions complexes
        $this->matiere1 = Matiere::factory()->create(['nom' => 'Farine']);
        $this->matiere2 = Matiere::factory()->create(['nom' => 'Sucre']);
        $this->matiere3 = Matiere::factory()->create(['nom' => 'Levure']);
    }

    /** @test */
    public function it_calculates_monthly_performance_with_single_material_productions()
    {
        // Arrange
        $employeId = $this->user->id;
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Production avec une seule matière - mois actuel
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'PROD001',
            'quantite_produit' => 100.50,
            'quantite_matiere' => 10.5,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Autre production avec une seule matière - mois actuel
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id,
            'id_lot' => 'PROD002',
            'quantite_produit' => 200.75,
            'quantite_matiere' => 20.5,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Production mois précédent
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'PROD003',
            'quantite_produit' => 150.25,
            'quantite_matiere' => 15.0,
            'unite_matiere' => 'kg',
            'created_at' => $lastMonth
        ]);

        // Act
        $result = $this->performanceService->calculateMonthlyPerformance($employeId);

        // Assert
        $this->assertEquals(2, $result['current_month']['total_lots']);
        $this->assertEquals(301.25, $result['current_month']['total_quantity']);
        $this->assertEquals(150.625, $result['current_month']['average_per_lot']);

        $this->assertEquals(1, $result['last_month']['total_lots']);
        $this->assertEquals(150.25, $result['last_month']['total_quantity']);
        $this->assertEquals(150.25, $result['last_month']['average_per_lot']);
    }

    /** @test */
    public function it_handles_production_with_multiple_materials_correctly()
    {
        // Arrange
        $employeId = $this->user->id;
        $currentMonth = Carbon::now();

        // PROD001: Production avec 3 matières différentes
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id, // Farine
            'id_lot' => 'PROD001',
            'quantite_produit' => 500.00, // Même quantité produite
            'quantite_matiere' => 50.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id, // Sucre
            'id_lot' => 'PROD001',
            'quantite_produit' => 500.00, // Même quantité produite
            'quantite_matiere' => 25.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere3->id, // Levure
            'id_lot' => 'PROD001',
            'quantite_produit' => 500.00, // Même quantité produite
            'quantite_matiere' => 2.5,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // PROD002: Production avec 2 matières
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id, // Farine
            'id_lot' => 'PROD002',
            'quantite_produit' => 300.00,
            'quantite_matiere' => 30.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id, // Sucre
            'id_lot' => 'PROD002',
            'quantite_produit' => 300.00,
            'quantite_matiere' => 15.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Act
        $result = $this->performanceService->calculateMonthlyPerformance($employeId);

        // Assert
        // Doit compter 2 productions distinctes (PROD001 et PROD002)
        $this->assertEquals(2, $result['current_month']['total_lots']);
        
        // Total : 500 (PROD001) + 300 (PROD002) = 800
        $this->assertEquals(800.00, $result['current_month']['total_quantity']);
        
        // Moyenne : (500 + 300) / 2 = 400
        $this->assertEquals(400.00, $result['current_month']['average_per_lot']);
    }

    /** @test */
    public function it_correctly_picks_first_entry_quantity_for_multi_material_production()
    {
        // Arrange
        $employeId = $this->user->id;
        $currentMonth = Carbon::now();

        // PROD001: Production avec 3 matières et quantités différentes par entrée
        // (pour simuler des erreurs potentielles de saisie)
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'PROD001',
            'quantite_produit' => 450.00, // Première entrée - doit être utilisée
            'quantite_matiere' => 45.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id,
            'id_lot' => 'PROD001',
            'quantite_produit' => 500.00, // Deuxième entrée - ne doit pas être utilisée
            'quantite_matiere' => 25.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere3->id,
            'id_lot' => 'PROD001',
            'quantite_produit' => 480.00, // Troisième entrée - ne doit pas être utilisée
            'quantite_matiere' => 2.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Act
        $result = $this->performanceService->calculateMonthlyPerformance($employeId);

        // Assert
        $this->assertEquals(1, $result['current_month']['total_lots']);
        // Doit utiliser la quantité de la première entrée (450.00)
        $this->assertEquals(450.00, $result['current_month']['total_quantity']);
        $this->assertEquals(450.00, $result['current_month']['average_per_lot']);
    }

    /** @test */
    public function it_handles_complex_scenario_with_mixed_production_types()
    {
        // Arrange
        $employeId = $this->user->id;
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // MOIS ACTUEL
        // Production simple (1 matière)
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'SIMPLE001',
            'quantite_produit' => 200.00,
            'quantite_matiere' => 20.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Production complexe (3 matières)
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'COMPLEX001',
            'quantite_produit' => 600.00,
            'quantite_matiere' => 60.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id,
            'id_lot' => 'COMPLEX001',
            'quantite_produit' => 600.00,
            'quantite_matiere' => 30.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere3->id,
            'id_lot' => 'COMPLEX001',
            'quantite_produit' => 600.00,
            'quantite_matiere' => 3.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Production moyenne (2 matières)
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'MEDIUM001',
            'quantite_produit' => 400.00,
            'quantite_matiere' => 40.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id,
            'id_lot' => 'MEDIUM001',
            'quantite_produit' => 400.00,
            'quantite_matiere' => 20.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // MOIS PRÉCÉDENT
        // Production simple
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'PREV001',
            'quantite_produit' => 300.00,
            'quantite_matiere' => 30.0,
            'unite_matiere' => 'kg',
            'created_at' => $lastMonth
        ]);

        // Production complexe (2 matières)
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'PREV002',
            'quantite_produit' => 500.00,
            'quantite_matiere' => 50.0,
            'unite_matiere' => 'kg',
            'created_at' => $lastMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id,
            'id_lot' => 'PREV002',
            'quantite_produit' => 500.00,
            'quantite_matiere' => 25.0,
            'unite_matiere' => 'kg',
            'created_at' => $lastMonth
        ]);

        // Act
        $result = $this->performanceService->calculateMonthlyPerformance($employeId);

        // Assert
        // Mois actuel : 3 productions (SIMPLE001, COMPLEX001, MEDIUM001)
        $this->assertEquals(3, $result['current_month']['total_lots']);
        $this->assertEquals(1200.00, $result['current_month']['total_quantity']); // 200 + 600 + 400
        $this->assertEquals(400.00, $result['current_month']['average_per_lot']); // 1200 / 3

        // Mois précédent : 2 productions (PREV001, PREV002)
        $this->assertEquals(2, $result['last_month']['total_lots']);
        $this->assertEquals(800.00, $result['last_month']['total_quantity']); // 300 + 500
        $this->assertEquals(400.00, $result['last_month']['average_per_lot']); // 800 / 2

        // Évolution
        $this->assertEquals(50.0, $result['evolution']['lots']); // (3-2)/2 * 100 = 50%
        $this->assertEquals(50.0, $result['evolution']['quantity']); // (1200-800)/800 * 100 = 50%
        $this->assertEquals(0.0, $result['evolution']['efficiency']); // (400-400)/400 * 100 = 0%
    }

    /** @test */
    public function it_handles_productions_with_different_materials_combinations()
    {
        // Arrange
        $employeId = $this->user->id;
        $currentMonth = Carbon::now();

        // Production A: Farine + Sucre
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id, // Farine
            'id_lot' => 'PROD_A',
            'quantite_produit' => 300.00,
            'quantite_matiere' => 30.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id, // Sucre
            'id_lot' => 'PROD_A',
            'quantite_produit' => 300.00,
            'quantite_matiere' => 15.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Production B: Farine + Levure
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id, // Farine
            'id_lot' => 'PROD_B',
            'quantite_produit' => 250.00,
            'quantite_matiere' => 25.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere3->id, // Levure
            'id_lot' => 'PROD_B',
            'quantite_produit' => 250.00,
            'quantite_matiere' => 1.5,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Production C: Sucre + Levure
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id, // Sucre
            'id_lot' => 'PROD_C',
            'quantite_produit' => 180.00,
            'quantite_matiere' => 18.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere3->id, // Levure
            'id_lot' => 'PROD_C',
            'quantite_produit' => 180.00,
            'quantite_matiere' => 1.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Production D: Les 3 matières
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id, // Farine
            'id_lot' => 'PROD_D',
            'quantite_produit' => 450.00,
            'quantite_matiere' => 45.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id, // Sucre
            'id_lot' => 'PROD_D',
            'quantite_produit' => 450.00,
            'quantite_matiere' => 22.5,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere3->id, // Levure
            'id_lot' => 'PROD_D',
            'quantite_produit' => 450.00,
            'quantite_matiere' => 2.25,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Act
        $result = $this->performanceService->calculateMonthlyPerformance($employeId);

        // Assert
        $this->assertEquals(4, $result['current_month']['total_lots']);
        $this->assertEquals(1180.00, $result['current_month']['total_quantity']); // 300 + 250 + 180 + 450
        $this->assertEquals(295.00, $result['current_month']['average_per_lot']); // 1180 / 4
    }

    /** @test */
    public function it_correctly_handles_same_lot_id_with_different_employees()
    {
        // Arrange
        $employee1 = $this->user;
        $employee2 = User::factory()->create();
        $currentMonth = Carbon::now();

        // Même id_lot mais employés différents - doit les traiter séparément
        Utilisation::factory()->create([
            'producteur' => $employee1->id,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'SHARED_LOT',
            'quantite_produit' => 300.00,
            'quantite_matiere' => 30.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employee2->id,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'SHARED_LOT',
            'quantite_produit' => 500.00,
            'quantite_matiere' => 50.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Act
        $result1 = $this->performanceService->calculateMonthlyPerformance($employee1->id);
        $result2 = $this->performanceService->calculateMonthlyPerformance($employee2->id);

        // Assert
        $this->assertEquals(1, $result1['current_month']['total_lots']);
        $this->assertEquals(300.00, $result1['current_month']['total_quantity']);

        $this->assertEquals(1, $result2['current_month']['total_lots']);
        $this->assertEquals(500.00, $result2['current_month']['total_quantity']);
    }

    /** @test */
    public function it_handles_edge_case_with_zero_quantity_production()
    {
        // Arrange
        $employeId = $this->user->id;
        $currentMonth = Carbon::now();

        // Production avec quantité zéro
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'ZERO_PROD',
            'quantite_produit' => 0.00,
            'quantite_matiere' => 10.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Production normale
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'NORMAL_PROD',
            'quantite_produit' => 200.00,
            'quantite_matiere' => 20.0,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Act
        $result = $this->performanceService->calculateMonthlyPerformance($employeId);

        // Assert
        $this->assertEquals(2, $result['current_month']['total_lots']);
        $this->assertEquals(200.00, $result['current_month']['total_quantity']); // 0 + 200
        $this->assertEquals(100.00, $result['current_month']['average_per_lot']); // 200 / 2
    }

    /** @test */
    public function it_maintains_consistency_across_multiple_calls()
    {
        // Arrange
        $employeId = $this->user->id;
        $currentMonth = Carbon::now();

        // Créer une production complexe
        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere1->id,
            'id_lot' => 'CONSISTENCY_TEST',
            'quantite_produit' => 350.75,
            'quantite_matiere' => 35.075,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        Utilisation::factory()->create([
            'producteur' => $employeId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere2->id,
            'id_lot' => 'CONSISTENCY_TEST',
            'quantite_produit' => 350.75,
            'quantite_matiere' => 17.5375,
            'unite_matiere' => 'kg',
            'created_at' => $currentMonth
        ]);

        // Act - Appeler plusieurs fois
        $result1 = $this->performanceService->calculateMonthlyPerformance($employeId);
        $result2 = $this->performanceService->calculateMonthlyPerformance($employeId);
        $result3 = $this->performanceService->calculateMonthlyPerformance($employeId);

        // Assert - Les résultats doivent être identiques
        $this->assertEquals($result1, $result2);
        $this->assertEquals($result2, $result3);
        $this->assertEquals(1, $result1['current_month']['total_lots']);
        $this->assertEquals(350.75, $result1['current_month']['total_quantity']);
    }
}
