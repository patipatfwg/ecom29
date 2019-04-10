<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MaintenanceRequest extends Request {

	public function rules()
	{
		return [
			'status' 		=> 'in:on,off',
			'start_date'    => 'required|date_format:"d/m/Y H:i"',
			'end_date'      => 'required|date_format:"d/m/Y H:i"|after:start_date',
			'disable_value' => 'required|numeric|min:1|max:60'
		];
	}

	public function messages()
	{
		return [
			'start_date.required' 	 => 'Date (Start) is required.',
            'start_date.date_format' => 'Date (Start) must be date format',
			'end_date.after' 	 	 => 'Date (End) must be after date (Start)',
			'end_date.date_format'   => 'Date (End) must be date format',
        ];
	}

	public function authorize()
	{
		return true;
	}
}
