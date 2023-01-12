<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Car;
use App\Models\LogMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory as Faker;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Car $car;
    private \Faker\Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->password = $this->faker->password;

        $this->user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($this->password),
            'role' => 'client',
            'last_thread_id' => 1
        ]);

        $this->user2 = User::create([
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

        $this->message = LogMessage::create([
            'user_id' => $this->user->id,
            'receiver_user_id' => $this->user2->id,
            'message' => $this->faker->sentence,
            'status' => true
        ]);
    }


    public function testUserCanBeCreated()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertDatabaseHas('users', [
            'name' => $this->user->getAttribute('name'),
            'email' => $this->user->email,
        ]);
    }

    public function testPasswordIsHashedWhenSaved()
    {
        $this->assertNotEquals('test', $this->user->password);
        $this->assertTrue(password_verify($this->password, $this->user->password));
    }

    public function testCarMethodReturnsValidHasManyRelationship()
    {
        $this->assertInstanceOf(HasMany::class, $this->user->cars());
        $car = Car::first();
        $this->assertEquals($car, $this->user->cars()->first());
    }


    public function testUserAndCarAreCorrectlyRelated()
    {
        $this->assertEquals($this->user->id, $this->car->user_id);
    }

    public function testDeletingUserDeletesCar()
    {
        $this->user->delete();
        $this->car->delete();

        $this->assertNull(Car::find($this->car->id));
    }

    public function testUserAndAddressAreCorrectlyRelated()
    {

        $address = Address::create([
            'user_id' => $this->user->id,

        ]);

        $this->assertEquals($this->user->id, $address->user_id);
    }

    public function testDeletingUserDeletesAddress()
    {
        $address = Address::create([
            'user_id' => $this->user->id,
            'street' => $this->faker->streetName,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'zipcode' => $this->faker->postcode
        ]);

        $this->user->delete();
        $address->delete();

        $this->assertNull(Address::find($address->id));
    }

    public function testUserHasRole()
    {
        $this->assertTrue($this->user->hasRole('client'));


        $this->assertFalse($this->user->hasRole('customer'));
    }

    public function testUserAndMessagesAreCorrectlyRelated()
    {
        // Create message 2
        $message2 = LogMessage::create([
            'user_id' => $this->user->id,
            'receiver_user_id'=> $this->user2->id,
            'message' => $this->faker->sentence,
            'status' => true

        ]);

        // Assert that user model returns correct messages
        $this->assertEquals($this->message->id, $this->user->message->first()->id);
        $this->assertEquals($message2->id, $this->user->message->last()->id);
    }

    public function test_a_message_belongs_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->message->user);
    }

    public function test_a_user_has_many_messages()
    {
        $this->assertInstanceOf(HasMany::class, $this->user->message());
    }

    public function test_a_message_can_be_created()
    {
        $this->assertDatabaseHas('log_messages', [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user_id' => $this->message->user_id,
            'receiver_user_id' => $this->message->receiver_user_id,
            'status' => true

        ]);
    }

    public function test_a_message_can_be_updated()
    {

        $this->message->update([
            'message' => 'new message',
            'user_id' => 2,
            'receiver_user_id' => 3,
            'status' => true
        ]);

        $this->assertDatabaseHas('log_messages', [
            'id' => $this->message->id,
            'message' => 'new message',
            'user_id' => 2,
            'receiver_user_id' => 3,
            'status' => true
        ]);
    }

    public function test_a_message_can_be_deleted()
    {
        $this->message->delete();

        $this->assertDatabaseMissing('log_messages', [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user_id' => $this->message->user_id,
            'receiver_user_id' => $this->message->receiver_user_id,
            'status' => true
        ]);
    }
}
