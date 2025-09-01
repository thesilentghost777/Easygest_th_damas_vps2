#!/bin/bash

# Script de création et exécution des tests pour ProductionStatsService
# Créé automatiquement pour les tests unitaires Laravel

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== Création du test unitaire pour ProductionStatsService ===${NC}"

# Vérification de l'existence du dossier tests/Unit/Services
if [ ! -d "tests/Unit/Services" ]; then
    echo -e "${YELLOW}Création du dossier tests/Unit/Services${NC}"
    mkdir -p tests/Unit/Services
fi

# Création du fichier de test
TEST_FILE="tests/Unit/Services/ProductionStatsServiceTest.php"

echo -e "${YELLOW}Création du fichier de test: ${TEST_FILE}${NC}"

cat > "${TEST_FILE}" << 'EOF'
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ProductionStatsService;
use App\Services\UniteConversionService;
use App\Models\Utilisation;
use App\Models\User;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ProductionStatsServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductionStatsService $service;
    private User $user;
    private Produit_fixes $produit1;
    private Produit_fixes $produit2;
    private Matiere $matiere1;
    private Matiere $matiere2;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new ProductionStatsService();
        
        // Création des données de test
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        $this->produit1 = Produit_fixes::factory()->create([
            'nom' => 'Produit Test 1',
            'prix' => 100,
            'categorie' => 'Test'
        ]);
        
        $this->produit2 = Produit_fixes::factory()->create([
            'nom' => 'Produit Test 2',
            'prix' => 200,
            'categorie' => 'Test'
        ]);
        
        $this->matiere1 = Matiere::factory()->create([
            'nom' => 'Matiere Test 1',
            'unite_minimale' => 'g',
            'unite_classique' => 'kg',
            'quantite_par_unite' => 1000,
            'quantite' => 100.00,
            'prix_unitaire' => 10.00,
            'prix_par_unite_minimale' => 0.01
        ]);
        
        $this->matiere2 = Matiere::factory()->create([
            'nom' => 'Matiere Test 2',
            'unite_minimale' => 'ml',
            'unite_classique' => 'l',
            'quantite_par_unite' => 1000,
            'quantite' => 50.00,
            'prix_unitaire' => 5.00,
            'prix_par_unite_minimale' => 0.005
        ]);
    }

    /** @test */
    public function test_getProductionsByLot_retourne_collection_vide_sans_productions()
    {
        $debut = Carbon::now()->subDays(30);
        $fin = Carbon::now();
        
        $result = $this->service->getProductionsByLot($this->user->id, $debut, $fin);
        
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertTrue($result->isEmpty());
    }

    /** @test */
    public function test_getProductionsByLot_retourne_productions_filtrees_par_utilisateur()
    {
        $autreUser = User::factory()->create();
        $debut = Carbon::now()->subDays(30);
        $fin = Carbon::now();
        
        // Création d'utilisations pour notre utilisateur
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 500.000,
            'unite_matiere' => 'g',
            'created_at' => $debut->addDays(5)
        ]);
        
        // Création d'utilisations pour un autre utilisateur (ne doit pas apparaître)
        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $autreUser->id,
            'quantite_produit' => 5.00,
            'quantite_matiere' => 250.000,
            'unite_matiere' => 'g',
            'created_at' => $debut->addDays(5)
        ]);
        
        $result = $this->service->getProductionsByLot($this->user->id, $debut, $fin);
        
        $this->assertCount(1, $result);
        $this->assertTrue($result->has('LOT001'));
        $this->assertFalse($result->has('LOT002'));
    }

    /** @test */
    public function test_getProductionsByLot_retourne_productions_filtrees_par_date()
    {
        $debut = Carbon::now()->subDays(30);
        $fin = Carbon::now()->subDays(10);
        
        // Utilisation dans la plage de dates
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 500.000,
            'unite_matiere' => 'g',
            'created_at' => $debut->addDays(5)
        ]);
        
        // Utilisation hors plage de dates
        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 5.00,
            'quantite_matiere' => 250.000,
            'unite_matiere' => 'g',
            'created_at' => $fin->addDays(5)
        ]);
        
        $result = $this->service->getProductionsByLot($this->user->id, $debut, $fin);
        
        $this->assertCount(1, $result);
        $this->assertTrue($result->has('LOT001'));
        $this->assertFalse($result->has('LOT002'));
    }

    /** @test */
    public function test_getProductionsByLot_groupe_par_lot_correctement()
    {
        $debut = Carbon::now()->subDays(30);
        $fin = Carbon::now();
        
        // Deux utilisations pour le même lot
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 500.000,
            'unite_matiere' => 'g',
            'created_at' => $debut->addDays(5)
        ]);
        
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit2->code_produit,
            'matierep' => $this->matiere2->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 5.00,
            'quantite_matiere' => 250.000,
            'unite_matiere' => 'ml',
            'created_at' => $debut->addDays(5)
        ]);
        
        // Une utilisation pour un autre lot
        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 3.00,
            'quantite_matiere' => 150.000,
            'unite_matiere' => 'g',
            'created_at' => $debut->addDays(10)
        ]);
        
        $result = $this->service->getProductionsByLot($this->user->id, $debut, $fin);
        
        $this->assertCount(2, $result);
        $this->assertTrue($result->has('LOT001'));
        $this->assertTrue($result->has('LOT002'));
        $this->assertCount(2, $result->get('LOT001'));
        $this->assertCount(1, $result->get('LOT002'));
    }

    /** @test */
    public function test_calculateGlobalStats_avec_collection_vide()
    {
        $productionsByLot = collect();
        
        $result = $this->service->calculateGlobalStats($productionsByLot);
        
        $this->assertIsArray($result);
        $this->assertEquals(0, $result['total_quantite']);
        $this->assertEquals(0, $result['total_revenu']);
        $this->assertEquals(0, $result['total_cout']);
        $this->assertEquals(0, $result['total_benefice']);
        $this->assertEmpty($result['lots']);
    }

    /** @test */
    public function test_calculateGlobalStats_avec_un_lot_simple()
    {
        // Mock du service de conversion
        $this->app->bind(UniteConversionService::class, function () {
            $mock = $this->createMock(UniteConversionService::class);
            $mock->method('convertir')->willReturn(500.0); // 500g convertis en unité minimale
            return $mock;
        });
        
        $utilisation = Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 500.000,
            'unite_matiere' => 'g'
        ]);
        
        $productionsByLot = collect(['LOT001' => collect([$utilisation])]);
        
        $result = $this->service->calculateGlobalStats($productionsByLot);
        
        $this->assertIsArray($result);
        $this->assertEquals(10.00, $result['total_quantite']);
        $this->assertEquals(1000, $result['total_revenu']); // 10 * 100
        $this->assertEquals(5.0, $result['total_cout']); // 500 * 0.01
        $this->assertEquals(995.0, $result['total_benefice']); // 1000 - 5
        $this->assertCount(1, $result['lots']);
        $this->assertArrayHasKey('LOT001', $result['lots']);
    }

    /** @test */
    public function test_calculateGlobalStats_avec_plusieurs_lots()
    {
        // Mock du service de conversion
        $this->app->bind(UniteConversionService::class, function () {
            $mock = $this->createMock(UniteConversionService::class);
            $mock->method('convertir')->willReturnCallback(function ($quantite) {
                return $quantite; // Retourne la quantité telle quelle pour simplifier
            });
            return $mock;
        });
        
        $utilisation1 = Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 500.000,
            'unite_matiere' => 'g'
        ]);
        
        $utilisation2 = Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $this->produit2->code_produit,
            'matierep' => $this->matiere2->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 5.00,
            'quantite_matiere' => 250.000,
            'unite_matiere' => 'ml'
        ]);
        
        $productionsByLot = collect([
            'LOT001' => collect([$utilisation1]),
            'LOT002' => collect([$utilisation2])
        ]);
        
        $result = $this->service->calculateGlobalStats($productionsByLot);
        
        $this->assertEquals(15.00, $result['total_quantite']); // 10 + 5
        $this->assertEquals(2000, $result['total_revenu']); // (10*100) + (5*200)
        $this->assertEquals(6.25, $result['total_cout']); // (500*0.01) + (250*0.005)
        $this->assertEquals(1993.75, $result['total_benefice']); // 2000 - 6.25
        $this->assertCount(2, $result['lots']);
    }

    /** @test */
    public function test_calculateGlobalStats_avec_meme_produit_dans_meme_lot()
    {
        // Mock du service de conversion
        $this->app->bind(UniteConversionService::class, function () {
            $mock = $this->createMock(UniteConversionService::class);
            $mock->method('convertir')->willReturnCallback(function ($quantite) {
                return $quantite;
            });
            return $mock;
        });
        
        // Deux utilisations du même produit dans le même lot
        $utilisation1 = Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 500.000,
            'unite_matiere' => 'g'
        ]);
        
        $utilisation2 = Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 5.00,
            'quantite_matiere' => 250.000,
            'unite_matiere' => 'g'
        ]);
        
        $productionsByLot = collect([
            'LOT001' => collect([$utilisation1, $utilisation2])
        ]);
        
        $result = $this->service->calculateGlobalStats($productionsByLot);
        
        $this->assertEquals(15.00, $result['total_quantite']); // 10 + 5
        $this->assertEquals(1500, $result['total_revenu']); // (10+5)*100
        $this->assertEquals(7.5, $result['total_cout']); // (500+250)*0.01
        $this->assertEquals(1492.5, $result['total_benefice']); // 1500 - 7.5
        
        // Vérification que les stats du lot regroupent bien les produits identiques
        $lotStats = $result['lots']['LOT001'];
        $this->assertCount(1, $lotStats['stats_par_produit']); // Un seul produit groupé
        $this->assertEquals(15.00, $lotStats['stats_par_produit'][0]['quantite_totale']);
    }

    /** @test */
    public function test_calculateGlobalStats_structure_complete_des_stats()
    {
        // Mock du service de conversion
        $this->app->bind(UniteConversionService::class, function () {
            $mock = $this->createMock(UniteConversionService::class);
            $mock->method('convertir')->willReturn(500.0);
            return $mock;
        });
        
        $utilisation = Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $this->produit1->code_produit,
            'matierep' => $this->matiere1->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 500.000,
            'unite_matiere' => 'g'
        ]);
        
        $productionsByLot = collect(['LOT001' => collect([$utilisation])]);
        
        $result = $this->service->calculateGlobalStats($productionsByLot);
        
        // Vérification de la structure globale
        $this->assertArrayHasKey('total_quantite', $result);
        $this->assertArrayHasKey('total_revenu', $result);
        $this->assertArrayHasKey('total_cout', $result);
        $this->assertArrayHasKey('total_benefice', $result);
        $this->assertArrayHasKey('lots', $result);
        
        // Vérification de la structure des lots
        $lotStats = $result['lots']['LOT001'];
        $this->assertArrayHasKey('quantite_totale', $lotStats);
        $this->assertArrayHasKey('cout_total', $lotStats);
        $this->assertArrayHasKey('revenu_total', $lotStats);
        $this->assertArrayHasKey('benefice', $lotStats);
        $this->assertArrayHasKey('stats_par_produit', $lotStats);
        
        // Vérification de la structure des stats par produit
        $produitStats = $lotStats['stats_par_produit'][0];
        $this->assertArrayHasKey('nom_produit', $produitStats);
        $this->assertArrayHasKey('code_produit', $produitStats);
        $this->assertArrayHasKey('quantite_totale', $produitStats);
        $this->assertArrayHasKey('cout_total', $produitStats);
        $this->assertArrayHasKey('revenu_total', $produitStats);
        $this->assertArrayHasKey('benefice', $produitStats);
        
        // Vérification des valeurs
        $this->assertEquals($this->produit1->nom, $produitStats['nom_produit']);
        $this->assertEquals($this->produit1->code_produit, $produitStats['code_produit']);
    }

    /** @test */
    public function test_calculateGlobalStats_avec_benefice_negatif()
    {
        // Création d'un produit avec prix très bas
        $produitPasCher = Produit_fixes::factory()->create([
            'nom' => 'Produit Pas Cher',
            'prix' => 1, // Prix très bas
            'categorie' => 'Test'
        ]);
        
        // Création d'une matière très chère
        $matiereChere = Matiere::factory()->create([
            'nom' => 'Matiere Chère',
            'unite_minimale' => 'g',
            'unite_classique' => 'kg',
            'quantite_par_unite' => 1000,
            'quantite' => 100.00,
            'prix_unitaire' => 10.00,
            'prix_par_unite_minimale' => 1.0 // Prix très élevé par unité minimale
        ]);
        
        // Mock du service de conversion
        $this->app->bind(UniteConversionService::class, function () {
            $mock = $this->createMock(UniteConversionService::class);
            $mock->method('convertir')->willReturn(1000.0); // Grande quantité convertie
            return $mock;
        });
        
        $utilisation = Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $produitPasCher->code_produit,
            'matierep' => $matiereChere->id,
            'producteur' => $this->user->id,
            'quantite_produit' => 10.00,
            'quantite_matiere' => 1000.000,
            'unite_matiere' => 'g'
        ]);
        
        $productionsByLot = collect(['LOT001' => collect([$utilisation])]);
        
        $result = $this->service->calculateGlobalStats($productionsByLot);
        
        // Le coût (1000 * 1.0 = 1000) est supérieur au revenu (10 * 1 = 10)
        $this->assertEquals(10, $result['total_revenu']);
        $this->assertEquals(1000, $result['total_cout']);
        $this->assertEquals(-990, $result['total_benefice']); // Bénéfice négatif
        $this->assertLessThan(0, $result['total_benefice']);
    }
}
EOF

echo -e "${GREEN}✓ Fichier de test créé avec succès${NC}"

# Vérification que le fichier a été créé
if [ -f "${TEST_FILE}" ]; then
    echo -e "${GREEN}✓ Le fichier de test existe: ${TEST_FILE}${NC}"
    
    # Affichage du nombre de lignes
    LINES=$(wc -l < "${TEST_FILE}")
    echo -e "${YELLOW}📄 Nombre de lignes dans le fichier de test: ${LINES}${NC}"
    
    # Exécution des tests
    echo -e "${YELLOW}🧪 Exécution des tests...${NC}"
    
    # Exécution du test spécifique
    if php artisan test "${TEST_FILE}" --verbose; then
        echo -e "${GREEN}✅ Tous les tests sont passés avec succès !${NC}"
    else
        echo -e "${RED}❌ Certains tests ont échoué. Vérifiez les logs ci-dessus.${NC}"
        exit 1
    fi
    
    # Exécution avec coverage si possible
    echo -e "${YELLOW}📊 Tentative d'exécution avec coverage...${NC}"
    if command -v phpunit &> /dev/null; then
        ./vendor/bin/phpunit "${TEST_FILE}" --coverage-text --colors=never 2>/dev/null || echo -e "${YELLOW}⚠️  Coverage non disponible${NC}"
    fi
    
else
    echo -e "${RED}❌ Erreur: Le fichier de test n'a pas pu être créé${NC}"
    exit 1
fi

echo -e "${GREEN}🎉 Script terminé avec succès !${NC}"
echo -e "${YELLOW}📋 Résumé des tests créés:${NC}"
echo -e "   • test_getProductionsByLot_retourne_collection_vide_sans_productions"
echo -e "   • test_getProductionsByLot_retourne_productions_filtrees_par_utilisateur"
echo -e "   • test_getProductionsByLot_retourne_productions_filtrees_par_date"
echo -e "   • test_getProductionsByLot_groupe_par_lot_correctement"
echo -e "   • test_calculateGlobalStats_avec_collection_vide"
echo -e "   • test_calculateGlobalStats_avec_un_lot_simple"
echo -e "   • test_calculateGlobalStats_avec_plusieurs_lots"
echo -e "   • test_calculateGlobalStats_avec_meme_produit_dans_meme_lot"
echo -e "   • test_calculateGlobalStats_structure_complete_des_stats"
echo -e "   • test_calculateGlobalStats_avec_benefice_negatif"

echo -e "${YELLOW}📁 Fichier de test créé: ${TEST_FILE}${NC}"