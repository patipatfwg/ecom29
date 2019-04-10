<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BannerUpdateRequest extends Request
{
	public function rules()
	{
		$inputs = $this->input();
		$bannerDimensions = [
			'NO_POSITION'   => '',
			'A1' => 'dimensions:width=285,height=380',
			'A2' => 'dimensions:width=380,height=190',
			'A3' => 'dimensions:width=190,height=190',
			'A4' => 'dimensions:width=190,height=190',
			'A5' => 'dimensions:width=190,height=190',
			'A6' => 'dimensions:width=190,height=190',
		];

		$thumbRule = 'image|mimes:jpg,jpeg,png';
		if (array_key_exists('position', $inputs)) {
			$dimension = $bannerDimensions[$inputs['position']];
			$thumbRule = $thumbRule.'|'.$dimension;
		}

		return [
			'banner_name'  => 'required',
			'redirect_url' => 'required',
			'position'     => 'required',
			'target'       => 'required',
			'slug'         => 'required',
			'thumb'        => $thumbRule
		];
	}

	public function messages()
	{
		return [
			'banner_name.required'  => 'Banner Name is required',
			'thumb.required'        => 'Banner Image is required',
			'thumb.image'           => 'Only JPG and PNG images are allowed',
			'thumb.mimes' 			=> 'Only .jpg or .jpg allowed',
			'thumb.dimensions'	    => 'Upload file size is not valid',
			'redirect_url.required' => 'Hyperlink is required',
			'redirect_url.url' 		=> 'Hyperlink is wrong character ( http://www.xxx.com | https://www.xxx.com )',
			'position.required'     => 'Position is required',
			'target.required'       => 'Target is required',
			'slug.required'         => 'Slug is required',
			'thumb.uploaded'		=> 'File size is exceeded limit'
		];
	}

	public function authorize()
	{
		return true;
	}
}
