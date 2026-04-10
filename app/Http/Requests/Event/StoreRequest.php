<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'section_ids' => ['required', 'array'],
            'event_date' => ['required'],
            'time' => ['required'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'reg_fee' => ['required', 'numeric', 'min:0'],
            'is_early_bird_enabled' => ['nullable', 'boolean'],
            'early_bird_discount' => ['nullable', 'numeric', 'min:0'],
            'poster' => ['required', 'file', 'mimes:jpeg,png,jpg'],
            'description' => ['required', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->boolean('is_early_bird_enabled')) {
                return;
            }

            $regFee = (float) $this->input('reg_fee', 0);
            $discount = (float) $this->input('early_bird_discount', 0);

            if ($discount > $regFee) {
                $validator->errors()->add('early_bird_discount', 'The early bird discount cannot exceed the registration fee.');
            }
        });
    }
}
