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
            'password' => 'admin',
            'last_thread_id' => 1
        ]);

        $this->user = User::create([
            'name' => 'testName',
            'email' => 'test@test.test',
            'password' => 'test',
            'deleted_at' => null,
            'last_thread_id' => 1
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
            'user_id' => $this->user->id,
            'car_id' => '1',
            'number' => '1',
            'row' => '1',
            'status' => 'unbekannt',
            'image' => 'unbekannt.jpg',
        ]);

        $this->parking_spot_service = new ParkingSpotService();
        $this->request = new ParkingSpotRequest([ 'status'=> true]);
        $this->actingAs($this->user)->get('/parking_spot/'.$this->parking_spot->id.'/');
        $this->session = app(Session::class);
        $this->request->setSession($this->session);
        $this->request->server->set('HTTP_REFERER', '//'.$this->parking_spot->id);
    }

    public function testParkingSpotCanUpdateDataSuccessful()
    {
        $this->actingAs($this->user)->parking_spot_service->update($this->request, new CreateMessage());

        $updated_parking_spot = ParkingSpot::findOrFail($this->parking_spot->id);

        $this->assertEquals($this->admin->id, $updated_parking_spot->getAttribute('user_id'));
        $this->assertEquals($this->car->id, $updated_parking_spot->car_id);
        $this->assertEquals('reserviert', $updated_parking_spot->status);
        $this->assertEquals('reserviert.jpg', $updated_parking_spot->image);
    }

    public function testParkingSpotUpdateCreateAMessageSuccessful()
    {
        $beforeCount = LogMessage::count();

        $this->parking_spot_service->update($this->request, new CreateMessage());

        $afterCount = LogMessage::count();

        $this->assertEquals($beforeCount + 1, $afterCount);

    }

    public function testresetParkingSpotSuccessful()
    {
        $this->actingAs($this->admin)->parking_spot_service->resetParkingSpot(
            new CreateMessage(),
            $this->parking_spot->id,
            $this->car->id);

        $this->assertDatabaseHas('parking_spots', [
            'user_id' => $this->admin->id,
            'car_id' => null,
            'status' => 'frei',
            'image' => 'frei.jpg',
        ]);
    }

    public function testResetParkingSpotCreateAMessageSuccessful()
    {
        $beforeCount = LogMessage::count();

        $this->parking_spot_service->resetParkingSpot(new CreateMessage(), $this->parking_spot->id, $this->car->id);

        $afterCount = LogMessage::count();

        $this->assertEquals($beforeCount + 1, $afterCount);

    }
}
