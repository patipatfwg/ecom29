<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class OrderRequest extends Request
{
    public function rules()
    {
	    return [
			'order_number' => 'required'
		];
    }

    public function messages()
    {
        return [
            'order_number.required' => 'Order number is required'
        ];
    }

	public function authorize()
	{
		return true;
	}
}
