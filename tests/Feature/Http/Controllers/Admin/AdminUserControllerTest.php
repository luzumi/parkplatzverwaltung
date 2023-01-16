<?php

namespace Http\Controllers\Admin;

use App\Actions\CreateMessage;
use App\Actions\SaveAddress;
use App\Actions\SetImageName;
use App\Actions\UpdateUser;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\UserRequest;
use App\Models\Address;
use App\Models\Car;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->actingAs($this->admin)->post(route("admin.user.store"));

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    public function testIndex()
    {
        $title = "Parkplatzverwaltung";
        $subtitle = "User-Übersicht";
        $users = User::all()->where('deleted_at', null);

        $response = $this->actingAs($this->admin)->get(route('admin.user.index'));

        $response->assertStatus(200);
        $response->assertSee($title);
        $response->assertSee($subtitle);
        $response->assertSee($users->first()->getAttribute('name'));
        $this->assertEquals($users->first()->email, $response->original->viewData['users']->first()->email);
    }

    public function testEdit()
    {
        $title = 'Admin-Page - Editiere Benutzer - Parkplatzverwaltung';

        $response = $this->actingAs($this->admin)->get(route('admin.user.edit', $this->admin->id));

        $response->assertStatus(200);
        $response->assertSee($title);
        $this->assertEquals($this->address->Land, $response->original->viewData['address']->Land);
        $this->assertEquals($this->admin->email, $response->original->viewData['user']->email);
    }

    public function testDelete()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $response = $this->actingAs($this->admin)->delete(route('admin.user.delete', $this->user->id));

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseMissing('cars', [
            'user_id' => $this->user->id,
            'deleted_at' => null
        ]);
        $this->assertDatabaseMissing('parking_spots', [
            'user_id' => $this->admin->id,
            'image' => 'frei.jpg',
            'status' => 'frei'
        ]);
    }

    public function testUpdate()
    {
        $title = 'Admin-Page - Editiere Fahrzeug - Parkplatzverwaltung';
        $userBefore = $this->user;
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->updateUser->update($this->request, $this->setImage, $this->user->id, $this->createMessage);

        $response = $this->actingAs($this->admin)->put(route('admin.user.update', $this->user->id));

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertNotEquals($this->user->email, User::where('id', '=', $this->user->id)->first()->email);

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

}
