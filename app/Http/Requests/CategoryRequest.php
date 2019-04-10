<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class CategoryRequest extends Request
{
    public function rules()
    {
	    return [
			'name_*' => 'required',
			'desc_*' => 'required'
		];
    }

	public function authorize()
	{
		return true;
	}
}