<?php

namespace Tests\Unit\Models;

use App\Models\Configuration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $configuration = new Configuration();
        $expectedFillable = [
            'first_config',
            'flag1',
            'flag2',
            'flag3',
            'flag4'
        ];
        
        $this->assertEquals($expectedFillable, $configuration->getFillable());
    }

    /** @test */
    public function it_casts_all_fields_to_boolean()
    {
        $configuration = new Configuration();
        $casts = $configuration->getCasts();
        
        $this->assertEquals('boolean', $casts['first_config']);
        $this->assertEquals('boolean', $casts['flag1']);
        $this->assertEquals('boolean', $casts['flag2']);
        $this->assertEquals('boolean', $casts['flag3']);
        $this->assertEquals('boolean', $casts['flag4']);
    }

    /** @test */
    public function it_can_be_created_using_factory()
    {
        $configuration = Configuration::factory()->create();
        
        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertDatabaseHas('configurations', [
            'id' => $configuration->id
        ]);
    }

     /** @test */
     public function boolean_fields_are_cast_properly()
     {
         $configuration = Configuration::factory()->create([
             'first_config' => 1,
             'flag1' => 0,
             'flag2' => 1,
             'flag3' => 0,
             'flag4' => 1
         ]);
         
         $this->assertTrue($configuration->first_config);
         $this->assertFalse($configuration->flag1);
         $this->assertTrue($configuration->flag2);
         $this->assertFalse($configuration->flag3);
         $this->assertTrue($configuration->flag4);
     }

    /** @test */
    public function it_has_timestamps()
    {
        $configuration = new Configuration();
        $this->assertTrue($configuration->usesTimestamps());
    }
}
