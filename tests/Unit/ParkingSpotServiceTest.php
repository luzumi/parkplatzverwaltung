<?php

namespace Tests\Unit;

use App\Actions\CreateMessage;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\Car;
use App\Models\LogMessage;
use App\Models\ParkingSpot;
use App\Models\User;
use App\Services\ParkingSpotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Session\Session;
use Tests\TestCase;

class ParkingSpotServiceTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $admin;
    private $car;
    private $parking_spot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'role' => 'admin',
            'name' => 'admin',
            'email' => 'admin@admin.admin',
            'password' => 'admin'
        ]);

        $this->user = User::create([
            'name' => 'testName',
            'email' => 'test@test.test',
            'password' => 'test',
            'deleted_at' => null
        ]);

        $this->car = Car::create([
            'user_id' => $this->user->id,
            'sign' => 'TE ST 1234',
            'manufacturer' => 'Tester',
            'model' => 'TRY',
            'color' => 'White',
            'image' => 'testCar.jpg',
            'status' => 1,
            'deleted_at' => null
        ]);

        $this->parking_spot = ParkingSpot::create([
            'user_id' => '2',
            'car_id' => '1',
            'number' => '1',
            'row' => '1',
            'status' => 'unbekannt',
            'image' => 'unbekannt.jpg',
        ]);

        $this->parking_spot_service = new ParkingSpotService();
        $this->request = new ParkingSpotRequest([ 'status'=>'1']);
        $this->actingAs($this->user)->get('/parking_spot/'.$this->parking_spot->id.'/');
        $this->session = app(Session::class);
        $this->request->setSession($this->session);
        $this->request->server->set('HTTP_REFERER', '/1');
    }

    public function testParkingSpotCanUpdateDataSuccessful()
    {

        // Rufe die update-Methode auf
        $this->parking_spot_service->update($this->request, new CreateMessage());

        // Hole den Parkplatz erneut aus der Datenbank
        $updated_parking_spot = ParkingSpot::findOrFail($this->parking_spot->id);

        // Vergleiche die Werte des Benutzers vor und nach der Aktualisierung
        $this->assertEquals($this->user->id, $updated_parking_spot->getAttribute('user_id'));
        $this->assertEquals($this->car->id, $updated_parking_spot->car_id);
        $this->assertEquals('reserviert', $updated_parking_spot->status);
        $this->assertEquals('reserviert.jpg', $updated_parking_spot->image);
    }

    public function testParkingSpotUpdateCreateAMessageSuccessful()
    {
        // Anzahl der Nachrichten in der Datenbank vor dem Aufruf von handle ermitteln
        $beforeCount = LogMessage::count();

        // Rufe die update-Methode auf
        $this->parking_spot_service->update($this->request, new CreateMessage());

        // Anzahl der Nachrichten in der Datenbank nach dem Aufruf von handle ermitteln
        $afterCount = LogMessage::count();

        // Vergleichen, ob eine Nachricht hinzugefügt wurde
        $this->assertEquals($beforeCount + 1, $afterCount);

    }

    public function testresetParkingSpotSuccessful()
    {
        // Rufe die Methode auf
        $this->actingAs($this->admin)->parking_spot_service->resetParkingSpot(
            new CreateMessage(),
            $this->parking_spot->id,
            $this->car->id);

        // Überprüfe, ob der Parkplatz aktualisiert wurde
        $this->assertDatabaseHas('parking_spots', [
            'user_id' => $this->admin->id,
            'car_id' => null,
            'status' => 'frei',
            'image' => 'frei.jpg',
        ]);
    }

    public function testResetParkingSpotCreateAMessageSuccessful()
    {
        // Anzahl der Nachrichten in der Datenbank vor dem Aufruf von handle ermitteln
        $beforeCount = LogMessage::count();

        // Rufe die update-Methode auf
        $this->parking_spot_service->resetParkingSpot(new CreateMessage(), $this->parking_spot->id, $this->car->id);

        // Anzahl der Nachrichten in der Datenbank nach dem Aufruf von handle ermitteln
        $afterCount = LogMessage::count();

        // Vergleichen, ob eine Nachricht hinzugefügt wurde
        $this->assertEquals($beforeCount + 1, $afterCount);

    }
}
