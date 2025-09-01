<?php

namespace Tests\Unit\Models;

use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /** @test */
    public function it_can_create_a_feature()
    {
        $feature = Feature::create([
            'code' => 'TEST_FEATURE',
            'name' => 'Test Feature',
            'category' => 'testing',
            'description' => 'A test feature',
            'active' => true
        ]);

        $this->assertDatabaseHas('features', [
            'code' => 'TEST_FEATURE',
            'name' => 'Test Feature',
            'category' => 'testing'
        ]);
    }

    /** @test */
    public function it_can_read_a_feature()
    {
        $feature = Feature::factory()->create([
            'code' => 'READ_TEST',
            'name' => 'Read Test'
        ]);

        $found = Feature::find($feature->id);
        
        $this->assertEquals('READ_TEST', $found->code);
        $this->assertEquals('Read Test', $found->name);
    }

    /** @test */
    public function it_can_update_a_feature()
    {
        $feature = Feature::factory()->create(['name' => 'Original Name']);

        $feature->update(['name' => 'Updated Name']);

        $this->assertEquals('Updated Name', $feature->fresh()->name);
    }

    /** @test */
    public function it_can_delete_a_feature()
    {
        $feature = Feature::factory()->create();

        $feature->delete();

        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    /** @test */
    public function it_casts_active_to_boolean()
    {
        $feature = Feature::factory()->create(['active' => 1]);

        $this->assertIsBool($feature->active);
        $this->assertTrue($feature->active);
    }

    /** @test */
    public function is_active_returns_true_for_active_feature()
    {
        Feature::factory()->create([
            'code' => 'ACTIVE_FEATURE',
            'active' => true
        ]);

        $this->assertTrue(Feature::isActive('ACTIVE_FEATURE'));
    }

    /** @test */
    public function is_active_returns_false_for_inactive_feature()
    {
        Feature::factory()->create([
            'code' => 'INACTIVE_FEATURE',
            'active' => false
        ]);

        $this->assertFalse(Feature::isActive('INACTIVE_FEATURE'));
    }

    /** @test */
    public function is_active_returns_true_for_non_existent_feature()
    {
        $this->assertTrue(Feature::isActive('NON_EXISTENT'));
    }

    /** @test */
    public function is_active_uses_cache()
    {
        Feature::factory()->create([
            'code' => 'CACHED_FEATURE',
            'active' => true
        ]);

        // Premier appel - met en cache
        Feature::isActive('CACHED_FEATURE');

        // Vérifier que la valeur est en cache
        $this->assertTrue(Cache::has('feature:CACHED_FEATURE'));
    }

    /** @test */
    public function get_active_by_category_returns_correct_array()
    {
        Feature::factory()->create([
            'code' => 'FEATURE_1',
            'category' => 'test_category',
            'active' => true
        ]);

        Feature::factory()->create([
            'code' => 'FEATURE_2',
            'category' => 'test_category',
            'active' => false
        ]);

        $result = Feature::getActiveByCategory('test_category');

        $this->assertIsArray($result);
        $this->assertEquals([
            'FEATURE_1' => true,
            'FEATURE_2' => false
        ], $result);
    }

    /** @test */
    public function reset_cache_clears_feature_cache()
    {
        $feature = Feature::factory()->create([
            'code' => 'CACHE_TEST',
            'category' => 'test_cat'
        ]);

        // Mettre en cache
        Feature::isActive('CACHE_TEST');
        Feature::getActiveByCategory('test_cat');

        $this->assertTrue(Cache::has('feature:CACHE_TEST'));

        // Réinitialiser le cache
        Feature::resetCache('CACHE_TEST');

        $this->assertFalse(Cache::has('feature:CACHE_TEST'));
        $this->assertFalse(Cache::has('features:category:test_cat'));
    }

    /** @test */
    public function reset_all_cache_clears_all_caches()
    {
        Feature::factory()->create(['code' => 'TEST_1', 'category' => 'cat_1']);
        Feature::factory()->create(['code' => 'TEST_2', 'category' => 'cat_2']);

        // Mettre en cache
        Feature::isActive('TEST_1');
        Feature::isActive('TEST_2');
        Feature::getActiveByCategory('cat_1');
        Feature::getActiveByCategory('cat_2');

        Feature::resetAllCache();

        $this->assertFalse(Cache::has('feature:TEST_1'));
        $this->assertFalse(Cache::has('feature:TEST_2'));
        $this->assertFalse(Cache::has('features:category:cat_1'));
        $this->assertFalse(Cache::has('features:category:cat_2'));
    }
}
