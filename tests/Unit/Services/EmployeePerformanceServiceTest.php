<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\EmployeePerformanceService;
use App\Services\ProducteurComparisonService;
use App\Models\User;
use App\Models\TransactionVente;
use App\Models\Evaluation;
use App\Models\History;
use App\Models\Produit_fixes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class EmployeePerformanceServiceTest extends TestCase
{
    use RefreshDatabase;

    private EmployeePerformanceService $service;
    private $mockProducteurComparisonService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock du ProducteurComparisonService
        $this->mockProducteurComparisonService = $this->createMock(ProducteurComparisonService::class);
        $this->app->instance(ProducteurComparisonService::class, $this->mockProducteurComparisonService);
        
        $this->service = new EmployeePerformanceService();
    }

    /** @test */
    public function it_can_collect_employee_performance_data_successfully()
    {
        // Arrange
        $dateDebut = '2024-01-01';
        $dateFin = '2024-01-31';
        
        // Créer des données de test
        $this->createTestData();
        
        // Mock du service producteur
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willReturn(collect([
                ['id' => 1, 'nom' => 'Test', 'stats' => ['efficacite' => 1.5]]
            ]));

        // Act
        $result = $this->service->collectEmployeePerformanceData($dateDebut, $dateFin);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('vendeur_performance', $result);
        $this->assertArrayHasKey('producteur_performance', $result);
        $this->assertArrayHasKey('evaluations', $result);
        $this->assertArrayHasKey('cp_theft_suspicions', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

    /** @test */
    public function it_returns_error_when_exception_occurs()
    {
        // Arrange
        $dateDebut = '2024-01-01';
        $dateFin = '2024-01-31';
        
        // Mock pour provoquer une exception
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willThrowException(new \Exception('Test error'));

        // Act
        $result = $this->service->collectEmployeePerformanceData($dateDebut, $dateFin);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    /** @test */
    public function it_calculates_vendeur_performance_correctly()
    {
        // Arrange
        $dateDebut = '2024-01-01';
        $dateFin = '2024-01-31';
        
        // Créer des vendeurs
        $vendeur1 = User::factory()->create(['name' => 'Vendeur 1', 'role' => 'vendeur']);
        $vendeur2 = User::factory()->create(['name' => 'Vendeur 2', 'role' => 'vendeur']);
        
        // Créer des produits
        $produit1 = Produit_fixes::factory()->create(['prix' => 1000]);
        $produit2 = Produit_fixes::factory()->create(['prix' => 2000]);
        
        // Créer des ventes pour vendeur1 (meilleur performance)
        TransactionVente::factory()->create([
            'serveur' => $vendeur1->id,
            'produit' => $produit1->code_produit,
            'quantite' => 5,
            'prix' => 1000,
            'type' => 'Vente',
            'date_vente' => '2024-01-15'
        ]);
        
        TransactionVente::factory()->create([
            'serveur' => $vendeur1->id,
            'produit' => $produit2->code_produit,
            'quantite' => 3,
            'prix' => 2000,
            'type' => 'Vente',
            'date_vente' => '2024-01-20'
        ]);
        
        // Créer des ventes pour vendeur2 (moins bonne performance)
        TransactionVente::factory()->create([
            'serveur' => $vendeur2->id,
            'produit' => $produit1->code_produit,
            'quantite' => 2,
            'prix' => 1000,
            'type' => 'Vente',
            'date_vente' => '2024-01-10'
        ]);

        // Mock du service producteur
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willReturn(collect([]));

        // Act
        $result = $this->service->collectEmployeePerformanceData($dateDebut, $dateFin);

        // Assert
        $vendeurPerformance = $result['vendeur_performance'];
        $this->assertCount(2, $vendeurPerformance);
        
        // Vérifier que vendeur1 est premier (meilleur CA)
        $this->assertEquals($vendeur1->id, $vendeurPerformance[0]['id']);
        $this->assertEquals(11000, $vendeurPerformance[0]['chiffre_affaires']); // (5*1000) + (3*2000)
        $this->assertEquals(8, $vendeurPerformance[0]['quantite_totale']); // 5 + 3
        $this->assertEquals(2, $vendeurPerformance[0]['nombre_ventes']);
        $this->assertEquals(2, $vendeurPerformance[0]['diversite_produits']);
        
        // Vérifier que vendeur2 est deuxième
        $this->assertEquals($vendeur2->id, $vendeurPerformance[1]['id']);
        $this->assertEquals(2000, $vendeurPerformance[1]['chiffre_affaires']); // 2*1000
        $this->assertEquals(2, $vendeurPerformance[1]['quantite_totale']);
        $this->assertEquals(1, $vendeurPerformance[1]['nombre_ventes']);
        $this->assertEquals(1, $vendeurPerformance[1]['diversite_produits']);
        
        // Vérifier les scores
        $this->assertEquals(100, $vendeurPerformance[0]['scores']['ca']); // Meilleur CA = 100%
        $this->assertLessThan(100, $vendeurPerformance[1]['scores']['ca']); // Moins bon CA < 100%
    }

    /** @test */
    public function it_handles_empty_vendeur_data()
    {
        // Arrange
        $dateDebut = '2024-01-01';
        $dateFin = '2024-01-31';
        
        // Mock du service producteur
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willReturn(collect([]));

        // Act
        $result = $this->service->collectEmployeePerformanceData($dateDebut, $dateFin);

        // Assert
        $this->assertIsArray($result['vendeur_performance']);
        $this->assertEmpty($result['vendeur_performance']);
    }

    /** @test */
    public function it_retrieves_employee_evaluations_correctly()
    {
        // Arrange
        $user = User::factory()->create(['name' => 'Test User', 'role' => 'vendeur', 'secteur' => 'vente']);
        
        $evaluation = Evaluation::factory()->create([
            'user_id' => $user->id,
            'note' => 85,
            'appreciation' => 'Très bon travail',
            'created_at' => Carbon::now()
        ]);

        // Mock du service producteur
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willReturn(collect([]));

        // Act
        $result = $this->service->collectEmployeePerformanceData('2024-01-01', '2024-01-31');

        // Assert
        $evaluations = $result['evaluations'];
        $this->assertCount(1, $evaluations);
        $this->assertEquals($evaluation->id, $evaluations[0]['id']);
        $this->assertEquals(85, $evaluations[0]['note']);
        $this->assertEquals('Très bon travail', $evaluations[0]['appreciation']);
        $this->assertEquals($user->id, $evaluations[0]['user']['id']);
        $this->assertEquals('Test User', $evaluations[0]['user']['name']);
    }

    /** @test */
    public function it_retrieves_chef_production_theft_suspicions()
    {
        // Arrange
        $user = User::factory()->create(['name' => 'Chef Production', 'role' => 'chef_production']);
        
        $history = History::factory()->create([
            'action_type' => 'verificateur_vol_cp',
            'description' => 'Suspicion de vol détectée',
            'user_id' => $user->id,
            'created_at' => Carbon::now()
        ]);

        // Mock du service producteur
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willReturn(collect([]));

        // Act
        $result = $this->service->collectEmployeePerformanceData('2024-01-01', '2024-01-31');

        // Assert
        $suspicions = $result['cp_theft_suspicions'];
        $this->assertCount(1, $suspicions);
        $this->assertEquals($history->id, $suspicions[0]['id']);
        $this->assertEquals('Suspicion de vol détectée', $suspicions[0]['description']);
        $this->assertEquals($user->id, $suspicions[0]['user']['id']);
        $this->assertEquals('Chef Production', $suspicions[0]['user']['name']);
    }

    /** @test */
    public function it_filters_ventes_by_date_range()
    {
        // Arrange
        $vendeur = User::factory()->create(['role' => 'vendeur']);
        $produit = Produit_fixes::factory()->create(['prix' => 1000]);
        
        // Vente dans la période
        TransactionVente::factory()->create([
            'serveur' => $vendeur->id,
            'produit' => $produit->code_produit,
            'quantite' => 5,
            'prix' => 1000,
            'type' => 'Vente',
            'date_vente' => '2024-01-15'
        ]);
        
        // Vente hors période
        TransactionVente::factory()->create([
            'serveur' => $vendeur->id,
            'produit' => $produit->code_produit,
            'quantite' => 3,
            'prix' => 1000,
            'type' => 'Vente',
            'date_vente' => '2024-02-15'
        ]);

        // Mock du service producteur
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willReturn(collect([]));

        // Act
        $result = $this->service->collectEmployeePerformanceData('2024-01-01', '2024-01-31');

        // Assert
        $vendeurPerformance = $result['vendeur_performance'];
        $this->assertCount(1, $vendeurPerformance);
        $this->assertEquals(5000, $vendeurPerformance[0]['chiffre_affaires']); // Seulement la vente de janvier
        $this->assertEquals(5, $vendeurPerformance[0]['quantite_totale']);
    }

    /** @test */
    public function it_excludes_non_vente_transactions()
    {
        // Arrange
        $vendeur = User::factory()->create(['role' => 'vendeur']);
        $produit = Produit_fixes::factory()->create(['prix' => 1000]);
        
        // Transaction de type 'Vente'
        TransactionVente::factory()->create([
            'serveur' => $vendeur->id,
            'produit' => $produit->code_produit,
            'quantite' => 5,
            'prix' => 1000,
            'type' => 'Vente',
            'date_vente' => '2024-01-15'
        ]);
        
        // Transaction de type 'Remboursement' (ne doit pas être incluse)
        TransactionVente::factory()->create([
            'serveur' => $vendeur->id,
            'produit' => $produit->code_produit,
            'quantite' => 3,
            'prix' => 1000,
            'type' => 'Remboursement',
            'date_vente' => '2024-01-20'
        ]);

        // Mock du service producteur
        $this->mockProducteurComparisonService
            ->method('compareProducteurs')
            ->willReturn(collect([]));

        // Act
        $result = $this->service->collectEmployeePerformanceData('2024-01-01', '2024-01-31');

        // Assert
        $vendeurPerformance = $result['vendeur_performance'];
        $this->assertCount(1, $vendeurPerformance);
        $this->assertEquals(5000, $vendeurPerformance[0]['chiffre_affaires']); // Seulement la vente
        $this->assertEquals(1, $vendeurPerformance[0]['nombre_ventes']); // Une seule vente comptée
    }

 
    private function createTestData()
    {
        // Créer quelques utilisateurs de test
        User::factory()->create(['role' => 'vendeur']);
        Produit_fixes::factory()->create();
    }
}
