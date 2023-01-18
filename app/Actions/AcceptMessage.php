<?php

namespace App\Actions;

use App\Enums\MessageType;
use App\Models\LogMessage;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AcceptMessage
{
    public function acceptMessage(int $message_id): Redirector|Application|RedirectResponse
    {
//        dd($message_id);
        LogMessage::findOrFail($message_id)->update([
            'status' => 'closed'
        ]);
        return redirect('/');
    }
}
