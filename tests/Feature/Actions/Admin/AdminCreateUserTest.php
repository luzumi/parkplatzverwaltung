<?php

namespace Actions\Admin;

use App\Actions\Admin\AdminCreateUser;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function testHandle()
    {
        $adminCreateUser = new AdminCreateUser();
        $faker = Faker::create();
        $password = $faker->password;
        $request = new UserRequest([
            'name' => $faker->name,
            'email' => $faker->email,
            'telefon' => $faker->phoneNumber,
        ]);

        $user = $adminCreateUser->handle($request);

        $this->assertDatabaseHas('users', [
            'name' => 'CreatedByAdmin-' . $request->name,
            'email' => $request->email,
            'telefon' => $request->telefon,
            'image' => 'unregistered_user.png',
            'role' => 'client',
        ]);

        $this->assertInstanceOf(User::class, $user);
    }
}
