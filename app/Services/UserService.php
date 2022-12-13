<?php

namespace App\Services;

use App\Actions\CreateMessage;
use App\Actions\SetImageName;
use App\Enums\MessageType;
use App\Http\Requests\UserPictureRequest;
use App\Http\Requests\UserRequest;
use App\Models\Address;
use App\Models\Car;
use App\Models\ParkingSpot;
use App\Models\User;
use DateTimeZone;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * @param UserPictureRequest $request
     * @param SetImageName $setImageName
     * @param int $user_id
     * @param CreateMessage $createMessage
     * @return RedirectResponse
     */
    public function updatePicture(
        UserPictureRequest $request,
        SetImageName       $setImageName,
        int                $user_id,
        CreateMessage      $createMessage
    ): RedirectResponse {
        $user = User::findOrFail($user_id);
        $user->update([
            'image' => $setImageName->handle($request, $user),
        ]);

        $createMessage->handle(MessageType::EditUser, $user_id, null, null);

        return redirect()->route('user.show', $user_id);
    }


    /**
     * @param UserRequest $request
     * @param $user_id
     * @param CreateMessage $createMessage
     * @return RedirectResponse
     */
    public function update(UserRequest $request, $user_id, CreateMessage $createMessage): RedirectResponse
    {
        $user = User::findOrFail($user_id);
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefon' => $request->input('telefon'),
        ]);

        $createMessage->handle(MessageType::EditUser, $user_id, null, null);

        return redirect()->route('user.show', $user_id);
    }


    /**
     * @param CreateMessage $createMessage
     * @return Redirector|Application|RedirectResponse
     */
    public function delete(CreateMessage $createMessage): Redirector|Application|RedirectResponse
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $count = User::where('deleted_at')->count();

        $user->update([
                'email' => 'deleted_'. $count . '_' . $user->email,
                'deleted_at' => now(),
            ]);
        Car::where('user_id', $id)
            ->update([
                'deleted_at' => now()
            ]);
        ParkingSpot::where('user_id', $id)
            ->update([
                'user_id' => '1',
                'image' => 'frei.jpg',
                'status' => 'frei',
            ]);

        $createMessage->handle(MessageType::DeleteUser, $id, null, null);
        Auth::logout();
        return redirect('/');
    }
}
