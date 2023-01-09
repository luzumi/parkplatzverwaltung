<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Car;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
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
    }

    public function test_index_should_return_view_when_called(): void
    {
        $title = "Parkplatzverwaltung";
        $subtitle = "User-Übersicht";
        $users = User::all()->where('deleted_at', null);

        $response = $this->actingAs($this->user)->get('/user/');

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($subtitle);
        $response->assertSee($users[0]->getAttribute('name'));
    }

    public function test_index_should_return_view_when_called_and_User_list_is_guest(): void
    {
        $response = $this->get('/user/');

        $response->assertStatus(500);
    }

    public function test_show_should_return_view_show_with_viewdata()
    {
        $user = User::findOrFail($this->user->id);

        $users = User::all()->where('deleted_at', null);
        $address = Address::where('user_id', $this->user->id)->first();
        $cars = Car::with('parkingSpot')->where('cars.user_id', $this->user->id)->get();

        $response = $this->actingAs($this->user)->get('/user/' . $this->user->id);

        $response->assertStatus(200);
        $response->assertSee($user->getAttribute('name'));
        $response->assertSee($users->first()->getAttribute('name'));
        $response->assertSee($address->first()->getAttribute('Land'));
        $response->assertSee($cars->first()->getAttribute('Sign'));

    }

    public function test_show_should_find_user_by_user_id()
    {
        $response = $this->actingAs($this->user)->get('/user/' . $this->user->id);

        $result = $response->original->viewData['user'];

        $this->assertEquals($this->user->name, $result->name);
    }

    public function test_show_should_retrieve_address_for_given_user_id()
    {
        $address = Address::where('user_id', '=', $this->user->id)->first();

        $response = $this->actingAs($this->user)->get('/user/' . $this->user->id);

        $response->assertSee($address->Land);
        $this->assertEquals($address->Land, $response->original->viewData['address']->Land);
    }

    public function test_show_should_set_title_and_subtitle_with_user_name_and_parkplatzverwaltung()
    {
        $title = $this->user->getAttribute('name') . " - Parkplatzverwaltung";
        $subtitle = $this->user->getAttribute('name') . " - User information";

        $response = $this->actingAs($this->user)->get('/user/' . $this->user->id);

        $this->assertEquals($title, $response->original->viewData['title']);
        $this->assertEquals($subtitle, $response->original->viewData['subtitle']);
    }


    public function testUserDataIsPopulated()
    {
        $user_id = $this->user->id;
        $user = User::findOrFail($user_id);

        $response = $this->actingAs($this->user)->get('/user/editor/' . $this->user->id);

        $this->assertEquals($user->id, $response->original->viewData['user']->id);
    }

    public function testSubtitleIsPopulated()
    {
        $user_id = $this->user->id;
        $user = User::findOrFail($user_id);

        $response = $this->actingAs($this->user)->get('/user/editor/' . $this->user->id);

        $expectedSubtitle = $user["name"] . " - User editor";
        $this->assertEquals($expectedSubtitle, $response->original->viewData['subtitle']);
    }

    public function testAddressDataIsPopulated()
    {
        $expectedAddress = Address::where('user_id', $this->user->id)->first();

        $response = $this->actingAs($this->user)->get('/user/editor/' . $this->user->id);

        $this->assertEquals($expectedAddress, $response->original->viewData['address']);
    }
}
