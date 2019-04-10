<?php
$max = 200;
$data = [];
    for($i = 1; $i <= 50 ; $i++){
        $data[] =  [
                        "id" => $i,
                        "coupon_code" => "coupon_code $i",
                        "coupon_name" => "coupon_name $i",
                        "coupon_type" => "coupon_type",
                        "discount_type" => "discount_type",
                        "started_date" => date('d-F-Y H:i:s'),
                        "end_date" => date('d-F-Y H:i:s'),
                        "usage" => rand(0,500),
                        "status" => 'active',
                    ];
    }

    $jdata = [
                'draw'=> 1,
                'recordsTotal'=> $max,
                'recordsFiltered'=> count($data),
                'data' => [
                            'records' => $data,
                            'pagination' => ['total_records'=>10]
                          ],
                'input'=> [
                            'length'=>15,
                          ],
            ];  
return $jdata;