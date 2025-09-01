<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PinService;
use App\Models\User;
use App\Models\UserPin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class PinServiceTest extends TestCase
{
    use RefreshDatabase;

    private PinService $pinService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pinService = new PinService();
    }

    /** @test */
    public function verify_pin_returns_1_when_pin_is_correct()
    {
        // Arrange
        $user = User::factory()->create();
        $correctPin = '1234';
        
        UserPin::create([
            'user_id' => $user->id,
            'pin_code' => Hash::make($correctPin),
            'flag' => true
        ]);

        // Act
        $result = $this->pinService->verifyPin($user->id, $correctPin);

        // Assert
        $this->assertEquals(1, $result);
    }

    /** @test */
    public function verify_pin_returns_0_when_pin_is_incorrect()
    {
        // Arrange
        $user = User::factory()->create();
        $correctPin = '1234';
        $incorrectPin = '5678';
        
        UserPin::create([
            'user_id' => $user->id,
            'pin_code' => Hash::make($correctPin),
            'flag' => true
        ]);

        // Act
        $result = $this->pinService->verifyPin($user->id, $incorrectPin);

        // Assert
        $this->assertEquals(0, $result);
    }

    /** @test */
    public function verify_pin_returns_0_when_user_has_no_pin()
    {
        // Arrange
        $user = User::factory()->create();
        $pinCode = '1234';

        // Act
        $result = $this->pinService->verifyPin($user->id, $pinCode);

        // Assert
        $this->assertEquals(0, $result);
    }

    /** @test */
    public function verify_pin_returns_0_when_user_does_not_exist()
    {
        // Arrange
        $nonExistentUserId = 99999;
        $pinCode = '1234';

        // Act
        $result = $this->pinService->verifyPin($nonExistentUserId, $pinCode);

        // Assert
        $this->assertEquals(0, $result);
    }

    /** @test */
    public function verify_pin_with_empty_pin_code()
    {
        // Arrange
        $user = User::factory()->create();
        $correctPin = '1234';
        
        UserPin::create([
            'user_id' => $user->id,
            'pin_code' => Hash::make($correctPin),
            'flag' => true
        ]);

        // Act
        $result = $this->pinService->verifyPin($user->id, '');

        // Assert
        $this->assertEquals(0, $result);
    }

    /** @test */
    public function verify_pin_with_numeric_string_pin()
    {
        // Arrange
        $user = User::factory()->create();
        $numericPin = '0000';
        
        UserPin::create([
            'user_id' => $user->id,
            'pin_code' => Hash::make($numericPin),
            'flag' => true
        ]);

        // Act
        $result = $this->pinService->verifyPin($user->id, $numericPin);

        // Assert
        $this->assertEquals(1, $result);
    }

    /** @test */
    public function verify_pin_with_long_pin_code()
    {
        // Arrange
        $user = User::factory()->create();
        $longPin = '123456789012345';
        
        UserPin::create([
            'user_id' => $user->id,
            'pin_code' => Hash::make($longPin),
            'flag' => true
        ]);

        // Act
        $result = $this->pinService->verifyPin($user->id, $longPin);

        // Assert
        $this->assertEquals(1, $result);
    }

    /** @test */
    public function verify_pin_with_special_characters()
    {
        // Arrange
        $user = User::factory()->create();
        $specialPin = '12@#';
        
        UserPin::create([
            'user_id' => $user->id,
            'pin_code' => Hash::make($specialPin),
            'flag' => true
        ]);

        // Act
        $result = $this->pinService->verifyPin($user->id, $specialPin);

        // Assert
        $this->assertEquals(1, $result);
    }

    /** @test */
    public function verify_pin_case_sensitivity()
    {
        // Arrange
        $user = User::factory()->create();
        $lowerPin = 'abcd';
        $upperPin = 'ABCD';
        
        UserPin::create([
            'user_id' => $user->id,
            'pin_code' => Hash::make($lowerPin),
            'flag' => true
        ]);

        // Act
        $result = $this->pinService->verifyPin($user->id, $upperPin);

        // Assert
        $this->assertEquals(0, $result);
    }

    /** @test */
    public function verify_pin_multiple_users_different_pins()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pin1 = '1111';
        $pin2 = '2222';
        
        UserPin::create([
            'user_id' => $user1->id,
            'pin_code' => Hash::make($pin1),
            'flag' => true
        ]);
        
        UserPin::create([
            'user_id' => $user2->id,
            'pin_code' => Hash::make($pin2),
            'flag' => true
        ]);

        // Act & Assert
        $this->assertEquals(1, $this->pinService->verifyPin($user1->id, $pin1));
        $this->assertEquals(1, $this->pinService->verifyPin($user2->id, $pin2));
        $this->assertEquals(0, $this->pinService->verifyPin($user1->id, $pin2));
        $this->assertEquals(0, $this->pinService->verifyPin($user2->id, $pin1));
    }
}
