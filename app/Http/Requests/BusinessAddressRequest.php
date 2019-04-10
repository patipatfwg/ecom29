<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class BusinessAddressRequest extends Request
{
    public function rules()
    {
	     return [	
			'first_name'				=> 'required|max:64',
			'last_name'					=> 'required|max:64',
			'contact_phone'				=> 'required|numeric|digits_between:10,10',
			'contact_email'				=> 'required|email|max:150',
			'bill_address_1'			=> 'required|max:70',
			'bill_address_2'			=> 'max:70',
			'bill_province'				=> 'required',
			'bill_districts'			=> 'required',
			'bill_sub_district'			=> 'required',
			'bill_postcode'				=> 'required|numeric|digits_between:1,35'
		];
    }

    public function messages()
    {
        return [
			'first_name.required'						=> 'First Name is required.',
			'first_name.max'							=> 'First Name must be less than equal 64.',
			'last_name.required'						=> 'Last Name is required',
			'last_name.max'								=> 'Last Name must be less than equal 64.',
			'contact_phone.required'					=> 'Mobile number is required.',
			'contact_phone.numeric'						=> 'Mobile number must be a number.',
			'contact_phone.digits_between'  			=> 'Mobile number must be 10 digit.',
			'contact_email.required'					=> 'Email is required.',
			'contact_email.email'						=> 'Email is invalid format.',
			'contact_email.max'							=> 'Email must be less than equal 150',
			'bill_address_1.required'					=> 'Address 1 is required',
			'bill_address_1.max'						=> 'Address 1 must be less than equal 70.',
			'bill_address_2.max'						=> 'Address 2 must be less than equal 70.',
			'bill_province.required'					=> 'Province is required.',
			'bill_districts.required'					=> 'District is required.',
			'bill_sub_district.required'				=> 'Sub District is required.',
			'bill_postcode.required'					=> 'Postcode is required.',
			'bill_postcode.numeric'						=> 'Postcode must be a number.',
			'bill_postcode.digits_between'  			=> 'Postcode must be less than equal 35.',
        ];
    }

	public function authorize()
	{
		return true;
	}
}