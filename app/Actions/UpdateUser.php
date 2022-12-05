<?php

namespace App\Actions;

use App\Enums\MessageType;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UpdateUser
{
    /**
     * @param UserRequest $request
     * @param SetImageName $setImageName
     * @param int $user_id
     * @param CreateMessage $message
     * @return User
     */
    public function update(
        UserRequest  $request,
        SetImageName $setImageName,
        int          $user_id,
        CreateMessage $message
    ): User {

        $user = User::findOrFail($user_id);
        $message->handle(MessageType::EditUser);

        return $user->updateOrCreate([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefon' => $request->input('telefon'),
            'image' => $setImageName->handle($request, $user)
        ]);
    }
}
