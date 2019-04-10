<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
	public function rules()
	{
		return [
			'username' => 'required|alpha_dash',
			'password' => 'required|min:4',
			'password_confirmation' => 'required|same:password',
			'makro_store_id' => 'required',
			'email'    => 'email',
			'mobile'   => 'numeric'
		];
	}

	public function messages()
	{
		return [
			'username.required'  => 'username is required',
			'username.alpha_dash'=> 'username must be a-z,A-Z,0-9,_',
			'password.required'  => 'password is required',
			'password.min'		 => 'password cant not less than 4 character',
			'password_confirmation.required' => 'password confirmation is required',
			'password_confirmation.same' => 'password must match with confirm password',
			'makro_store_id.required'  => 'makro store is required',
			'email.email'        => 'email must be sample@sample.com',
			'mobile.numeric'			 => 'mobile must be 08XXXXXXXX'
			
		];
	}

	public function authorize()
	{
		return true;
	}
}
