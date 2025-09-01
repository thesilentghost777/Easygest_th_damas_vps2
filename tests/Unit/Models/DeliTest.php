<?php

namespace Tests\Unit\Models;

use App\Models\Deli;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeliTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_deli()
    {
        $data = [
            'nom' => 'Retard répété',
            'description' => 'Employé en retard 3 fois',
            'montant' => 5000.00
        ];

        $deli = Deli::create($data);

        $this->assertDatabaseHas('delis', $data);
        $this->assertEquals('Retard répété', $deli->nom);
    }

    /** @test */
    public function it_can_read_deli()
    {
        $deli = Deli::factory()->create(['nom' => 'Test Deli']);
        
        $found = Deli::find($deli->id);
        
        $this->assertEquals($deli->nom, $found->nom);
    }

    /** @test */
    public function it_can_update_deli()
    {
        $deli = Deli::factory()->create();
        
        $deli->update(['nom' => 'Nouveau nom', 'montant' => 3000]);
        
        $this->assertEquals('Nouveau nom', $deli->fresh()->nom);
        $this->assertEquals(3000, $deli->fresh()->montant);
    }

    /** @test */
    public function it_can_delete_deli()
    {
        $deli = Deli::factory()->create();
        $id = $deli->id;
        
        $deli->delete();
        
        $this->assertDatabaseMissing('delis', ['id' => $id]);
    }

    /** @test */
    public function it_has_many_to_many_relationship_with_users()
    {
        $deli = Deli::factory()->create();
        $user = User::factory()->create();
        
        $deli->employes()->attach($user->id, ['date_incident' => now()]);
        
        $this->assertTrue($deli->employes->contains($user));
        $this->assertNotNull($deli->employes->first()->pivot->date_incident);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $fillable = ['nom', 'description', 'montant'];
        
        $this->assertEquals($fillable, (new Deli())->getFillable());
    }
}
