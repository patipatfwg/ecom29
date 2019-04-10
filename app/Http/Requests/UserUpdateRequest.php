<?php

namespace App\Http\Requests;

use App\Http\Requests\UserRequest;

class UserUpdateRequest extends UserRequest
{
	public function rules()
	{
		return [
			'password_confirmation' => 'same:password',
			'makro_store_id' => 'required',
			'email'    => 'email',
			'mobile'   => 'numeric'
		];
	}

	public function messages()
	{
		return [
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
