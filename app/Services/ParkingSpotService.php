<?php

namespace App\Services;

use App\Actions\CreateMessage;
use App\Enums\MessageType;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\ParkingSpot;
use App\Models\User;
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
        $user = User::where('role', '=', 'admin')->first();
        if ($request->server->has('HTTP_REFERER')) {
            $offset = strripos($request->server->get('HTTP_REFERER'), '/') + 1;
            $car_id = substr($request->server->get('HTTP_REFERER'), $offset);
        } else {
            $offset = strripos($request->session()->previousUrl(), '/') + 1;
            $car_id = substr($request->session()->previousUrl(), $offset);
        }
        $parking_spot_id = $request->get('radio') ?? $request->get('status');

        $parking_spot = ParkingSpot::findOrFail($parking_spot_id);
        $parking_spot->update([
            'user_id' => $user->id,
            'car_id' => $car_id,
            'number' => $parking_spot->number,
            'row' => $parking_spot->row,
            'status' => 'reserviert',
            'image' => 'reserviert.jpg',
        ]);

        $message->handle(MessageType::ReserveParkingSpot, $user->id, $car_id, $parking_spot_id);
    }

    /**
     * @param CreateMessage $message
     * @param $parking_spot_id
     * @param $car_id
     * @return bool
     */
    public static function resetParkingSpot(CreateMessage $message, $parking_spot_id, $car_id): bool
    {
        $parking_spot = ParkingSpot::findOrFail($parking_spot_id);
        $message->handle(MessageType::ResetParkingSpot, $parking_spot->user_id, $car_id, $parking_spot_id);
        $user_id = User::where('role', 'admin')->first('id')->value('id');

        return $parking_spot->update([
            'user_id' => $user_id,
            'car_id' => null,
            'status' => 'frei',
            'image' => 'frei.jpg',
        ]);
    }
}
