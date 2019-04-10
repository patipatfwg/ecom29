<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AttributeRequest extends Request {
	public function rules()
	{
		$inputs = $this->input();
		$rule = [
			'name_th' 			 => 'required',
			'name_en' 			 => 'required',
			'sub_attr_th.*.name' => 'required',
			'sub_attr_en.*.name' => 'required',
		];

		for($i=0;$i<count($inputs['sub_attr_th']);$i++){
			$rule["file_$i"] = 'image|mimes:jpg,jpeg,png|dimensions:width=16,height=16';
		}

		return $rule;
	}

	public function messages()
	{
		$inputs = $this->input();

		$message = [
			'name_th.required' 			  => 'Attribute Name (TH) is required',
			'name_en.required' 			  => 'Attribute Name (EN) is required',
			'sub_attr_th.*.name.required' => 'Attribute Value (TH) is required',
			'sub_attr_en.*.name.required' => 'Attribute Value (EN) is required',
		];

		for($i=0;$i<count($inputs['sub_attr_th']);$i++){
			$message["file_$i.image"]	      = 'Only JPG and PNG images are allowed';
			$message["file_$i.mimes"]		  = 'Only .jpg or .jpg allowed';
			$message["file_$i.dimensions"]  = 'Attribute invalid image size: 16x16';
			$message["file_$i.uploaded"]  = 'File '. ($i+1) .' size is exceeded limit';	
		}

		return $message;
	}

	public function authorize()
	{
		return true;
	}
}
