<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends Request
{
    public function rules()
    {
	    return [
			'name.*'		 => 'required',
			'sub_makro_unit' => 'required',
			'unit_type'		 => 'required',
			'makro_unit'	 => 'required',
			'images' 		 => 'required',
			'productCategory_id' => 'required',
			'minimum_order_limit' => 'numeric|min:1',
			'maximum_order_limit' => 'numeric|min:1'
		];
    }

	public function messages()
	{
		return [
			'name.th.required'		 => 'Name [TH] is required',
			'name.en.required'		 => 'Name [EN] is required',
			'sub_makro_unit.required' => 'Unit is required',
			'unit_type.required'		 => 'Unit type is required',
			'makro_unit.required'	 => 'Pieces per Unit is required',
			'images.required' 		 => 'Image is required',
			'productCategory_id.required' => 'Product category is required',
			'minimum_order_limit.numeric'  => 'Minimum must be numeric',
			'minimum_order_limit.min'	 => 'Minimum min is 1',
			'maximum_order_limit.numeric'  => 'Maximum must be numeric',
			'maximum_order_limit.min'	  => 'Maximum min is 1'
		
		];
	}

	public function authorize()
	{
		return true;
	}
}