<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentCategoryRequest extends FormRequest
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
            'input.th.name_th' => 'required',
            'input.en.name_en' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'input.th.name_th.required' => 'name_th is required',
            'input.en.name_en.required' => 'name_en is required'
        ];
    }
}
