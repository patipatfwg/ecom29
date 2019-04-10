<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfileRequest extends Request
{
    public function rules()
    {
		return [
			
			'email'         => 'required_without:phone|email|max:150|emaildomain',
			// 'identify_type' => 'required|in:citizen,passport',
			// 'identify_id'   => 'required|max:30',
			// 'birthday'      => 'date_format:d-m-Y',
			'phone'         => 'required_without:email|digits_between:10,10|numeric',
			// 'first_name'    => 'required|max:64',
			// 'last_name'     => 'required|max:64',		
			
			//'tax_id'        => 'required|max:100',
			//'pickup_store'  => 'numeric|max:5',
			// 'status'        => 'required|in:active,inactive'
		];
    }

    public function messages()
    {
        return [
			// 'email.required'         => 'Email required',
			'email.required_without' => 'Must input either Mobile Number or Email',
			'email.email'            => 'Email is not a valid email address.',
			'email.max'              => 'Email must be no greater than 150.',
			'email.emaildomain'			 => 'Email is not a valid email address.',
			// 'identify_type.required' => 'Identify Type required',
			// 'identify_type.in'       => 'Identify Type Invalid data received for parameter (citizen, passport)',
			// 'identify_id.required'   => 'Identify Id required',
			// 'identify_id.max'   	 => 'Identify Id must be no greater than 30.',
			// 'birthday.date_format'   => 'Birth Day wrong date format',
			// 'first_name.required'    => 'First Name required',
			// 'first_name.max'         => 'First Name must be no greater than 64.',
			// 'last_name.required'     => 'Last Name required',
			// 'last_name.max'          => 'Last Name must be no greater than 64.',
			// 'phone.required'         => 'Phone Number required',
			'phone.required_without'	 => 'Must input either Mobile Number or Email',
			'phone.numeric'			 => 'Phone Number must be a number.',
			'phone.digits_between'   => 'Phone Number must be 10 digit.'
			// 'tax_id.required'        => 'Tax Id required',
			// 'tax_id.max'             => 'Tax Id must be no greater than 100.',
			// 'pickup_store.numeric'   => 'Pickup Store must be a number.',
			// 'pickup_store.max'       => 'Pickup Store must be no greater than 100.',
			// 'status.required'        => 'Status required',
			// 'status.in'              => 'Status Invalid data received for parameter (active, inactive)'
        ];
    }

	public function authorize()
	{
		return true;
	}
}