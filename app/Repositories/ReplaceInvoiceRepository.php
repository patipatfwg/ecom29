<?php

namespace app\Repositories;

use Illuminate\Support\Facades\DB;

class ReplaceInvoiceRepository
{
    public function createLog($params)
    {
//        $params = array(
//            'type' => 'replace',
//            'old_info' => '{ "company_name" : "prite2shop", "tax_id" : "1234567890123", "branch_id" : "12345", "address_line1" : "3498", "address_line2" : "", "mobile_phone" : "0971267664", "provinces" : "กรุงเทพมหานคร", "districts" : "xxxxx", "sub_districts" : "yyyy", "zip_code" : "10240" }',
//            'new_info' => '{ "company_name" : "prite2shop", "tax_id" : "1234567890123", "branch_id" : "12345", "address_line1" : "3498", "address_line2" : "", "mobile_phone" : "0971267664", "provinces" : "กรุงเทพมหานคร", "districts" : "xxxxx", "sub_districts" : "yyyy", "zip_code" : "10240" }',
//            'old_invoice' => '123456789',
//            'new_invoice' => '122222222',
//            'created_at' => '2017-08-25 13:59:39',
//            'created_by' => 'prite'
//        );
        $insertData = DB::collection('replace_invoice')->insert($params);
        if($insertData){
            return true;
        }
    }
}