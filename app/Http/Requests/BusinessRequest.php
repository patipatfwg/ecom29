<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class BusinessRequest extends Request
{
    public function rules()
    {
	    return [
			'business_shop_name'	=> 'max:100',
			'business_phone'		=> 'required|numeric|digits_between:10,10',
			'business_email'		=> 'email|max:150',
			'tax_address_1'			=> 'required|max:70',
			'tax_address_2'			=> 'max:70',
			'tax_id'				=> 'required|numeric|digits_between:1,40',
			'tax_province'			=> 'required',
			'tax_districts'			=> 'required',
			'tax_sub_district'		=> 'required',
			'tax_postcode'			=> 'required|numeric|digits_between:1,35'
		];
    }

    public function messages()
    {
        return [
			'business_shop_name.max'				=> 'Company Name must be less than equal 100',
			'business_phone.required'       		=> 'Mobile Phone is required.',
			'business_phone.digits_between'       	=> 'Mobile Phone must be 10 digit.',
			'business_phone.numeric'    			=> 'Mobile Phone must be a number.',
			'business_email.email'					=> 'Email is not a valid email address.',
			'business_email.max'					=> 'Email must be no greater than 150.',
			'tax_address_1.required'				=> 'Address 1 is required.',
			'tax_address_1.max'						=> 'Address must be less than equal 70.',
			'tax_address_2.max'						=> 'Address must be less than equal 70.',
			'tax_id.required'						=> 'Tax ID is required.',
			'tax_id.numeric'						=> 'Tax ID must be a number.',
			'tax_id.digits_between'					=> 'Tax ID must be less than equal 40.',
			'tax_province.required'					=> 'Province is required.',
			'tax_districts.required'				=> 'District is required.',
			'tax_sub_district.required'				=> 'Sub District is required.',
			'tax_postcode.required'					=> 'Postcode is required.',
			'tax_postcode.numeric'	    			=> 'Postcode must be a number.',
			'tax_postcode.digits_between'	   		=> 'Postcode must be less than equal 35.'
        ];
    }

	public function authorize()
	{
		return true;
	}
}