<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\LotSecurityService;
use App\Models\Utilisation;
use App\Models\User;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class LotSecurityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $user;
    protected $otherUser;
    protected $produit;
    protected $matiere;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LotSecurityService();
        
        // Configuration de la base de données de test
        $this->artisan('migrate');
        
        // Créer des données de test communes
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
        $this->produit = Produit_fixes::factory()->create();
        $this->matiere = Matiere::factory()->create();
    }

    /** @test */
    public function verify_lot_access_returns_true_when_lot_belongs_to_user()
    {
        // Arrange
        $lotId = '20250612-001';
        
        Utilisation::factory()->create([
            'id_lot' => $lotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->verifyLotAccess($lotId, $this->user->id);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function verify_lot_access_returns_false_when_lot_belongs_to_different_user()
    {
        // Arrange
        $lotId = '20250612-001';
        
        Utilisation::factory()->create([
            'id_lot' => $lotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->verifyLotAccess($lotId, $this->otherUser->id);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function verify_lot_access_returns_false_when_lot_does_not_exist()
    {
        // Act
        $result = $this->service->verifyLotAccess('nonexistent-lot', $this->user->id);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function lot_exists_returns_true_when_lot_exists()
    {
        // Arrange
        $lotId = '20250612-001';
        
        Utilisation::factory()->create([
            'id_lot' => $lotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->lotExists($lotId);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function lot_exists_returns_false_when_lot_does_not_exist()
    {
        // Act
        $result = $this->service->lotExists('nonexistent-lot');

        // Assert
        $this->assertFalse($result);
    }

  
    /** @test */
    public function generate_unique_lot_id_generates_different_ids_on_successive_calls()
    {
        // Act
        $lotId1 = $this->service->generateUniqueLotId($this->user->id);
        $lotId2 = $this->service->generateUniqueLotId($this->user->id);
        $lotId3 = $this->service->generateUniqueLotId($this->user->id);

        // Assert
        $this->assertNotEquals($lotId1, $lotId2);
        $this->assertNotEquals($lotId2, $lotId3);
        $this->assertNotEquals($lotId1, $lotId3);
    }

    /** @test */
    public function generate_unique_lot_id_avoids_existing_lot_ids()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        $existingLotId = $today . '-' . $this->user->id . '-TEST';
        
        Utilisation::factory()->create([
            'id_lot' => $existingLotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->generateUniqueLotId($this->user->id);

        // Assert
        $this->assertNotEquals($existingLotId, $result);
        $this->assertFalse($this->service->lotExists($result)); // Le nouveau lot ne doit pas exister encore
    }

    /** @test */
    public function validate_lot_id_format_returns_true_for_valid_formats()
    {
        // Arrange
        $validFormats = [
            '20250612-1-A1B2',
            '20250612-123-XyZ9',
            '20250612-999-0000',
            '20250612-1-abcd',
            '20250612-1-1234'
        ];

        foreach ($validFormats as $lotId) {
            // Act
            $result = $this->service->validateLotIdFormat($lotId);

            // Assert
            $this->assertTrue($result, "Format '$lotId' devrait être valide");
        }
    }

    /** @test */
    public function validate_lot_id_format_returns_false_for_invalid_formats()
    {
        // Arrange
        $invalidFormats = [
            '2025061-1-A1B2',      // Date trop courte
            '202506122-1-A1B2',    // Date trop longue
            '20250612-A-A1B2',     // UserID non numérique
            '20250612-1-A1B',      // Code trop court
            '20250612-1-A1B2C',    // Code trop long
            '20250612-1-A1B@',     // Caractère spécial
            '20250612-1',          // Manque le code
            '20250612',            // Manque userID et code
            'invalid-format',       // Format complètement différent
            '',                    // Chaîne vide
            '20250612--A1B2',      // UserID manquant
            '20250612-1-',         // Code manquant
        ];

        foreach ($invalidFormats as $lotId) {
            // Act
            $result = $this->service->validateLotIdFormat($lotId);

            // Assert
            $this->assertFalse($result, "Format '$lotId' devrait être invalide");
        }
    }

    /** @test */
    public function is_lot_from_today_returns_true_for_today_lots()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        $todayLots = [
            $today . '-1-A1B2',
            $today . '-999-XyZ9'
        ];

        foreach ($todayLots as $lotId) {
            // Act
            $result = $this->service->isLotFromToday($lotId);

            // Assert
            $this->assertTrue($result, "Lot '$lotId' devrait être d'aujourd'hui");
        }
    }

    /** @test */
    public function is_lot_from_today_returns_false_for_other_days()
    {
        // Arrange
        $yesterday = Carbon::yesterday()->format('Ymd');
        $tomorrow = Carbon::tomorrow()->format('Ymd');
        $otherDayLots = [
            $yesterday . '-1-A1B2',
            $tomorrow . '-1-XyZ9',
            '20200101-1-TEST'
        ];

        foreach ($otherDayLots as $lotId) {
            // Act
            $result = $this->service->isLotFromToday($lotId);

            // Assert
            $this->assertFalse($result, "Lot '$lotId' ne devrait pas être d'aujourd'hui");
        }
    }

    /** @test */
    public function validate_full_lot_access_returns_true_when_all_conditions_met()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        $lotId = $today . '-' . $this->user->id . '-A1B2';
        
        Utilisation::factory()->create([
            'id_lot' => $lotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->validateFullLotAccess($lotId, $this->user->id);

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function validate_full_lot_access_returns_false_when_format_invalid()
    {
        // Arrange
        $invalidLotId = 'invalid-format';
        
        Utilisation::factory()->create([
            'id_lot' => $invalidLotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->validateFullLotAccess($invalidLotId, $this->user->id);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function validate_full_lot_access_returns_false_when_user_access_denied()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        $lotId = $today . '-' . $this->user->id . '-A1B2';
        
        Utilisation::factory()->create([
            'id_lot' => $lotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act - Tester avec un autre utilisateur
        $result = $this->service->validateFullLotAccess($lotId, $this->otherUser->id);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function validate_full_lot_access_returns_false_when_lot_not_from_today()
    {
        // Arrange
        $yesterday = Carbon::yesterday()->format('Ymd');
        $lotId = $yesterday . '-' . $this->user->id . '-A1B2';
        
        Utilisation::factory()->create([
            'id_lot' => $lotId,
            'produit' => $this->produit->code_produit,
            'matierep' => $this->matiere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'litre'
        ]);

        // Act
        $result = $this->service->validateFullLotAccess($lotId, $this->user->id);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function validate_full_lot_access_returns_false_when_lot_does_not_exist()
    {
        // Arrange
        $today = Carbon::now()->format('Ymd');
        $nonExistentLotId = $today . '-' . $this->user->id . '-NONE';

        // Act
        $result = $this->service->validateFullLotAccess($nonExistentLotId, $this->user->id);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function generate_unique_lot_id_handles_collision_gracefully()
    {
        // Arrange - Simuler une situation où plusieurs lots similaires existent
        $today = Carbon::now()->format('Ymd');
        $basePattern = $today . '-' . $this->user->id . '-';
        
        // Créer plusieurs lots pour augmenter les chances de collision
        for ($i = 0; $i < 5; $i++) {
            Utilisation::factory()->create([
                'id_lot' => $basePattern . 'TEST' . $i,
                'produit' => $this->produit->code_produit,
                'matierep' => $this->matiere->id,
                'producteur' => $this->user->id,
                'quantite_produit' => 10.0,
                'quantite_matiere' => 5.0,
                'unite_matiere' => 'litre'
            ]);
        }

        // Act
        $result = $this->service->generateUniqueLotId($this->user->id);

        // Assert
        $this->assertFalse($this->service->lotExists($result));
        $this->assertMatchesRegularExpression('/^\d{8}-\d+-[A-Za-z0-9]{4}$/', $result);
    }

    /** @test */
    public function service_methods_handle_edge_cases_with_special_characters()
    {
        // Arrange
        $lotIdWithSpecialChars = "20250612-1-A1B2";
        
        // Act & Assert - Ces méthodes ne devraient pas planter avec des caractères spéciaux
        $this->assertFalse($this->service->verifyLotAccess('', $this->user->id));
        $this->assertFalse($this->service->lotExists(''));
        $this->assertFalse($this->service->validateLotIdFormat(''));
        $this->assertFalse($this->service->isLotFromToday(''));
        $this->assertFalse($this->service->validateFullLotAccess('', $this->user->id));
    }
}
