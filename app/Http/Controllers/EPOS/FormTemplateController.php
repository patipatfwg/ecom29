<?php
namespace App\Http\Controllers\EPOS;


class FormTemplateController extends EPOSBaseController
{
    public function invoice($type) {
        if($type == 2) {
            return view('epos.invoice.template.sheet002', []);
        } else {
            return view('epos.invoice.template.sheet001', []);
        }
    }
}