<?php

namespace Actions\Admin;

use App\Actions\Admin\AdminCreateMessage;
use App\Actions\Admin\AdminUpdateParkingSpot;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Requests\ParkingSpotRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUpdateParkingSpotTest extends TestCase
{
    use RefreshDatabase;

    public function testHandle()
    {
        $request = new ParkingSpotRequest([
            'status' => 'available',
        ]);

        $setImageName = new SetImageName();
        $adminUpdateParkingSpot = new AdminUpdateParkingSpot();
        $parking_spot_id = 1;
        $createMessage = new AdminCreateMessage();

        $parking_spot = $adminUpdateParkingSpot->handle($request, $setImageName, $parking_spot_id, $createMessage);

        $this->assertEquals($request->input('status'), $parking_spot->status);
        $this->assertEquals($parking_spot->user_id, $parking_spot->user_id);
        $this->assertNotNull($parking_spot->image);
        $this->assertDatabaseHas('parking_spots', [
            'id' => $parking_spot_id,
            'status' => $request->input('status'),
            'user_id' => $parking_spot->user_id,
            'image' => $parking_spot->image
        ]);
        $this->assertDatabaseHas('log_messages', [
            'message' => MessageType::AntwortMessage,
            'car_id' => $parking_spot->car_id,
            'parking_spot_id' => $parking_spot_id,
            'status' => 'closed',
        ]);
    }
}
