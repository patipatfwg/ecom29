<?php
namespace App\Repositories;

use App;
use App\Services\Guzzle;
use Excel;
use App\Library\Unit;
use App\Repositories\StoreRepository;
class PickupStoreRepository
{
    public $coupon_type = [
            'cart discount' => 'Fixed Cart Discount',
            'product discount' => 'Fixed Product Discount',
            '' => 'No Type'
    ];

    public function __construct(Guzzle $guzzle, Unit $unit, StoreRepository $storeRepository)
	{
        $this->guzzle           = $guzzle;
        $this->unit             = $unit;
		$this->messages         = config('message');
        $this->url              = env('CURL_API_STORE') . "stores";
        $this->storeRepository  = $storeRepository;
	}

    public function getDataStore($inputs)
    {
        $filter = [
            'offset' => isset($inputs['start'])  ? $inputs['start']  : 0,
            'limit'  => isset($inputs['length']) ? $inputs['length'] : 10,
        ];

        if ($inputs['makro_store_id'] != 'All') {
            $filter['makro_store_id'] = $inputs['makro_store_id'];
        }

        if ($inputs['pickup'] != 'All') {
            $filter['pickup'] = $inputs['pickup'];
        }

        if (isset($inputs['order'][0]['column']) && isset($inputs['order'][0]['dir'])) {
            $filter['order'] = $inputs['order'][0]['column'] . '|' . $inputs['order'][0]['dir'];
        }

        $params = [
            'query' => $filter
        ];

        $result     = $this->getDataList($params);
        $dataTable  = [];
        $count_page = 0;
        $count_all  = 0;

        if (isset($result['data']['records']) && !empty($result['data']['records'])) {
            $dataTable  = $this->setDataTableSearch($result['data']['records'], $inputs);
            $count_page = count($result['data']['records']); //count page
            $count_all  = $result['data']['pagination']['total_records']; //count all
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
        $result = $this->guzzle->curl('GET', $this->url, $params);
        return  $result;
    }

    public function setDataTableSearch($data,$params)
    {
        $dataTable = [];
        $language = App::getLocale();

        foreach ($data as $kData => $vData) {

            $numberData = ($kData + 1) + $params['start'];
            $data = [
                'number'                   => $numberData,
                'id'                       => isset($vData["id"]) ? $vData["id"] : '',
                'makro_store_id'           => isset($vData["makro_store_id"]) ? $vData["makro_store_id"] : '',
                'name_th'                  => isset($vData["name"]['th']) ? $vData["name"]['th'] : '',
                'name_en'                  => isset($vData["name"]['en']) ? $vData["name"]['en'] : '',
                // 'price_store'              => isset($vData["price_store"]) ?  $vData["price_store"] : '',
                // 'price_store_professional' => isset($vData["price_store_professional"]) ? $vData["price_store_professional"] : '',
            ];
            // pickup
            $pickup = isset($vData["pickup"]) ? $vData["pickup"] : 'N';
            $data['pickup'] = "<span class='pickup' field='pickup'>" . $pickup . "</span>" ;
            $data['pickup'] .= '<select class="hidden select-dropdown form-border-select pickup" field="pickup"><option ' . ($pickup == "N" ? "selected":"") . '>N</option><option ' . ($pickup == "Y" ? "selected":"") . '>Y</option></select>';

            // price_store
            $price_store = isset($vData["price_store"]) ? $vData["price_store"] : '';
            $data['price_store'] = "<span class='price_store' field='price_store'>" . $price_store . "</span>" ;
            $getStoreParams  = [
                'limit'  => 9999,
                'offset' => 0,
                'order'  => 'makro_store_id|ASC',
            ];
            $stores = $this->storeRepository->getStoreSelect($getStoreParams);
            $data['price_store'] .= '<select class="hidden select-dropdown form-border-select price_store" field="price_store">';
            if (!empty($stores)) {
                $data['price_store'] .= '<option value="">Select Store</option>';
                foreach ($stores as $key => $value) {
                    $data['price_store'] .= '<option ' .($price_store == $key ? 'selected' : '') . '>' . $key . '</option>';
                }
            }
            $data['price_store'] .= '</select>';

            // price_store_professional
            $price_store_professional = isset($vData["price_store_professional"]) ? $vData["price_store_professional"] : '';
            $data['price_store_professional'] = "<span class='price_store_professional' field='price_store_professional'>" . $price_store_professional . "</span>" ;
            $getStoreParams  = [
                'limit'  => 9999,
                'offset' => 0,
                'order'  => 'makro_store_id|ASC',
            ];
            $stores = $this->storeRepository->getStoreSelect($getStoreParams);
            $data['price_store_professional'] .= '<select class="hidden select-dropdown form-border-select price_store_professional" field="price_store_professional">';
            if (!empty($stores)) {
                $data['price_store_professional'] .= '<option value="">Select Store</option>';
                foreach ($stores as $key => $value) {
                    $data['price_store_professional'] .= '<option ' .($price_store_professional == $key ? 'selected' : '') . '>' . $key . '</option>';
                }
            }
            $data['price_store_professional'] .= '</select>';

            $dataTable[] = $data;
        }

        return $dataTable;
    }

}