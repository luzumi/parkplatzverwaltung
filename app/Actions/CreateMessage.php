<?php

namespace App\Actions;

use App\Enums\MessageType;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateMessage
{
    /**
     * @param MessageType $message
     * @param $car_id
     * @param $parking_spot_id
     * @return Message
     */
    public function handle(MessageType $message, $user_id, $car_id, $parking_spot_id): Message
    {
        return Message::create([
            'user_id' => $user_id,
            'receiver_user_id' => User::where('role', 'admin')->first()->id,
            'message' => $message,
            'car_id' => $car_id,
            'parking_spot_id' => $parking_spot_id,
            'status' => 'offen',
        ]);
    }
}
