<?php

namespace Tests\Unit\Services;

use App\Services\DayService;
use PHPUnit\Framework\TestCase;

class DayServiceTest extends TestCase
{
    private DayService $dayService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dayService = new DayService();
    }

    /** @test */
    public function it_returns_correct_day_number_for_valid_days()
    {
        $this->assertEquals(0, $this->dayService->getDayNumber('dimanche'));
        $this->assertEquals(1, $this->dayService->getDayNumber('lundi'));
        $this->assertEquals(2, $this->dayService->getDayNumber('mardi'));
        $this->assertEquals(3, $this->dayService->getDayNumber('mercredi'));
        $this->assertEquals(4, $this->dayService->getDayNumber('jeudi'));
        $this->assertEquals(5, $this->dayService->getDayNumber('vendredi'));
        $this->assertEquals(6, $this->dayService->getDayNumber('samedi'));
    }

    /** @test */
    public function it_handles_case_insensitive_day_names()
    {
        $this->assertEquals(1, $this->dayService->getDayNumber('LUNDI'));
        $this->assertEquals(2, $this->dayService->getDayNumber('Mardi'));
        $this->assertEquals(3, $this->dayService->getDayNumber('MeRcReDi'));
        $this->assertEquals(5, $this->dayService->getDayNumber('VENDREDI'));
    }

    /** @test */
    public function it_returns_zero_for_invalid_day_names()
    {
        $this->assertEquals(0, $this->dayService->getDayNumber('invalid'));
        $this->assertEquals(0, $this->dayService->getDayNumber('monday'));
        $this->assertEquals(0, $this->dayService->getDayNumber('sunday'));
        $this->assertEquals(0, $this->dayService->getDayNumber(''));
        $this->assertEquals(0, $this->dayService->getDayNumber('123'));
        $this->assertEquals(0, $this->dayService->getDayNumber('dim'));
    }

    /** @test */
    public function it_handles_special_characters_and_spaces()
    {
        $this->assertEquals(0, $this->dayService->getDayNumber(' dimanche '));
        $this->assertEquals(0, $this->dayService->getDayNumber('dimanche!'));
        $this->assertEquals(0, $this->dayService->getDayNumber('di manche'));
        $this->assertEquals(0, $this->dayService->getDayNumber('dimanche@'));
    }

    /** @test */
    public function it_returns_all_french_days()
    {
        $expectedDays = [
            'dimanche',
            'lundi', 
            'mardi',
            'mercredi',
            'jeudi',
            'vendredi',
            'samedi'
        ];
        
        $actualDays = $this->dayService->getAllDays();
        
        $this->assertIsArray($actualDays);
        $this->assertCount(7, $actualDays);
        $this->assertEquals($expectedDays, $actualDays);
    }

    /** @test */
    public function it_returns_consistent_day_mapping()
    {
        $allDays = $this->dayService->getAllDays();
        
        foreach ($allDays as $day) {
            $dayNumber = $this->dayService->getDayNumber($day);
            $this->assertIsInt($dayNumber);
            $this->assertGreaterThanOrEqual(0, $dayNumber);
            $this->assertLessThanOrEqual(6, $dayNumber);
        }
    }

    /** @test */
    public function it_handles_null_and_non_string_inputs()
    {
        // Test avec des valeurs qui pourraient causer des erreurs
        $this->assertEquals(0, $this->dayService->getDayNumber('0'));
        $this->assertEquals(0, $this->dayService->getDayNumber('false'));
        $this->assertEquals(0, $this->dayService->getDayNumber('null'));
    }
}
