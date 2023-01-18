<?php

namespace Http\Requests;

use App\Enums\SampleRequest;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressRequestTest extends TestCase
{use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        $this->admin = User::create([
            'name' => 'testUser',
            'email' => 'test@test.de',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);

        $this->user = User::create([
            'name' => 'testUser',
            'email' => 'user@test.de',
            'password' => bcrypt('password'),
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
    }

    public function testRulesWithValidInputs()
    {
        $data = [
            'Land' => $this->faker->country,
            'PLZ' => $this->faker->randomNumber(5),
            'Stadt' => $this->faker->city,
            'Strasse' => $this->faker->streetName,
            'Nummer' => $this->faker->randomNumber(3),
        ];

        $response = $this->actingAs($this->user)->put('address/'. $this->user->id .'/create', $data);

        $response->assertStatus(302);
        $response->assertRedirect('/user/' . $this->user->id);
        $response->assertSessionHasNoErrors('Land');
        $response->assertSessionHasNoErrors('PLZ');
        $response->assertSessionHasNoErrors('Stadt');
        $response->assertSessionHasNoErrors('Strasse');
        $response->assertSessionHasNoErrors('Nummer');
    }

    public function testRulesWithEmptyFields()
    {
        $data = [
            'Land' => SampleRequest::NoSigns->value,
            'PLZ' => SampleRequest::NoSigns->value,
            'Stadt' => SampleRequest::NoSigns->value,
            'Strasse' => SampleRequest::NoSigns->value,
            'Nummer' => SampleRequest::NoSigns->value,
        ];

        $response = $this->actingAs($this->user)->put('address/'. $this->user->id .'/create', $data);


        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('Land');
        $response->assertSessionHasErrors('PLZ');
        $response->assertSessionHasErrors('Stadt');
        $response->assertSessionHasErrors('Strasse');
        $response->assertSessionHasErrors('Nummer');
    }

    public function testRulesWithInValidFields()
    {
        $data = [
            'Land' => SampleRequest::SqlInject->value,
            'PLZ' => SampleRequest::LongText300Sign->value,
            'Stadt' => SampleRequest::ValidEmail->value,
            'Strasse' => SampleRequest::SqlInject->value,
            'Nummer' => SampleRequest::LongText300Sign->value,
        ];

        $response = $this->actingAs($this->user)->put('address/'. $this->user->id .'/create', $data);

        $response->assertSessionHas('errors');
        $response->assertSessionHasErrors('Land');
        $response->assertSessionHasErrors('PLZ');
        $response->assertSessionHasErrors('Stadt');
        $response->assertSessionHasErrors('Strasse');
        $response->assertSessionHasErrors('Nummer');
    }
}
