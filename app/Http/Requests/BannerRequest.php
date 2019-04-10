<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class BannerRequest extends Request
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

		$thumbRule = 'required|image|mimes:jpg,jpeg,png';
		if (array_key_exists('position', $inputs)&&$inputs['position']!='NO_POSITION') {
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
			'thumb.required'        => 'banner image is required',
			'thumb.image'           => 'upload file must be an image',
			'thumb.mimes' 			=> 'upload file must be in .jpg or .png format',
			'thumb.dimensions'	    => 'upload file size is not valid',
			'redirect_url.required' => 'Hyperlink is required',
			'position.required'     => 'position is required',
			'target.required'       => 'target is required',
			'slug.required'         => 'slug is required',
			'thumb.uploaded'	 	=> 'File size is exceeded limit'
		];
	}

	public function authorize()
	{
		return true;
	}
}
