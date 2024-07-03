<?php

namespace Database\Factories;

use App\Enums\Enums\IdentificationTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nnjeim\World\Models\City;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        $city = City::inRandomOrder()->first();

        return [
            'identification_type' => fake()->randomElement(IdentificationTypeEnum::class),
            'identification_number' => fake()->unique()->numerify('##########'),
            'names' => fake()->firstName(),
            'last_names' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'is_featured' => fake()->boolean(),
            'city_id' => $city->id,
            'state_id' => $city->state_id,
            'country_id' => $city->state->country_id,
        ];
    }
}
