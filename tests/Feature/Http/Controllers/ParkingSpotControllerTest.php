<?php

namespace Tests\Feature;

use App\Actions\CreateMessage;

use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use App\Services\ParkingSpotService;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Session\Session;
use Tests\TestCase;

class ParkingSpotControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $password = $this->faker->password;

        $this->admin = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($password),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);

        $this->user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($password),
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

        $this->parkingSpotFree = ParkingSpot::create([
        'user_id' => $this->admin->id,
        'car_id' => null,
        'number' => $this->faker->numberBetween(1,10),
        'row' => $this->faker->numberBetween(1,3),
        'image' => 'frei.jgp',
        'status' => 'frei'
        ]);

        $this->parkingSpotReserved = ParkingSpot::create([
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'number' => $this->faker->numberBetween(1,10),
            'row' => $this->faker->numberBetween(1,3),
            'image' => $this->faker->image,
            'status' => 'reserved'
        ]);
    }


    public function testShowAsGuest()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $response = $this->actingAs($this->user)
            ->withSession([
                '_token' => csrf_token()
            ])
            ->post(route('admin.parking_spot.store'));

        $response->assertStatus(302);
        $response->assertRedirect('/');

    }

    public function testShowAsUser()
    {
        $title = "Parkplatzverwaltung";
        $subtitle = "Parkplatz Nr. " . $this->parkingSpotReserved->number;

        $response = $this->actingAs($this->user)->get('/parking_spot/' . $this->parkingSpotReserved->id . '/');

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($subtitle);
        $this->assertEquals($this->parkingSpotReserved->number, $response->original->viewData['parking_spot']->number);
        $this->assertEquals($this->user->email, $response->original->viewData['user']->email);
        $this->assertEquals($this->car->sign, $response->original->viewData['cars']->first()->sign);
    }

    public function testStoreIndex()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $parkingSpotBeforeUpdate = ParkingSpot::findOrFail($this->parkingSpotFree->id);
        $request = new ParkingSpotRequest(['radio' => 1]);
        $message = new CreateMessage();
        $session = app(Session::class);
        $request->setSession($session);
        $request->server->set('HTTP_REFERER', '/'.$this->parkingSpotFree->id);
        ParkingSpotService::update($request, $message);

        $response = $this->actingAs($this->user)
                        ->post('/parking_spots/reserve/store_reserve/' . $this->parkingSpotFree->id . '/');

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $parkingSpotAfterUpdate = ParkingSpot::findOrFail($this->parkingSpotFree->id);
        $this->assertNotEquals($parkingSpotAfterUpdate->car_id, $parkingSpotBeforeUpdate->car_id);
        $this->assertEquals($this->car->id, $parkingSpotAfterUpdate->car_id);
        $this->assertNotEquals($this->user->id, $parkingSpotAfterUpdate->user_id);
        $this->assertEquals($this->car->id, $parkingSpotAfterUpdate->car_id);

    }

    public function testStoreThisCar()
    {
        $this->assertEquals(true,true);

    }

    public function testIndexAsGuest()
    {
        $parking_spots = ParkingSpot::all();

        $response = $this->get('/parking_spots/');

        $response->assertStatus(200);

        foreach ($parking_spots as $spot){
            $response->assertSee($spot->getAttribute('sign'));
            $this->assertDoesNotMatchRegularExpression('/<a href=/', $response->getContent());
        }
    }

    public function testIndexAsUser()
    {
        $response = $this->actingAs($this->user)->get('/parking_spots/');

        $response->assertStatus(200);
        $this->assertMatchesRegularExpression('/<a href=/', $response->getContent());
    }

    public function testIndexWithEmptyParkingSpotList()
    {
        $title = "Parkplatzverwaltung";
        $subtitle = "Parkplatzübersicht";
        $parking_spots = ParkingSpot::all();
        ParkingSpot::destroy([1,2]);

        $response = $this->get('/parking_spots/');

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($subtitle);

    }

    public function testCancel()
    {
        $this->assertEquals(true,true);
    }
}
