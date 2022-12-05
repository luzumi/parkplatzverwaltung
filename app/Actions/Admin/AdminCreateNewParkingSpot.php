<?php

namespace App\Actions\Admin;

use App\Actions\CreateMessage;
use App\Enums\MessageType;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\ParkingSpot;

class AdminCreateNewParkingSpot
{
    /**
     * @param ParkingSpotRequest $request
     * @param CreateMessage $createMessage
     * @return ParkingSpot
     */
    public function handle(ParkingSpotRequest $request, CreateMessage $createMessage): ParkingSpot
    {
        $count = ParkingSpot::count() + 1;

        $parkingSpot =  ParkingSpot::create([
            'user_id' => 1,
            'number' => $count,
            'row' => intdiv($count - 1, 4) + 1,
            'image' => $request->input('status') . '.jpg',
            'status' => $request->input('status'),
        ]);
        $createMessage->handle(MessageType::AddParkingSpot);

        return $parkingSpot;
    }
}
