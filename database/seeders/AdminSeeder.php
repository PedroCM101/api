<?php

namespace Database\Seeders;

use App\Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            [
                'email' => 'admin@admin.com'
            ],
            [
                'name' => 'Admin',
                'surname' => 'Admin',
                'phone' => '569878519',
                'address' => 'Calle de Pablo Neruda',
                'password' => Hash::make('12345'),
            ]
        );
    }
}
