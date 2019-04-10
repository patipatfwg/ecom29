<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmailSubscriptionSearchRequest extends Request
{
    public function rules()
    {
	    $rules = [
			'date-start'				=> 'date_format:"d/m/Y H:i"',
			'date-end'					=> 'date_format:"d/m/Y H:i"|after:date-start'
		];

		return $rules;
    }

    public function messages()
    {
        return [
			'date-start.required'				=> 'Subscription Date (From) is required.',
			'date-start.date_format'            => 'Subscription Date (From) must be date format',
			'date-end.required'					=> 'Subscription Date (To) is required.',
			'date-end.date_format'				=> 'Subscription Date (To) must be date format',
			'date-end.after'					=> 'Subscription Date (To) must be after subscription date (From)'
        ];
    }

	public function authorize()
	{
		return true;
	}
}