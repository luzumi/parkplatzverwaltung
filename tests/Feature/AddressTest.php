<?php

use App\Models\Address;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
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
    }

    public function test_user_can_be_added_to_address(): void
    {
        $this->assertEquals(1, $this->address->user_id);
    }

    public function test_user_is_added_to_address(): void
    {
        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
        $this->assertDatabaseHas('addresses', ['user_id' => $this->user->id]);
        $this->assertDatabaseHas('addresses', ['Land' => $this->address->Land]);
        $this->assertDatabaseHas('addresses', ['PLZ' => $this->address->PLZ]);
        $this->assertDatabaseHas('addresses', ['Stadt' => $this->address->Stadt]);
        $this->assertDatabaseHas('addresses', ['Strasse' => $this->address->Strasse]);
        $this->assertDatabaseHas('addresses', ['Nummer' => $this->address->Nummer]);
    }
}
