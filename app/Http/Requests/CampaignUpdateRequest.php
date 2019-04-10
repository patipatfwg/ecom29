<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CampaignUpdateRequest extends Request {
	public function rules()
	{
		return [
			'campaign_code' => 'required',
			'name_th'       => 'required',
			'name_en'       => 'required',
			'start_date'    => 'required',
			'end_date'      => 'required',
			'slug'			=> 'required|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
			'thumb'			=> 'image|mimes:jpg,jpeg,png',
			'thumb2'		=> 'image|mimes:jpg,jpeg,png'
		];
	}

	public function messages()
	{
		return [
			'campaign_code.required' => 'campaign code is required',
			'name_th.required' => 'name_th is required',
			'name_en.required' => 'name_en is required',
			'start_date.required' => 'start_date is required',
			'end_date.required' => 'end_date is required',
			'slug.required'			=> 'slug is required',
			'slug.regex'			=> 'slug is not valide',

			'thumb.image'		=> 'upload file must be an image',
			'thumb2.image'		=> 'upload file must be an image',
			'thumb.mimes'		=> 'upload file must be in .jpg or .png format',
			'thumb2.mimes'		=> 'upload file must be in .jpg or .png format',

			'thumb.uploaded'	 => 'Banner Campaign Page (A) File size is exceeded limit',
			'thumb2.uploaded'	 => 'Banner Campaign Page (B) File size is exceeded limit'
			//'ads_script.required'	=> 'ads script is required'
		];
	}

	public function authorize()
	{
		return true;
	}
}
