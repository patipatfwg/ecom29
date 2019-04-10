<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Services\Guzzle;
use Excel;
use Cache;
use Session;
use App\Library\Unit;

class MembersRepository extends BaseRepository
{
    private $guzzle;
    private $messages;
    private $memberUrl;
    private $bssUrl;
    public $urlStore;
    public $customer_channel;

    public function __construct(Guzzle $guzzle)
    {
        parent::__construct();
        $this->guzzle     = $guzzle;
        $this->messages   = config('message');
        $this->memberUrl  = env('CURL_API_MEMBER_PROFILE');
        $this->addressUrl = env('CURL_API_ADDRESS');
        $this->bssUrl     = config('api.makro_bss_api');
        $this->urlStore   = env('CURL_API_STORE');
        $this->_unit      = new Unit;
        $this->customer_channel = config('config.customer_channel');
    }

    /**
     * Method for set data order
     */
    private function setOrderData(array $params)
    {
        $order = [];
        if (isset($params['order']) && count($params['order']) > 0) {
            foreach ($params['order'] as $kData => $vData) {
                if (isset($vData['column']) && isset($vData['dir'])) {
                    $order[] = $vData['column'] . '|' . $vData['dir'];
                }
            }
        }

        return implode(',', $order);
    }

    /**
     * Method for set search text all
     */
    private function setSearchText(array $params)
    {
        $search = [];
        
        if (isset($params['search']) && is_array($params['search'])) {
            foreach ($params['search'] as $kData => $vData) {
                if ($vData['name'] !== '_token') {
                    if (strpos($vData['name'], 'date') !== false && !empty($vData['value'])) {
                        $search[] = $vData['name'] . '=' . convertDateTime($vData['value'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
                    } 
                    else if($vData['name'] == 'customer_type'){
                        $search[] = 'fields=business.shop_type_id,business.shop_type';
                        $search[] = 'search=' . $vData['value']; 
                    }
                    else {
                        $search[] = $vData['name'] . '=' . $vData['value'];
                    }
                }
            }
        }

        return $search;
    }

    /**
     * Method for set data search
     */
    private function setSearch(array $params)
    {
        $search = implode('&', array_merge([
            'order=' . $this->setOrderData($params),
            'offset=' . array_get($params, 'start', 0),
            'limit=' . array_get($params, 'length', 10),
            'report=' . array_get($params, 'report', '')
        ], $this->setSearchText($params)));

        return $search;
    }

    /**
     * Method for curl api by id
     */
    public function getMember($id)
    {
        $member_result = [];
        $member_result = $this->guzzle->curl('GET', $this->memberUrl . 'members/' . $id);

        if($member_result['status']['code']!=200) {
            return $member_result;
        }

        $store_id = $member_result['data']['records'][0]['makro_register_store_id'];
        if(isset($member_result['data']['records']) && $store_id!="" && (int)$store_id!=0) {
 
            $store = $this->getStoreMakroName($store_id,$language='th|en'); 

            if(isset($store['store_name'])){
                $member_result['data']['records'][0]['makro_register_store_name'] = $store['store_name'];
            } 
        }

        return $member_result;
    }

    public function getStoreMakroName($store_id,$language='th|en'){
      
        $storeurl = $this->urlStore . 'stores/';
        $options = [
            'query'   => [ 'makro_store_id' => (int)$store_id],
            'headers' => ['X-Language' => $language ]
        ];

        $store_result = $this->guzzle->curl('GET', $storeurl, $options);
        $store_name = NULL;
 
        if($store_result['status']['code']==200 && isset($store_result['data']['records'])) {

            $store_name = isset($store_result['data']['records'][0]['name']) ? $store_result['data']['records'][0]['name'] : '';

            return ['store_id' => $store_id,'store_name'=>$store_name];

        } else {
            return ['store_id' => $store_id];
        }

        return $member_result;
        
    }

    /**
     * Method for curl api address
     */
    public function getAddress($id, $model, $country)
    {
        //api get address member
        $getMember   = $this->getAddressMember($id, 'member');
        //api get address member
        $getTax = $this->getAddressMember($id, 'tax');
        //api get address member
        $getBill = $this->getAddressMember($id, 'bill');
        //api province
        $provinces   = $this->getProvinces($country);

        return [
            'provinces' => $provinces,
            'profile'   => [
                'districts'    => (isset($getMember[0]['province']['id'])) ? $this->getDistricts($getMember[0]['province']['id']) : [],
                'subdistricts' => (isset($getMember[0]['district']['id'])) ? $this->getSubDistricts($getMember[0]['district']['id']) : [],
                'address'      => (!empty($getMember[0]))? $getMember[0] : []
            ],
            'tax'  => [
                'districts'    => (isset($getTax[0]['province']['id'])) ? $this->getDistricts($getTax[0]['province']['id']) : [],
                'subdistricts' => (isset($getTax[0]['district']['id'])) ? $this->getSubDistricts($getTax[0]['district']['id']) : [],
                'address'      => (!empty($getTax[0]))? $getTax[0] : []
            ],
            'bill'  => [
                'districts'    => (isset($getBill[0]['province']['id'])) ? $this->getDistricts($getBill[0]['province']['id']) : [],
                'subdistricts' => (isset($getBill[0]['district']['id'])) ? $this->getSubDistricts($getBill[0]['district']['id']) : [],
                'address'      => (!empty($getBill[0]))? $getBill[0] : []
            ]
        ];
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

    /**
     * Method for curl api address member
     */
    private function getAddressMember($id, $type)
    {
        $output = [];
        $getUrl = 'addresses?content_id=' . $id . '&content_type=member&content_sub_type=' . $type;

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
     * Method for set mode
     */
    private function setParamsMode($id, array $params)
    {
        if ($params['mode'] === 'profile') {

            return [
                'url'    => $this->memberUrl . 'members/' . $id,
                'method' => 'PUT',
                'params' => [
                    'email'           => $params['email'],
                    // 'identify_type'   => (isset($params['identify_type']))? $params['identify_type'] : '',
                    // 'identify_id'     => $params['identify_id'],
                    'first_name'      => $params['first_name'],
                    'last_name'       => $params['last_name'],
                    'phone'           => $params['phone'],
                    'birth_day'       => (isset($params['birth_day']))? convertDateTime($params['birth_day'], 'd/m/Y', 'Y-m-d') :'0000-00-00',
                    'pickup_store_id' => (isset($params['pickup_store']))? $params['pickup_store']:'',
                    'status'          => (isset($params['status']))? $params['status']:'',
                    'customer_channel'          => (isset($params['customer_channel']))? $params['customer_channel']:'',
                ]
            ];

        } elseif ($params['mode'] === 'profile_address' && isset($params['profile_id'])) {

            $data = [
                'address'    => [
                    'th' => $params['address_1'],
                    'en' => $params['address_1']
                ],
                'address3'    => [
                    'th' => '',
                    'en' => ''
                ],
                'subdistrict' => [
                    'id' => (isset($params['profile_sub_district']))?$params['profile_sub_district']:''
                ],
                'district'    => [
                    'id' => (isset($params['profile_districts']))?$params['profile_districts']:''
                ],
                'province'    => [
                    'id' => (isset($params['profile_province']))?$params['profile_province']:''
                ],
                'postcode'    => $params['profile_postcode']
            ];

            $address = $this->setAddressData($id, $params['profile_id'], $data, 'member');

            return [
                'url'    => $address['url'],
                'method' => $address['method'],
                'params' => $address['params']
            ];

        } elseif ($params['mode'] === 'tax') {

            return [
                'url'    => $this->memberUrl . 'shopprofile/' . $id,
                'method' => 'PUT',
                'params' => [
                    'email'           => $params['business_email'],
                    'shop_name'       => $params['business_shop_name'],
                    'branch'          => $params['business_branch'],
                    'main_phone'      => $params['business_phone'],
                    'mobile_phone'    => $params['business_phone'],
                    'business_status' => ''
                ]
            ];

        } else if ($params['mode'] === 'bill') {
            //dd($params);
            $data = [
                'address'    => [
                    'th' => $params['bill_address_1'],
                    'en' => $params['bill_address_1']
                ],
                'address2'    => [
                    'th' => $params['bill_address_2'],
                    'en' => $params['bill_address_2']
                ],
                'address3'    => [
                    'th' => '',
                    'en' => ''
                ],
                'subdistrict' => [
                    'id' => (isset($params['bill_sub_district']))?$params['bill_sub_district']:''
                ],
                'district'    => [
                    'id' => (isset($params['bill_districts']))?$params['bill_districts']:''
                ],
                'province'    => [
                    'id' => (isset($params['bill_province']))?$params['bill_province']:''
                ],
                'postcode'    => $params['bill_postcode'],
                'first_name'    => $params['first_name'],
                'last_name'    => $params['last_name'],
                'contact_phone'    => $params['contact_phone'],
                'contact_email'    => $params['contact_email'],
                'content_type'  => $params['content_type']
            ];

            $address = $this->setAddressData($id, $params['bill_address_id'], $data, 'bill');
            //dd($address);
            return [
                'url'    => $address['url'],
                'method' => $address['method'],
                'params' => $address['params']
            ];

        }
    }

    /**
     * Method for curl api post update
     */
    public function updateDB($id, array $params)
    {

        $request = $this->setParamsMode($id, $params);

        if($params['mode'] == 'tax'){
            // Update Member Business
            $result = $this->guzzle->curl('PUT', $this->memberUrl . "shopprofile/" .$id, [
                'json' => [
                    'tax_id' => $params['tax_id'],
                    'email' => $params['business_email'],
                    'shop_name' => $params['business_shop_name']
                ]
            ]);

            // Update Or Create Tax Address
            if(isset($params['tax_address_id']) && !empty($params['tax_address_id'])) {
                $result = $this->updateTaxAddress($params);
            }
            else{
                $result = $this->createTaxAddress($params);
            }
        } else {
            $result =  $this->guzzle->curl($request['method'], $request['url'],[
                'form_params' => $request['params']
            ]);
        }

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            return [
                'status'   => true,
                'messages' => $this->messages['database']['success']
            ];
        }

        return [
            'success'  => false,
            'messages' => $this->messages['database']['update_error']
        ];
    }

    public function createTaxAddress($params)
    {
        $url = $this->addressUrl . "addresses";
        $result = $this->guzzle->curl('POST', $url, [
            'json' => [
                'address' => [
                    'th' => empty($params['tax_address_1'])? '' : $params['tax_address_1'],
                    'en' => empty($params['tax_address_1'])? '' : $params['tax_address_1']
                ],
                'address2' => [
                    'th' => empty($params['tax_address_1'])? '' : $params['tax_address_1'],
                    'en' => empty($params['tax_address_1'])? '' : $params['tax_address_1']
                ],
                'province' => [
                    'id' => empty($params['tax_province'])? '' : $params['tax_province']
                ],
                'district' => [
                    'id' => empty($params['tax_districts'])? '' : $params['tax_districts']
                ],
                'subdistrict' => [
                    'id' => empty($params['tax_sub_district'])? '' : $params['tax_sub_district'] 
                ],
                'contact_phone' => empty($params['business_phone'])? '' : $params['business_phone'],
                'country_code' => 'TH',
                'postcode' => empty($params['tax_postcode'])? '' : $params['tax_postcode'],
                'content_id' => empty($params['online_customer_id'])? '' : $params['online_customer_id'],
                'content_type' => 'member',
                'content_sub_type' => 'tax'
            ]
        ]);
        return $result;
    }

    public function updateTaxAddress($params)
    {
        $url = $this->addressUrl . "addresses/" .$params['tax_address_id'];
        // dd($params);
        $result = $this->guzzle->curl('PUT', $url, [
            'json' => [
                'address' => [
                    'th' => empty($params['tax_address_1'])? '' : $params['tax_address_1'],
                    'en' => empty($params['tax_address_1'])? '' : $params['tax_address_1']
                ],
                'address2' => [
                    'th' => empty($params['tax_address_2'])? '' : $params['tax_address_2'],
                    'en' => empty($params['tax_address_2'])? '' : $params['tax_address_2']
                ],
                'province' => [
                    'id' => empty($params['tax_province'])? '' : $params['tax_province']
                ],
                'district' => [
                    'id' => empty($params['tax_districts'])? '' : $params['tax_districts']
                ],
                'subdistrict' => [
                    'id' => empty($params['tax_sub_district'])? '' : $params['tax_sub_district'] 
                ],
                'postcode' => empty($params['tax_postcode'])? '' : $params['tax_postcode'],
                'contact_phone' => empty($params['business_phone'])? '' : $params['business_phone'],
                'content_type' => 'member'
            ]
        ]);
        // dd($result);
        return $result;
    }

    /**
     * Method for curl api member
     */
    public function getDataMember(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->memberUrl . 'members?' . $getUrl);
        $output = [
            'draw'            => $params['draw'],
            'recordsTotal'    => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data'            => isset($result['data']['records']) ? $this->setDataTable($result['data']['records'], $params) : [],
            'input'           => $params
        ];

        return json_encode($output);
    }

    /**
     * Method for curl api member report
     */
    public function getDataMemberReport(array $params)
    {
        $getUrl = $this->setSearch($params);

        $result = $this->guzzle->curl('GET', $this->memberUrl . 'members?' . $getUrl);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users  = $result['data']['records'];

            return Excel::create('member_report_' . date('YmdHis'), function($excel) use ($users) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Member', function($sheet) use ($users) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:K1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                         'No.',
                         'Username',
                         'Email',
                         'Registration Date',
                         'First Name',
                         'Last Name',
                         'Mobile Number',
                         'Member Number',
                         'Company Name/Personal Name',
                         'Customer Type',
                         'Customer Channel',
                         'Tax ID',
                         'Date Registered At Store',
                         'Registered Store ID',
                         'Last Login Date'

                    ]);
                    $sheet->row(1, function($row) {
                        $row->setBackground('#000000');
                    });

                    $row = 1;
                    foreach ($users as $kData => $vData) {
                        ++$row;
                        $customer_type_id = "";
                        $customer_type_id = array_get($vData, 'business.shop_type_id', '');
                        if($customer_type_id!=="" && $customer_type_id!=null){
                            $customer_type = array_get($vData, 'business.shop_type', '')." (".$customer_type_id.")";
                        } else {
                            $customer_type = array_get($vData, 'business.shop_type', '');
                        }
                        $data = [
                            $kData + 1,
                            $this->_unit->removeFirstInjection(array_get($vData, 'username', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'email', '')),
                            $this->_unit->removeFirstInjection(strtoupper(date('d/m/Y H:i:s', strtotime($vData['created_at'])))),
                            $this->_unit->removeFirstInjection(array_get($vData, 'first_name', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'last_name', '')),
                            sprintf('="%s"', array_get($vData, 'phone', '')),
                            sprintf('="%s"', array_get($vData, 'makro_member_card', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'business.shop_name', '')),
                            $this->_unit->removeFirstInjection($customer_type),
                            $this->getNameCustomerChannel($vData['customer_channel']),
                            sprintf('="%s"', array_get($vData, 'tax_id', '')),
                            !empty($vData['makro_register_date'])? '="'.date('d/m/Y',strtotime($vData['makro_register_date'])). '"':'',
                            '="'.array_get($vData, 'makro_register_store_id', ''). '"',
                            !empty($vData['last_login_date'])? '="'.date('d/m/Y H:i:s',strtotime($vData['last_login_date'])). '"' :''
                        ];

                        $sheet->row($row, $data);
                    }
                });
            })->export('csv');
        }

        return false;
    }

    /**
     * Method for datetable
     */
    public function setDataTable($data, array $params)
    {
        //loop get data
        $dataTable = [];
        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {
                $numberData  = ($kData + 1) + $params['start'];

                $customer_type_id = "";
                $customer_type_id = $vData['business']['shop_type_id'];
                if($customer_type_id!=="" && $customer_type_id!=null){
                    $customer_type = $vData['business']['shop_type']." (".$customer_type_id.")";
                } else {
                    $customer_type = $vData['business']['shop_type'];
                }

                $activate_class = (isset($vData['is_activate']) && $vData['is_activate'] == 'N') ? 'text-danger cursor-pointer"' : 'text-grey';
                $delete_action = (isset($vData['is_activate']) && $vData['is_activate'] == 'N') ? 'deleteMember(\'' . $vData['online_customer_id'] . '\');' : '';

                $dataTable[] = [
                    'number'                  => $numberData,
                    'created_at'              => date('d/m/Y H:i:s', strtotime($vData['created_at'])) ,
                    'first_name'              => $this->checkEmpty($vData['first_name'], $params['search'][2]['value']),
                    'last_name'               => $this->checkEmpty($vData['last_name'], $params['search'][3]['value']),
                    'username'                => $this->checkEmpty($vData['username'], $params['search'][1]['value']),
                    'phone'                   => $this->checkEmpty($vData['phone'], $params['search'][4]['value']),
                    'makro_member_card'       => $this->checkEmpty($vData['makro_member_card'], $params['search'][5]['value']),
                    'shop_name'               => $this->checkEmpty($vData['business']['shop_name'], $params['search'][6]['value']),
                    'shop_type'               => $this->checkEmpty($customer_type, $params['search'][7]['value']),
                    'tax_id'                  => $this->checkEmpty($vData['tax_id'], $params['search'][8]['value']),
                    'makro_register_date'     => !empty($vData['makro_register_date']) ? date('d/m/Y', strtotime($vData['makro_register_date'])) : '',
                    'last_login_date'         => !empty($vData['last_login_date']) ?  date('d/m/Y H:i:s', strtotime($vData['last_login_date']))  : '',
                    'makro_register_store_id' => $vData['makro_register_store_id'],
                    'action'                  => '<a href="' . url('/member/' . $vData['online_customer_id'] . '/edit') . '"><i class="icon-pencil"></i></a>',
                    'delete_action'           => '<i onclick="' . $delete_action . '" class="icon-trash ' . $activate_class . '"></i>',
                    'email'                   => $this->checkEmpty($vData['email'], ''),
                    'customer_channel'        => isset($vData['customer_channel']) ? $this->getNameCustomerChannel($vData['customer_channel'] ) : ''
                ];
            }
        }

        return $dataTable;
    }
    private function getNameCustomerChannel(String $name)
    {
        if (!empty($name) && isset($this->customer_channel[$name])) {
            return $this->customer_channel[$name];
        }

        return '';
    }
    /**
     * Method for set address
     */
    private function setAddressData($id, $mongoId, $data, $type)
    {
        if (empty($mongoId)) {

            return [
                'url'    => $this->addressUrl . 'addresses',
                'method' => 'POST',
                'params' => $data + [
                    'content_id'       => $id,
                    'content_type'     => 'member',
                    'country_code'     => 'TH',
                    'content_sub_type' => $type
                ]
            ];
        }

        return [
            'url'    => $this->addressUrl . 'addresses/' . $mongoId,
            'method' => 'PUT',
            'params' => $data
        ];
    }

    /**
     * Method for check empty
     */
    private function checkEmpty($data, $search = '')
    {
        if (!empty($data)) {
            return $this->highlight($search, $data);
        }

        return  '';
    }
    public function getStores()
	{
        //default data
        $data = [];

        //default params
		$params = [
			'limit'  => 500,
			'offset' => 0,
			'order'  => 'name|ASC'
		];

		$result = $this->curlStores($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

		    $stores = $result['data']['records'];

		    foreach ($stores as $store) {
			    $data[$store['makro_store_id']] = $store['name']['th']." (".$store['makro_store_id'].")";
		    }
        }

		return $data;
	}

    public function getStoresFilter()
    {
        $stores = $this->getStores();

        $makroStoreId = Session::get('makroStoreId');

        if(!empty($makroStoreId) && array_key_exists($makroStoreId, $stores)){
            $stores = [
                $makroStoreId => $stores[$makroStoreId]
            ];
        }

        return $stores;
        
    }

	public function curlStores($params)
	{
		$url = $this->urlStore . 'stores';
		$options = [
			'query'   => $params ,
			'headers' => [
				'X-Language' => 'th|en'
			]
		];
		return $this->guzzle->curl('GET', $url, $options);
    }
    
    public function deleteMember($id)
    {
        $delete_result = $this->guzzle->curl('DELETE', $this->memberUrl . 'members/' . $id);
        if(isset($delete_result['status']) && isset($delete_result['status']['code']) && $delete_result['status']['code'] == '200'){
            $bssDeleteUrl = $this->bssUrl . 'members/' . $id;
            $options = [
                'headers' => ['api-key' => env('MSIS_APIKEY')]
            ];
            $bss_delete_result = $this->guzzle->curl('DELETE', $bssDeleteUrl, $options);
            if(isset($bss_delete_result['data']['status']) && $bss_delete_result['data']['status'] == 'deleted'){
                return [
                    'status' => true
                ];
            }
            return [
                'status' => false,
                'message' => $bss_delete_result['userMessage']
            ];
        }

        return [
            'status' => false,
            'message' => $delete_result['errors']['message']
        ];
    }
}