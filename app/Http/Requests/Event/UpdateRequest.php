<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            "title" => ["required", "max:250"],
            "description" => ["required"],
            "event_date" => ["required"],
            "time" => ["required"],
            "location" => ["required"],
            "latitude" => ["required_with:location", "numeric"],
            "longitude" => ["required_with:location", "numeric"],
        ];
    }
}
