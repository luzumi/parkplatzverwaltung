<?php
namespace App\Actions\Admin;

use App\Actions\CreateMessage;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Requests\CarRequest;
use App\Models\Car;

class AdminUpdateCar
{
    /**
     * @param CarRequest $request
     * @param SetImageName $setImageName
     * @param int $car_id
     * @param CreateMessage $createMessage
     * @return bool
     */
    public function handle(
        CarRequest $request,
        SetImageName $setImageName,
        int $car_id,
        CreateMessage $createMessage
    ): bool {

        $car = Car::findOrFail($car_id);
        $createMessage->handle(MessageType::EditCar, $car->id, null);
        return $car->update([
            'sign' =>  $request->input('sign'),
            'manufacturer' =>  $request->input('manufacturer'),
            'model' =>  $request->input('model'),
            'color' =>  $request->input('color'),
            'image' => $setImageName->handle($request, $car),
        ]);
    }
}
