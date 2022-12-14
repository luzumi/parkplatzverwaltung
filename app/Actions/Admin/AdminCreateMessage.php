<?php

namespace App\Actions\Admin;

use App\Enums\MessageType;
use App\Models\Message;
use App\Models\User;
use Auth;

class AdminCreateMessage
{
    /**
     * @param MessageType $message
     * @param $car_id
     * @param $parking_spot_id
     * @return Message
     */
    public function handle(MessageType $message, $user_id, $car_id, $parking_spot_id): Message
    {
        $status = 'closed';
        switch ($message) {
            case MessageType::AddParkingSpot;
            case MessageType::DeleteUser;
            case MessageType::AddUser;
            case MessageType::EditAddress;
            case MessageType::EditUser;
            case MessageType::AddCar;
            case MessageType::EditCar;
            case MessageType::DeleteCar;
                break;
            case MessageType::EditParkingSpot;
            case MessageType::ResetParkingSpot;
                $messages = Message::where('parking_spot_id', '=', $parking_spot_id)->get();
                foreach ($messages as $mess) {
                    $mess->update([
                        'status' => $status
                    ]);
                }
                break;
            default:
                $status = 'open';
        }

        return Message::create([
            'user_id' => Auth::id(),
            'receiver_user_id' => $user_id,
            'message' => $message,
            'car_id' => $car_id,
            'parking_spot_id' => $parking_spot_id,
            'status' => $status,
        ]);
    }
}
