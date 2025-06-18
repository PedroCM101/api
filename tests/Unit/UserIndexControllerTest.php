<?php

namespace Tests\Unit;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserIndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_get_listed(): void
    {
        $auth = User::factory()->create();

        $this->actingAs($auth);

        $users = User::factory()->count(10)->create();

        $response = $this->getJson(route('user.index'));

        $response->assertStatus(200);

        foreach ($users as $user) {
            $response->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
            ]);
        }

        $response->assertJsonMissing([
            'id' => $auth->id,
            'email' => $auth->email,
        ]);
    }

    public function test_soft_deleted_users_are_not_listed(): void
    {
        $auth = User::factory()->create();

        $this->actingAs($auth);

        $users = User::factory()->count(10)->create();

        User::factory()->count(10)->create([
            'deleted_at' => now()
        ]);

        $response = $this->getJson(route('user.index'));

        $response->assertStatus(200);

        foreach ($users as $user) {
            $response->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
            ]);
        }

        $this->assertDatabaseCount('users', 21);

        $response->assertJsonCount(10, 'data');
    }
}
