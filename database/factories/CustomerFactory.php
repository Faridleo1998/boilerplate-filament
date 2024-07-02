<?php

namespace Database\Factories;

use App\Enums\Enums\IdentificationTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        $userIds = User::all()->pluck('id')->toArray();

        return [
            'identification_type' => fake()->randomElement(IdentificationTypeEnum::class),
            'identification_number' => fake()->unique()->numerify('##########'),
            'names' => fake()->firstName(),
            'last_names' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'is_featured' => fake()->boolean(),

            'created_by' => fake()->randomElement($userIds),
        ];
    }
}
