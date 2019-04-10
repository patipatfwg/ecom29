<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MenusRequest extends Request
{
    public function rules()
    {
	    return [
			'icon' => 'required',
			'name' => 'required'
		];
    }

    public function messages()
    {
        return [
            'icon.required' => 'Menu Icon required',
            'name.required' => 'Menu Name required'
        ];
    }

	public function authorize()
	{
		return true;
	}
}
