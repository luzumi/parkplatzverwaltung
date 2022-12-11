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
        User::destroy(Auth::id());
        Address::where('user_id', Auth::id())->delete();
        Car::where('user_id', Auth::id())->delete();
        ParkingSpot::where('user_id', Auth::id())->delete();

        $createMessage->handle(MessageType::DeleteUser, $id, null, null);

        return redirect('/');
    }
}
