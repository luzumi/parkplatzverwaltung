<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'Land' => 'required|string|max:50',
            'PLZ' => 'required|integer|digits:5',
            'Stadt' => 'required|string|max:50',
            'Strasse' => 'required|string|max:100',
            'Nummer' => 'required|integer|max_digits:5',
        ];
    }
}
