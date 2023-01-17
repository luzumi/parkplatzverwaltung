<?php

namespace Http\Controllers\Admin;

use App\Actions\CreateMessage;
use App\Actions\SaveAddress;
use App\Actions\SetImageName;
use App\Actions\UpdateUser;
use App\Http\Controllers\Admin\AdminParkingSpotController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\UserRequest;
use App\Models\Address;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminParkingSpotControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

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
            'user_id' => $this->admin->id,
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

        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->request = new UserRequest([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ]);
        $this->addressRequest = new AddressRequest();
        $this->setImage = new SetImageName();
        $this->createMessage = new CreateMessage();
        $this->saveAddress = new SaveAddress();
        $this->updateUser = new UpdateUser();
    }

    public function testStoreNewParkingSpot()
    {
        $countBefore = ParkingSpot::count();

        $response = $this->actingAs($this->admin)->post(route('admin.parking_spot.store'), [
            'status' => 'frei',
        ]);

        $response->assertStatus(302);
        $this->assertEquals($countBefore + 1, ParkingSpot::count());

    }

    public function testEdit()
    {
        $countBefore = ParkingSpot::count();
        $before = ParkingSpot::first();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.parking-spot.update', [
                'id' => $this->parking_spot->id,
                'status' => 'frei,'
            ]));

        $response->assertStatus(302);
        $this->assertEquals($countBefore, ParkingSpot::count());
        $this->assertNotEquals($before->status, ParkingSpot::first()->status);
    }

    public function testDelete()
    {
        $countBefore = ParkingSpot::where('deleted_at', null)->count();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.parking_spot.delete', [ 'id' => $this->parking_spot->id]));

        $response->assertStatus(302);
        $this->assertEquals($countBefore - 1, ParkingSpot::where('deleted_at', null)->count());
        $this->assertDatabaseMissing('parking_spots', ['deleted_at' => null]);
    }

    public function testIndex()
    {
        $title = 'Admin-Panel - Parkplatzübersicht - Parkplatzverwaltung';

        $response = $this->actingAs($this->admin)->get(route('admin.parking_spot.index'));

        $response->assertStatus(200);
        $response->assertSee($title);
        $this->assertEquals(
            $this->parking_spot->user_id,
            $response->original->viewData['parking_spots']->first()->user_id
        );
        $this->assertEquals($this->car->id, $response->original->viewData['parking_spots']->first()->car_id);

    }

    public function testUpdate()
    {
        $this->assertTrue(true);
    }
}
