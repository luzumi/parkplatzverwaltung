<?php

namespace Actions;

use App\Actions\UpdateUser;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        parent::setUp();

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
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'Testname',
            'email' => 'test@email.com',
            'telefon' => '123456789'
        ];

        $response = $this->actingAs($this->user)->put(route('user.update', ['id' => $this->user->id]), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('user.show', $this->user->id));
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Testname',
            'email' => 'test@email.com',
            'telefon' => '123456789'
        ]);
    }
}
