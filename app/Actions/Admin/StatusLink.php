<?php

namespace App\Actions\Admin;

use App\Enums\MessageType;
use App\Models\LogMessage;

class StatusLink
{
    public static function createLink($message)
    {
//       dd($message);
        switch ($message->message) {
            case MessageType::ReserveParkingSpot->value:
                return self::getParkingSpotLink($message);

            default:
                return 'Done';
        }
    }


    private static function getParkingSpotLink($message)
    {
        if ( $message->status != 'open' && $message->status != 'pending' ) return 'Done';

        return '<a class="btn btn-primary"
                       href="' . route('admin.parking-spot.edit', ['id' => $message->parkingSpot->id]) . '">
                    <i class="bi-pencil"></i>
                </a>';
    }
}
