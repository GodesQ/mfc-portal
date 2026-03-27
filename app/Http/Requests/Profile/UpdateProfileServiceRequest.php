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
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['nullable', 'integer'],

            'service_category' => ['nullable', 'array'],
            'service_category.*' => ['required_with:service_type.*,section.*,service_area.*', 'string'],

            'service_type' => ['nullable', 'array'],
            'service_type.*' => ['required_with:service_category.*,section.*,service_area.*', 'string'],

            'section' => ['nullable', 'array'],
            'section.*' => ['required_with:service_category.*,service_type.*,service_area.*', 'string'],

            'service_area' => ['nullable', 'array'],
            'service_area.*' => ['required_with:service_category.*,service_type.*,section.*', 'string'],
        ];
    }
}
