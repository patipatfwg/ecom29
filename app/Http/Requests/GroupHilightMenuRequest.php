<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupHilightMenuRequest extends FormRequest
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
        $inputs = $this->input();

        $rule = [
            'name_th' => 'required',
            'name_en' => 'required',
            'target'  => 'required',
            'type'    => 'required',
        ];

        if(!empty($inputs['type'])) {
            $rule['value'] = "required";
        }

        return $rule;
    }

    public function messages()
    {
        $inputs = $this->input();

        $valueName = [
            'link_external' => 'external link is required',
            'link_internal' => 'internal link is required',
            'banner' => 'banner is required',
            'campaign' => 'campaign is required',
            'business_category' => 'business category is required',
            'product_category' => 'product category is required',
            'content' => 'content is required'
        ];
            
        $message = [
            'name_th.required' => 'name_th is required',
            'name_en.required' => 'name_en is required',
            'target.required' => 'target is required',
            'type.required' => 'type is required',
        ];

        if(!empty($inputs['type'])) {
            if($inputs['value']=='undefine')
            {
                $inputs['value'] = '';
            }
            $message['value.required'] = $valueName[$inputs['type']];
        }

        return $message;
    }
}
