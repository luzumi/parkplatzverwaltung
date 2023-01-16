<?php

namespace Http\Controllers;

use App\Actions\CreateMessage;
use App\Actions\CreateNewCar;
use App\Actions\SaveAddress;
use App\Http\Controllers\AddressController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\User;
use Faker\Factory as Faker;
use Symfony\Component\HttpFoundation\Session\Session;
use Tests\TestCase;



class AddressControllerTest extends TestCase
{
    public function testCreate()
    {
        $addressController = new AddressController();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->request->setSession($this->session);
        $addressController->create($this->request, $this->saveAddress, $this->user->id, $this->message);

        $response = $this->actingAs($this->user)->put('address/'. $this->user->id .'/create');

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertDatabaseHas('addresses', ['PLZ' => $this->address->PLZ]);
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

        $this->address = Address::create([
            'user_id'=> $this->user->id,
            'Land' => $this->faker->country,
            'PLZ' => $this->faker->randomNumber(5),
            'Stadt' => $this->faker->city,
            'Strasse' => $this->faker->streetName,
            'Nummer' => $this->faker->randomNumber(2),
        ]);

        $this->request = new AddressRequest([
            'Land' => $this->address->Land,
            'PLZ' => $this->address->PLZ,
            'Stadt' => $this->address->Stadt,
            'Strasse' => $this->address->Strasse,
            'Nummer' => $this->address->Nummer,
        ]);

        $this->createNewCar = new CreateNewCar();
        $this->saveAddress = new SaveAddress();
        $this->message = new CreateMessage();
        $this->session = app(Session::class);
    }

}
