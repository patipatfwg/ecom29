<?php
namespace App\Repositories;

use App\Events\ProductUpdated;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\RequestException;
use App\Services\Guzzle;
use App;
use Excel;
use Cache;
use Symfony\Component\HttpFoundation\Request;

class PaymentRepository extends BaseRepository
{
    protected $guzzle;
    protected $messages;

    public function __construct(Guzzle $guzzle, ProductRepository $productRepository)
    {
        $this->url               = config('api.makro_payment_api');
        $this->bssUrl            = config('api.makro_bss_api');
        $this->messages          = config('message');
        $this->guzzle            = $guzzle;
        $this->productRepository = $productRepository;
        $this->_bss_cache_service = App::make('App\Services\Bss\CacheServices');
    }

    public function getConfigs($inputs)
    {
        $language = App::getLocale();
        $params = [
            'query' => [
                'offset' => $inputs['start'],
                'limit' => $inputs['length'],
                'config_type' => $inputs['config_type'],
            ]
        ];
        
        $url = $this->url . 'configs';
 
        $result = $this->guzzle->curl('GET', $url, $params);

        return $result;
    }

    public function getPaymentMethod($id = false)
    {
        //default data
        $configs= [];

        //default params
        $params = [
            'config_type' => 'Payment Method',
            'status'      => 'active,inactive',
            'order'       => 'created_at|ASC'
        ];

        if($id){
            $params['_id'] = $id;
        }

        $result = $this->curlConfigs($params);
        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $configs = $result['data']['records'];
        }

        return $configs;
    }

    public function getPaymentMethodTable()
    {
        //default params
        $params = [
            'config_type' => 'Payment Method',
            'status'      => 'active,inactive',
            'order'       => 'priority|ASC'
        ];

        $result = $this->curlConfigs($params);

        $output = [
                'recordsTotal'    => 0, //count page
                'recordsFiltered' => 0,
                'data'            => [],
                'input'           => [
                    'draw'    => '1',
                    'order'   => null,
                    'start'   => 0,
                    'length'  => 10,
                    'search'  => null
                ]
            ];

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            foreach ($result['data']['records'] as $index => $vData)
            {
                $result['data']['records'][$index]['priority'] = '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" name="priority[' . $vData['id'] . ']" value="' . $vData['priority'] . '">';
            }

            $total_record = count($result['data']['records']);

            $output['recordsTotal'] = $total_record;
            $output['recordsFiltered'] = $total_record;
            $output['data'] = $result['data']['records'];

        }


        return  json_encode($output);
    }

    public function curlConfigs($params)
    {
        $url = $this->url. 'configs';

        $options = [
            'query'   => $params
        ];
        return $this->guzzle->curl('GET', $url, $options);
    }

    public function deleteContent($id, $contentId)
    {
        // dd($contentId);
        $url = $this->url.'installments/'.$id.'/content/'.$contentId;
        $result = $this->guzzle->curl('DELETE', $url);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }

    public function updateStatusPayment($ids, $status)
    {

        $options = [
            'json' => [
                'status' => $status
            ]
        ];
       
        $url    = $this->url . 'configs/'.$ids.'/status';
        $result = $this->guzzle->curl('PUT', $url, $options);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {

            $this->_bss_cache_service->flushCache('Payment Method','payments');
            return array('status' => true);

        } else {

            return array('status' => false, 'messages' => $result['error']['message']);

        }
    }


    public function updatePriorityPayments($params)
    {

        $options = [
            'json' => $params

        ];

        $url    = $this->url . 'configs/priority';
        $result = $this->guzzle->curl('POST', $url, $options);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {

            $this->_bss_cache_service->flushCache('Payment Method','payments');
            return array('status' => true);

        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }

    public function getInstallmentOption($inputs)
	{
		$params = [
			'query' => [
				'offset' => $inputs['start'],
				'limit'  => $inputs['length'],
			]
		];

		if($inputs['order'][0]['dir'] != 'false') {
			if(preg_match('/option_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
				$language = substr($inputs['order'][0]['column'], -2);
				$fieldName = "option_name.$language";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
        
		$result = $this->getInstallmentOptionData($params);
		
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

    public function getInstallmentOptionData($params)
	{
		$url = $this->url . 'installments';
		$result = $this->guzzle->curl('get', $url, $params);
		return $result; 
    }

    public function getInstallmentOptionDataById($id)
    {
    	$url = $this->url . 'installments/' . $id;
    	$result = $this->guzzle->curl('get', $url);
        return $result; 
    }
    

    public function setDataTable($data, array $params)
	{
		$dataTable = [];
		$language = App::getLocale();
        
		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
				'_id'     => $vData['id'],
				'option_name_th' => $vData['option_name']['th'],
				'option_name_en' => $vData['option_name']['en'],
				'bank_name_th' => $vData['bank']['name']['th'],
				'installment_term' => number_format((float)$vData['installment_term'], 2, '.', ','),
				'interest_rate' => number_format((float)$vData['interest_rate'], 2, '.', ','),
                'status' => $vData['status'],
				'edit'   => url('/config/' . $vData['id'] . '/edit'),
				'add_item'   => url('/config/payment_method/' . $vData['id']),
				'delete' => $vData['id']
			];
		}

		return $dataTable;
	}

    public function getDeleteInstallmentOption($ids)
    {
        $url = $this->url . 'installments/' . $ids;
		$result = $this->guzzle->curl('DELETE', $url);
		if(isset($result['status'])&&$result['status']['code']==200) {
			return array('status' => true, 'deleted' => $result['data']['deleted']);
		}
		return array('status' => false, 'messages' => $result['message']);
    }

    public function getDataInstallmentOptionReport($inputs) {
		
		$params = [
			'query' => [
				'offset' => $inputs['start'],
				'limit'  => $inputs['length']
			]
		];

        if($inputs['order'][0]['dir'] != 'false') {
			if(preg_match('/option_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
				$language = substr($inputs['order'][0]['column'], -2);
				$fieldName = "option_name.$language";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}

		$result = $this->getInstallmentOptionData($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $installment_option = $result['data']['records'];
            $start = $result['data']['pagination']['offset'] + 1;
			
            return Excel::create('installment_option_report_' . date('YmdHis'), function($excel) use ($installment_option,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Installment Option', function($sheet) use ($installment_option,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Option Name (TH)',
                        'Option Name (EN)',
                        'Bank',
                        'Installment Term',
						'Interest Rate',
						'Active'
                
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($installment_option as $kData => $vData) {
                        ++$row;
						
                        $data = [
                            $start,
                            $vData['option_name']['th'],
                            $vData['option_name']['en'],
                            $vData['bank']['name']['th'],
                            $vData['installment_term'],
							$vData['interest_rate'],
							($vData['status']=='active')? 'Publish' : 'Unpublish'
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
	}

    public function updateStatusInstallmentOption($params)
    {
        $options = [
            'json' => $params
        ];
        $url = $this->url . 'installments/status/';
        $result = $this->guzzle->curl('PUT',$url,$options);
        return $result;
    }

    public function updateEnableType($id, $enableType)
    {
        $options = [
            'json' => ['enable_type' => $enableType],
        ];
        $url = $this->url . 'installments/' . $id;
        $result = $this->guzzle->curl('PUT',$url,$options);
        return $result;
    } 


    public function getBankData(){
        $get = '?config_type=Bank&status=active&order=code|ASC';
        $params = [
                        'config_type'=>'Bank',
                        'status'=>'active,inactive',
                        'order'=>'code|ASC'
                 ];
        $options = [
                        'query' => $params
                    ];

        $url = $this->url . 'configs';

        $result = $this->guzzle->curl('GET',$url,  $options);
        return $result;
    }

    public function addItemToInstallmentOption($input){
        $data = [];
        $content_id_text = [];
            foreach ($input['item'] as $key => $value) {
                 $data[] = [
                                "content_type"  => 'product',
                                "content_id"    => $value
                            ] ;
                $content_id_text[] = $value;
            }
          

            $data_set = ['data' => $data];
            $options = [
                        'json' => $data_set
                    ];
            

            $url = $this->url .'installments/'. $input['id'] . '/content';
            $result = $this->guzzle->curl('POST',$url,  $options);
            return $result;
    }

    public function getInstallmentData($id){
            $url = $this->url .'installments/'. $id . '/content';
            $result = $this->guzzle->curl('GET',$url);
            return $result;
    }

    public function create($params)
    {
        $options = [
                        'json' => $params
                    ];

        $url = $this->url . 'installments';
        $result = $this->guzzle->curl('POST',$url,  $options);
        return $result;
    }
    
    public function getPayment($id)
    {
        $url = $this->url . 'installments/'.$id;

        $result = $this->guzzle->curl('GET',$url);
        return $result;
    }

    public function update($id, $params)
    {

        $options = [
            'json' => $params
        ];

        $url = $this->url . 'installments/'.$id ;

        $result = $this->guzzle->curl('PUT',$url,  $options);
        return $result;
    }

    public function getDataInstallmentDataReport($id,$inputs) 
    {
        $return_data = $this->getInstallmentData($id);
		// If no products were binded to this installment options, don't query product detail	 
        $notfound = [
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
        ];
        if ($return_data['data']['pagination']['total_records'] == 0) {
            return $notfound;
        }
        
        if($return_data['status']['code'] == 200){
            $data = $return_data['data']['records'] ; 
            $ids = [];
            foreach ($data as $key => $value) {
                $ids[] =  $value['content_id'];
            }
            $option = [
                'fields' => 'name.th,name.en,item_id',
                'name' => $inputs['search_text_input'],
                'ids' => $ids,
                'order' => $inputs['order']
            ];
            $result = $this->productRepository->getProducts($option);
                // SetdataTable
            $data_table = [];
            foreach ($result['data']['records'] as $key => $value) {
                    $data = $return_data['data']['records'];
                    $content_id = '';
                    foreach ($data as $dataKey => $dataValue) {
                        if ($value['id'] == $dataValue['content_id']) {
                            $contentId = $dataValue['id'];
                        }
                    }
                    $data_table[] = [
                        'content_id'=>  $contentId,
                        'itemType' 	=>	$value['product_type'],
                        'item_id' 	=>	$value['item_id'],
                        'name_th'	=>	$value['name']['th'],
                        'name_en'	=>	$value['name']['en']
                    ];
            }
        }
        if (isset($data_table) && count($data_table) > 0) {
            $start = $result['data']['pagination']['offset'] + 1;
			
            return Excel::create('installment_report_' . date('YmdHis'), function($excel) use ($data_table,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Installment', function($sheet) use ($data_table,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Item Type',
                        'Item Code',
                        'Item Name (TH)',
                        'Item Name (EN)',       
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($data_table as $kData => $vData) {
                        ++$row;
						
                        $data = [
                            $start,
                            $vData['itemType'],
                            $vData['item_id'],
                            $vData['name_th'],
							$vData['name_en'],
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
	}
    
    public function curlGetConfigById($id)
    {
        $result = $this->guzzle->curl('GET', $this->url . 'configs', ['query' => [
            'status' => 'active,inactive',
            'id' => $id,
            'config_type' => 'Payment Method'
        ]]);
        return (isset($result['data']['records']) && count($result['data']['records']) > 0) ? $result['data']['records'][0] : [];
    }

    public function updatePaymentMethodById($id, $params)
    {
        if(!isset($params['status']) || empty($params['status'])){
            $params['status'] = 'inactive';
        }
        if(intval($params['priority']) == 0|| $params['priority'] == ''){
            $params['priority'] = 99;
        }

        if(intval($params['max_amount']) == 0|| $params['max_amount'] == ''){
            $params['max_amount'] = null;
        }
        
        $params['config_type'] = 'Payment Method';

        $result = $this->guzzle->curl('PUT', $this->url . 'configs/' . $id, ['json' => $params]);
        $updatePriority = $this->updatePriorityPayments([
            'priority' => [
                $id => $params['priority']
            ]
        ]);

        return (isset($result['data']['records']) && count($result['data']['records']) > 0);
    }
}
