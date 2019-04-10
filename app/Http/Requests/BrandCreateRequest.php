<?php
namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class BrandCreateRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
    {	
		$rules = [];

		$languages = config('language.brand');
		foreach($languages as $language){
			$rules['name.'.$language] = 'required';
		}

		$rules['banner_small_image'] =  'image|mimes:jpg,jpeg,png|dimensions:width=178,height=70';
		$rules['banner_pageA_image'] =  'image|mimes:jpg,jpeg,png|dimensions:width=1140,height=380';
		$rules['banner_pageB_image'] =  'image|mimes:jpg,jpeg,png|dimensions:width=285,height=380';
		
		// $rules['banner_pageB_image'] =  'upload|mimes:jpg,jpeg,png|dimensions:width=285,height=380';

	    return $rules;
    }

	public function messages()
    {	
		$messages = [];

		$languages = config('language.brand');
		foreach($languages as $language){
			$messages['name.'.$language.'.required'] = 'name ('. $language .') is required';
		}

		$messages['banner_small_image.required'] 		= 'banner small is required';
		$messages['banner_small_image.image'] 			= 'banner small upload file must be image';
		$messages['banner_small_image.mimes'] 			= 'banner small image type must be .jpg or .png format';
		$messages['banner_small_image.dimensions'] 		= 'banner small invalid image size';

		$messages['banner_pageA_image.required'] 		= 'banner brand page (A) is required';
		$messages['banner_pageA_image.image'] 			= 'banner brand page (A) upload file must be image';
		$messages['banner_pageA_image.mimes'] 			= 'banner brand page (A) image type must be .jpg or .png format';
		$messages['banner_pageA_image.dimensions'] 		= 'banner brand page (A) invalid image size';

		$messages['banner_pageB_image.required'] 		= 'banner brand page (B) is required';
		$messages['banner_pageB_image.image'] 			= 'banner brand page (B) upload file must be image';
		$messages['banner_pageB_image.mimes'] 			= 'banner brand page (B) image type must be .jpg or .png format';
		$messages['banner_pageB_image.dimensions'] 		= 'banner brand page (B) invalid image size';


		$messages['banner_small_image.uploaded']		= 'banner small upload file size is exceeded limit';
		$messages['banner_pageA_image.uploaded']		= 'banner brand page (A) upload file size is exceeded limit';
		$messages['banner_pageB_image.uploaded']		= 'banner brand page (B) upload file size is exceeded limit';
        return $messages;
	}
}