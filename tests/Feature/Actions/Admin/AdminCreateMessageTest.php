<?php

namespace Actions\Admin;

use App\Actions\Admin\AdminCreateMessage;
use App\Actions\CreateMessage;
use App\Enums\MessageType;
use App\Models\Address;
use App\Models\Car;
use App\Models\LogMessage;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        parent::setUp();

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
        $this->address = Address::create([
            'user_id' => $this->user->id,
            'Land' => $this->faker->country,
            'PLZ' => $this->faker->randomNumber(5),
            'Stadt' => $this->faker->city,
            'Strasse' => $this->faker->streetName,
            'Nummer' => $this->faker->numerify(),
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

        $this->parking_spot = ParkingSpot::create([
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'number' => 1,
            'row' => 1,
            'image' => 'besetzt',
            'status' => 'besetzt',
            'deleted_at' => null
        ]);

        $this->createMessage = new AdminCreateMessage();
    }


    public function testHandleAddParkingSpot()
    {
        $message = MessageType::AddParkingSpot;
        $userId = $this->admin->id;
        $carId = $this->admin->id;
        $parkingSpotId = $this->parking_spot->id;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleDeleteUser()
    {
        $message = MessageType::DeleteUser;
        $userId = $this->admin->id;
        $carId = $this->admin->id;
        $parkingSpotId = $this->parking_spot->id;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleAddUser()
    {
        $message = MessageType::AddUser;
        $userId = $this->admin->id;
        $carId = null;
        $parkingSpotId = null;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleEditAddress()
    {
        $message = MessageType::EditAddress;
        $userId = $this->admin->id;
        $carId = $this->admin->id;
        $parkingSpotId = $this->parking_spot->id;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleEditUser()
    {
        $message = MessageType::EditUser;
        $userId = $this->admin->id;
        $carId = null;
        $parkingSpotId = null;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleAddCar()
    {
        $message = MessageType::AddCar;
        $userId = $this->admin->id;
        $carId = $this->admin->id;
        $parkingSpotId = null;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleEditCar()
    {
        $message = MessageType::EditCar;
        $userId = $this->admin->id;
        $carId = $this->admin->id;
        $parkingSpotId = $this->parking_spot->id;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleDeleteCar()
    {
        $message = MessageType::DeleteCar;
        $userId = $this->admin->id;
        $carId = $this->admin->id;
        $parkingSpotId = $this->parking_spot->id;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleEditParkingSpot()
    {
        $message = MessageType::EditParkingSpot;
        $userId = $this->admin->id;
        $carId = $this->admin->id;
        $parkingSpotId = $this->parking_spot->id;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }

    public function testHandleResetParkingSpot()
    {
        $message = MessageType::ResetParkingSpot;
        $userId = $this->user->id;
        $carId = $this->user->id;
        $parkingSpotId = $this->parking_spot->id;

        $logMessage = $this->createMessage->handle($message, $userId, $carId, $parkingSpotId);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'receiver_user_id' => $userId,
            'message' => $message,
            'car_id' => $carId,
            'parking_spot_id' => $parkingSpotId,
            'status' => 'closed',
        ]);

        $this->assertInstanceOf(LogMessage::class, $logMessage);
    }
}
