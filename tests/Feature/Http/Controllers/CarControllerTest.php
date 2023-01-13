<?php

namespace Http\Controllers;

use App\Actions\CreateMessage;
use App\Actions\CreateNewCar;
use App\Actions\SetImageName;
use App\Http\Controllers\CarController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use App\Services\CarService;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Session\Session;
use Tests\TestCase;


class CarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAddCar()
    {
        $carController = new CarController();

        $this->request->setSession($this->session);
        $carController->addCar($this->request, $this->createNewCar, $this->setImage, $this->message);

        $response = $this->actingAs($this->user)
            ->post('/user/addCar/addCar');

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertDatabaseHas('cars', ['sign' => $this->car->sign]);

    }

    public function testShow()
    {
        $title = 'Reservierung: ' . $this->car->sign;;
        $subtitle = 'Details von ' . $this->car->sign;

        $response = $this->actingAs($this->user)->get('/car/' . $this->car->id . '/');

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($subtitle);
        $this->assertEquals($this->car->sign, $response->original->viewData['car']->sign);
        $this->assertEquals($this->parkingSpot->number,
            $response->original->viewData['parking_spots']->first()->number);
    }

    public function testIndex()
    {
        $title = 'Parkplatzverwaltung';
        $subtitle = 'Fahrzeugübersicht';

        $response = $this->actingAs($this->user)->get('/cars/');

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($subtitle);
        $this->assertEquals($this->car->sign, $response->original->viewData['cars']->first()->sign);

    }

    public function testStoreIndex()
    {

    }

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

        $this->request = new CarRequest([
            'user_id' => $this->user->id,
            "sign" => $this->car->sign,
            'manufacturer' => $this->car->manufacturer,
            "model" => $this->car->model,
            "color" => $this->car->color,
            "image" => $this->car->image
        ]);
        $this->createNewCar = new CreateNewCar();
        $this->setImage = new SetImageName();
        $this->message = new CreateMessage();
        $this->session = app(Session::class);
    }
}
