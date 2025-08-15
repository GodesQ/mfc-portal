<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
      'first_name' => ['required', 'string', 'max:255'],
      'last_name' => ['required', 'string', 'max:255'],
      'avatar' => ['nullable', 'image', 'max:2048'], // Optional avatar image, max size 2MB
      'section_id' => ['required', 'exists:sections,id'],
      'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore(auth()->id())],
      'contact_number' => ['nullable', 'string', 'max:15'],
      'country_code' => ['nullable', 'string', 'max:5'],
      'email' => [
        'required',
        'string',
        'email',
        'max:255',
        Rule::unique('users')->ignore(auth()->id()),
      ],
    ];
  }
}
