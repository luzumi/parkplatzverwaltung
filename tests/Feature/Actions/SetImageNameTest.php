<?php

namespace Actions;

use App\Actions\SetImageName;
use App\Http\Requests\CarRequest;
use App\Http\Requests\ParkingSpotRequest;
use App\Http\Requests\UserPictureRequest;
use App\Models\Address;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SetImageNameTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setImageName = new SetImageName();
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

        $this->parkingSpot = ParkingSpot::create([
            'user_id' => $this->admin->id,
            'car_id' => null,
            'number' => $this->faker->numberBetween(1,10),
            'row' => $this->faker->numberBetween(1,3),
            'image' => 'frei.jgp',
            'status' => 'frei'
        ]);
    }

    public function testHandleUserImage()
    {
        $request = new UserPictureRequest([ 'image' => 'testBild.jpg']);

        $imageName = $this->setImageName->handle($request, $this->user);

        $this->assertEquals($imageName, $this->user->image);
        Storage::disk('public/media')->assertExists($imageName);
    }

    public function testHandleCarImage()
    {
        $request = new CarRequest([
            'user_id' => $this->user->id,
            'sign' => $this->car->sign,
            'manufacturer' => $this->car->manufacturer,
            'model' => $this->car->model,
            'color' => $this->car->color,
        ]);

        $imageName = $this->setImageName->handle($request, $this->car);

        $this->assertEquals($imageName, $this->car->image);
        Storage::disk('public/media')->assertExists($imageName);
    }

    public function testHandleParkingSpotImage()
    {
        $request = new ParkingSpotRequest([
            'status' => 'besetzt',
        ]);

        $imageName = $this->setImageName->handle($request, $this->parkingSpot);

        $this->assertEquals($imageName, $this->parkingSpot->image);
        Storage::disk('public/media')->assertExists($imageName);
    }
}
