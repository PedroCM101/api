<?php

namespace Database\Seeders;

use App\Domain\User\Models\User;
use Illuminate\Database\Seeder;

class FakeUserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->count(20)->create();
    }
}
