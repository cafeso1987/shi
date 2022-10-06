<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CoinSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =[
            'name'=>'required|max:100',
            'coin_type'=>'required|max:80|unique:coins',
            'coin_price' => 'required|numeric|gt:0',
            'network' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages=[
            'coin_type.required'=>__('Coin type is required'),
            'coin_type.unique'=> __('coin type already exists'),
            'name.required'=> __('coin full name is required'),
            'coin_price.required'=> __('coin price is required'),
            'coin_price.numeric'=> __('coin price must be number'),
        ];

        return $messages;
    }
}
