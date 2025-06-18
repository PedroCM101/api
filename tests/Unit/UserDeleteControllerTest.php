<?php

namespace Tests\Unit;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserDeleteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_user_be_deleted(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $toDelete = User::factory()->create();

        $response = $this->deleteJson(route('user.delete', ['user' => $toDelete->id]));

        $response->assertStatus(200);

        $this->assertSoftDeleted('users', [
            'id' => $toDelete->id,
        ]);
    }

    public function test_non_existing_user_cant_be_deleted(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson(route('user.delete', ['user' => 999]));

        $response->assertStatus(404);
    }

    public function test_you_need_to_be_logged_to_delete_user(): void
    {
        $response = $this->deleteJson(route('user.delete', ['user' => 999]));

        $response->assertStatus(401);
    }

    public function test_you_cant_delete_yourself(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->deleteJson(route('user.delete', ['user' => $user->id]));

        $response->assertStatus(401);
    }
}
