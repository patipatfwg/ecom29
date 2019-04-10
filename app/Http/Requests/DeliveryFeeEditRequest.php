<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class DeliveryFeeEditRequest extends Request
{
    public function rules()
    {
	    $rules = [
            'data.*.min'                => 'required|regex:/^[0-9]{1,3}(,?[0-9]{3})*$/', //(\.[0-9]{1,2})? decimal
			'data.*.fee'				=> 'required|regex:/^[0-9]{1,3}(,?[0-9]{3})*$/'
		];

		return $rules;
    }

    public function messages()
    {
        return [

            'data.*.min.required'		     => 'This field is required',
            'data.*.min.regex'               => 'Please enter integer number only',
            'data.*.fee.required'            => 'This field is required',
            'data.*.fee.regex'               => 'Please enter integer number only'
        ];
    }

	public function authorize()
	{
		return true;
	}
}