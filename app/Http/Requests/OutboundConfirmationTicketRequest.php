<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutboundConfirmationTicketRequest extends FormRequest
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
            'name_agent' => 'required|string',
            'ticket_number' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|exists:status_tracks,name',
            'call_time' => 'required|date_format:Y-m-d H:i',
            'call_duration' => 'required|numeric',
            'result_call' => 'required|string',
        ];
    }
}
