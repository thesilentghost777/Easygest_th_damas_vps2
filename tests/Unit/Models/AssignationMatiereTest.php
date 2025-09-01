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
            'date_limite_utilisation'
        ];
        
        $this->assertEquals($expectedFillable, $assignation->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $assignation = new AssignationMatiere();
        $expectedCasts = [
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
        ]);

        $assignation->update([
            'quantite_restante' => 50,
        ]);

        $this->assertEquals(50, $assignation->quantite_restante);
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
