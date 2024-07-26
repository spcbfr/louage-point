<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class StationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'longitude' => fake()->longitude(),
            'latitude' => fake()->latitude(),
        ];
    }
}
