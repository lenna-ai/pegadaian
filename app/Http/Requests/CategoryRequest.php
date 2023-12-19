<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        $name = $this->name;
        $owned = $this->owned;
        return [
            'name'=> [
                'required',
                'max:254',
                Rule::unique('categories')->where(function($query) use($name,$owned){
                    return $query->where('name',$name)->where('owned',$owned);
                })
            ],
            'owned'=> 'required|in:operator,helpdesk',
        ];
    }
}
