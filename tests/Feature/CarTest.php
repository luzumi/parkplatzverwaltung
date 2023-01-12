<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\LogMessage;
use App\Models\ParkingSpot;
use App\Models\User;
use Cmgmyr\Messenger\Models\Message;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CarTest extends TestCase
{
    use RefreshDatabase;

    private \Faker\Generator $faker;

    public function testCarCanBeCreated()
    {
        $this->assertInstanceOf(Car::class, $this->car);
        $this->assertDatabaseHas('cars', [
            'sign' => $this->car->sign,
            'user_id' => $this->user->id,
        ]);
    }

    public function testUserRelationship()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertEquals($this->user->id, $this->car->user_id);
    }


    public function testUserRelationshipLocalKey()
    {
        $this->assertEquals('id', $this->car->getKeyName());
    }

    public function testParkingSpotRelationship()
    {
        $parkingSpot = ParkingSpot::where('car_id','=',$this->car->id)->first();

        $this->assertInstanceOf(ParkingSpot::class, $parkingSpot);
    }

    public function testItUsesTheCorrectForeignKeyAttribute()
    {
        $this->assertEquals('car_id', $this->car->getForeignKey());
        $this->assertEquals('parking_spot_id', $this->parking_spot->getForeignKey());
    }

    public function testParkingSpotRelationships()
    {
        $this->assertInstanceOf(HasOne::class, $this->car->user());
        $this->assertInstanceOf(HasOne::class, $this->car->parkingSpot());
        $this->assertInstanceOf(HasOne::class, $this->car->message());
    }

    public function testCarIsSavedInDatabase()
    {
        $this->assertInstanceOf(Car::class, $this->car);
        $this->assertDatabaseHas('cars', [
            'user_id' => $this->car->user_id,
            'sign' => $this->car->sign,
            'manufacturer' => $this->car->manufacturer,
            'model' => $this->car->model,
            'color' => $this->car->color,
            'image' => $this->car->image,
            'status' => $this->car->status,
            'deleted_at' => null
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        $this->user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($this->password),
            'role' => 'client',
            'last_thread_id' => 1
        ]);

        $this->user2 = User::create([
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

        $this->message = LogMessage::create([
            'user_id' => $this->user->id,
            'receiver_user_id' => $this->user2->id,
            'message' => $this->faker->sentence,
            'status' => true
        ]);

        $this->parking_spot = ParkingSpot::create([
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'number' => $this->faker->randomNumber(1),
            'row' => $this->faker->randomNumber(1),
            'image' => $this->faker->image,
            'status' => 'free'
        ]);
    }
}
