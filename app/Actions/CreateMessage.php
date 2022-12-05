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
     * @return Message
     */
    public function handle(MessageType $message): Message
    {
        return Message::create([
            'sender_user_id' => Auth::id(),
            'receiver_user_id' => User::where('role', 'admin')->first()->id,
            'message' => $message,
            'status' => true,
        ]);
    }
}
