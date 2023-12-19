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
        $nameCustomer = 'required|string|min:3';
        if ($this->method() == 'POST') {
            $nameCustomer .= '|unique:operators,name_customer';
        }elseif ($this->method() == 'PUT') {
            $nameCustomer .= '|unique:operators,name_customer,'.$this->route('id');
        }
        return [
            'name_agent' => 'string|exists:users,name',
            'name_customer'=>$nameCustomer,
            'date_to_call'=>'required',
            'call_duration'=>'required|numeric',
            'result_call'=>'required',
            'category'=>'required|exists:categories,name',
            'tag'=>'required|exists:tags,name',
        ];
    }
}
