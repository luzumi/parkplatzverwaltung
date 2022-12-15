<?php

namespace App\Actions;

use App\Enums\MessageType;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AcceptMessage
{
    public function acceptMessage(int $message_id): Redirector|Application|RedirectResponse
    {
//        dd($message_id);
        Message::findOrFail($message_id)->update([
            'status' => 'closed'
        ]);
        return redirect('/');
    }
}
