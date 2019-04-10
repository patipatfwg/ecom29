<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_th' => 'required',
            'name_en' => 'required',
            'description_th' => 'required',
            'description_en' => 'required',
            'slug'	=> 'required|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',

        ];
    }
    public function messages()
    {
        return [
            'name_th.required' => 'Name (TH) is required',
            'name_en.required' => 'Name (EN) is required',
            'description_th.required' => 'Detail (TH) is required',
            'description_en.required' => 'Detail (EN) is required',
            'slug.required'    => 'slug is required',
            'slug.regex'       => 'slug is not valide',

        ];
    }
}
