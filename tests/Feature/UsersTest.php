<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UsersTest extends TestCase
{
    use RefreshDatabase;


//      Testen ob ein Benutzer erfolgreich erstellt und in der Datenbank gespeichert werden kann.
//      Testen ob das Passwort eines Benutzers korrekt gehasht wird, wenn es gespeichert wird.
//    Testen ob die car-Methode eine gültige BelongsTo-Beziehung zurückgibt.
//    Testen ob die cars-Methode eine gültige HasMany-Beziehung zurückgibt.
//    Testen ob die parkingSpot-Methode eine gültige HasMany-Beziehung zurückgibt.
//    Testen ob die parkingSpots-Methode eine gültige BelongsToMany-Beziehung zurückgibt.
//    Testen ob die address-Methode eine gültige HasOne-Beziehung zurückgibt.
//    Testen ob die hasRole-Methode das richtige Ergebnis für verschiedene Rollen zurückgibt.
//    Testen ob die message-Methode eine gültige HasMany-Beziehung zurückgibt.
    private User $user;

    public function testUserCanBeCreated()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertDatabaseHas('users', [
            'name' => $this->user->getAttribute('name'),
            'email' => 'test@test.test',
        ]);
    }

    public function testPasswordIsHashedWhenSaved()
    {
        $this->assertNotEquals('test', $this->user->password);
        $this->assertTrue(password_verify('test', $this->user->password));
    }

    public function testCarMethodReturnsValidHasManyRelationship()
    {
        $this->assertInstanceOf(HasMany::class, $this->user->cars());
        $car = Car::first();
        $this->assertEquals($car, $this->user->cars()->first());
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = app(\Faker\Generator::class);

        $this->user = User::create([
            'name' => 'name',
            'email' => 'test@test.test',
            'password' => bcrypt('test')
        ]);

        $user = User::find($this->user->id); // hole den Benutzer mit der gegebenen ID aus der Datenbank
        $car = new Car(); // erstelle ein neues Car-Modell
        $car->user_id = $user->id; // setze die foreign key Spalte "user_id" auf die ID des Benutzers
        $car->sign = 'ABC123'; // setze weitere Attribute des Autos
        $car->manufacturer = 'Ford';
        $car->model = 'Mustang';
        $car->color = 'Red';
        $car->image = 'https://via.placeholder.com/640x480.png/ff0000?text=Mustang';
        $car->status = true;
        $car->save(); // speichere das neue Auto in der Datenbank

    }

}
