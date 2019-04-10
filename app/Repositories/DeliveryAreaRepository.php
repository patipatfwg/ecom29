<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Services\Guzzle;

class DeliveryAreaRepository extends BaseRepository
{
    private $messages;

    public function __construct(Guzzle $guzzle)
    {
        parent::__construct();
        $this->messages   = config('message');
        $this->guzzle     = $guzzle;
        $this->addressUrl = env('CURL_API_ADDRESS');
        $this->storeUrl   = env('CURL_API_STORE');
    }

    public function getEditPermission()
    {
        $permissions = \Session::get('permission_menus');
        $edit = false;

        if(isset($permissions['delivery_area']) && isset($permissions['delivery_area']['update'])){
            $edit = ($permissions['delivery_area']['update'] == '1') ? true : false;
        }
        
        return $edit;
    }

    public function getProvince()
    {
        $result = [];
        $province = $this->guzzle->curl('GET', $this->addressUrl . 'provinces/delivery?country_code=TH&status=Y,N&order=name.th|asc');
        if(count($province['data']['records']) > 0){
            foreach($province['data']['records'] as $eachProvince){
                $result[$eachProvince['province_id']] = $eachProvince['name']['th'];
            }
        }
        return $result;
    }

    public function getDistrict($province_id, $flip = false)
    {
        $result = [];
        $district = $this->guzzle->curl('GET', $this->addressUrl . 'districts/delivery?province_id=' . $province_id . '&status=Y,N&order=name.th|asc');
        if(count($district['data']['records']) > 0){
            foreach($district['data']['records'] as $eachDistrict){
                $result[$eachDistrict['name']['th']] = (String) $eachDistrict['district_id'];
            }

            if($flip){
                $result = array_flip($result);
            }
        }

        return json_encode($result);
    }

    public function getSubDistrict($district_id, $flip = false)
    {
        $result = [];
        $subDistrict = $this->guzzle->curl('GET', $this->addressUrl . 'subdistricts/delivery?district_id=' . $district_id . '&status=Y,N&order=name.th|asc');
        if(count($subDistrict['data']['records']) > 0){
            foreach($subDistrict['data']['records'] as $eachSubDistrict){
                $result[$eachSubDistrict['name']['th']] = (String) $eachSubDistrict['sub_district_id'];
            }

            if($flip){
                $result = array_flip($result);
            }
        }
        return json_encode($result);
    }

    public function getSubDistrictAll($province_id, $flip = false)
    {
        $result = [];
        $district = $this->getDistrict($province_id);
        if(count($district) > 0){
            $district_id = implode(',', json_decode($district, true));
            $result = $this->getSubDistrict($district_id);
            if(json_decode($result)){
                if($flip){
                    $result = array_flip($result);
                }

                return $result;
            }
        }
        return json_encode($result);
    }

    public function getStatus($all = false)
    {
        $status = [
            'Y' => 'Y',
            'N' => 'N'
        ];

        if($all) $status = ['Y,N' => 'All'] + $status;

        return $status;
    }

    private function getSearchParams($params)
    {
        $result = [];
        foreach($params as $param){
            $result[$param['name']] = $param['value'];
        }
        return $result;
    }

    /**
     * Method for datetable
     */
    public function setDataTable($data, array $params)
    {
        $search = $this->getSearchParams($params['search']);
        $stores = $this->getStores();

        //loop get data
        $dataTable = [];
        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {
                $dataTable[] = [
                    'postcode'                  => $this->checkEmpty($vData['postcode'], @$params['search']['']),
                    'province'                  => $this->checkEmpty($vData['province']['original_name']['th'], @$params['search']['']),
                    'district'                  => $this->checkEmpty($vData['district']['original_name']['th'], @$params['search']['']),
                    'subdistrict'               => $this->checkEmpty($vData['sub_district']['original_name']['th'], @$params['search']['']),
                    'status'                    => $this->generateDropdown($vData, 'status', $this->checkEmpty($vData['status'], @$params['search']['']), $this->getStatus()),
                    'makro_inventory_store'     => $this->generateDropdown($vData, 'makro_inventory_store', $this->checkEmpty($vData['makro_inventory_store'], @$params['search']['']), $stores),
                    'price_store'               => $this->generateDropdown($vData, 'price_store', $this->checkEmpty($vData['price_store'], @$params['search']['']), $stores),
                    'price_store_professional'  => $this->generateDropdown($vData, 'price_store_professional', $this->checkEmpty($vData['price_store_professional'], @$params['search']['']), $stores)
                ];
            }
        }

        return $dataTable;
    }

    public function generateDropdown($params, $key, $value, $data)
    {
        $output = '<span class="edit_text">' . $value . '</span>';
        $idData = base64_encode(json_encode([
            'id' => $params['id'],
            'province_id' => $params['province']['id'],
            'district_id' => $params['district']['id'],
            'sub_district_id' => $params['sub_district']['id'],
            'country_code' => $params['country_code'],
            'postcode' => $params['postcode'],
            'status' => $params['status'],
            'key' => $key
        ]));
        $output .= "<select id='{$idData}' name='{$idData}' class='edit_select {$key} hide'>";
        foreach($data as $val => $text){
            $selected = ($val == $value) ? 'selected' : '';
            $output .= "<option value='{$val}' {$selected}>{$text}</option>";
        }
        $output .= "</select>";

        return $output;
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

    public function getDeliveryAreaData(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->addressUrl . 'deliveryarea?' . $getUrl);

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
                    // if (strpos($vData['name'], 'date') !== false && !empty($vData['value'])) {
                    //     $search[] = $vData['name'] . '=' . convertDateTime($vData['value'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
                    // } 
                    // else if($vData['name'] == 'customer_type'){
                    //     $search[] = 'fields=business.shop_type_id,business.shop_type';
                    //     $search[] = 'search=' . $vData['value']; 
                    // }
                    // else {
                        $search[] = $vData['name'] . '=' . $vData['value'];
                    // }
                }
            }
        }

        return $search;
    }


    public function getStores()
    {
        $result = ["" => "Select Store"];
        $stores = $this->guzzle->curl('GET', $this->storeUrl . 'stores?order=makro_store_id|asc&limit=0');
        if(count($stores['data']['records']) > 0){
            foreach($stores['data']['records'] as $eachStore){
                $result[$eachStore['makro_store_id']] = $eachStore['makro_store_id'];
            }
        }
        return $result;
    }

    public function saveData($data)
    {
        $updateData = [];
        foreach($data as $params => $value){
            $decode = json_decode(base64_decode($params), true);
            $id = $decode['id'];
            $key = $decode['key'];
            unset($decode['key']);

            if(!isset($updateData[$id])){
                $updateData[$id] = $decode;
                $updateData[$id][$key] = $value;
            } else {
                $updateData[$id][$key] = $value;
            } 
        }

        $updateData = array_values($updateData);
        $curlResult = $this->guzzle->curl('POST', $this->addressUrl . 'delivery/imports', [
            'form_params' => $updateData
        ]);

        return (isset($curlResult['data']['error']) && count($curlResult['data']['error']) > 0) ? false : true;
    }

}