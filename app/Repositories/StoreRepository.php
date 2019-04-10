<?php
namespace App\Repositories;

use App\Services\Guzzle;
use App;
use DateTime;
use DateTimeZone;
use Excel;
use Cache;

class StoreRepository
{
	protected $guzzle;
	protected $messages;

	public function __construct(Guzzle $guzzle)
	{
		$this->url = config('api.makro_store_api');
		$this->addressUrl = env('CURL_API_ADDRESS');
		$this->guzzle = $guzzle;
	}

	public function getDataStore($params)
	{
		$url = $this->url . 'stores';
		return $this->guzzle->curl('GET', $url, $params);
	}

	public function getStore($inputs)
    {
		if($inputs['region'] == 'all'){
			$inputs['region'] = '';
		}
		if($inputs['delivery'] == 'all'){
			$inputs['delivery'] = '';
		}
		if($inputs['status'] == 'all'){
			$inputs['status'] = '';
		}
        $params = [
			'query' => [
				'fields'      	 => 'name.th,name.en',
				'search'         => $inputs['store_name'],
				'offset'      	 => $inputs['start'],
				'limit'       	 => $inputs['length'],
				'makro_store_id' => $inputs['store_id'],
				'region'  		 => $inputs['region'],
				'have_delivery'  => $inputs['delivery'],
				'status'         => $inputs['status']
			]
		];
        if($inputs['order'][0]['dir'] != 'false') {
			if(preg_match('/name_[a-z]{2}/', $inputs['order'][0]['column'])) {
				$language = substr($inputs['order'][0]['column'], -2);
				$fieldName = "name.$language";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
		$result = $this->getDataStore($params);
		foreach($result['data']['records'] as $kData => $vData) {
			if(isset($vData['region']) && $vData['region'] !== "") {
				$resultRegion = $this->getRegionById($vData['region']);
				$result['data']['records'][$kData]['region_name']['th'] = $resultRegion['data']['records'][0]['name']['th'];
				$result['data']['records'][$kData]['region_name']['en'] = $resultRegion['data']['records'][0]['name']['en'];
			}
		}
		
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

	public function setDataTable($data,$params)
    {
        $dataTable = [];
		$language = App::getLocale();
		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
				'number'			=> $numberData,
				'id'                => $vData['id'],
				'makro_store_id' 	=> $vData['makro_store_id'],
				'name_th'     		=> $vData['name']['th'],
                'name_en'     		=> $vData['name']['en'],
				'region_th'     	=> isset($vData['region_name']['th'])? $vData['region_name']['th'] : '',
                'region_en'    		=> isset($vData['region_name']['en'])? $vData['region_name']['en'] : '',
				'contact_phone'     => $vData['contact_phone'],
				'have_delivery'     => isset($vData['have_delivery'])? $vData['have_delivery'] : '',
				'status'            => $vData['status'],
				'edit'   			=> url('/store/' . $vData['id'] . '/edit'),
			];
		}

		return $dataTable;
    }

	private function setSearchData($params){

		$searchData = [
			'limit' => $params['limit'],
			'offset' => $params['offset'],
			'order' => $params['order']
		];
		return $searchData;
	}
	public function getStores($params)
	{
		$url = $this->url . 'stores';
		$options = [
			'query'   => $this->setSearchData($params),
			'headers' => [
				'X-Language' => 'th|en'
			]
		];
		return $this->guzzle->curl('GET', $url, $options);
	}

	public function getStoreById($id)
	{
		$url = $this->url . 'stores/' . $id;
		$options = [
			'headers' => [
				'X-Language' => 'th|en'
			]
		];
		return $this->guzzle->curl('GET',$url,$options);
	}

    public function setStatus($id,$inputs){
		
        $url = $this->url. 'stores/status';
        $store = [
            'ids' => $id,
            'status' => $inputs['status']
        ];
        $options = [
            'json' => $store
        ];
        $result = $this->guzzle->curl('PUT',$url,$options);
        return $result;
    }

	public function getDataStoreReport($inputs) 
    {

        $params = [
			'query' => [
				'fields'      	 => 'name.th,name.en',
				'search'         => $inputs['store_name'],
				'offset'      	 => $inputs['start'],
				'limit'       	 => $inputs['length'],
				'makro_store_id' => $inputs['store_id'],
				'region'  		 => $inputs['region'],
				'have_delivery'  => $inputs['delivery'],
				'status'         => $inputs['status']
			]
		];

        if($inputs['order'][0]['dir'] != 'false') {
			if(preg_match('/name_[a-z]{2}/', $inputs['order'][0]['column'])) {
				$language = substr($inputs['order'][0]['column'], -2);
				$fieldName = "name.$language";
			} else {
				$fieldName = $inputs['order'][0]['column'];
			}	
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
		$result = $this->getDataStore($params);
		foreach($result['data']['records'] as $kData => $vData) {
			if(isset($vData['region']) && $vData['region'] !== "") {
				$resultRegion = $this->getRegionById($vData['region']);
				$result['data']['records'][$kData]['region_name']['th'] = $resultRegion['data']['records'][0]['name']['th'];
				$result['data']['records'][$kData]['region_name']['en'] = $resultRegion['data']['records'][0]['name']['en'];
			}
		}

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $start = $result['data']['pagination']['offset'] + 1;
            $store = $result['data']['records'];
            return Excel::create('store_report_' . date('YmdHis'), function($excel) use ($store,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Bank', function($sheet) use ($store,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
						'Store No.',
                        'Store Name (TH)',
                        'Store Name (EN)',
                        'Zone (TH)',
                        'Zone (EN)',
						'Phone Number',
						'Have Delivery',
						'Status'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($store as $kData => $vData) {
                        ++$row;
						
                        $data = [
							$vData['makro_store_id'],
                            $vData['name']['th'],
                            $vData['name']['en'],
                            isset($vData['region_name']['th'])? $vData['region_name']['th'] : '',
                            isset($vData['region_name']['en'])? $vData['region_name']['en'] : '',
							$vData['contact_phone'],
							isset($vData['have_delivery'])? $vData['have_delivery'] : '',
                            (array_get($vData, 'status', '')=='active'?'Publish':'Unpublish')
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
	}

	public function getAddress($id, $model, $country)
	{
		//api get address member
		$getStore   = $this->getAddressStore($id, 'store');
		//api province
		$provinces   = $this->getProvinces($country);
		return [
            'provinces' => $provinces,
            'store'   => [
                'districts'    => (isset($getStore[0]['province']['id'])) ? $this->getDistricts($getStore[0]['province']['id']) : [],
                'subdistricts' => (isset($getStore[0]['district']['id'])) ? $this->getSubDistricts($getStore[0]['district']['id']) : [],
                'address'      => (!empty($getStore[0]))? $getStore[0] : []
            ]
        ];
	}

	    /**
     * Method for curl api address store
     */
	private function getAddressStore($id, $type)
	{
		$output = [];
		$getUrl = 'addresses?content_id=' . $id . '&content_type=' . $type;
 
		 //api call
		$result = $this->guzzle->curl('GET', $this->addressUrl . $getUrl, [
			'headers' => [
				'X-Language' => 'th|en'
			]
		]);
		if (isset($result['data']['records']) && !isset($result['data']['records']['msgError'])) {
			 $output = $result['data']['records'];
		}
		 //dd($output); die;
		return $output;
	}

	    /**
     * Method for curl api provinces
     */
	public function getProvinces($country = 'TH')
	{
		$output = [];
		$result = Cache::get('provinces', function () use ($country) {
			 return $this->guzzle->curl('GET', $this->addressUrl . 'provinces?country_code=' . $country);
		});
 
		if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
			foreach ($result['data']['records'] as $kData => $vData) {
				 $output[$vData['province_id']] = $vData['name']['th'];
			}
		}
 
		 return $output;
	}

	    /**
     * Method for curl api districts
     */
	public function getDistricts($province_id)
	{
		$output = [];
	 	$result = $this->guzzle->curl('GET', $this->addressUrl . 'districts?province_id=' . $province_id);
 
		if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
			foreach ($result['data']['records'] as $kData => $vData) {
				$output[$vData['district_id']] = $vData['name']['th'];
			}
		}
 
		return $output;
	}
 
	 /**
	  * Method for curl api sub_districts
	  */
	public function getSubDistricts($district_id)
	{
		$output = [];
		$result = $this->guzzle->curl('GET', $this->addressUrl . 'subdistricts?district_id=' . $district_id);
 
		if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
			foreach ($result['data']['records'] as $kData => $vData) {
				$output[$vData['sub_district_id']] = $vData['name']['th'];
			}
		}
 
		return $output;
	}

	   /**
     * Method for curl api postcode
     */
	public function getPostcode($sub_district_id)
	{
		$output = [];
		$result = $this->guzzle->curl('GET', $this->addressUrl . 'subdistricts/' . $sub_district_id);
 
		if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
			$output['postcode'] = $result['data']['records'][0]['postcode'];
		}
 
		return $output;
	}

	public function getRegionSearch()
	{
		$url = $this->url . '/region';
		$result = $this->guzzle->curl('GET',$url);
		$region = [];
		$region['all'] = 'Any';
		foreach($result['data']['records'] as $kData => $vData) {
			$region[$vData['id']] = $vData['name']['th'] . ' (' . $vData['name']['en'] . ')';
		}
		return $region;
	}

	public function getRegion()
	{
		$url = $this->url . '/region';
		$result = $this->guzzle->curl('GET',$url);
		$region = [];

		foreach($result['data']['records'] as $kData => $vData) {
			$region[$vData['id']] = $vData['name']['th'] . ' (' . $vData['name']['en'] . ')';
		}
		return $region;
	}

	public function getRegionById($id)
	{
		$url = $this->url . '/region/' . $id;
		$result = $this->guzzle->curl('GET',$url);
		return $result;
	}


	public function updateStore($id,$params)
	{
		$url = $this->url . 'stores/' . $id;
		$option = [
			'json' => $params
		];

		$result = $this->guzzle->curl('PUT', $url , $option);

		return $result;
	}

	public function updateAddress($params)
	{
		$url = $this->addressUrl . 'addresses/' . $params['id'];
		$option = [
			'json' => $params
		];
		$result = $this->guzzle->curl('PUT', $url , $option);
		return $result;
	}

	public function getStoreSelect($params)
	{
		$stores = [];

		$params['limit']   = isset($params['limit']) ? $params['limit']: 9999;
		$params['offset']  = isset($params['offset']) ? $params['offset'] : 0;

        $storeResult = $this->getStores($params);

        if (isset($storeResult['data']['records'])&&count($storeResult['data']['records']) > 0) {
            foreach ($storeResult['data']['records'] as $store) {
				if (!empty($store['makro_store_id']) && isset($store['name']['th'])) {
					$stores[$store['makro_store_id']] = $store['name']['th'] . " (" . $store['makro_store_id'] . ")";
				}
            }
		}

		return $stores;
	}
}
