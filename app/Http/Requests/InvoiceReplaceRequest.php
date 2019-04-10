<?php

namespace App\Http\Requests;

class InvoiceReplaceRequest extends Request
{
    public function rules()
    {
        return [
            'shop_name'      => 'required|max:100|regex:/[^{}<>]+/',
            'tax_id'         => 'required|numeric|digits:13',
            'branch_id'      => 'required|numeric|digits:5',
            // 'phone'          => 'required|numeric|digits:10',
            'address_line_1' => 'required|max:300|regex:/[^{}<>]+/',
            'province'       => 'required',
            'districts'      => 'required',
            'sub_districts'  => 'required',
            'zipcode'        => 'required|numeric|digits:5',

        ];
    }

    public function messages()
    {
        return [
            'shop_name.required'      => 'Company or Personal name is required',
            'shop_name.max'           => 'Company is maximum 100 characters',
            'shop_name.regex'         => 'Company or Personal name must not be included in { , } , < and >',
            'tax_id.required'         => 'Tax ID is required',
            'tax_id.numeric'          => 'Tax ID must be numeric',
            'tax_id.digits'           => 'Tax ID must have an exact 13-digits',
            'branch_id.required'      => 'Branch ID is required',
            'branch_id.numeric'       => 'Branch ID must be numeric',
            'branch_id.digits'        => 'A 5 digits number with 00000 for Head office and Branch no as 00001 and upward.',
            // 'phone.required'          => 'Mobile number is required',
            // 'phone.numeric'           => 'Mobile number is required',
            // 'phone.digits'            => 'Mobile number must have an exact 10-digit',
            'address_line_1.required' => 'Address is required',
            'address_line_1.max'      => 'Address is maximum 300 characters',
            'address_line_1.regex'    => 'Address must not be included in { , } , < and >',
            'province.required'       => 'Province is required',
            'districts.required'      => 'District is required',
            'sub_districts.required'  => 'Sub-district is required',
            'zipcode.required'        => 'Zipcode is required',
            'zipcode.numeric'         => 'Zipcode number is required',
            'zipcode.digits'          => 'Zipcode number must have an exact 5-digit',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
