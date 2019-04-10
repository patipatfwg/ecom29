<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserGroupsRequest extends Request {
	public function rules()
	{
		return [
			'name' => 'required'
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'User group name required'
		];
	}

	public function authorize()
	{
		return true;
	}
}
