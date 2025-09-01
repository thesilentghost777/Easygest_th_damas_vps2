<?php

namespace Tests\Unit\Models;

use App\Models\Depense;
use App\Models\User;
use App\Models\Matiere;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class DepenseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_depense()
    {
        $user = User::factory()->create();
        $matiere = Matiere::factory()->create();
        
        $data = [
            'auteur' => $user->id,
            'nom' => 'Achat fournitures',
            'prix' => 15000.50,
            'type' => 'autre',
            'idm' => $matiere->id,
            'date' => '2024-01-15',
            'valider' => true
        ];

        $depense = Depense::create($data);

        $this->assertDatabaseHas('depenses', $data);
        $this->assertEquals('Achat fournitures', $depense->nom);
        $this->assertEquals(15000.50, $depense->prix);
    }

    /** @test */
    public function it_can_read_depense()
    {
        $depense = Depense::factory()->create(['nom' => 'Test Depense']);
        
        $found = Depense::find($depense->id);
        
        $this->assertEquals($depense->nom, $found->nom);
    }

    /** @test */
    public function it_can_update_depense()
    {
        $depense = Depense::factory()->create();
        
        $depense->update(['nom' => 'Nouveau nom', 'prix' => 25000]);
        
        $this->assertEquals('Nouveau nom', $depense->fresh()->nom);
        $this->assertEquals(25000, $depense->fresh()->prix);
    }

    /** @test */
    public function it_can_delete_depense()
    {
        $depense = Depense::factory()->create();
        $id = $depense->id;
        
        $depense->delete();
        
        $this->assertDatabaseMissing('depenses', ['id' => $id]);
    }

    /** @test */
    public function it_belongs_to_user_via_auteur_relation()
    {
        $user = User::factory()->create();
        $depense = Depense::factory()->create(['auteur' => $user->id]);
        
        $this->assertInstanceOf(User::class, $depense->auteurRelation);
        $this->assertEquals($user->id, $depense->auteurRelation->id);
    }

    /** @test */
    public function it_belongs_to_user_via_user_relation()
    {
        $user = User::factory()->create();
        $depense = Depense::factory()->create(['auteur' => $user->id]);
        
        $this->assertInstanceOf(User::class, $depense->user);
        $this->assertEquals($user->id, $depense->user->id);
    }

    /** @test */
    public function it_belongs_to_matiere()
    {
        $matiere = Matiere::factory()->create();
        $depense = Depense::factory()->create(['idm' => $matiere->id]);
        
        $this->assertInstanceOf(Matiere::class, $depense->matiere);
        $this->assertEquals($matiere->id, $depense->matiere->id);
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $depense = Depense::factory()->create([
            'date' => '2024-01-15',
            'valider' => 1,
            'prix' => '15000.50'
        ]);

        $this->assertInstanceOf(Carbon::class, $depense->date);
        $this->assertIsBool($depense->valider);
        $this->assertTrue($depense->valider);
        $this->assertEquals('15000.50', $depense->prix);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = ['auteur', 'nom', 'prix', 'type', 'idm', 'date', 'valider'];
        
        $this->assertEquals($fillable, (new Depense())->getFillable());
    }
}
