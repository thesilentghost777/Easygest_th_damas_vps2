<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\IceCreamSectorService;
use App\Models\User;
use App\Models\TransactionVente;
use App\Models\Utilisation;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IceCreamSectorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new IceCreamSectorService();
        
        // Configuration de la base de données de test
        $this->artisan('migrate');
    }

    /** @test */
    public function it_can_collect_ice_cream_data_for_valid_month_and_year()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        // Créer des utilisateurs du secteur glace
        $user1 = User::factory()->create([
            'secteur' => 'glace',
            'role' => 'glace',
            'name' => 'Vendeur Glace 1'
        ]);
        
        $user2 = User::factory()->create([
            'secteur' => 'glace',
            'role' => 'glace',
            'name' => 'Vendeur Glace 2'
        ]);

        // Créer des produits
        $produit1 = Produit_fixes::factory()->create([
            'nom' => 'Glace Vanille',
            'prix' => 500,
            'categorie' => 'glace'
        ]);
        
        $produit2 = Produit_fixes::factory()->create([
            'nom' => 'Glace Chocolat',
            'prix' => 600,
            'categorie' => 'glace'
        ]);

        // Créer des matières premières
        $matiere = Matiere::factory()->create([
            'nom' => 'Lait',
            'unite_minimale' => 'l',
            'unite_classique' => 'l',
            'quantite_par_unite' => 1.0,
            'quantite' => 100.0,
            'prix_unitaire' => 350.0,
            'prix_par_unite_minimale' => 350.0
        ]);

        // Créer des données de production pour juin 2025
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $produit1->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user1->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'l',
            'created_at' => Carbon::create(2025, 6, 15)
        ]);

        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $produit2->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user2->id,
            'quantite_produit' => 15.0,
            'quantite_matiere' => 8.0,
            'unite_matiere' => 'l',
            'created_at' => Carbon::create(2025, 6, 20)
        ]);

        // Créer des données de ventes pour juin 2025
        TransactionVente::factory()->create([
            'produit' => $produit1->code_produit,
            'serveur' => $user1->id,
            'quantite' => 5,
            'prix' => 500,
            'date_vente' => Carbon::create(2025, 6, 16),
            'type' => 'Vente',
            'monnaie' => 'XAF'
        ]);

        TransactionVente::factory()->create([
            'produit' => $produit2->code_produit,
            'serveur' => $user2->id,
            'quantite' => 8,
            'prix' => 600,
            'date_vente' => Carbon::create(2025, 6, 22),
            'type' => 'Vente',
            'monnaie' => 'XAF'
        ]);

        // Créer des données d'horaires
        DB::table('Horaire')->insert([
            'employe' => $user1->id,
            'arrive' => Carbon::create(2025, 6, 15, 8, 0),
            'depart' => Carbon::create(2025, 6, 15, 18, 0),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('Horaire')->insert([
            'employe' => $user2->id,
            'arrive' => Carbon::create(2025, 6, 20, 9, 0),
            'depart' => Carbon::create(2025, 6, 20, 17, 0),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('production', $result);
        $this->assertArrayHasKey('ventes', $result);
        $this->assertArrayHasKey('tendance', $result);
        $this->assertArrayHasKey('personnel', $result);

        // Vérifier les données de production
        $this->assertEquals(2, $result['production']['total_produits']);
        $this->assertEquals(25.0, $result['production']['quantite_totale']);
        $this->assertEquals(2, $result['production']['nombre_lots']);

        // Vérifier les données de ventes
        $this->assertEquals(2, $result['ventes']['total_produits']);
        $this->assertEquals(7300, $result['ventes']['chiffre_affaires']); // (5*500) + (8*600)
        $this->assertEquals(13, $result['ventes']['quantite_vendue']);

        // Vérifier les données du personnel
        $this->assertCount(2, $result['personnel']);
    }

    /** @test */
    public function it_returns_empty_data_when_no_ice_cream_users_exist()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        // Créer des utilisateurs d'autres secteurs
        User::factory()->create([
            'secteur' => 'boulangerie',
            'role' => 'boulanger',
            'name' => 'Boulanger'
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(0, $result['production']['total_produits']);
        $this->assertEquals(0, $result['production']['quantite_totale']);
        $this->assertEquals(0, $result['ventes']['total_produits']);
        $this->assertEquals(0, $result['ventes']['chiffre_affaires']);
        $this->assertCount(0, $result['personnel']);
    }

    /** @test */
    public function it_handles_users_with_only_secteur_glace()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        $user = User::factory()->create([
            'secteur' => 'glace',
            'role' => 'autre_role', // Role différent mais secteur glace
            'name' => 'Employé Secteur Glace'
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert - L'utilisateur ne devrait pas être inclus car le service filtre sur secteur='glace' ET role='glace'
        $this->assertCount(0, $result['personnel']);
    }

    /** @test */
    public function it_handles_users_with_only_role_glace()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        $user = User::factory()->create([
            'secteur' => 'autre_secteur',
            'role' => 'glace', // Role glace mais secteur différent
            'name' => 'Employé Role Glace'
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert - L'utilisateur ne devrait pas être inclus car le service filtre sur secteur='glace' ET role='glace'
        $this->assertCount(0, $result['personnel']);
    }

    /** @test */
    public function it_filters_data_by_specific_month_and_year()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        $user = User::factory()->create([
            'secteur' => 'glace',
            'role' => 'glace',
            'name' => 'Vendeur Glace'
        ]);
        
        $produit = Produit_fixes::factory()->create([
            'nom' => 'Glace Test',
            'prix' => 400,
            'categorie' => 'glace'
        ]);

        $matiere = Matiere::factory()->create();

        // Données dans la période (juin 2025)
        Utilisation::factory()->create([
            'id_lot' => 'LOT_JUIN',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'l',
            'created_at' => Carbon::create(2025, 6, 15)
        ]);

        // Données hors période (mai 2025)
        Utilisation::factory()->create([
            'id_lot' => 'LOT_MAI',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user->id,
            'quantite_produit' => 20.0,
            'quantite_matiere' => 10.0,
            'unite_matiere' => 'l',
            'created_at' => Carbon::create(2025, 5, 15)
        ]);

        // Ventes dans la période
        TransactionVente::factory()->create([
            'produit' => $produit->code_produit,
            'serveur' => $user->id,
            'quantite' => 5,
            'prix' => 400,
            'date_vente' => Carbon::create(2025, 6, 16),
            'type' => 'Vente'
        ]);

        // Ventes hors période
        TransactionVente::factory()->create([
            'produit' => $produit->code_produit,
            'serveur' => $user->id,
            'quantite' => 8,
            'prix' => 400,
            'date_vente' => Carbon::create(2025, 5, 16),
            'type' => 'Vente'
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert - Seules les données de juin doivent être prises en compte
        $this->assertEquals(10.0, $result['production']['quantite_totale']);
        $this->assertEquals(2000, $result['ventes']['chiffre_affaires']); // 5 * 400
        $this->assertEquals(5, $result['ventes']['quantite_vendue']);
    }

    /** @test */
    public function it_calculates_personnel_hours_correctly()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        $user = User::factory()->create([
            'secteur' => 'glace',
            'role' => 'glace',
            'name' => 'Employé Test'
        ]);

        // Créer plusieurs entrées d'horaires pour le mois
        DB::table('Horaire')->insert([
            [
                'employe' => $user->id,
                'arrive' => Carbon::create(2025, 6, 15, 8, 0),
                'depart' => Carbon::create(2025, 6, 15, 17, 0), // 9 heures
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'employe' => $user->id,
                'arrive' => Carbon::create(2025, 6, 16, 9, 0),
                'depart' => Carbon::create(2025, 6, 16, 18, 0), // 9 heures
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert
        $personnel = $result['personnel']->first();
        $this->assertEquals($user->id, $personnel['id']);
        $this->assertEquals($user->name, $personnel['name']);
        $this->assertEquals(2, $personnel['presence']['jours']);
        $this->assertEquals(18, $personnel['presence']['heures']); // 9 + 9
    }

    /** @test */
    public function it_handles_database_exceptions_gracefully()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        // Simuler une erreur de base de données en utilisant une table inexistante dans une requête
        DB::shouldReceive('table')->andThrow(new \Exception('Database connection error'));

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert
        $this->assertArrayHasKey('error', $result);
    }

    /** @test */
    public function it_groups_production_data_correctly_by_product_and_producer()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        $user1 = User::factory()->create(['secteur' => 'glace', 'role' => 'glace']);
        $user2 = User::factory()->create(['secteur' => 'glace', 'role' => 'glace']);
        
        $produit = Produit_fixes::factory()->create(['nom' => 'Glace Unique']);
        $matiere = Matiere::factory()->create();

        // Plusieurs utilisations du même produit par différents producteurs
        Utilisation::factory()->create([
            'id_lot' => 'LOT001',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user1->id,
            'quantite_produit' => 10.0,
            'quantite_matiere' => 5.0,
            'unite_matiere' => 'l',
            'created_at' => Carbon::create(2025, 6, 15)
        ]);

        Utilisation::factory()->create([
            'id_lot' => 'LOT002',
            'produit' => $produit->code_produit,
            'matierep' => $matiere->id,
            'producteur' => $user2->id,
            'quantite_produit' => 15.0,
            'quantite_matiere' => 8.0,
            'unite_matiere' => 'l',
            'created_at' => Carbon::create(2025, 6, 16)
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert
        $this->assertEquals(2, $result['production']['total_produits']); // 2 entrées groupées
        $this->assertEquals(25.0, $result['production']['quantite_totale']);
        
        // Vérifier le groupement
        $productionDetails = $result['production']['detail_par_produit'];
        $this->assertCount(2, $productionDetails); // Une pour chaque producteur
    }

    /** @test */
    public function it_handles_edge_case_with_null_departure_times()
    {
        // Arrange
        $month = 6;
        $year = 2025;
        
        $user = User::factory()->create([
            'secteur' => 'glace',
            'role' => 'glace',
            'name' => 'Employé Sans Départ'
        ]);

        // Créer une entrée avec heure de départ null
        DB::table('Horaire')->insert([
            'employe' => $user->id,
            'arrive' => Carbon::create(2025, 6, 15, 8, 0),
            'depart' => null, // Pas d'heure de départ
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Act
        $result = $this->service->collectIceCreamData($month, $year);

        // Assert - Ne devrait pas planter et gérer gracieusement les valeurs null
        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('error', $result);
        
        $personnel = $result['personnel']->first();
        $this->assertEquals(1, $personnel['presence']['jours']);
        // Les heures peuvent être 0 ou null selon le comportement de TIMESTAMPDIFF avec null
    }
}
