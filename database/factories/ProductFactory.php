<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userId = fake()->randomElement(User::all()->pluck('_id'));
        return [
            'name' => fake()->numerify('Item ###'),
            'price' => intval(fake()->numberBetween(1, 100)) * intval(fake()->randomElement(['10000', '100000'])),
            'description' => fake()->text(),
            'created_by' => $userId,
            'updated_by' => $userId,
        ];
    }
}
