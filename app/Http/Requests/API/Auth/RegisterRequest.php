<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'username' => ['required', 'max:50', 'unique:users,username'],
            'password' => ['required', 'min:8'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'country_code' => ['required', 'integer'],
            'contact_number' => ['required', 'unique:users,contact_number'],
            'section_id' => ['required', 'exists:sections,id']
        ];
    }
}
