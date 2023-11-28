<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $nameEmail = 'required|string|min:3';
        if ($this->method() == 'POST') {
            $nameEmail .= '|unique:users,email';
        }elseif ($this->method() == 'POST') {
            $nameEmail .= '|unique:users,email,'.$this->get('id');
        }
        $data = [
            'name'=>'string|required|min:3',
            'email'=>$nameEmail,
            'roles'=>'required|numeric',
            'password'=>'required',
            'status'=>'in:login,break,offline'
        ];
        return $data;
    }
}
