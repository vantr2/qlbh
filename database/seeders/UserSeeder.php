<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'example@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make(config('auth.admin.default_password')),
                'role' => User::SUPER_ADMIN,
            ]
        );
        User::factory()->count(20)->create();
        User::factory()->count(3)->admin()->create();
    }
}
