<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PayStoreRequest extends Request
{
    public function rules()
    {
	    return [
			'order_number' => 'required',
			'deposit_invoice' => 'required|numeric',
            'amount' => 'required|numeric|between:0,9999999999.99',
            'sub_payment_type' => 'required',
		];
    }

    public function messages()
    {
        return [
            'order_number.required' => 'Please enter order number',
            'deposit_invoice.required' => 'Please enter deposit invoice',
            'deposit_invoice.numeric' => 'Please enter deposit invoice is number',
            'amount.required' => 'Please enter amount',
            'amount.numeric' => 'Please enter amount is number',
            'amount.between' => 'Please enter amount between 0 and 10 digits',
            'sub_payment_type.required' => 'Please enter payment type',
        ];
    }

	public function authorize()
	{
		return true;
	}
}
