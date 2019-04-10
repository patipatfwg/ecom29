<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'coupon_name.th'        => 'required',
            'coupon_name.en'        => 'required',
            'description.th'        => 'required',
            'description.en'        => 'required',
            'coupon_code'           => 'required|regex:/^([a-zA-Z0-9]{8})$/',
            'discount'              => 'required',
            'ref_code'              => 'required',
        ];
        if($inputs['discount_type'] == 'product discount') {
            $rule['product'] = "required";
            $rule['product_threshold'] = "required";
        }else {
            $rule['least_amount'] = "required";
        }
        return $rule;
    }
    public function messages()
    {
        $inputs = $this->input();
        $valueName = [
            'product discount' => 'product is required',
            'cart discount' => 'threshold is required',
        ];
        
        $message = [
            'coupon_name.th.required'   => 'name_th is required',
            'coupon_name.en.required'   => 'name_en is required',
            'description.th.required'   => 'description_th is required',
            'description.en.required'   => 'description_en is required',
            'coupon_code.required'      => 'coupon_code is required',
            'coupon_code.regex'         => 'coupon_code must be a-Z , 0-9 and 8 character',
            'ref_code.required'         => 'ref_code is required',
            'discount.required'         => 'discount is required',
        ];
        if($inputs['discount_type'] == 'product discount') {
            $message['product.required'] = $valueName[$inputs['discount_type']];
            $message['product_threshold.required'] = 'product_threshold is required';         
        }else {
            $message['least_amount.required'] = $valueName[$inputs['discount_type']];
        }
        
        return $message;
    }
}
