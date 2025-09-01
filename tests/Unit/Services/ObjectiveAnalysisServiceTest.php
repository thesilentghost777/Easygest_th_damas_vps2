<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ObjectiveAnalysisService;
use App\Models\Objective;
use App\Models\ObjectiveProgress;
use App\Models\SubObjective;
use App\Models\Utilisation;
use App\Models\TransactionVente;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ObjectiveAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ObjectiveAnalysisService();
    }

    /** @test */
    public function it_can_collect_objective_data_successfully()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        $this->seedTestData($month, $year);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_objectives', $result);
        $this->assertArrayHasKey('achieved_objectives', $result);
        $this->assertArrayHasKey('objectives_analysis', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

   
    /** @test */
    public function it_filters_objectives_by_sector_and_date_range()
    {
        // Arrange
        $user = User::factory()->create();
        $month = 6;
        $year = 2025;
        
        // Objectif dans le secteur boulangerie-patisserie (doit être inclus)
        $objectifInclus = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30),
            'title' => 'Objectif Boulangerie',
            'target_amount' => 100000
        ]);

        // Objectif dans un autre secteur (doit être exclu)
        Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'alimentation',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30),
            'title' => 'Objectif Alimentation'
        ]);

        // Objectif hors période (doit être exclu)
        Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month + 2, 1),
            'end_date' => Carbon::create($year, $month + 2, 30),
            'title' => 'Objectif Futur'
        ]);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $this->assertEquals(1, $result['total_objectives']);
        $this->assertCount(1, $result['objectives_analysis']);
        $this->assertEquals('Objectif Boulangerie', $result['objectives_analysis'][0]['objectif']['titre']);
    }

   

    /** @test */
    public function it_includes_sub_objectives_analysis()
    {
        // Arrange
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create(['nom' => 'Pain']);
        $month = 6;
        $year = 2025;

        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30),
            'title' => 'Objectif Principal'
        ]);

        // Sous-objectif atteint
        SubObjective::factory()->create([
            'objective_id' => $objectif->id,
            'product_id' => $produit->code_produit,
            'title' => 'Vente Pain',
            'target_amount' => 50000,
            'current_amount' => 55000,
            'progress_percentage' => 110.00
        ]);

        // Sous-objectif problématique
        SubObjective::factory()->create([
            'objective_id' => $objectif->id,
            'product_id' => $produit->code_produit,
            'title' => 'Vente Croissant',
            'target_amount' => 30000,
            'current_amount' => 15000,
            'progress_percentage' => 50.00
        ]);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $analyse = $result['objectives_analysis'][0];
        $this->assertArrayHasKey('sous_objectifs', $analyse);
        $this->assertCount(2, $analyse['sous_objectifs']);
        
        $sousObjectifs = collect($analyse['sous_objectifs']);
        $painSousObjectif = $sousObjectifs->firstWhere('titre', 'Vente Pain');
        $this->assertEquals(110.00, $painSousObjectif['progression']);
        
        $croissantSousObjectif = $sousObjectifs->firstWhere('titre', 'Vente Croissant');
        $this->assertEquals(50.00, $croissantSousObjectif['progression']);
    }

    /** @test */
    public function it_analyzes_problematic_sub_objectives()
    {
        // Arrange
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create(['nom' => 'Croissant']);
        $month = 6;
        $year = 2025;

        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30)
        ]);

        // Sous-objectif avec progression < 80%
        SubObjective::factory()->create([
            'objective_id' => $objectif->id,
            'product_id' => $produit->code_produit,
            'title' => 'Vente Croissant Faible',
            'target_amount' => 40000,
            'current_amount' => 20000,
            'progress_percentage' => 50.00
        ]);

        // Ajouter des ventes réelles pour ce produit
        TransactionVente::factory()->create([
            'produit' => $produit->code_produit,
            'serveur' => $user->id,
            'quantite' => 100,
            'prix' => 200,
            'date_vente' => Carbon::create($year, $month, 15),
            'type' => 'vente'
        ]);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $analyse = $result['objectives_analysis'][0]['analyse'];
        $this->assertArrayHasKey('sous_objectifs_problematiques', $analyse);
        $this->assertCount(1, $analyse['sous_objectifs_problematiques']);
        
        $problematique = $analyse['sous_objectifs_problematiques'][0];
        $this->assertEquals('Vente Croissant Faible', $problematique['titre']);
        $this->assertEquals(50.00, $problematique['progression']);
        $this->assertArrayHasKey('ventes_reelles', $problematique);
    }

    /** @test */
    public function it_calculates_production_vs_sales_ratio()
    {
        // Arrange
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();
        $month = 6;
        $year = 2025;

        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30)
        ]);

        // Production: 100 unités
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 100.00,
            'quantite_matiere' => 50.000,
            'unite_matiere' => 'kg',
            'created_at' => Carbon::create($year, $month, 15)
        ]);

        // Ventes: 70 unités (70% de ratio)
        TransactionVente::factory()->create([
            'produit' => $produit->code_produit,
            'serveur' => $user->id,
            'quantite' => 70,
            'prix' => 500,
            'date_vente' => Carbon::create($year, $month, 20),
            'type' => 'vente'
        ]);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $analyse = $result['objectives_analysis'][0]['analyse'];
        $this->assertArrayHasKey('ratio_ventes_production', $analyse);
        $this->assertStringContainsString('70%', $analyse['ratio_ventes_production']);
        $this->assertArrayHasKey('probleme_ecoulement', $analyse);
    }

    /** @test */
    public function it_detects_insufficient_production()
    {
        // Arrange
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();
        $month = 6;
        $year = 2025;

        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30)
        ]);

        // Production: 100 unités
        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 100.00,
            'quantite_matiere' => 50.000,
            'unite_matiere' => 'kg',
            'created_at' => Carbon::create($year, $month, 15)
        ]);

        // Ventes: 99 unités (99% de ratio - presque tout vendu)
        TransactionVente::factory()->create([
            'produit' => $produit->code_produit,
            'serveur' => $user->id,
            'quantite' => 99,
            'prix' => 500,
            'date_vente' => Carbon::create($year, $month, 20),
            'type' => 'vente'
        ]);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $analyse = $result['objectives_analysis'][0]['analyse'];
        $this->assertArrayHasKey('ratio_ventes_production', $analyse);
        $this->assertStringContainsString('99%', $analyse['ratio_ventes_production']);
        $this->assertArrayHasKey('production_insuffisante', $analyse);
    }

    /** @test */
    public function it_handles_objectives_with_no_sub_objectives()
    {
        // Arrange
        $user = User::factory()->create();
        $month = 6;
        $year = 2025;

        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30),
            'title' => 'Objectif Sans Sous-Objectifs'
        ]);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $analyse = $result['objectives_analysis'][0];
        $this->assertEmpty($analyse['sous_objectifs']);
        $this->assertArrayNotHasKey('sous_objectifs_problematiques', $analyse['analyse']);
    }

    /** @test */
    public function it_handles_objectives_spanning_multiple_months()
    {
        // Arrange
        $user = User::factory()->create();
        $month = 6;
        $year = 2025;

        // Objectif qui commence avant et finit après le mois demandé
        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month - 1, 15), // Mois précédent
            'end_date' => Carbon::create($year, $month + 1, 15),   // Mois suivant
            'title' => 'Objectif Multi-Mois'
        ]);

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $this->assertEquals(1, $result['total_objectives']);
        $this->assertEquals('Objectif Multi-Mois', $result['objectives_analysis'][0]['objectif']['titre']);
    }

    /** @test */
    public function it_handles_empty_production_and_sales_data()
    {
        // Arrange
        $user = User::factory()->create();
        $month = 6;
        $year = 2025;

        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30)
        ]);

        // Pas de production ni de ventes

        // Act
        $result = $this->service->collectObjectiveData($month, $year);

        // Assert
        $analyse = $result['objectives_analysis'][0]['analyse'];
        $this->assertArrayNotHasKey('ratio_ventes_production', $analyse);
        $this->assertArrayNotHasKey('probleme_ecoulement', $analyse);
        $this->assertArrayNotHasKey('production_insuffisante', $analyse);
    }

    private function seedTestData($month, $year)
    {
        $user = User::factory()->create([
            'name' => 'Directeur Test',
            'role' => 'directeur'
        ]);

        $produit = Produit_fixes::factory()->create([
            'nom' => 'Pain de mie',
            'prix' => 300,
            'categorie' => 'Boulangerie'
        ]);

        $objectif = Objective::factory()->create([
            'user_id' => $user->id,
            'title' => 'Objectif Mensuel Juin',
            'description' => 'Objectif de chiffre d\'affaires pour juin',
            'target_amount' => 500000,
            'sector' => 'boulangerie-patisserie',
            'start_date' => Carbon::create($year, $month, 1),
            'end_date' => Carbon::create($year, $month, 30),
            'is_achieved' => true
        ]);

        SubObjective::factory()->create([
            'objective_id' => $objectif->id,
            'product_id' => $produit->code_produit,
            'title' => 'Vente Pain de Mie',
            'target_amount' => 200000,
            'current_amount' => 220000,
            'progress_percentage' => 110.00
        ]);
    }
}
