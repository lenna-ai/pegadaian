<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperatorRequest extends FormRequest
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
        // $nameCustomer = 'required|string|min:3';
        // if ($this->routeIs('create-operator')) {
        //     $nameCustomer .= '|unique:operators,name_customer';
        // }elseif ($this->routeIs('update-operator')) {
        //     $nameCustomer .= '|unique:operators,name_customer,'.$this->route('id');
        // }
        $inputVoiceCall = $this->file('input_voice_call') !== null ? 'file|mimes:mpga,wav,m4a,mp4a,wma,aac,mp3,mp4,3gp|max:10000' : '';
        return [
            'name_agent' => 'string|exists:users,name',
            'name_customer'=>'required|string|min:3',
            'date_to_call'=>'required|date_format:Y-m-d H:i',
            'call_duration'=>'required|numeric',
            'result_call'=>'required',
            'category'=>'required',
            'tag'=>'required|exists:tags,name',
            'input_voice_call' => $inputVoiceCall,
        ];
    }
}