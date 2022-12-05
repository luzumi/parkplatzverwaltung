<?php

namespace App\Actions;

use App\Enums\MessageType;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateNewCar extends Model
{
    /**
     * @param CarRequest $request
     * @param SetImageName $setImageName
     * @param CreateMessage $message
     * @return Car
     */
    public function handle(CarRequest $request, SetImageName $setImageName, CreateMessage $message): Car
    {
        $message->handle(MessageType::AddCar);

        return Car::create([
            'user_id' => Auth::id(),
            'sign' => $request->input('sign'),
            'manufacturer' => $request->input('manufacturer'),
            'model' => $request->input('model'),
            'color' => $request->input('color'),
            'image' => $setImageName->handle($request, $this),
            'status' => true,
        ]);
    }
}
