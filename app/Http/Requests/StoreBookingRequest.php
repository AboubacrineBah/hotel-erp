<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Accessible aux visiteurs publics et clients connectés
    }

    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'exists:hotels,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'client_name' => ['required', 'string', 'max:150'],
            'client_email' => ['required', 'email', 'max:150'],
            'client_phone' => ['required', 'string', 'max:40'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests_summary' => ['required', 'string', 'max:100'],
            'extra_option' => ['nullable', 'in:standard,airport,halfboard'],
        ];
    }
}
