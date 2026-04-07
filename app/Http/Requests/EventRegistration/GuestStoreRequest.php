<?php

namespace App\Http\Requests\EventRegistration;

use Illuminate\Foundation\Http\FormRequest;

class GuestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payer_first_name' => 'required|string|max:255',
            'payer_last_name' => 'required|string|max:255',
            'payer_email' => 'required|email|max:255',
            'payer_contact_number' => 'required|string|max:20',
            'payer_tshirt_size' => 'nullable|string|max:50',
            'payer_mfc_section' => 'nullable|string|max:255',
            'payer_area' => 'nullable|string|max:255',
            'payer_address' => 'required|string|max:255',
            'attendees' => 'nullable|array',
            'attendees.*.first_name' => 'required_with:attendees.*.last_name,attendees.*.email,attendees.*.contact_number,attendees.*.address|nullable|string|max:255',
            'attendees.*.last_name' => 'required_with:attendees.*.first_name,attendees.*.email,attendees.*.contact_number,attendees.*.address|nullable|string|max:255',
            'attendees.*.email' => 'required_with:attendees.*.first_name,attendees.*.last_name,attendees.*.contact_number,attendees.*.address|nullable|email|max:255',
            'attendees.*.contact_number' => 'required_with:attendees.*.first_name,attendees.*.last_name,attendees.*.email,attendees.*.address|nullable|string|max:20',
            'attendees.*.tshirt_size' => 'nullable|string|max:50',
            'attendees.*.mfc_section' => 'nullable|string|max:255',
            'attendees.*.area' => 'nullable|string|max:255',
            'attendees.*.address' => 'required_with:attendees.*.first_name,attendees.*.last_name,attendees.*.email,attendees.*.contact_number|nullable|string|max:255',
            'donation' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'attendees.array' => 'The attendee list is invalid.',
            'payer_first_name.required' => 'The payer first name is required.',
            'payer_last_name.required' => 'The payer last name is required.',
            'payer_email.required' => 'The payer email is required.',
            'payer_contact_number.required' => 'The payer contact number is required.',
            'payer_address.required' => 'The payer address is required.',
        ];
    }
}
