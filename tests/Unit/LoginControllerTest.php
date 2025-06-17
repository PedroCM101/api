<?php

namespace Tests\Unit;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_user_can_log_in(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $request = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson(route('login'), $request);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'surname',
                'address',
                'email',
                'phone',
            ],
            'token',
        ]);
    }

    public function test_non_existing_user_cant_log_in(): void
    {
        $request = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('login'), $request);

        $response->assertStatus(422);
    }

    public function test_you_need_email(): void
    {
        $request = [
            'password' => 'password',
        ];

        $response = $this->postJson(route('login'), $request);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email' => 'El correo electrónico es obligatorio.']);
    }

    public function test_you_need_valid_email(): void
    {
        $request = [
            'email' => 'Invalid mail',
            'password' => 'password',
        ];

        $response = $this->postJson(route('login'), $request);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['email' => 'Debes ingresar un correo válido.']);
    }

    public function test_you_need_valid_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $request = [
            'email' => $user->email,
            'password' => 'qweqwe',
        ];

        $response = $this->postJson(route('login'), $request);

        $response->assertStatus(422);
    }
}
