<?php

namespace App\Http\Requests;

class CronmemberRequest extends Request
{
	public function rules()
	{
		return [
			'member_card_no'   => 'numeric|digits_between:13,14'
		];
	}

	public function messages() 
	{
		return [
			'member_card_no.numeric'   => 'Member Number must be 13-digit or 14-digit',
			'member_card_no.digits_between'   => 'Member Number must be 13-digit or 14-digit'
		];
	}

	public function authorize()
	{
		return true;
	}
}
