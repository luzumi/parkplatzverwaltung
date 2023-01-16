<?php

namespace Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminHomeController;
use App\Models\Address;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Tests\TestCase;

class AdminHomeControllerTest extends TestCase
{

    public function testIndex()
    {
        $title = 'Adminpage - Home -Parkplatzverwaltung';
        $subtitle = '';
        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        $this->admin = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($this->password),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);

        $this->user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($this->password),
            'role' => 'client',
            'last_thread_id' => 1
        ]);

        $this->car = Car::create([
            'user_id' => $this->user->id,
            'sign' => $this->faker->word,
            'manufacturer' => $this->faker->word,
            'model' => $this->faker->word,
            'color' => $this->faker->colorName,
            'image' => $this->faker->image,
            'status' => true
        ]);

        $this->parkingSpot = ParkingSpot::create([
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'number' => $this->faker->randomNumber(2),
            'row' => $this->faker->randomNumber(1),
            'image' => $this->faker->image,
            'status' => $this->faker->boolean
        ]);

        $response = $response = $this->actingAs($this->admin);
    }
}
