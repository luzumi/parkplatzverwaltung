<?php

namespace Tests\Feature\Models;

use App\Models\Car;
use App\Models\LogMessage;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogMessageTest extends TestCase
{
    use RefreshDatabase;

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

        $this->parking_spot = ParkingSpot::create([
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'number' => $this->faker->randomNumber(1),
            'row' => $this->faker->randomNumber(1),
            'image' => $this->faker->image,
            'status' => 'free'
        ]);

        $this->message = LogMessage::create([
            'user_id' => $this->user->id,
            'receiver_user_id' => $this->user2->id,
            'message' => $this->faker->sentence,
            'car_id' => $this->car->id,
            'parking_spot_id' => $this->parking_spot->id,
            'status' => 'reserviert'
        ]);

    }

    public function testLogMessageIsSavedInDatabase()
    {
        $this->assertInstanceOf(LogMessage::class, $this->message);
        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->message->user_id,
            'receiver_user_id' => $this->message->receiver_user_id,
            'message' => $this->message->message,
            'car_id' => $this->message->car_id,
            'parking_spot_id' => $this->message->parking_spot_id,
            'status' => $this->message->status
        ]);
    }

    public function testItReturnsAnEloquentRelationship()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $this->message->user());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $this->message->car());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $this->message->parkingSpot());
    }

    public function testItUsesTheCorrectTable()
    {
        $this->assertEquals('log_messages', $this->message->getTable());
    }

    public function testItUsesTheCorrectPrimaryKeyAttribute()
    {
        $this->assertEquals('users.id', $this->message->user()->getQualifiedOwnerKeyName());
        $this->assertEquals('cars.id', $this->message->car()->getQualifiedOwnerKeyName());
        $this->assertEquals('parking_spots.id', $this->message->parkingSpot()->getQualifiedOwnerKeyName());
    }

    public function testItUsesTheCorrectForeignKeyAttribute()
    {
        $this->assertEquals('user_id', $this->message->user()->getForeignKeyName());
        $this->assertEquals('car_id', $this->message->car()->getForeignKeyName());
        $this->assertEquals('parking_spot_id', $this->message->parkingSpot()->getForeignKeyName());
    }
}
//1. Testen Sie, ob die Funktion user() eine Eloquent-Beziehung zurückgibt.
//
//2. Testen Sie, ob die Funktion user() die richtige Klasse zurück gibt.
//
//3. Testen Sie, ob die Funktion user() die richtige Tabelle verwendet.
//
//4. Testen Sie, ob die Funktion user() das richtige Primärschlüssel-Attribut für die Eloquent-Beziehung verwendet.
//
//5. Testen Sie, ob die Funktion user() die richtige Fremdschlüssel-Eigenschaft für die Eloquent-Beziehung verwendet.
//
//6. Testen Sie, ob die Funktion user() das richtige Modell zurückgibt.
