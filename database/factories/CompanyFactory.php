<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userId = fake()->randomElement(User::where('role', 'in', [User::ADMIN, User::SUPER_ADMIN])->get()->pluck('_id'));
        return [
            'name' => fake()->company,
            'address' => fake()->address,
            'established_year' => fake()->year(),
            'created_by' => $userId,
            'updated_by' => $userId,
        ];
    }
}
