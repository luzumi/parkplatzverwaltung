<?php

namespace Tests\Unit;

use App\Actions\CreateMessage;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Requests\UserPictureRequest;
use App\Http\Requests\UserRequest;
use App\Models\Car;
use App\Models\LogMessage;
use App\Models\ParkingSpot;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $admin;
    private $car;
    private $parking_spot;
    private $request;
    private $pictureRequest;
    private $userService;
    private $imageName;

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
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'number' => 1,
            'row' => 1,
            'image' => 'besetzt',
            'status' => 'besetzt',
            'deleted_at' => null
        ]);

        $this->request = new UserRequest([
            'name' => 'New Name',
            'email' => 'new@email.com',
            'telefon' => '123456',
        ]);

        $this->pictureRequest = new UserPictureRequest([
            'image' => 'NewImage.jpg',
        ]);

        $this->userService = new UserService();
        $this->imagename = new SetImageName();
        $this->imagename->handle($this->pictureRequest, $this->user);
    }

//User deleting
    public function testUserServiceDeleteSuccessful()
    {
        // Zähle die Anzahl der Benutzer in der Datenbank vor dem Löschen
        $beforeCount = User::where('deleted_at', null)->count();

        $this->actingAs($this->user)->delete('/user/delete/');

        // Prüfe, ob der Benutzer erfolgreich gelöscht wurde
        $this->assertDatabaseMissing('users', ['id' => $this->user->id, 'deleted_at' => null]);

        // und vergleiche die Anzahl vor und nach dem Löschen
        $afterCount = User::where('deleted_at', null)->count();
        $this->assertEquals($beforeCount - 1, $afterCount);
    }

    public function testUserServiceCarIsDeletedAfterDeletingSuccessful()
    {
        $this->actingAs($this->user)->delete('/user/delete/');
        $this->assertDatabaseMissing('cars', ['user_id' => $this->user->id, 'deleted_at' => null]);
    }

    public function testUserServiceParkingSpotIsEditedAfterDeletingSuccessful()
    {
        $this->actingAs($this->user)->delete('/user/delete/');
        $this->assertDatabaseHas('parking_spots', [
            'user_id' => 1,
            'image' => 'frei.jpg',
            'status' => 'frei',
        ]);

        $this->assertDatabaseMissing('parking_spots', [
            'user_id' => $this->user->id,
            'car_id' => $this->car->id,
            'number' => 1,
            'row' => 1,
            'image' => 'besetzt',
            'status' => 'besetzt',
        ]);
    }

    public function testUserServiceDeletingHandleHasSendACreatesMessageSuccessful()
    {
        // Anzahl der Nachrichten in der Datenbank vor dem Aufruf von handle ermitteln
        $beforeCount = LogMessage::count();

        $this->actingAs($this->user)->delete('/user/delete/');

        // Anzahl der Nachrichten in der Datenbank nach dem Aufruf von handle ermitteln
        $afterCount = LogMessage::count();

        // Vergleichen, ob eine Nachricht hinzugefügt wurde
        $this->assertEquals($beforeCount + 1, $afterCount);
    }

    public function testUserServiceRedirectAfterDeleteSuccessful()
    {
        $response = $this->actingAs($this->user)->delete('user/delete');
        $response->assertRedirect('/');
    }

    public function testUserServiceUserIsLogoutAfterDeletingSuccessful()
    {
        $this->actingAs($this->user)->delete('user/delete');
        $this->assertGuest();
    }


    //User updating
    public function testUserCanUpdateTheirDataSuccessful()
    {
        // Rufe die update-Methode auf
        $this->userService->update($this->request, $this->user->id, new CreateMessage());

        // Hole den Benutzer erneut aus der Datenbank
        $updatedUser = User::findOrFail($this->user->id);

        // Vergleiche die Werte des Benutzers vor und nach der Aktualisierung
        $this->assertEquals('New Name', $updatedUser->getAttribute('name'));
        $this->assertEquals('new@email.com', $updatedUser->email);
        $this->assertEquals('123456', $updatedUser->telefon);
    }

    public function testUserUpdateCreateAMessageSuccessful()
    {
        // Anzahl der Nachrichten in der Datenbank vor dem Aufruf von handle ermitteln
        $beforeCount = LogMessage::count();

        // Rufe die update-Methode auf
        $this->userService->update($this->request, $this->user->id, new CreateMessage());

        // Anzahl der Nachrichten in der Datenbank nach dem Aufruf von handle ermitteln
        $afterCount = LogMessage::count();

        // Vergleichen, ob eine Nachricht hinzugefügt wurde
        $this->assertEquals($beforeCount + 1, $afterCount);

    }

    public function testUserServiceRedirectAfterUpdatingSuccessful()
    {
        $response = $this->userService->update($this->request, $this->user->id, new CreateMessage());
        $this->assertTrue($response->isRedirection());
        $this->assertEquals('http://localhost/user/' . $this->user->id, $response->getTargetUrl());
    }

    public function testUserUpdatePictureCreateAMessageSuccessful()
    {
        // Anzahl der Nachrichten in der Datenbank vor dem Aufruf von handle ermitteln
        $beforeCount = LogMessage::count();

        // Rufe die update-Methode auf
        $imagename = new SetImageName();
        $imagename->handle($this->pictureRequest, $this->user);
        $response = $this->userService->updatePicture(
            $this->pictureRequest,
            $imagename,
            $this->user->id,
            new CreateMessage()
        );

        // Anzahl der Nachrichten in der Datenbank nach dem Aufruf von handle ermitteln
        $afterCount = LogMessage::count();

        // Vergleichen, ob eine Nachricht hinzugefügt wurde
        $this->assertEquals($beforeCount + 1, $afterCount);

    }

    public function testUserServiceRedirectAfterUpdatingPictureSuccessful()
    {

        $response = $this->userService->updatePicture(
            $this->pictureRequest,
            $this->imagename,
            $this->user->id,
            new CreateMessage()
        );
        $this->assertTrue($response->isRedirection());
        $this->assertEquals('http://localhost/user/' . $this->user->id, $response->getTargetUrl());
    }
}
