<?php

namespace Tests\Unit\Models;

use App\Models\HistoriqueSoldeCP;
use App\Models\SoldeCP;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoriqueSoldeCPTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

   

    /** @test */
    public function it_can_read_an_historique_solde_cp()
    {
        $historique = HistoriqueSoldeCP::factory()->create([
            'montant' => 500,
            'type_operation' => 'retrait',
            'user_id' => $this->user->id
        ]);

        $found = HistoriqueSoldeCP::find($historique->id);
        
        $this->assertEquals(500, $found->montant);
        $this->assertEquals('retrait', $found->type_operation);
        $this->assertEquals($this->user->id, $found->user_id);
    }

    /** @test */
    public function it_can_update_an_historique_solde_cp()
    {
        $historique = HistoriqueSoldeCP::factory()->create([
            'description' => 'Description originale'
        ]);

        $historique->update(['description' => 'Description mise à jour']);

        $this->assertEquals('Description mise à jour', $historique->fresh()->description);
    }

    /** @test */
    public function it_can_delete_an_historique_solde_cp()
    {
        $historique = HistoriqueSoldeCP::factory()->create();

        $historique->delete();

        $this->assertDatabaseMissing('historique_solde_cp', ['id' => $historique->id]);
    }

    /** @test */
    public function it_uses_correct_table_name()
    {
        $historique = new HistoriqueSoldeCP();
        
        $this->assertEquals('historique_solde_cp', $historique->getTable());
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $historique = new HistoriqueSoldeCP();
        
        $expected = [
            'montant',
            'type_operation',
            'operation_id',
            'solde_avant',
            'solde_apres',
            'user_id',
            'description'
        ];
        
        $this->assertEquals($expected, $historique->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $historique = HistoriqueSoldeCP::factory()->create(['user_id' => $this->user->id]);

        $this->assertInstanceOf(User::class, $historique->user);
        $this->assertEquals($this->user->id, $historique->user->id);
    }

   
    /** @test */
    public function it_can_filter_by_user()
    {
        $otherUser = User::factory()->create();
        
        HistoriqueSoldeCP::factory()->create(['user_id' => $this->user->id]);
        HistoriqueSoldeCP::factory()->create(['user_id' => $this->user->id]);
        HistoriqueSoldeCP::factory()->create(['user_id' => $otherUser->id]);

        $userHistorique = HistoriqueSoldeCP::where('user_id', $this->user->id)->get();
        
        $this->assertCount(2, $userHistorique);
    }
}
