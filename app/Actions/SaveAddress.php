<?php

namespace App\Actions;

use App\Enums\MessageType;
use App\Http\Requests\AddressRequest;
use App\Models\Address;

class SaveAddress
{
    /**
     * @param AddressRequest $request
     * @param int $user_id
     * @param CreateMessage $message
     * @return Address
     */
    public function handle(AddressRequest $request, int $user_id, CreateMessage $message): Address
    {
        $message->handle(MessageType::EditAddress, null, null);
        $address = Address::where('user_id', $user_id)->first();
        $address->update([
            'user_id' => $user_id,
            'Land' => $request->input('Land'),
            'PLZ' => $request->input('PLZ'),
            'Stadt' => $request->input('Stadt'),
            'Strasse' => $request->input('Strasse'),
            'Nummer' => $request->input('Nummer'),
        ]);
        return $address;
    }
}
