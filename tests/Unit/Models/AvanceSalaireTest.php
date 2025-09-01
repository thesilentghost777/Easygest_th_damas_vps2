<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Models\AvanceSalaire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PHPUnit\Framework\Attributes\Test;

class AvanceSalaireTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function fillable_attributes_are_correct()
    {
        $avance = new AvanceSalaire();

        $this->assertEquals([
            'id_employe',
            'sommeAs',
            'flag',
            'retrait_demande',
            'retrait_valide',
            'mois_as'
        ], $avance->getFillable());
    }

    #[Test]
    public function casts_are_correct()
    {
        $avance = new AvanceSalaire();

        $this->assertEquals([
            'id' => 'int',
            'mois_as' => 'date',
            'flag' => 'boolean',
            'retrait_demande' => 'boolean',
            'retrait_valide' => 'boolean',
        ], $avance->getCasts());
    }

    #[Test]
    public function employe_relationship_returns_belongsto()
    {
        $avance = new AvanceSalaire();

        $relation = $avance->employe();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('id_employe', $relation->getForeignKeyName());
    }

    #[Test]
    public function est_en_attente_retourne_vrai_si_demande_sans_validation()
    {
        $avance = new AvanceSalaire([
            'retrait_demande' => true,
            'retrait_valide' => false,
        ]);

        $this->assertTrue($avance->estEnAttente());
    }

    #[Test]
    public function est_validee_retourne_vrai_si_validee()
    {
        $avance = new AvanceSalaire(['retrait_valide' => true]);

        $this->assertTrue($avance->estValidee());
    }

    #[Test]
public function peut_demander_as_retourne_faux_si_demande_existe_et_flag_est_true()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    AvanceSalaire::create([
        'id_employe' => $user->id,
        'sommeAs' => 10000,
        'flag' => true, // ❌ déjà validée
        'retrait_demande' => true,
        'retrait_valide' => false,
        'mois_as' => now(),
    ]);

    $avance = new AvanceSalaire();

    $this->assertFalse($avance->peutDemanderAS());
}

#[Test]
public function peut_demander_as_retourne_vrai_si_flag_est_false_pour_demande_existe()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    AvanceSalaire::create([
        'id_employe' => $user->id,
        'sommeAs' => 10000,
        'flag' => false, // ✅ pas encore validée
        'retrait_demande' => true,
        'retrait_valide' => false,
        'mois_as' => now(),
    ]);

    $avance = new AvanceSalaire();

    $this->assertTrue($avance->peutDemanderAS());
}

#[Test]
public function peut_demander_as_retourne_vrai_si_aucune_demande_existe_ce_mois()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    // aucune demande créée

    $avance = new AvanceSalaire();

    $this->assertTrue($avance->peutDemanderAS());
}

}
