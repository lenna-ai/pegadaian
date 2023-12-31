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
        $inputVoiceCall = $this->file('input_voice_call') !== null ? 'file|mimes:mpga,wav,m4a,mp4a,wma,aac,mp3,mp4,3gp|max:10000' : '';
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
            'parent_branch'=>'required',
            'category'=>'required',
            'tag'=>'required|exists:tags,name',
            'input_voice_call' => $inputVoiceCall
        ];
    }
}
