<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;

class CategoryCreateRequest extends Request
{
    public function rules()
    {
    	return [];
    }

	public function messages()
	{
		return [
			'input.th.name_th.required'      => 'name_th is required',
			'input.en.name_en.required'      => 'name_en is required',
			'image_position_A.image'         => 'Banner main category (A) upload file must be image',
			'image_position_A.mimes'         => 'Banner main category (A) image type must be , .jpeg, .png format',
			'image_position_A.dimensions'    => 'Banner main category (A) invalid image size',
			'image_position_B.image'         => 'Banner main category (B) upload file must be image',
			'image_position_B.mimes'         => 'Banner main category (B) image type must be , .jpeg, .png format',
			'image_position_B.dimensions'    => 'Banner main category (B) invalid image size',
			'image_position_C.image'         => 'Banner main category (C) upload file must be image',
			'image_position_C.mimes'         => 'Banner main category (C) image type must be , .jpeg, .png format',
			'image_position_C.dimensions'    => 'Banner main category (C) invalid image size',
			'image_position_D1_1.image'      => 'Banner main category (D1_1) upload file must be image',
			'image_position_D1_1.mimes'      => 'Banner main category (D1_1) image type must be , .jpeg, .png format',
			'image_position_D1_1.dimensions' => 'Banner main category (D1_1) invalid image size',
			'image_position_D1_2.image'      => 'Banner main category (D1_2) upload file must be image',
			'image_position_D1_2.mimes'      => 'Banner main category (D1_2) image type must be , .jpeg, .png format',
			'image_position_D1_2.dimensions' => 'Banner main category (D1_2) invalid image size',
			'image_position_D1_3.image'      => 'Banner main category (D1_3) upload file must be image',
			'image_position_D1_3.mimes'      => 'Banner main category (D1_3) image type must be , .jpeg, .png format',
			'image_position_D1_3.dimensions' => 'Banner main category (D1_3) invalid image size',
			'image_position_D2.image'        => 'Banner main category (D2) upload file must be image',
			'image_position_D2.mimes'        => 'Banner main category (D2) image type must be , .jpeg, .png format',
			'image_position_D2.dimensions'   => 'Banner main category (D2) invalid image size',
			'image_position_D3.image'        => 'Banner main category (D3) upload file must be image',
			'image_position_D3.mimes'        => 'Banner main category (D3) image type must be , .jpeg, .png format',
			'image_position_D3.dimensions'   => 'Banner main category (D3) invalid image size',
			'image_position_D4.image'        => 'Banner main category (D4) upload file must be image',
			'image_position_D4.mimes'        => 'Banner main category (D4) image type must be , .jpeg, .png format',
			'image_position_D4.dimensions'   => 'Banner main category (D4) invalid image size',
			'image_position_D5.image'        => 'Banner main category (D5) upload file must be image',
			'image_position_D5.mimes'        => 'Banner main category (D5) image type must be , .jpeg, .png format',
			'image_position_D5.dimensions'   => 'Banner main category (D5) invalid image size',
			'image_position_D6.image'        => 'Banner main category (D6) upload file must be image',
			'image_position_D6.mimes'        => 'Banner main category (D6) image type must be , .jpeg, .png format',
			'image_position_D6.dimensions'   => 'Banner main category (D6) invalid image size',
			'image_position_A.uploaded'      => 'Banner main category (A) upload file size is exceeded limit',
			'image_position_B.uploaded'      => 'Banner main category (B) upload file size is exceeded limit',
			'image_position_C.uploaded'      => 'Banner main category (C) upload file size is exceeded limit',
			'image_position_D1_1.uploaded'   => 'Banner main category (D1_1) upload file size is exceeded limit',
			'image_position_D1_2.uploaded'   => 'Banner main category (D1_2) upload file size is exceeded limit',
			'image_position_D1_3.uploaded'   => 'Banner main category (D1_3) upload file size is exceeded limit',
			'image_position_D2.uploaded'     => 'Banner main category (D2) upload file size is exceeded limit',
			'image_position_D3.uploaded'     => 'Banner main category (D3) upload file size is exceeded limit',
			'image_position_D4.uploaded'     => 'Banner main category (D4) upload file size is exceeded limit',
			'image_position_D5.uploaded'     => 'Banner main category (D5) upload file size is exceeded limit',
			'image_position_D6.uploaded'     => 'Banner main category (D6) upload file size is exceeded limit'
		];
	}

	public function authorize()
	{
		return true;
	}
}