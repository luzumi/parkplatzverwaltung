<?php

namespace Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Models\Address;
use App\Models\Car;
use App\Models\LogMessage;
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

        $this->logMessage = LogMessage::create([
            'user_id' => $this->user->id,
            'receiver_user_id' => $this->admin->id,
            'message' => MessageType::EditParkingSpot->value,
            'car_id' => $this->car->id,
            'parking_spot_id' => $this->parkingSpot->id,
            'status' => 'open'
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.home.index'));

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($subtitle);
        $response->assertSee($this->logMessage->message);
        $this->assertEquals(MessageType::EditParkingSpot->value, $response->original->viewData['messages']->first()->message);
        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->user->id,
            'receiver_user_id' => $this->admin->id,
            'message' => MessageType::EditParkingSpot->value,
            'car_id' => $this->car->id,
            'parking_spot_id' => $this->parkingSpot->id,
            'status' => 'open'
        ]);
    }
}
