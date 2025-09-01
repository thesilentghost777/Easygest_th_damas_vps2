#!/bin/bash

# Script de test unitaire pour le modèle AssignationMatiere
# Auteur: Script généré automatiquement
# Date: $(date +"%Y-%m-%d")

echo "🚀 Création du test unitaire pour AssignationMatiere..."

# Créer le fichier de test
TEST_FILE="tests/Unit/Models/AssignationMatiereTest.php"

# Créer le répertoire s'il n'existe pas
mkdir -p tests/Unit/Models

# Créer le fichier de test avec le contenu complet
cat > "$TEST_FILE" << 'EOF'
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\AssignationMatiere;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignationMatiereTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_has_correct_table_name()
    {
        $assignation = new AssignationMatiere();
        $this->assertEquals('assignations_matiere', $assignation->getTable());
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $assignation = new AssignationMatiere();
        $expectedFillable = [
            'producteur_id',
            'matiere_id',
            'quantite_assignee',
            'quantite_restante',
            'unite_assignee',
            'utilisee',
            'date_limite_utilisation'
        ];
        
        $this->assertEquals($expectedFillable, $assignation->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $assignation = new AssignationMatiere();
        $expectedCasts = [
            'utilisee' => 'boolean',
            'date_limite_utilisation' => 'datetime'
        ];
        
        // Vérifier les casts définis
        foreach ($expectedCasts as $attribute => $cast) {
            $this->assertEquals($cast, $assignation->getCasts()[$attribute]);
        }
    }

    /** @test */
    public function it_can_be_created_with_valid_data()
    {
        $producteur = User::factory()->create();
        $matiere = Matiere::factory()->create();
        
        $assignationData = [
            'producteur_id' => $producteur->id,
            'matiere_id' => $matiere->id,
            'quantite_assignee' => 100.50,
            'quantite_restante' => 75.25,
            'unite_assignee' => 'kg',
            'utilisee' => false,
            'date_limite_utilisation' => now()->addDays(30)
        ];

        $assignation = AssignationMatiere::create($assignationData);

        $this->assertInstanceOf(AssignationMatiere::class, $assignation);
        $this->assertDatabaseHas('assignations_matiere', [
            'producteur_id' => $producteur->id,
            'matiere_id' => $matiere->id,
            'quantite_assignee' => 100.50,
            'quantite_restante' => 75.25,
            'unite_assignee' => 'kg',
            'utilisee' => false
        ]);
    }

    /** @test */
    public function it_belongs_to_a_producteur()
    {
        $assignation = new AssignationMatiere();
        $relation = $assignation->producteur();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('producteur_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    /** @test */
    public function it_belongs_to_a_matiere()
    {
        $assignation = new AssignationMatiere();
        $relation = $assignation->matiere();
        
        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('matiere_id', $relation->getForeignKeyName());
        $this->assertEquals('id', $relation->getOwnerKeyName());
        $this->assertInstanceOf(Matiere::class, $relation->getRelated());
    }

    /** @test */
    public function it_can_retrieve_related_producteur()
    {
        $producteur = User::factory()->create();
        $matiere = Matiere::factory()->create();
        
        $assignation = AssignationMatiere::factory()->create([
            'producteur_id' => $producteur->id,
            'matiere_id' => $matiere->id
        ]);

        $this->assertInstanceOf(User::class, $assignation->producteur);
        $this->assertEquals($producteur->id, $assignation->producteur->id);
    }

    /** @test */
    public function it_can_retrieve_related_matiere()
    {
        $producteur = User::factory()->create();
        $matiere = Matiere::factory()->create();
        
        $assignation = AssignationMatiere::factory()->create([
            'producteur_id' => $producteur->id,
            'matiere_id' => $matiere->id
        ]);

        $this->assertInstanceOf(Matiere::class, $assignation->matiere);
        $this->assertEquals($matiere->id, $assignation->matiere->id);
    }

    /** @test */
    public function utilisee_attribute_is_cast_to_boolean()
    {
        $assignation = AssignationMatiere::factory()->create(['utilisee' => 1]);
        $this->assertTrue($assignation->utilisee);
        $this->assertIsBool($assignation->utilisee);

        $assignation = AssignationMatiere::factory()->create(['utilisee' => 0]);
        $this->assertFalse($assignation->utilisee);
        $this->assertIsBool($assignation->utilisee);
    }

    /** @test */
    public function date_limite_utilisation_is_cast_to_datetime()
    {
        $date = '2024-12-31 23:59:59';
        $assignation = AssignationMatiere::factory()->create([
            'date_limite_utilisation' => $date
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $assignation->date_limite_utilisation);
        $this->assertEquals($date, $assignation->date_limite_utilisation->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_can_update_attributes()
    {
        $assignation = AssignationMatiere::factory()->create([
            'quantite_restante' => 100,
            'utilisee' => false
        ]);

        $assignation->update([
            'quantite_restante' => 50,
            'utilisee' => true
        ]);

        $this->assertEquals(50, $assignation->quantite_restante);
        $this->assertTrue($assignation->utilisee);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $assignation = AssignationMatiere::factory()->create();
        $assignationId = $assignation->id;

        $assignation->delete();

        $this->assertDatabaseMissing('assignations_matiere', ['id' => $assignationId]);
    }
}
EOF

echo "✅ Fichier de test créé: $TEST_FILE"

# Vérifier les dépendances (User et Matiere factories)
echo "🔍 Vérification des dépendances..."

if [ ! -f "database/factories/UserFactory.php" ]; then
    echo "⚠️  UserFactory non trouvé - il devrait exister par défaut dans Laravel"
fi

if [ ! -f "database/factories/MatiereFactory.php" ]; then
    echo "⚠️  MatiereFactory non trouvé. Vous devrez peut-être le créer ou adapter les tests"
fi

echo ""
echo "🧪 Exécution des tests..."
echo "================================"

# Exécuter uniquement le test créé
php artisan test "$TEST_FILE" --verbose

echo ""
echo "📊 Résumé de l'exécution:"
echo "================================"

# Exécuter avec plus de détails
php artisan test "$TEST_FILE" --coverage --min=80 2>/dev/null || php artisan test "$TEST_FILE"

echo ""
echo "✨ Script terminé !"
echo "📁 Fichier de test: $TEST_FILE"
echo "🔧 Pour relancer les tests: php artisan test $TEST_FILE"
echo ""
echo "💡 Conseils:"
echo "   - Vérifiez que les factories User et Matiere existent"
echo "   - Adaptez les tests selon vos besoins spécifiques"
echo "   - Ajoutez des tests métier si nécessaire"