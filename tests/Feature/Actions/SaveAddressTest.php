<?php

namespace Actions;

use App\Actions\CreateMessage;
use App\Actions\SaveAddress;
use App\Enums\MessageType;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveAddressTest extends TestCase
{
    use RefreshDatabase;

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
            'Stadt' => $this->faker->city,
            'PLZ' => $this->faker->randomNumber(5),
            'Strasse' => $this->faker->streetName,
            'Nummer' => $this->faker->numberBetween(1, 10)
        ]);
    }

    public function testHandle()
    {
        $saveAddress = new SaveAddress();
        $addressRequest = new AddressRequest([
            'user_id' => $this->admin->id,
            'Land' => 'land',
            'Stadt' => 'stadt',
            'PLZ' => 12345,
            'Strasse' => 'strasse',
            'Nummer' => 5
        ]);
        $message = new CreateMessage();

        $saveAddress->handle($addressRequest, $this->user->id, $message);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->admin->id,
            'Land' => 'land',
            'Stadt' => 'stadt',
            'PLZ' => 12345,
            'Strasse' => 'strasse',
            'Nummer' => 5
        ]);

        $this->assertDatabaseHas('log_messages', [
            'user_id' => $this->admin->id,
            'message' => MessageType::EditAddress->value
        ]);
    }
}
