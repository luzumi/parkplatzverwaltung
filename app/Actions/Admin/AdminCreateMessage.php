<?php

namespace App\Actions\Admin;

use App\Enums\MessageType;
use App\Models\Message;
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
            case MessageType::AntwortMessage;
                $status = 'pending';
                $messages = Message::where('parking_spot_id', '=', $parking_spot_id)
                    ->where('car_id', $car_id)
                    ->where('user_id', $user_id)
                    ->where('status', '!=', 'closed')
                    ->get();
                foreach ($messages as $mess) {
                    $mess->update([
                        'status' => 'closed'
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
