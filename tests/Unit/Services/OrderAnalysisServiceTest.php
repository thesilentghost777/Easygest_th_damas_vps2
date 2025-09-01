<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\OrderAnalysisService;
use App\Models\Commande;
use App\Models\Produit_fixes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OrderAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    private $orderAnalysisService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderAnalysisService = new OrderAnalysisService();
        
        // Désactiver les logs pendant les tests
        Log::shouldReceive('info')->andReturn(null);
        Log::shouldReceive('error')->andReturn(null);
    }

    /** @test */
    public function it_collects_order_data_successfully_for_given_month_and_year()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create([
            'nom' => 'Produit Test',
            'prix' => 1000,
            'categorie' => 'Electronique'
        ]);

        $month = 6;
        $year = 2025;
        $dateCommande = Carbon::create($year, $month, 15);

        // Créer des commandes de test
        Commande::factory()->create([
            'libelle' => 'Commande 1',
            'date_commande' => $dateCommande,
            'produit' => $produit->code_produit,
            'quantite' => 2,
            'categorie' => 'Electronique',
            'valider' => true
        ]);

        Commande::factory()->create([
            'libelle' => 'Commande 2',
            'date_commande' => $dateCommande,
            'produit' => $produit->code_produit,
            'quantite' => 1,
            'categorie' => 'Electronique',
            'valider' => false
        ]);

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('stats', $result);
        $this->assertArrayHasKey('commandes_par_categorie', $result);
        $this->assertArrayHasKey('tendances', $result);
        $this->assertArrayHasKey('commandes_recentes', $result);

        // Vérifier les statistiques
        $this->assertEquals(2, $result['stats']['total']);
        $this->assertEquals(1, $result['stats']['validees']);
        $this->assertEquals(1, $result['stats']['en_attente']);
    }

    /** @test */
    public function it_returns_correct_stats_when_no_orders_exist()
    {
        // Arrange
        $month = 6;
        $year = 2025;

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(0, $result['stats']['total']);
        $this->assertEquals(0, $result['stats']['validees']);
        $this->assertEquals(0, $result['stats']['en_attente']);
        $this->assertEmpty($result['commandes_par_categorie']);
        $this->assertEmpty($result['commandes_recentes']);
    }

    /** @test */
    public function it_groups_orders_by_category_correctly()
    {
        // Arrange
        $produit1 = Produit_fixes::factory()->create(['categorie' => 'Electronique']);
        $produit2 = Produit_fixes::factory()->create(['categorie' => 'Alimentaire']);

        $month = 6;
        $year = 2025;
        $dateCommande = Carbon::create($year, $month, 15);

        // Créer des commandes dans différentes catégories
        Commande::factory()->create([
            'date_commande' => $dateCommande,
            'produit' => $produit1->code_produit,
            'categorie' => 'Electronique',
            'valider' => true
        ]);

        Commande::factory()->create([
            'date_commande' => $dateCommande,
            'produit' => $produit1->code_produit,
            'categorie' => 'Electronique',
            'valider' => false
        ]);

        Commande::factory()->create([
            'date_commande' => $dateCommande,
            'produit' => $produit2->code_produit,
            'categorie' => 'Alimentaire',
            'valider' => true
        ]);

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $this->assertCount(2, $result['commandes_par_categorie']);
        
        $electroniqueCategory = collect($result['commandes_par_categorie'])
            ->where('categorie', 'Electronique')
            ->first();
        
        $this->assertEquals(2, $electroniqueCategory['nombre']);
        $this->assertEquals(1, $electroniqueCategory['validees']);
        $this->assertEquals(1, $electroniqueCategory['en_attente']);

        $alimentaireCategory = collect($result['commandes_par_categorie'])
            ->where('categorie', 'Alimentaire')
            ->first();
        
        $this->assertEquals(1, $alimentaireCategory['nombre']);
        $this->assertEquals(1, $alimentaireCategory['validees']);
        $this->assertEquals(0, $alimentaireCategory['en_attente']);
    }

    /** @test */
    public function it_filters_orders_by_correct_date_range()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create();
        
        $month = 6;
        $year = 2025;

        // Commandes dans le mois ciblé
        Commande::factory()->create([
            'date_commande' => Carbon::create($year, $month, 1),
            'produit' => $produit->code_produit
        ]);

        Commande::factory()->create([
            'date_commande' => Carbon::create($year, $month, 30),
            'produit' => $produit->code_produit
        ]);

        // Commandes hors du mois ciblé (ne doivent pas être incluses)
        Commande::factory()->create([
            'date_commande' => Carbon::create($year, $month - 1, 15),
            'produit' => $produit->code_produit
        ]);

        Commande::factory()->create([
            'date_commande' => Carbon::create($year, $month + 1, 15),
            'produit' => $produit->code_produit
        ]);

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $this->assertEquals(2, $result['stats']['total']);
        $this->assertCount(2, $result['commandes_recentes']);
    }

    /** @test */
    public function it_limits_recent_orders_to_20_items()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create();
        $month = 6;
        $year = 2025;
        $dateCommande = Carbon::create($year, $month, 15);

        // Créer plus de 20 commandes
        for ($i = 1; $i <= 25; $i++) {
            Commande::factory()->create([
                'date_commande' => $dateCommande,
                'produit' => $produit->code_produit
            ]);
        }

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $this->assertEquals(25, $result['stats']['total']);
        $this->assertCount(20, $result['commandes_recentes']);
    }

    /** @test */
    public function it_includes_product_information_in_recent_orders()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create([
            'nom' => 'Produit Spécial'
        ]);

        $month = 6;
        $year = 2025;
        $dateCommande = Carbon::create($year, $month, 15);

        $commande = Commande::factory()->create([
            'libelle' => 'Test Commande',
            'date_commande' => $dateCommande,
            'produit' => $produit->code_produit,
            'quantite' => 3,
            'categorie' => 'Test Category',
            'valider' => true
        ]);

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $recentOrder = $result['commandes_recentes'][0];
        $this->assertEquals($commande->id, $recentOrder['id']);
        $this->assertEquals('Test Commande', $recentOrder['libelle']);
        $this->assertEquals('Produit Spécial', $recentOrder['produit']);
        $this->assertEquals(3, $recentOrder['quantite']);
        $this->assertEquals('Test Category', $recentOrder['categorie']);
        $this->assertTrue($recentOrder['valider']);
    }

    /** @test */
    public function it_handles_orders_without_product_relation()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        $dateCommande = Carbon::create($year, $month, 15);

        // Créer une commande sans produit associé
        Commande::factory()->create([
            'libelle' => 'Commande sans produit',
            'date_commande' => $dateCommande,
            'produit' => null,
            'quantite' => 1,
            'categorie' => 'Divers',
            'valider' => false
        ]);

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $this->assertEquals(1, $result['stats']['total']);
        $recentOrder = $result['commandes_recentes'][0];
        $this->assertEquals('N/A', $recentOrder['produit']);
    }



    /** @test */
    public function it_returns_trends_data_structure()
    {
        // Arrange
        $month = 6;
        $year = 2025;

        // Act
        $result = $this->orderAnalysisService->collectOrderData($month, $year);

        // Assert
        $this->assertIsArray($result['tendances']);
        $this->assertCount(6, $result['tendances']);
        
        foreach ($result['tendances'] as $trend) {
            $this->assertArrayHasKey('mois', $trend);
            $this->assertArrayHasKey('total', $trend);
            $this->assertArrayHasKey('validees', $trend);
            $this->assertArrayHasKey('en_attente', $trend);
        }
    }
}
