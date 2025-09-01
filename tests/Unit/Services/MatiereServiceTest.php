<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\MatiereService;
use App\Enums\UniteMinimale;
use App\Enums\UniteClassique;

class MatiereServiceTest extends TestCase
{
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MatiereService();
    }

    /** @test */
    public function it_returns_zero_when_quantity_is_zero()
    {
        // Act
        $result = $this->service->calculerPrixParUniteMinimale(100.0, 0.0, 'kg', 'g');

        // Assert
        $this->assertEquals(0, $result);
    }

    /** @test */
    public function it_returns_zero_when_quantity_is_negative()
    {
        // Act
        $result = $this->service->calculerPrixParUniteMinimale(100.0, -5.0, 'kg', 'g');

        // Assert
        $this->assertEquals(0, $result);
    }

    /** @test */
    public function it_calculates_price_per_minimal_unit_for_kg_to_g()
    {
        // Arrange - 100 FCFA pour 2 kg = 100 FCFA pour 2000 g = 0.05 FCFA/g
        $prix = 100.0;
        $quantite = 2.0;
        $uniteClassique = 'kg';
        $uniteMinimale = 'g';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $this->assertEquals(0.05, $result);
    }

    /** @test */
    public function it_calculates_price_per_minimal_unit_for_litre_to_ml()
    {
        // Arrange - 50 FCFA pour 1 litre = 50 FCFA pour 1000 ml = 0.05 FCFA/ml
        $prix = 50.0;
        $quantite = 1.0;
        $uniteClassique = 'litre';
        $uniteMinimale = 'ml';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $this->assertEquals(0.05, $result);
    }

    /** @test */
    public function it_calculates_price_when_base_unit_equals_minimal_unit_for_kg()
    {
        // Arrange - Test quand l'unité de base (g) = unité minimale (g)
        // Supposons que getBaseUnit('kg') retourne 'g'
        $prix = 200.0;
        $quantite = 4.0; // 4 kg = 4000 g
        $uniteClassique = 'kg';
        $uniteMinimale = 'g';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $this->assertEquals(0.05, $result); // 200 / 4000 = 0.05
    }

    /** @test */
    public function it_calculates_price_when_base_unit_equals_minimal_unit_for_litre()
    {
        // Arrange - Test quand l'unité de base (ml) = unité minimale (ml)
        $prix = 300.0;
        $quantite = 6.0; // 6 litres = 6000 ml
        $uniteClassique = 'litre';
        $uniteMinimale = 'ml';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $this->assertEquals(0.05, $result); // 300 / 6000 = 0.05
    }

   
    /** @test */
    public function it_handles_conversion_rate_of_zero_or_null()
    {
        // Arrange - Test quand getConversionRate retourne null ou 0
        $prix = 100.0;
        $quantite = 2.0;
        $uniteClassique = 'kg';
        $uniteMinimale = 'unite_inexistante';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        // Quand le taux est null, il utilise 1 par défaut
        $this->assertIsFloat($result);
        $this->assertGreaterThan(0, $result);
    }

    /** @test */
    public function it_calculates_correctly_with_decimal_quantities()
    {
        // Arrange
        $prix = 75.5;
        $quantite = 1.5; // 1.5 kg = 1500 g
        $uniteClassique = 'kg';
        $uniteMinimale = 'g';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $expected = 75.5 / 1500; // ≈ 0.0503
        $this->assertEquals($expected, $result, '', 0.0001);
    }

    /** @test */
    public function it_calculates_correctly_with_decimal_prices()
    {
        // Arrange
        $prix = 33.33;
        $quantite = 3.0; // 3 litres = 3000 ml
        $uniteClassique = 'litre';
        $uniteMinimale = 'ml';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $expected = 33.33 / 3000; // ≈ 0.01111
        $this->assertEquals($expected, $result, '', 0.00001);
    }

    /** @test */
    public function it_handles_very_small_quantities()
    {
        // Arrange
        $prix = 10.0;
        $quantite = 0.001; // 0.001 kg = 1 g
        $uniteClassique = 'kg';
        $uniteMinimale = 'g';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $this->assertEquals(10.0, $result); // 10 / 1 = 10
    }

    /** @test */
    public function it_handles_very_large_quantities()
    {
        // Arrange
        $prix = 50000.0;
        $quantite = 1000.0; // 1000 kg = 1000000 g
        $uniteClassique = 'kg';
        $uniteMinimale = 'g';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $this->assertEquals(0.05, $result); // 50000 / 1000000 = 0.05
    }

    /** @test */
    public function it_handles_unknown_classical_units()
    {
        // Arrange - Test avec une unité classique non reconnue
        $prix = 100.0;
        $quantite = 2.0;
        $uniteClassique = 'unite_inconnue';
        $uniteMinimale = 'g';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        // Pour une unité inconnue, pas de conversion * 1000
        // Le résultat dépendra de la logique de getBaseUnit
        $this->assertIsFloat($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    /** @test */
    public function it_maintains_chirugical_precision_with_financial_calculations()
    {
        // Arrange - Test de précision pour calculs financiers
        $prix = 999.99;
        $quantite = 7.777; // 7.777 kg = 7777 g
        $uniteClassique = 'kg';
        $uniteMinimale = 'g';

        // Act
        $result = $this->service->calculerPrixParUniteMinimale($prix, $quantite, $uniteClassique, $uniteMinimale);

        // Assert
        $expected = 999.99 / 7777;
        $this->assertEquals($expected, $result, '', 0.000001);
        $this->assertIsFloat($result);
    }
}
