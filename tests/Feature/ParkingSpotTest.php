<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\LogMessage;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParkingSpotTest extends TestCase
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
            'role' => 'client'
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
    }

    public function testParkingSpotIsSavedInDatabase()
    {
        $this->assertInstanceOf(ParkingSpot::class, $this->parking_spot);
        $this->assertDatabaseHas('parking_spots', [
            'user_id' => $this->parking_spot->user_id,
            'car_id' => $this->parking_spot->car_id,
            'number' => $this->parking_spot->number,
            'row' => $this->parking_spot->row,
            'image' => $this->parking_spot->image,
            'status' => $this->parking_spot->status
        ]);
    }

    public function testItReturnsAnEloquentRelationship()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $this->parking_spot->user());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $this->parking_spot->car());

    }

    public function testItUsesTheCorrectTable()
    {
        $this->assertEquals('parking_spots', $this->parking_spot->getTable());
    }

    public function testItUsesTheCorrectPrimaryKeyAttribute()
    {
        $this->assertEquals('users.id', $this->parking_spot->user()->getQualifiedOwnerKeyName());
        $this->assertEquals('cars.id', $this->parking_spot->car()->getQualifiedOwnerKeyName());
    }

    public function testItUsesTheCorrectForeignKeyAttribute()
    {
        $this->assertEquals('user_id', $this->parking_spot->user()->getForeignKeyName());
        $this->assertEquals('car_id', $this->parking_spot->car()->getForeignKeyName());
    }
}
