<?php

namespace App\Actions\Admin;

use App\Actions\CreateMessage;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Requests\ParkingSpotRequest;
use App\Models\Car;
use App\Models\ParkingSpot;
use LaravelIdea\Helper\App\Models\_IH_ParkingSpot_C;

class AdminUpdateParkingSpot
{
    /**
     * @param ParkingSpotRequest $request
     * @param SetImageName $setImageName
     * @param int $parking_spot_id
     * @param CreateMessage $createMessage
     * @return ParkingSpot
     */
    public function handle(
        ParkingSpotRequest $request,
        SetImageName $setImageName,
        int $parking_spot_id,
        CreateMessage $createMessage
    ): ParkingSpot {
        $parking_spot = ParkingSpot::findOrFail($parking_spot_id);

        $parking_spot->update(['status' => $request->input('status')]);
        $parking_spot->update(['image' => $setImageName->handle($request, $parking_spot)]);

        $createMessage->handle(MessageType::EditParkingSpot, null, $parking_spot_id);

        return $parking_spot;
    }
}