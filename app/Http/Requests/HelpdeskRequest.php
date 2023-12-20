<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HelpdeskRequest extends FormRequest
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
            'branch_code' => 'required|numeric',
            'branch_name' => 'required|string',
            'branch_name_staff' => 'required|string',
            'branch_phone_number' => 'required|numeric',
            'date_to_call' => 'required|date_format:Y-m-d H:i',
            'call_duration' => 'required|numeric',
            'result_call' => 'required|string',
            'name_agent' => 'required|string',
            'status'=>'required|exists:status_tracks,name',
            'parent_branch'=>'required|exists:help_desk_outlets,parent_branch',
            'category'=>'required|exists:categories,name',
            'tag'=>'required|exists:tags,name',
            'input_voice_call' => 'required|file|mimes:mpga,wav,m4a,wma,aac,mp3,mp4|max:5000'
        ];
    }
}
