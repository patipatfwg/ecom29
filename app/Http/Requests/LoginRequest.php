<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    public function rules()
    {
	    return [
	        'username' => 'required|max:30',
			'password' => 'required|max:30'
		];
    }

    public function messages()
    {
        return [
			'username.required' => 'Username required',
			'username.min'      => 'Username must be no less than 5.',
			'username.max'      => 'Username must be no greater than 30.',
			'password.required' => 'Password required',
			'password.min'      => 'Password must be no less than 5.',
			'password.max'      => 'Password must be no greater than 30.'
        ];
    }


	public function authorize()
	{
		return true;
	}
}