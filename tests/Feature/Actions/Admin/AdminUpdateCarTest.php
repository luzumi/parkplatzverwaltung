<?php

namespace Actions\Admin;

use App\Actions\Admin\AdminUpdateCar;
use App\Actions\CreateMessage;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUpdateCarTest extends TestCase
{
    use RefreshDatabase;

    public function testHandle()
    {
        $faker = Faker::create();
        $password = $faker->password;
        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt($password),
            'role' => 'client',
            'last_thread_id' => 1
        ]);
        $admin = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt($password),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);
        $car = Car::create([
            'user_id' => $user->id,
            'sign' => 'Sign',
            'manufacturer' => 'Manufacturer',
            'model' => 'Model',
            'color' => 'Color',
            'image' => 'image.jpg',
            'status' => 1
        ]);
        $adminUpdateCar = new AdminUpdateCar();
        $carRequest = new CarRequest([
            'sign' => 'newSign',
            'manufacturer' => 'newManufacturer',
            'model' => 'newModel',
            'color' => 'newColor',
            'image' => 'newImage.jpg'
        ]);
        $setImageName = new SetImageName();
        $createMessage = new CreateMessage();
        $car_id = $car->id;

        $response = $adminUpdateCar->handle($carRequest, $setImageName, $car_id, $createMessage);

        $this->assertTrue($response);
        $this->assertDatabaseHas('cars', [
            'sign' => 'newSign',
            'manufacturer' => 'newManufacturer',
            'model' => 'newModel',
            'color' => 'newColor',
        ]);
        $this->assertDatabaseHas('log_messages', [
            'user_id' => $car->user_id,
            'message' => MessageType::EditCar,
            'car_id' => $car->id,
            'status' => 'closed',
        ]);
    }
}
