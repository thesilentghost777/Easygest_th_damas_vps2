<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\LotGeneratorService;
use App\Models\Utilisation;
use App\Models\User;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class LotGeneratorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LotGeneratorService();
        
        // Configuration de la base de données de test
        $this->artisan('migrate');
    }

    /** @test */
    public function it_generates_first_lot_id_of_the_day()
    {
        // Arrange - Aucun lot existant pour aujourd'hui
        $expectedDate = Carbon::now()->format('Ymd');
        $expectedLotId = $expectedDate . '-001';

        // Act
        $result = $this->service->generateLotId();

        // Assert
        $this->assertEquals($expectedLotId, $result);
        $this->assertStringStartsWith($expectedDate, $result);
        $this->assertStringEndsWith('-001', $result);
    }

    /** @test */
    public function it_increments_sequence_when_lots_exist_for_today()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        
        // Créer des données de test nécessaires
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();

        // Créer des lots existants pour aujourd'hui
        Utilisation::factory()->create([
            'id_lot' => $today . '-001',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        Utilisation::factory()->create([
            'id_lot' => $today . '-002',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 15.0,
            'quantite_matiere' => 8.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateLotId();

        // Assert
        $expectedLotId = $today . '-003';
        $this->assertEquals($expectedLotId, $result);
    }

    /** @test */
    public function it_handles_high_sequence_numbers()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();

        // Créer un lot avec un numéro de séquence élevé
        Utilisation::factory()->create([
            'id_lot' => $today . '-099',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateLotId();

        // Assert
        $expectedLotId = $today . '-100';
        $this->assertEquals($expectedLotId, $result);
    }

    /** @test */
    public function it_handles_very_high_sequence_numbers()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();

        // Créer un lot avec un numéro de séquence très élevé
        Utilisation::factory()->create([
            'id_lot' => $today . '-999',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateLotId();

        // Assert
        // Après 999, le service devrait générer 1000 (4 chiffres)
        $expectedLotId = $today . '-1000';
        $this->assertEquals($expectedLotId, $result);
    }

    /** @test */
    public function it_ignores_lots_from_other_days()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        $yesterday = Carbon::yesterday()->format('Ymd');
        $tomorrow = Carbon::tomorrow()->format('Ymd');
        
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();

        // Créer des lots d'hier et de demain
        Utilisation::factory()->create([
            'id_lot' => $yesterday . '-005',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        Utilisation::factory()->create([
            'id_lot' => $tomorrow . '-010',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateLotId();

        // Assert - Devrait commencer à 001 car aucun lot pour aujourd'hui
        $expectedLotId = $today . '-001';
        $this->assertEquals($expectedLotId, $result);
    }

    /** @test */
    public function it_finds_highest_sequence_when_multiple_lots_exist()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();

        // Créer plusieurs lots dans le désordre
        Utilisation::factory()->create([
            'id_lot' => $today . '-003',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        Utilisation::factory()->create([
            'id_lot' => $today . '-001',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 15.0,
            'quantite_matiere' => 8.0,
            'unite_matiere' => 'litre'
        ]);

        Utilisation::factory()->create([
            'id_lot' => $today . '-007',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 20.0,
            'quantite_matiere' => 10.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateLotId();

        // Assert - Devrait prendre le plus haut (007) et incrémenter
        $expectedLotId = $today . '-008';
        $this->assertEquals($expectedLotId, $result);
    }

    /** @test */
    public function it_generates_correct_format_for_lot_id()
    {
        // Act
        $result = $this->service->generateLotId();

        // Assert - Vérifier le format général
        $this->assertMatchesRegularExpression('/^\d{8}-\d{3,}$/', $result);
        
        // Vérifier la longueur minimale (AAAAMMJJ-XXX = 12 caractères minimum)
        $this->assertGreaterThanOrEqual(12, strlen($result));
        
        // Vérifier que la date est correcte
        $dateFromLot = substr($result, 0, 8);
        $expectedDate = Carbon::now()->format('Ymd');
        $this->assertEquals($expectedDate, $dateFromLot);
        
        // Vérifier le séparateur
        $this->assertEquals('-', $result[8]);
    }

    /** @test */
    public function it_pads_sequence_numbers_correctly()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();

        // Créer un lot avec séquence 5
        Utilisation::factory()->create([
            'id_lot' => $today . '-005',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateLotId();

        // Assert - Le numéro suivant (6) devrait être paddé en 006
        $expectedLotId = $today . '-006';
        $this->assertEquals($expectedLotId, $result);
        $this->assertStringEndsWith('-006', $result);
    }

    /** @test */
    public function it_handles_edge_case_with_different_date_formats_in_existing_lots()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();

        // Créer un lot avec un format différent qui ne devrait pas être pris en compte
        Utilisation::factory()->create([
            'id_lot' => 'LOT-CUSTOM-001',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Créer un lot valide
        Utilisation::factory()->create([
            'id_lot' => $today . '-003',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 15.0,
            'quantite_matiere' => 8.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateLotId();

        // Assert - Devrait ignorer le lot avec format différent et incrémenter à partir de 003
        $expectedLotId = $today . '-004';
        $this->assertEquals($expectedLotId, $result);
    }

    /** @test */
    public function it_generates_unique_lot_ids_in_succession()
    {
        // Act - Générer plusieurs IDs de suite
        $lotId1 = $this->service->generateLotId();
        
        // Simuler la création d'un lot avec le premier ID
        $user = User::factory()->create();
        $produit = Produit_fixes::factory()->create();
        $matiere = Matiere::factory()->create();
        
        Utilisation::factory()->create([
            'id_lot' => $lotId1,
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        $lotId2 = $this->service->generateLotId();
        
        Utilisation::factory()->create([
            'id_lot' => $lotId2,
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 15.0,
            'quantite_matiere' => 8.0,
            'unite_matiere' => 'litre'
        ]);

        $lotId3 = $this->service->generateLotId();

        // Assert - Chaque ID devrait être unique et séquentiel
        $this->assertNotEquals($lotId1, $lotId2);
        $this->assertNotEquals($lotId2, $lotId3);
        $this->assertNotEquals($lotId1, $lotId3);

        $today = Carbon::now()->format('Ymd');
        $this->assertEquals($today . '-001', $lotId1);
        $this->assertEquals($today . '-002', $lotId2);
        $this->assertEquals($today . '-003', $lotId3);
    }
}
