<?php

namespace App\Http\Requests\Tithe;

use Illuminate\Foundation\Http\FormRequest;

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
            'mfc_user_id' => ['required', 'exists:users,mfc_id_number'],
            'amount' => ['required', 'numeric'],
            'is_payment_required' => ['required'],
            'for_the_month_of' => ['required'],
        ];
    }
}
