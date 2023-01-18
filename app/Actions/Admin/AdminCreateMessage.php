<?php

namespace App\Actions\Admin;

use App\Enums\MessageType;
use App\Models\LogMessage;
use App\Models\User;
use Auth;

class AdminCreateMessage
{
    /**
     * @param MessageType $message
     * @param $car_id
     * @param $parking_spot_id
     * @return LogMessage
     */
    public function handle(MessageType $message, $user_id, $car_id, $parking_spot_id): LogMessage
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
                $messages = LogMessage::where('parking_spot_id', '=', $parking_spot_id)->get();
                foreach ($messages as $mess) {
                    $mess->update([
                        'status' => $status
                    ]);
                }
                break;
            default:
                $status = 'open';
        }

        return LogMessage::create([
            'user_id' => User::where('role', '=', 'admin')->first()->id,
            'receiver_user_id' => $user_id,
            'message' => $message,
            'car_id' => $car_id,
            'parking_spot_id' => $parking_spot_id,
            'status' => $status,
        ]);
    }
}
