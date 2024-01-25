<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'prefix' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(5),
                // Password::min(5)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            'tel' => ['required', 'string',],
            'home_id' => ['required', 'string',],
            'mu' => ['required', 'string',],
            'tambon' => ['required', 'string',],
            'amphure' => ['required', 'string',],
            'city' => ['required', 'string',],
            'zip_id' => ['required',],
        ];
    }
}
