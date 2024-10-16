<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MemberRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() : array
    {
        return [
            "username" => ['required', 'unique:users,username', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email'],
            'firstname' => ['required', 'max:50'],
            'lastname' => ['required', 'max:50'],
            'password' => ['required', 'min:8'],
            'section' => ['required', 'exists:sections,name'],
            'contact_number' => ['required', 'unique:users,contact_number'],
        ];
    }
}
