<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['email' => 'admin@admin.com', 'name' => 'Administrator Account', 'password' => bcrypt('password')],
        ])->each(function ($data) {
            User::firstOrCreate([
                'email' => $data['email'],
            ], array_merge($data, [
                'email_verified_at' => now(),
            ]));
        });
    }
}
