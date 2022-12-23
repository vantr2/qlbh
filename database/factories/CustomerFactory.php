<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $genderToText = [
            Customer::MALE => 'male',
            Customer::FEMALE => 'female',
            Customer::OTHER => null,
        ];

        $gender = fake()->numberBetween(1, 3);
        $userId = fake()->randomElement(User::whereIn('role', [User::ADMIN, User::SUPER_ADMIN])->get()->pluck('_id'));

        return [
            'first_name' => fake()->firstName([$genderToText[$gender]]),
            'last_name'  => fake()->lastName(),
            'age' => fake()->numberBetween(18, 65),
            'gender' => $gender,
            'birthday' => fake()->date(),
            'address' => fake()->address,
            'type' => fake()->randomElement([Customer::VIP, Customer::NORMAL]),
            'company_id' => fake()->randomElement(Company::all()->pluck('_id')),
            'created_by' => $userId,
            'updated_by' => $userId,
        ];
    }
}
