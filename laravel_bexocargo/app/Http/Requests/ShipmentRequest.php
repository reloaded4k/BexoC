<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'sender_name' => 'required|string|max:100',
            'sender_email' => 'required|email|max:100',
            'sender_address' => 'required|string',
            'sender_contact' => 'required|string|max:20',
            'recipient_name' => 'required|string|max:100',
            'recipient_email' => 'required|email|max:100',
            'recipient_address' => 'required|string',
            'recipient_contact' => 'required|string|max:20',
            'cargo_name' => 'required|string|max:100',
            'cargo_description' => 'required|string',
            'cargo_length' => 'nullable|numeric',
            'cargo_width' => 'nullable|numeric',
            'cargo_height' => 'nullable|numeric',
            'cargo_weight' => 'nullable|numeric',
        ];
    }
}