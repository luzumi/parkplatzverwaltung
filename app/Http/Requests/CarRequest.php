<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            "sign" => "required|max:12|alpha_num_spaces",
            'manufacturer' => "required|alpha_num_spaces",
            "model" => "required|alpha_num_spaces",
            "color" => "required|alpha_num_spaces",
            "image" => "image",
        ];
    }
}
