<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class ConfigPaymentMethodEditRequest extends Request
{
    public function rules()
    {
	    $rules = [
            'data.priority'             => 'regex:/^([1-9]\d?)?$/',
            'data.name.*'               => 'required|max:35',
            'data.subtitle.*'           => 'max:60',
            'data.description.*'        => 'required',
            'data.payment_gateway'      => 'required',
            'data.min_amount'           => 'required|regex:/^[0-9]*(\.[0-9]{1,2})?$/|numeric|min:0.25',
            'data.max_amount'           => 'regex:/^[0-9]*(\.[0-9]{1,2})?$/',
            'data.percent_of_charge'    => 'required|regex:/^[0-9]*(\.[0-9]{1,2})?$/',
            'data.vat_percentage'       => 'required|regex:/^[0-9]*(\.[0-9]{1,2})?$/',
            'data.withholding_tax'      => 'required|regex:/^[0-9]*(\.[0-9]{1,2})?$/'
		];

		return $rules;
    }

    public function messages()
    {
        return [

            'data.priority.regex'               => 'Please enter integer number between 1-99 only',
            'data.name.*.required'              => 'This field is required',
            'data.name.*.max'                   => 'Please enter no more than 35 characters',
            'data.subtitle.*.max'             => 'Please enter no more than 60 characters',
            'data.description.*.required'       => 'This field is required',
            'data.payment_gateway.required'     => 'This field is required',
            'data.min_amount.required'          => 'This field is required',
            'data.min_amount.regex'             => 'Please enter 2 decimal places only',
            'data.min_amount.min'               => 'Minimum value is 0.25',
            'data.max_amount.regex'             => 'Please enter 2 decimal places only',
            'data.percent_of_charge.required'   => 'This field is required',
            'data.percent_of_charge.regex'      => 'Please enter 2 decimal places only',
            'data.vat_percentage.required'      => 'This field is required',
            'data.vat_percentage.regex'         => 'Please enter 2 decimal places only',
            'data.withholding_tax.required'     => 'This field is required',
            'data.withholding_tax.regex'        => 'Please enter 2 decimal places only',
        ];
    }

	public function authorize()
	{
		return true;
	}
}