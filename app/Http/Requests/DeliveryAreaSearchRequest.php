<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class DeliveryAreaSearchRequest extends Request
{
    public function rules()
    {
	    $rules = [
            'postcode'                => 'regex:/^([0-9]{5})*$/'
		];

		return $rules;
    }

    public function messages()
    {
        return [
            'postcode.regex'		     => 'Please enter 5 integer characters',
        ];
    }

	public function authorize()
	{
		return true;
	}
}