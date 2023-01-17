<?php

namespace Http\Controllers\Admin;

use App\Actions\CreateMessage;
use App\Actions\CreateNewCar;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Controllers\CarController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminCarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAddCar()
    {
        $carController = new CarController();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->request->setSession($this->session);
        $carController->addCar($this->request, $this->createNewCar, $this->setImage, $this->message);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.car.store'));

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertDatabaseHas('cars', ['sign' => $this->car->sign]);

    }

    public function testIndex()
    {
        $title = 'Admin-Panel - Fahrzeugübersicht - Parkplatzverwaltung';

        $response = $this->actingAs($this->admin)->get(route('admin.car.index'));

        $response->assertStatus(200);
        $response->assertSee($title);
        $this->assertEquals($this->car->sign, $response->original->viewData['cars']->first()->sign);

    }

    public function testEdit()
    {
        $title = 'Admin-Page - Editiere Fahrzeug - Parkplatzverwaltung';

        $response = $this->actingAs($this->admin)->get(route('admin.car.edit', [$this->car->id]));

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($this->car->sign);
    }

    public function testUpdate()
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.car.update', $this->car->id), [
                'sign' => 'ABCD1234',
                'manufacturer' => "Manufacturer1",
                "model" => "Model1",
                "color" => "color",
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('admin/admin/cars/');
        $this->assertDatabaseHas('cars', ['sign' => 'ABCD1234']);
        $this->assertDatabaseHas('cars', ['image' => $this->car->image]);
    }


    public function testDelete()
    {
        $before = Car::where('deleted_at', null)->count();

        $response = $this->actingAs($this->admin)->delete(route('admin.car.delete', $this->car->id));

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertEquals($before - 1, Car::where('deleted_at', null)->count());

        $this->assertDatabaseHas('log_messages', [
            'status' => 'closed',
            'user_id' => $this->admin->id,
            'car_id' => $this->car->id,
            'message' => MessageType::DeleteCar->value
        ]);

        $this->assertDatabaseMissing('cars', [
            'id' => $this->car->id,
            'deleted_at' => null
        ]);
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
            'number' => $this->faker->numberBetween(1, 10),
            'row' => $this->faker->numberBetween(1, 3),
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
