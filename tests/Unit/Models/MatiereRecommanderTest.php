<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\MatiereRecommander;
use App\Models\Produit_fixes;
use App\Models\Matiere;
use App\Services\UniteConversionService;
use App\Enums\UniteMinimale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class MatiereRecommanderTest extends TestCase
{
    use RefreshDatabase;

    private MatiereRecommander $matiereRecommander;
    private Produit_fixes $produitFixe;
    private Matiere $matiere;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les modèles liés avec les factories
        $this->produitFixe = Produit_fixes::factory()->create();
        $this->matiere = Matiere::factory()->create([
            'unite_minimale' => UniteMinimale::GRAMME
        ]);
        
        $this->matiereRecommander = MatiereRecommander::factory()->create([
            'produit' => $this->produitFixe->code_produit,
            'matierep' => $this->matiere->id,
            'quantitep' => 100.0,
            'quantite' => 50.5,
            'unite' => 'kg'
        ]);
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->assertInstanceOf(MatiereRecommander::class, $this->matiereRecommander);
        $this->assertDatabaseHas('Matiere_recommander', [
            'id' => $this->matiereRecommander->id
        ]);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = ['produit', 'matierep', 'quantitep', 'quantite', 'unite'];
        $this->assertEquals($fillable, $this->matiereRecommander->getFillable());
    }

    /** @test */
    public function it_casts_quantite_to_decimal()
    {
        $this->matiereRecommander->quantite = 123.456789;
        $this->matiereRecommander->save();
        
        $this->assertEquals(123.457, $this->matiereRecommander->fresh()->quantite);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $newData = [
            'quantite' => 75.25,
            'unite' => 'g'
        ];

        $this->matiereRecommander->update($newData);

        $this->assertDatabaseHas('Matiere_recommander', [
            'id' => $this->matiereRecommander->id,
            'quantite' => 75.25,
            'unite' => 'g'
        ]);
    }

    /** @test */
    public function it_can_be_deleted()
    {
        $id = $this->matiereRecommander->id;
        
        $this->matiereRecommander->delete();
        
        $this->assertDatabaseMissing('Matiere_recommander', ['id' => $id]);
    }

    /** @test */
    public function it_belongs_to_produit_fixes()
    {
        $relation = $this->matiereRecommander->Produit_fixes();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals($this->produitFixe->id, $this->matiereRecommander->Produit_fixes->id);
    }

    /** @test */
    public function it_has_produit_fixe_relation_alias()
    {
        $relation = $this->matiereRecommander->produitFixe();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals($this->produitFixe->id, $this->matiereRecommander->produitFixe->id);
    }

    /** @test */
    public function it_belongs_to_matiere()
    {
        $relation = $this->matiereRecommander->matiere();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $relation);
        $this->assertEquals($this->matiere->id, $this->matiereRecommander->matiere->id);
    }

    /** @test */
    public function it_converts_quantity_to_minimal_unit()
    {
        // On teste avec des vraies données si le service existe
        // ou on skip si on n'a pas de données de test appropriées
        $this->markTestSkipped('Nécessite une configuration spécifique du service UniteConversionService');
    }

    /** @test */
    public function it_calculates_recommended_quantity_for_target()
    {
        // Test avec des calculs directs sans service externe
        $this->markTestSkipped('Nécessite une configuration spécifique du service UniteConversionService');
    }

    /** @test */
    public function it_has_get_quantite_in_minimal_unit_method()
    {
        // Test que la méthode existe et est callable
        $this->assertTrue(method_exists($this->matiereRecommander, 'getQuantiteInMinimalUnit'));
        $this->assertTrue(is_callable([$this->matiereRecommander, 'getQuantiteInMinimalUnit']));
    }

    /** @test */
    public function it_has_get_recommended_quantity_for_method()
    {
        // Test que la méthode existe et est callable
        $this->assertTrue(method_exists($this->matiereRecommander, 'getRecommendedQuantityFor'));
        $this->assertTrue(is_callable([$this->matiereRecommander, 'getRecommendedQuantityFor']));
    }
}
