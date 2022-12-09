<?php

namespace App\Services;

use App\Actions\CreateMessage;
use App\Enums\MessageType;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\ParkingSpot;
use Illuminate\Support\Facades\Auth;

class ParkingSpotService
{
    /**
     * @param ParkingSpotRequest $request
     * @param CreateMessage $message
     * @return void
     */
    public static function update(ParkingSpotRequest $request, CreateMessage $message): void
    {
        $offset = strripos($request->session()->previousUrl(), '/') + 1;
        $car_id = substr($request->session()->previousUrl(), $offset);
        $parking_spot_id = $request->get('radio')??$request->get('status');

        $parking_spot = ParkingSpot::findOrFail($parking_spot_id)->update([
            'user_id' => Auth::id(),
            'car_id' => $car_id,
            'status' => 'reserviert',
            'image' => 'reserviert.jpg',
        ]);

        $message->handle(MessageType::ReserveParkingSpot, $car_id, $parking_spot_id);
    }

    /**
     * @param CreateMessage $message
     * @param $parking_spot_id
     * @param $car_id
     * @return bool
     */
    public static function resetParkingSpot(CreateMessage $message, $parking_spot_id, $car_id): bool
    {
        $message->handle(MessageType::ResetParkingSpot, $car_id, null);

        return ParkingSpot::findOrFail($parking_spot_id)->update([
            'user_id' => '1',
            'car_id' => null,
            'status' => 'frei',
            'image' => 'frei.jpg',
        ]);
    }
}
