<?php
namespace App\Repositories;

use App\Events\ProductUpdated;
use App;
use App\Services\Guzzle;
use Excel;
use App\Library\Unit;
class CouponsRepository
{
    public $coupon_type = [
            'cart discount' => 'Fixed Cart Discount',
            'product discount' => 'Fixed Product Discount',
            '' => 'No Type'
    ];

    public function __construct(Guzzle $guzzle)
	{
		$this->guzzle   = $guzzle;
		$this->messages = config('message');
		$this->url      = env('CURL_API_COUPON');
        $this->urlOrder = env('CURL_API_ORDER');
        $this->_unit    = new Unit;
	}

    public function getUsage($id,$inputs)
    {
        $used_date = '';
        if(!empty($inputs['start_date'])||!empty($inputs['end_date']))
        {
            $used_date = '['.convertDateTime($inputs['start_date'],'d/m/Y H:i:s','Y-m-d H:i:s').','.convertDateTime($inputs['end_date'],'d/m/Y H:i:s','Y-m-d H:i:s').']';
        }
        if($inputs['status']=='all') {
            $status = '';
        }else {
            $status = $inputs['status'];
        }
        $params = [
			'query' => [
				'fields'    => 'order_no',
				'search'    => $inputs['full_text'],
				'offset'    => $inputs['start'],
				'limit'     => $inputs['length'],
                'status'    => $status,
                'used_date' => $used_date	
			]
		];
        if($inputs['order'][0]['dir'] != 'false') {
			$fieldName = $inputs['order'][0]['column'];
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
        }
        
        $result = $this->getDataUsage($id,$params); 
        
		$dataTable = [];
		$count_page = 0;
		$count_all = 0;
		if (isset($result['status']) && !empty($result['status']) && $result['status']['code'] == 200) {
			$dataTable = $this->setDataTable($result['data']['records'], $inputs);
			$count_page = count($result['data']['records']); //count page
			$count_all = $result['data']['pagination']['total_records']; //count all
		}

		$output = [
			'draw'            => $inputs['draw'],
			'recordsTotal'    => $count_page, //count page
			'recordsFiltered' => $count_all, //count all
			'data'            => $dataTable,
			'input'           => $inputs
		];
		
		return json_encode($output);
    }

    public function createCoupon($params) {
        $options = [
            'json' => $params
        ];
        
        $url = $this->url. 'coupons';

        $result = $this->guzzle->curl('POST', $url, $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            //ticker Solr
            if(isset($params['products'])){
                event(new ProductUpdated( implode(',', $params['products']) ));
            }
            $couponId = $result['data']['records'][0]['id'];
            return array('status' => true, 'bannerId' => $couponId);
        } else {
            return array('status' => false, 'messages' => $result['errors']['message']);
        }
    }

    public function updateCoupon($params) {
        $options = [
            'json' => $params
        ];
        
        $url = $this->url. 'coupons';
        
        $result = $this->guzzle->curl('PUT', $url.'/'.$params['id'], $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $couponId = $result['data']['records'][0]['id'];
            //ticker Solr
            if(isset($params['products'])){
                event(new ProductUpdated( implode(',', $params['products']) ));
                event(new ProductUpdated($params['product_old_id']));
            }
            return array('status' => true, 'bannerId' => $couponId);
        } else {
            return array('status' => false, 'messages' => $result['errors']['message']);
        }
    }

    public function getDataOrder($params)
    {
        $url = $this->urlOrder . 'orders/';
		$result = $this->guzzle->curl('GET', $url, $params);
		return $result; 
    }

    public function getDataUsage($id,$params)
	{
        $url = $this->url . 'coupons/'.$id.'/usage';
        $result = $this->guzzle->curl('GET', $url, $params);
		return $result; 
    }

    public function getDataCoupon($id)
    {
        $url = $this->url . 'coupons/'.$id;
		$result = $this->guzzle->curl('GET', $url, []);
		return $result; 
    }

    public function getCoupon()
    {
        $data = [];
        $url = $this->url . 'coupons/';
        $result = $this->guzzle->curl('GET', $url, []);
        if(count($result['data']['records']) > 0) {
            foreach($result['data']['records'] as $kData => $vData) {
                $data[$vData['id']] = $vData['coupon_code'] . ' (' . convertDateTime($vData['started_date'],'Y-m-d H:i:s','d/m/Y H:i:s') . ' - ' . convertDateTime($vData['end_date'],'Y-m-d H:i:s','d/m/Y H:i:s') . ')';
            }
            return $data;
        }
		return $result; 
    }

    public function getCouponCode()
    {
        $data = [];
        $url = $this->url . 'coupons/';
        $result = $this->guzzle->curl('GET', $url, []);
        if(count($result['data']['records']) > 0) {
            foreach($result['data']['records'] as $kData => $vData) {
                $data[$vData['coupon_code']] = $vData['coupon_code'] . ' (' . convertDateTime($vData['started_date'],'Y-m-d H:i:s','d/m/Y H:i:s') . ' - ' . convertDateTime($vData['end_date'],'Y-m-d H:i:s','d/m/Y H:i:s') . ')';
            }
            return $data;
        }
		return $result; 
    }

    public function getDataCouponByCouponCode($id)
    {
        $url = $this->url.'coupons';
        $params = [
            'query' => [
                'id' => $id
            ]
        ];
        $result = $this->guzzle->curl('GET',$url,$params);
        return $result;
    }

    public function getDataCoupons($inputs){

        if($inputs['coupon_type']=='all') {
            $status = '';
        }else {
            $status = $inputs['coupon_type'];
        }

        $params = [
            'query' => [
                   'fields' => 'coupon_name.th,coupon_name.en',
                   'search' => $inputs['coupon_name'],
                   'coupon_code_fields' => 'coupon_code',
                   'coupon_code_search' => $inputs['coupon_code'],
                   'coupon_type' => $status, 
                   'limit'  => $inputs['length'],
                   'offset' => $inputs['start'],  
            ]
        ];

        if($inputs['order'][0]['dir'] != 'false') {
            if(preg_match('/coupon_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
                $language = substr($inputs['order'][0]['column'], -2);
                $fieldName = "coupon_name.$language";
            } else {
                $fieldName = $inputs['order'][0]['column'];
            }   
            $direction = $inputs['order'][0]['dir'];
            $params['query']['order']= "$fieldName|$direction";
        }

        $result = $this->getDataList($params);
        $dataTable = [];
        $count_page = 0;
        $count_all = 0;
       
        if (isset($result['data']['records']) && !empty($result['data']['records'])) {
            $dataTable = $this->setDataTableSearch($result['data']['records'], $inputs);
            $count_page = count($result['data']['records']); //count page
            $count_all = $result['data']['pagination']['total_records']; //count all
        }

        $output = [
            'draw'            => $inputs['draw'],
            'recordsTotal'    => $count_page, //count page
            'recordsFiltered' => $count_all, //count all
            'data'            => $dataTable,
            'input'           => $inputs
        ];
        
        return json_encode($output);
    }
    public function getDataList($params)
    {
        $url = $this->url. 'coupons';
        $result = $this->guzzle->curl('GET', $url, $params);
        return  $result; 
    }

    public function setDataTable($data,$params)
    {
        $dataTable = [];
		$language = App::getLocale();
		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
                'number'            => $numberData,
                'coupon_code'       => isset($vData['coupon_code'])? $vData['coupon_code'] : '',
				'used_date'         => isset($vData['used_date'])? date("d/m/Y H:i:s",strtotime($vData["used_date"])) : '',
				'order_no'          => isset($vData['order_no'])? $vData['order_no'] : '',
				'order_amount'      => isset($vData['order_amount'])? number_format((float)$vData['order_amount'], 2, '.', ',') : '',
				'makro_member_card' => isset($vData['makro_member_card'])? $vData['makro_member_card'] : '',
				'first_name'        => isset($vData['customer_firstname'])? $vData['customer_firstname'] : '',
				'last_name'         => isset($vData['customer_lastname'])? $vData['customer_lastname'] : '',
                'customer_type'     => isset($vData['customer_type'])? $vData['customer_type'] . ' (' . $vData['customer_type_id'] . ')' : '',
				'mobile_number'     => isset($vData['mobile_number'])? $vData['mobile_number'] : '',
                'email'             => isset($vData['email'])? $vData['email'] : '',
                'status'            => isset($vData['status'])? $vData['status'] : '',
			];
		}

		return $dataTable;
    }

    public function setDataTableSearch($data,$params)
    {
        $dataTable = [];
        $language = App::getLocale();

        foreach ($data as $kData => $vData) {

            $numberData = ($kData + 1) + $params['start'];
            $coupon_type_name = $this->coupon_type[ $vData["coupon_type"] ];
         
            $dataTable[] = [
                    'number'                        => $numberData,
                    'id'                            => $vData["id"],
                    'coupon_code'                   => $vData["coupon_code"],
                    'coupon_name_th'                => $vData["coupon_name"]['th'],
                    'coupon_name_en'                => $vData["coupon_name"]['en'],
                    'coupon_type'                   => $coupon_type_name,
                    'created_at'                    => date("d/m/Y H:i:s",strtotime($vData["created_at"])),
                    'expired_at'                    => isset($vData['expired_at']) && !empty($vData['expired_at'])? date("d/m/Y H:i:s",strtotime($vData["expired_at"])) : '',
                    'started_date'                  => isset($vData['started_date']) && !empty($vData['started_date'])? date("d/m/Y H:i:s",strtotime($vData["started_date"])) : '',
                    'end_date'                      => isset($vData['end_date']) && !empty($vData['end_date'])? date("d/m/Y H:i:s",strtotime($vData["end_date"])) : '',
                    'amount'                        => number_format((float)$vData['amount'], 2, '.', ','),        
                    'status'                        => $vData["status"],
                    'usage_count'                   => $vData["usage_count"],
            ];
        }

        return $dataTable;
    }

    // Export Report to excel
    public function getDataReport($inputs) 
    {
        if($inputs['coupon_type']=='all') {
            $status = '';
        }else {
            $status = $inputs['coupon_type'];
        }

        $params = [
            'query' => [
                   'fields' => 'coupon_name.th,coupon_name.en',
                   'search' => $inputs['coupon_name'],
                   'coupon_code_fields' => 'coupon_code',
                   'coupon_code_search' => $inputs['coupon_code'],
                   'coupon_type' => $status, 
                   'limit'  => $inputs['length'],
                   'offset' => $inputs['start'],  
            ]
        ];

        if($inputs['order'][0]['dir'] != 'false') {
            if(preg_match('/coupon_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
                $language = substr($inputs['order'][0]['column'], -2);
                $fieldName = "coupon_name.$language";
            } else {
                $fieldName = $inputs['order'][0]['column'];
            }   
            $direction = $inputs['order'][0]['dir'];
            $params['query']['order']= "$fieldName|$direction";
        }

        $result = $this->getDataList($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $coupon = $result['data']['records'];
            $start = $result['data']['pagination']['offset'] + 1;

            return Excel::create('coupon_report_' . date('YmdHis'), function($excel) use ($coupon,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Coupon', function($sheet) use ($coupon,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Coupon Code',
                        'Coupon Name (TH)',
                        'Coupon Name (EN)',
                        'Coupon Type',
                        'Coupon Discount',
                        'Created Date',
                        'Started Date',
                        'End Date',
                        'Usage',
                        'Status'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($coupon as $kData => $vData) {
                        ++$row;
                        $coupon_type_name = $this->coupon_type[ $vData['coupon_type'] ];

                        $data = [
                            $start,
                            $this->_unit->removeFirstInjection($vData['coupon_code']),
                            $this->_unit->removeFirstInjection($vData['coupon_name']['th']),
                            $this->_unit->removeFirstInjection($vData['coupon_name']['en']),
                            $this->_unit->removeFirstInjection($coupon_type_name),
                            number_format((float)$vData['amount'], 2, '.', ','),
                            '="'.date("d/m/Y H:i:s",strtotime($vData["created_at"])).'"',
                            '="'.date("d/m/Y H:i:s",strtotime($vData["started_date"])).'"',
                            '="'.date("d/m/Y H:i:s",strtotime($vData["end_date"])).'"',
                            $this->_unit->removeFirstInjection($vData['usage_count']),
                            $this->_unit->removeFirstInjection($vData['status'])
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
    }

    // Export Coupon History

	public function getDataHistoryReport($id,$inputs)
    {
        $used_date = '';
        if(!empty($inputs['start_date'])||!empty($inputs['end_date']))
        {
            $used_date = '['.convertDateTime($inputs['start_date'],'d/m/Y H:i:s','Y-m-d H:i:s').','.convertDateTime($inputs['end_date'],'d/m/Y H:i:s','Y-m-d H:i:s').']';
        }
        if($inputs['status']=='all') {
            $status = '';
        }else {
            $status = $inputs['status'];
        }
        $params = [
			'query' => [
				'fields' => 'order_no',
				'search' => $inputs['full_text'],
				'offset' => $inputs['start'],
				'limit'  => $inputs['length'],
                'status' => $status,
                'used_date' => $used_date	
			]
		];
        if($inputs['order'][0]['dir'] != 'false') {
			$fieldName = $inputs['order'][0]['column'];
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
		$result = $this->getDataUsage($id,$params);
        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $start = $result['data']['pagination']['offset'] + 1;
            $usage = $result['data']['records'];
            return Excel::create('coupon_history_report_' . date('YmdHis'), function($excel) use ($usage,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Coupon History', function($sheet) use ($usage,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Used Date',
                        'Order Number',
                        'Order Amount',
                        'Makro ID',
						'First Name',
                        'Last Name',
						'Customer Type',
                        'Mobile Number',
                        'Email',
                        'Status'
                
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($usage as $kData => $vData) {
                        ++$row;

                        $data = [
                            $start,
                            isset($vData['used_date'])? date("d/m/Y H:i:s",strtotime($vData["used_date"])) : '',
                            isset($vData['order_no'])? $vData['order_no'] : '',
                            isset($vData['order_amount'])? number_format((float)$vData['order_amount'], 2, '.', ',') : '',
                            isset($vData['makro_member_card'])? $vData['makro_member_card'] : '',
                            isset($vData['customer_firstname'])?  $vData['customer_firstname'] : '',
                            isset($vData['customer_lastname'])? $vData['customer_lastname'] : '',
                            isset($vData['customer_type'])? $vData['customer_type'] . ' (' . $vData['customer_type_id'] . ')' : '',
                            isset($vData['mobile_number'])? sprintf('="%s"', array_get($vData, 'mobile_number', '')) : '',
                            isset($vData['email'])? $vData['email'] : '',
                            isset($vData['status'])? $vData['status'] : '',
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
	}
    public function setStatus($inputs){
        $url = $this->url. 'coupons/status';
        $coupon = [
            'ids' => implode(",",$inputs['coupon_ids']),
            'status' => $inputs['status']
        ];

        $options = [
            'json' => $coupon
        ];
        $result = $this->guzzle->curl('PUT',$url,$options);
        return $result;
    }
    public function delete($id){
            $url = $this->url. 'coupons';
            $result = $this->guzzle->curl('DELETE', $url."/".$id);
            return $result ;
    }
    



}
?>