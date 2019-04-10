<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfileAddressRequest extends Request
{
    public function rules()
    {
	    return [
			'address_1'				=> 'max:100',
			'postcode'				=> 'numeric|digits_between:1,35'
		];
    }

    public function messages()
    {
        return [
			'address_1.max'				=> 'Address must be less than equal 100.',
			'postcode.numeric'			=> 'Postcode must be a number.',
			'postcode.digits_between'   => 'Postcode must be less than equal 35.'
        ];
    }

	public function authorize()
	{
		return true;
	}
}