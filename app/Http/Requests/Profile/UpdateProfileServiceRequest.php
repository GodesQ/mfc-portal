<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileServiceRequest extends FormRequest
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
            'service_category' => ['required', 'array', 'min:1'], // Ensure there is at least one service category
            'service_category.*' => ['required', 'string'], // Each service category should be a string

            'service_type' => ['required', 'array', 'min:1'], // Ensure there is at least one service type
            'service_type.*' => ['required', 'string'], // Each service type should be a string

            'section' => ['required', 'array', 'min:1'], // Ensure there is at least one section
            'section.*' => ['required', 'string'], // Each section should be a string

            'service_area' => ['required', 'array', 'min:1'], // Ensure there is at least one service area
            'service_area.*' => ['required', 'string'], // Each service area should be a string
        ];
    }
}
