<?php

namespace Actions\Admin;

use App\Actions\Admin\StatusLink;
use App\Enums\MessageType;
use App\Models\LogMessage;
use App\Models\ParkingSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusLinkTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateLink()
    {
        $statusLink = new StatusLink();
        $message = new LogMessage(['message' => MessageType::AddParkingSpot->value]);
        $result = $statusLink->createLink($message);

        $this->assertEquals('Done', $result);
    }

    public function testgetParkingSpotLink()
    {
        $statusLink = new StatusLink();
        $this->parking_spot = ParkingSpot::create([
            'user_id' => 1,
            'car_id' => 1,
            'number' => 1,
            'row' => 1,
            'image' => 'besetzt',
            'status' => 'besetzt',
            'deleted_at' => null
        ]);
        $message = new LogMessage([
            'message' => MessageType::ReserveParkingSpot->value,
            'status' => 'open',
            'parking_spot_id' => 1
        ]);
        $expectedLink = '<a class="btn btn-primary"
                       href="http://localhost/admin/admin/parking_spots/1/edit">
                    <i class="bi-pencil"></i>
                </a>';

        $result = $statusLink->createLink($message);

        $this->assertEquals($expectedLink, $result);
    }
}
