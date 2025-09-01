<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\MaterialUsageService;
use App\Models\Matiere;
use App\Models\MatiereRecommander;
use App\Models\Utilisation;
use App\Models\Produit_fixes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MaterialUsageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MaterialUsageService();
    }

    /** @test */
    public function it_can_collect_material_usage_data_successfully()
    {
        // Arrange - Créer des données de test
        $this->seedTestData();

        // Act
        $result = $this->service->collectMaterialUsageData();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('material_usage_comparison', $result);
        $this->assertArrayHasKey('stock_levels', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

  
    /** @test */
    public function it_compares_material_usage_correctly_with_significant_difference()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create([
            'nom' => 'Produit Test',
            'prix' => 1000,
            'categorie' => 'Test'
        ]);

        $matiere = Matiere::factory()->create([
            'nom' => 'Matiere Test',
            'unite_minimale' => 'kg',
            'unite_classique' => 'tonne',
            'quantite_par_unite' => 1000.000,
            'quantite' => 100.00,
            'prix_unitaire' => 50.00,
            'prix_par_unite_minimale' => 0.0500
        ]);

        $user = User::factory()->create();

        // Recommandation : 2 kg de matière pour 10 unités de produit (ratio = 0.2)
        MatiereRecommander::factory()->create([
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'quantitep' => 10,
            'quantite' => 2.000,
            'unite' => 'kg'
        ]);

        // Utilisation réelle : 3 kg pour 10 unités (ratio = 0.3) - 50% de plus
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 3.000,
            'unite_matiere' => 'kg',
            'created_at' => now()->subMonth()
        ]);

        // Act
        $result = $this->service->collectMaterialUsageData();

        // Assert
        $comparison = $result['material_usage_comparison'];
        $this->assertNotEmpty($comparison);
        $this->assertEquals($produit->code_produit, $comparison[0]['produit_id']);
        $this->assertEquals('Produit Test', $comparison[0]['produit_nom']);
        $this->assertEquals(0.2, $comparison[0]['ratio_recommande']);
        $this->assertEquals(0.3, $comparison[0]['ratio_reel']);
        $this->assertGreaterThan(0, $comparison[0]['impact_financier']);
    }

    /** @test */
    public function it_excludes_differences_below_5_percent()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create([
            'prix_par_unite_minimale' => 0.0500
        ]);
        $user = User::factory()->create();

        // Recommandation : 2 kg pour 10 unités (ratio = 0.2)
        MatiereRecommander::factory()->create([
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'quantitep' => 10,
            'quantite' => 2.000,
            'unite' => 'kg'
        ]);

        // Utilisation réelle : 2.08 kg pour 10 unités (ratio = 0.208) - 4% de plus (< 5%)
        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 2.080,
            'unite_matiere' => 'kg',
            'created_at' => now()->subMonth()
        ]);

        // Act
        $result = $this->service->collectMaterialUsageData();

        // Assert
        $this->assertEmpty($result['material_usage_comparison']);
    }

    /** @test */
    public function it_only_considers_data_from_last_3_months()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();
        $user = User::factory()->create();

        MatiereRecommander::factory()->create([
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'quantitep' => 10,
            'quantite' => 2.000,
            'unite' => 'kg'
        ]);

        // Utilisation ancienne (> 3 mois) - ne doit pas être prise en compte
        Utilisation::factory()->create([
            'id_lot' => 'LOT_OLD',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 5.000,
            'unite_matiere' => 'kg',
            'created_at' => now()->subMonths(4)
        ]);

        // Act
        $result = $this->service->collectMaterialUsageData();

        // Assert
        $this->assertEmpty($result['material_usage_comparison']);
    }

    /** @test */
    public function it_returns_stock_levels_correctly()
    {
        // Arrange
        $matiere1 = Matiere::factory()->create([
            'nom' => 'Farine',
            'quantite' => 50.00,
            'prix_unitaire' => 25.00
        ]);

        $matiere2 = Matiere::factory()->create([
            'nom' => 'Sucre',
            'quantite' => 30.00,
            'prix_unitaire' => 40.00
        ]);

        // Act
        $result = $this->service->collectMaterialUsageData();
        $stockLevels = $result['stock_levels'];
        $this->assertCount(3, $stockLevels);
        
        $farine = $stockLevels->firstWhere('nom', 'Farine');
        $this->assertNotNull($farine);
        $this->assertEquals(50.00, $farine->quantite);
        $this->assertEquals(25.00, $farine->prix_unitaire);

        $sucre = $stockLevels->firstWhere('nom', 'Sucre');
        $this->assertNotNull($sucre);
        $this->assertEquals(30.00, $sucre->quantite);
        $this->assertEquals(40.00, $sucre->prix_unitaire);
    }

    /** @test */
    public function it_handles_empty_recommendations()
    {
        // Arrange - Pas de recommandations dans la base

        // Act
        $result = $this->service->collectMaterialUsageData();

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result['material_usage_comparison']);
        $this->assertArrayHasKey('stock_levels', $result);
    }

    /** @test */
    public function it_handles_multiple_utilisations_for_same_product_material()
    {
        // Arrange
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create([
            'prix_par_unite_minimale' => 0.0500
        ]);
        $user = User::factory()->create();

        MatiereRecommander::factory()->create([
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'quantitep' => 10,
            'quantite' => 2.000,
            'unite' => 'kg'
        ]);

        // Plusieurs utilisations qui doivent être agrégées
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 5.00,
            'quantite_matiere' => 1.500,
            'unite_matiere' => 'kg',
            'created_at' => now()->subMonth()
        ]);

        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 5.00,
            'quantite_matiere' => 1.500,
            'unite_matiere' => 'kg',
            'created_at' => now()->subWeek()
        ]);

        // Total: 10 unités produit, 3 kg matière (ratio = 0.3 vs 0.2 recommandé)

        // Act
        $result = $this->service->collectMaterialUsageData();

        // Assert
        $comparison = $result['material_usage_comparison'];
        $this->assertNotEmpty($comparison);
        $this->assertEquals(0.2, $comparison[0]['ratio_recommande']);
        $this->assertEquals(0.3, $comparison[0]['ratio_reel']);
        $this->assertEquals(10.00, $comparison[0]['quantite_produit_periode']);
    }

    private function seedTestData()
    {
        // Créer un produit de test
        $produit = Produit_fixes::factory()->create([
            'nom' => 'Pain',
            'prix' => 500,
            'categorie' => 'Boulangerie'
        ]);

        // Créer une matière
        $matiere = Matiere::factory()->create([
            'nom' => 'Farine de blé',
            'unite_minimale' => 'kg',
            'unite_classique' => 'sac',
            'quantite_par_unite' => 25.000,
            'quantite' => 100.00,
            'prix_unitaire' => 30.00,
            'prix_par_unite_minimale' => 1.2000
        ]);

        // Créer un utilisateur
        $user = User::factory()->create([
            'name' => 'Boulanger Test',
            'role' => 'producteur'
        ]);

        // Créer une recommandation
        MatiereRecommander::factory()->create([
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'quantitep' => 1,
            'quantite' => 0.500,
            'unite' => 'kg'
        ]);

        // Créer une utilisation récente
        Utilisation::factory()->create([
            'id_lot' => 'LOT12345',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 6.000,
            'unite_matiere' => 'kg',
            'created_at' => now()->subWeeks(2)
        ]);
    }
}
