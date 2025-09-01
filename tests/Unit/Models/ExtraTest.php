<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Extra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;

class ExtraTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_extra()
    {
        $extra = Extra::create([
            'secteur' => 'IT',
            'heure_arriver_adequat' => '08:00',
            'heure_depart_adequat' => '17:00',
            'salaire_adequat' => 2500.00,
            'age_adequat' => 18,
            'interdit' => 'fumeur, alcool',
            'regles' => 'ponctualité, respect'
        ]);

        $this->assertInstanceOf(Extra::class, $extra);
        $this->assertEquals('IT', $extra->secteur);
        $this->assertDatabaseHas('Extra', ['secteur' => 'IT']);
    }

    /** @test */
    public function it_can_read_extra()
    {
        $extra = Extra::factory()->create(['secteur' => 'Marketing']);
        
        $found = Extra::find($extra->id);
        
        $this->assertEquals($extra->id, $found->id);
        $this->assertEquals('Marketing', $found->secteur);
    }

    /** @test */
    public function it_can_update_extra()
    {
        $extra = Extra::factory()->create();
        
        $extra->update(['secteur' => 'Finance']);
        
        $this->assertEquals('Finance', $extra->fresh()->secteur);
    }

    /** @test */
    public function it_can_delete_extra()
    {
        $extra = Extra::factory()->create();
        $id = $extra->id;
        
        $extra->delete();
        
        $this->assertDatabaseMissing('Extra', ['id' => $id]);
    }

    /** @test */
    public function it_calculates_duree_travail_correctly()
    {
        $extra = Extra::factory()->create([
            'heure_arriver_adequat' => '08:00',
            'heure_depart_adequat' => '17:30'
        ]);

        $this->assertEquals(9.5, $extra->duree_travail);
    }

    /** @test */
    public function it_calculates_salaire_horaire_correctly()
    {
        $extra = Extra::factory()->create([
            'heure_arriver_adequat' => '08:00',
            'heure_depart_adequat' => '16:00',
            'salaire_adequat' => 2400.00
        ]);

        $this->assertEquals(300.00, $extra->salaire_horaire);
    }

    /** @test */
    public function it_checks_age_adequat_correctly()
    {
        $extra = Extra::factory()->create(['age_adequat' => 21]);

        $this->assertTrue($extra->isAgeAdequat(25));
        $this->assertTrue($extra->isAgeAdequat(21));
        $this->assertFalse($extra->isAgeAdequat(18));
    }

    /** @test */
    public function it_converts_interdits_to_array()
    {
        $extra = Extra::factory()->create(['interdit' => 'fumeur, alcool, retard']);

        $expected = ['fumeur', 'alcool', 'retard'];
        $this->assertEquals($expected, $extra->interdits_array);
    }

    /** @test */
    public function it_converts_regles_to_array()
    {
        $extra = Extra::factory()->create(['regles' => 'ponctualité, respect, propreté']);

        $expected = ['ponctualité', 'respect', 'propreté'];
        $this->assertEquals($expected, $extra->regles_array);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $rules = Extra::rules();
        
        $this->assertArrayHasKey('secteur', $rules);
        $this->assertArrayHasKey('heure_arriver_adequat', $rules);
        $this->assertArrayHasKey('salaire_adequat', $rules);
        $this->assertArrayHasKey('age_adequat', $rules);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $extra = new Extra();
        $fillable = $extra->getFillable();

        $expected = [
            'secteur',
            'heure_arriver_adequat', 
            'heure_depart_adequat',
            'salaire_adequat',
            'interdit',
            'regles',
            'age_adequat'
        ];

        $this->assertEquals($expected, $fillable);
    }

    /** @test */
    public function it_has_correct_casts()
    {
        $extra = new Extra();
        $casts = $extra->getCasts();

        $this->assertEquals('datetime', $casts['heure_arriver_adequat']);
        $this->assertEquals('datetime', $casts['heure_depart_adequat']);
        $this->assertEquals('decimal:2', $casts['salaire_adequat']);
        $this->assertEquals('integer', $casts['age_adequat']);
    }
}
