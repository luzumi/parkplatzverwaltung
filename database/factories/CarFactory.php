<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'sign' => Str::random(8),
            'manufacturer' => $this->faker->company(),
            'model' => $this->faker->name(),
            'color' => $this->faker->colorName(),
            'image' => $this->faker->imageUrl,
            'status'=> true,
        ];
    }
}
