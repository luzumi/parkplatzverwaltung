<?php

namespace App\Http\Controllers;

use App\Actions\CreateMessage;
use App\Actions\SaveAddress;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\RedirectResponse;


class AddressController extends Controller
{
    /**
     * @param AddressRequest $request
     * @param SaveAddress $saveAddress
     * @param int $user_id
     * @param CreateMessage $message
     * @return RedirectResponse
     */
    public function create(
        AddressRequest $request,
        SaveAddress    $saveAddress,
        int            $user_id,
        CreateMessage  $message
    ): RedirectResponse
    {
        $address = $saveAddress->handle($request, $user_id, $message);

        return redirect()->route('user.show', $user_id);
    }
}
