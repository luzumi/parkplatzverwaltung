<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingSpot>
 */
class ParkingSpotFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $number = 1;
        return [
            'user_id'=> User::inRandomOrder()->first()->id,
            'number' => $number++,
            'row' =>  $number%4,
            'image' => 'frei.jpg',
            'status' => 'frei',
        ];
    }

}
