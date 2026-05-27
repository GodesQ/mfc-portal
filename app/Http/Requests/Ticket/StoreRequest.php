<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_name' => ['required', 'string', 'max:255'],
            'is_free' => ['boolean'],
            'price' => ['nullable', 'required_if:is_free,0', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_unlimited' => ['boolean'],
            'total_number_of_tickets' => ['nullable', 'required_if:is_unlimited,0', 'integer', 'min:1'],
        ];
    }
}
