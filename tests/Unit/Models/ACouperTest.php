<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ACouper;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ACouperTest extends TestCase
{
    /** @test */
    public function test_fillable_attributes_are_correct()
    {
        $acouper = new ACouper();

        $this->assertEquals([
            'id_employe',
            'manquants',
            'remboursement',
            'pret',
            'caisse_sociale',
            'date'
        ], $acouper->getFillable());
    }

    /** @test */
    public function test_casts_are_correct()
    {
        $acouper = new ACouper();

        $expectedCasts = [
            'id' => 'int',
            'date' => 'date',
            'manquants' => 'integer',
            'remboursement' => 'integer',
            'pret' => 'integer',
            'caisse_sociale' => 'integer'
        ];
        

        $this->assertEquals($expectedCasts, $acouper->getCasts());
    }

    /** @test */
    public function test_employe_relationship_returns_belongsto()
    {
        $acouper = new ACouper();

        $this->assertInstanceOf(BelongsTo::class, $acouper->employe());
        $this->assertEquals('id_employe', $acouper->employe()->getForeignKeyName());
        $this->assertEquals(User::class, $acouper->employe()->getRelated()::class);
    }
}
