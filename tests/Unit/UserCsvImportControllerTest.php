<?php

namespace Tests\Feature;

use App\Domain\User\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class UserCsvImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $csvPath;

    protected function tearDown(): void
    {
        parent::tearDown();

        if (isset($this->csvPath) && File::exists($this->csvPath)) {
            File::delete($this->csvPath);
        }
    }

    private function createCsvFile(string $content): UploadedFile
    {
        $this->csvPath = storage_path('app/test.csv');

        File::put($this->csvPath, $content);

        return new UploadedFile(
            $this->csvPath,
            'test.csv',
            'text/csv',
            null,
            true
        );
    }



    public function test_it_imports_new_users_from_csv()
    {
        $auth = User::factory()->create();

        $csv = "name,surname,email,phone,address\n"
            . "John,Doe,john@example.com,123456789,123 Street\n";

        $file = $this->createCsvFile($csv);

        $this->actingAs($auth);

        $response = $this->postJson(route('user.import'), ['csv' => $file]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_it_updates_existing_users_from_csv()
    {
        $auth = User::factory()->create();

        $existing = User::factory()->create([
            'email' => 'jane@example.com',
            'name' => 'OldName',
        ]);

        $csv = "name,surname,email,phone,address\n"
            . "Jane,Updated,jane@example.com,9999999,New Address\n";

        $file = $this->createCsvFile($csv);

        $this->actingAs($auth);

        $this->postJson(route('user.import'), ['csv' => $file])->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'surname' => 'Updated',
            'address' => 'New Address',
        ]);
    }

    public function test_it_restores_soft_deleted_users()
    {
        $auth = User::factory()->create();

        $deleted = User::factory()->create([
            'email' => 'restore@example.com',
        ]);

        $deleted->delete();

        $this->assertDatabaseHas('users', [
            'email' => 'restore@example.com',
            'deleted_at' => now(),
        ]);

        $csv = "name,surname,email,phone,address\n"
            . "Restored,User,restore@example.com,5555555,Restored Street\n";

        $file = $this->createCsvFile($csv);

        $this->actingAs($auth);

        $this->postJson(route('user.import'), ['csv' => $file])->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'restore@example.com',
            'deleted_at' => null,
        ]);
    }

    public function test_file_must_be_csv()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $png = UploadedFile::fake()->image('image.png');

        $response = $this->postJson(route('user.import'), ['csv' => $png]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('csv');
    }
}
