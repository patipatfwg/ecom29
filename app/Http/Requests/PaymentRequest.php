<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PaymentRequest extends Request {
	public function rules()
	{
		$rules =[
			'name_.th'       => 'required',
			'name_.en'       => 'required',
			'description_.th'=> 'required',
			'description_.en'=> 'required',
			'bank'			=> 'required',
			'installment'	=> 'required|numeric',
			'threshold'		=> 'required|numeric',
			'interest_rate'	=> 'required|numeric',
		];

		return $rules;
	}

	public function messages()
	{
		return [
			'name_.th.required' 		=> 'name_th is required',
			'name_.en.required' 		=> 'name_en is required',
			'description_.th.required'	=> 'description_th is required',
			'description_.en.required'	=> 'description_en is required',
			'bank.required'				=> 'bank is required',
			'installment.required'		=> 'installment is required',
			'threshold.required'		=> 'threshold is required',
			'interest_rate.required'	=> 'interest_rate is required',
			'installment.numeric'		=> 'installment must be a number.',
			'threshold.numeric'		=> 'threshold must be a number.',
			'interest_rate.numeric'	=> 'interest_rate must be a number.',
		];
	}

	public function authorize()
	{
		return true;
	}
}
