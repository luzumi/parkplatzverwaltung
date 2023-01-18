<?php

namespace Http\Requests;

use App\Enums\SampleRequest;
use App\Http\Requests\CarRequest;
use App\Models\Address;
use App\Models\Car;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        $this->admin = User::create([
            'name' => 'testUser',
            'email' => 'test@test.de',
            'password' => bcrypt($this->password),
            'role' => 'admin',
            'last_thread_id' => 1
        ]);

        $this->user = User::create([
            'name' => 'testUser',
            'email' => 'user@test.de',
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
    }

    public function testRulesWithValidInputs()
    {
        $data = [
            'sign' => SampleRequest::Only6Letters->value,
            'manufacturer' => SampleRequest::Only6Letters->value,
            'model' => SampleRequest::Only6Letters->value,
            'color' => SampleRequest::Only6Letters->value,
        ];

        $response = $this->actingAs($this->user)->post('/user/addCar/addCar', $data);

        $response->assertStatus(302);
        $response->assertRedirect('/user/' . $this->user->id);
        $response->assertSessionHasNoErrors('sign');
        $response->assertSessionHasNoErrors('manufacturer');
        $response->assertSessionHasNoErrors('model');
        $response->assertSessionHasNoErrors('color');
    }

    public function testRulesWithInValidInputs()
    {
        $data = [
            'sign' => SampleRequest::SqlInject->value,
            'manufacturer' => SampleRequest::LongText300Sign->value,
            'model' => SampleRequest::SignWithSymbols->value,
            'color' => SampleRequest::LongText300Sign->value,
        ];

        $response = $this->actingAs($this->user)->post('/user/addCar/addCar', $data);

        $response->assertSessionHasErrors('sign');
        $response->assertSessionHasErrors('manufacturer');
        $response->assertSessionHasErrors('model');
        $response->assertSessionHasErrors('color');
    }

    public function testRulesWithEmptyFields()
    {
        $data = [
            'sign' => SampleRequest::NoSigns->value,
            'manufacturer' => SampleRequest::NoSigns->value,
            'model' => SampleRequest::NoSigns->value,
            'color' => SampleRequest::NoSigns->value,
        ];

        $response = $this->actingAs($this->user)->post('/user/addCar/addCar', $data);

        $response->assertSessionHasErrors('sign');
        $response->assertSessionHasErrors('manufacturer');
        $response->assertSessionHasErrors('model');
        $response->assertSessionHasErrors('color');
    }
}
