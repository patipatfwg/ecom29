<?php
namespace App\Repositories;

use App\Services\Guzzle;
use App;
use DateTime;
use DateTimeZone;
use Excel;
use Response;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Exception\ClientException;
use App\Library\Unit;
class ProductRepository
{
	protected $guzzle;
	protected $messages;

	protected $searchOrder = [
        '_id','_id','item_id','_id','buyer_id','name.th','have_image','have_detail','normal_price','have_categories','approve_status','published_status','priority','id'
    ];

	protected $product_status = [
		'approved' => 'approve',
		'ready' => 'ready to approve',
		'editing' => 'save',
		'active' => 'Y',
		'inactive' => 'N'
	];

	public $approve_status = ['approved', 'ready to approve', 'editing'];
	public $published_status = ['Y', 'N'];

	public function __construct(Guzzle $guzzle)
	{
		$this->url = config('api.makro_product_api');
		$this->api['makro_bss_api'] = Config::get('api.makro_bss_api');
		$this->guzzle = $guzzle;
		$this->_unit  = new Unit;
	}

	public function getStorePrice($item_ids)
	{
		$url = $this->url . 'products/stores';

		return $this->guzzle->curl('GET', $url, [
			'query' => [
				'item_ids' => $item_ids
			]
		]);
	}

	public function getStore($params)
	{
		$url = $this->url . 'products/stores';

		return $this->guzzle->curl('GET', $url, [
			'query' => $params
		]);
	}

	protected function setSearchData($params)
	{
		$search = [];

		$search['intermediate'] = isset($params['online'])? "0" : "1";

		// Set search full text
		$fields = [];
		$languages = config('language.product');
		
		if(isset($params['fields'])){
			$search['fields'] = $params['fields'];
		} else {
			foreach($languages as $language){
				array_push($fields, 'name.' . $language);
			}
			$search['fields'] = implode(',', $fields);
		}

		$search['search'] = isset($params['name'])? $params['name'] : '';

		// Set search full text (Supplier)
		$search['supplier_fields'] = 'supplier_id,supplier_name';
		$search['supplier_search'] = isset($params['supplier'])? $params['supplier'] : '';

		// Set search full text (Buyer)
		$search['buyer_fields'] = 'buyer_id,buyer_name';
		$search['buyer_search'] = isset($params['buyer'])? $params['buyer'] : '';

		// Set search by ids
		if(isset($params['ids']) && !empty($params['ids'])){
			$search['ids'] = implode(',', $params['ids']);
		}

		// Set search item id
		if(isset($params['item_id']) && !empty($params['item_id'])){
			$search['item_id'] = $params['item_id'];
		}

		// Set search approve status
		if(isset($params['approve_status']) && !empty($params['approve_status'])){
			if($params['approve_status'] == 'any'){
				$search['approve_status'] = implode(',', $this->approve_status);
			}
			else{
				$search['approve_status'] = $params['approve_status'];
			}
		}

		// Set search action status
		if(isset($params['action_status']) && !empty($params['action_status'])){
			if($params['action_status'] == 'any'){
				$search['action_status'] = '';
			}
			else{
				$search['action_status'] = $params['action_status'];
			}
		}

		// Set search makro store id
		if(isset($params['makro_store_id']) && !empty($params['makro_store_id'])){
			$search['makro_store_id'] = $params['makro_store_id'];
		}

		// Set search image status
		if(isset($params['have_image']) && !empty($params['have_image'])){
			if($params['have_image'] == 'any'){
				$search['have_image'] = '';
			}
			else{
				$search['have_image'] = $params['have_image'];
			}
		}

		// Set search published status
		if(isset($params['published_status']) && !empty($params['published_status'])){
			if($params['published_status'] == 'any'){
				$search['published_status'] = implode(',', $this->published_status);
			}
			else{
				$search['published_status'] = $params['published_status'];
			}
		}

		// Set search updated after
		if(isset($params['updated_after']) && !empty($params['updated_after'])){
			$search['updated_at'] = sprintf('[%s]',convertDateTime($params['updated_after'], 'd/m/Y', 'Y-m-d 00:00:00'));
		}

		// Set Limit
		if(isset($params['length'])){
			$search['limit'] = $params['length'];
		}

		// Set offset
		if(isset($params['start'])){
			$search['offset'] = $params['start'];
		}

		// Set Sort
		if(isset($params['order'][0]['column'])){

			if($params['order'][0]['column'] == 'name_th' || $params['order'][0]['column'] == 'name_en'){
				$str = $params['order'][0]['column'];
				$value = str_replace('_','.',$str);
			}
			else{
				$value = $params['order'][0]['column'];
			}

			$search['order'] = $value . '|' . $params['order'][0]['dir'];
		}

		return $search;
	}

	protected function getApproveStatus($status)
	{
		switch(strtolower($status)){
			case 'approved': return 'approve';
			case 'ready': return 'ready to approve';
			case 'editing': return 'editing';
			case 'reject': return 'reject';
		}
	}

	protected function prepareProductData($inputs)
	{
		$params = [];

		$params['name'] = $inputs['name'];

		// Description
		$params['description'] = $inputs['description'];
		$haveDescription = false;
		foreach($inputs['description'] as $key => $value){
			if(!empty($value)){
				$haveDescription = true;
				break;
			}
		}
		$params['have_detail'] = $haveDescription? 'Y' : 'N';

		$params['seo_subject'] = $inputs['seo_subject'];
		$params['seo_explanation'] = $inputs['seo_explanation'];

		// Have Categories
		$params['have_categories'] = (!empty($inputs['productCategory_id']))? 'Y' : 'N';

		$params['suggest_price'] = $inputs['suggest_price'];
		$params['profit_per_unit'] = $inputs['profit_per_unit'];
		$params['total_profit'] = $inputs['total_profit'];
		$params['minimum_order_limit'] = $inputs['minimum_order_limit'];
		$params['maximum_order_limit'] = $inputs['maximum_order_limit'];

		// Images
		$params['image'] = $inputs['images'];
		$params['have_image'] = empty($inputs['images'])? 'N' : 'Y';

		// Published Date
		$started_date = empty($inputs['start_date'])? null : convertDateTime($inputs['start_date'], 'd/m/Y H:i:s', 'Y-m-d 00:00:00');
		$end_date = empty($inputs['end_date'])? null : convertDateTime($inputs['end_date'], 'd/m/Y H:i:s', 'Y-m-d 23:59:59');
		$params['published'] = [
			'started_date' => $started_date,
			'end_date' => $end_date
		];

		// length, Width, Height
		$params['length'] = $inputs['length'];
		$params['width'] = $inputs['width'];
		$params['height'] = $inputs['height'];
		$params['lwh_uom'] = json_decode($inputs['lwh_uom'], true);

		// Weight
		$params['weight'] = $inputs['weight'];
		$params['weight_uom'] = json_decode($inputs['weight_uom'], true);

		// Makro unit
		$params['makro_unit'] = $inputs['makro_unit'];
		$params['sub_makro_unit'] = $inputs['sub_makro_unit'];
		$params['unit_type'] = json_decode($inputs['unit_type'], true);

		//Product relate
		$params['related_product_search'] = isset($inputs['related_product_search']) && $inputs['related_product_search'] === 'on' ? 'Y' : 'N';
		$params['related']                = isset($inputs['product_relate']) ? $inputs['product_relate'] : [];

		return $params;
	}

	public function putUpdateProductActionStatus($id, $params)
	{
		$url = $this->url . 'products/' . $id;
		$options = [
			'json' => $params
		];		

		return $this->guzzle->curl('PUT', $url, $options);
	}

	public function getProduct($id)
	{
		$url = $this->url . 'products/' . $id;

		return $this->guzzle->curl('GET', $url, []);
	}

	public function getProductIntermediate($id)
	{
		$url = $this->url . 'products/' . $id;

		return $this->guzzle->curl('GET', $url, [
			'query' => [
				'intermediate' => 1
			]
		]);
	}

	protected function setDataForTable($data, $start)
	{
		$dataModel = [];
		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $start;
			$dataModel[] = [
				'id'               => $vData['id'],
				'number'           => $numberData,
				'item_id'          => $vData['item_id'],
				'updated_at'       => convertDateTime($vData['updated_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
				'online'           => $vData['id'],
				'action_status'    => isset($vData['action_status']) ? $vData['action_status'] : '',
				'name_th'          => $vData['name']['th'],
				'name_en'          => $vData['name']['en'],
				'buyer_id'         => $vData['buyer_id'],
				'buyer_name'       => $vData['buyer_name'],
				'supplier_id'      => $vData['supplier_id'],
				'supplier_name'    => $vData['supplier_name'],
				'have_image'       => $vData['have_image'],
				'have_detail'      => $vData['have_detail'],
				'normal_price'     => number_format((float)$vData['normal_price'], 2, '.', ','),
				'have_categories'  => $vData['have_categories'],
				'approve_status'   => $vData['approve_status'],
				'published_status' => $vData['published_status'],
				'priority'         => $vData['priority'],
				'action'           => $vData['id']
			];
		}

		return $dataModel;
	}

	public function getProductList($inputs)
	{	
		$output = [
			'draw'            => $inputs['draw'],
			'recordsTotal'    => 0,
			'recordsFiltered' => 0,
			'data'            => array(),
			'input'           => $inputs
		];

		$products = $this->getProducts($inputs);

		if(isset($products['status']['code']) && $products['status']['code'] == 200){
			$dataModel = $this->setDataForTable($products['data']['records'], $inputs['start']);
			$output = [
				'draw'            => $inputs['draw'],
				'recordsTotal'    => count($products['data']['records']),
				'recordsFiltered' => $products['data']['pagination']['total_records'],
				'data'            => $dataModel,
				'input'           => $inputs
			];
		}
		return json_encode($output);
	}

	public function getProducts($params)
	{
		$url = $this->url . 'products/search';
		$options = [
			'form_params'   => $this->setSearchData($params),
			'headers' => [
				'X-Language' => 'th|en',
				'X-Location' => 'TH'
			]
		];
		
		return $this->guzzle->curl('POST', $url, $options);
	}

	public function setPriorityProduct($params)
	{
		$outputs['data']['status'] = false;

		$setData = $this->checkPriorityProduct($params);

		if (!empty($setData)) {
			$outputs = $this->sendPriorityProduct($setData);
		}

		return $outputs;
	}

	private function checkPriorityProduct($params)
	{
		$outputs = false;

		if (isset($params['priority']) && count($params['priority']) > 0) {

			$outputs = [
				'product_id' => implode(',', array_keys($params['priority'])),
				'priority'   => implode(',', $params['priority'])
			];
		}

		return $outputs;
	}

	private function sendPriorityProduct($params)
	{
		$url = $this->url . 'products/priority/' . $params['product_id'];

		return $this->guzzle->curl('PUT', $url, [
			'form_params' => $params
		]);
	}

	public function setStatusProduct($inputs)
	{
		$params = [];
		$action = $inputs['status'];
		foreach($inputs['product_ids'] as $id){

			if($action == 'active' || $action == 'inactive'){
				$params[] = [
					'product_id' => $id,
					'published_status' => $this->product_status[$action]
				];
			}
			else{
				$params[] = [
					'product_id' => $id,
					'action' => $this->product_status[$action]
				];
			}
		}

		$url = $this->url . 'products/status';
		$options = [
			'headers' => [
				'X-Location' => 'TH'
			],
			'json' => $params
		];
		return $this->guzzle->curl('PUT', $url, $options);
	}

	public function putUpdateProduct($id, $params, $ignoreUpdateApproveStatus = false)
	{
		$url = $this->url . 'products/' . $id;

		if($ignoreUpdateApproveStatus){
			$url = $this->url . 'products/update/desc/' . $id;
		}

		$options = [
			'json' => $this->prepareProductData($params)
		];
		
		return $this->guzzle->curl('PUT', $url, $options);
	}

	public function putUpdateActionStatus($id, $params)
	{
		$url = $this->url . 'products/' . $id;
		$options = [
			'json' => $params
		];		

		return $this->guzzle->curl('PUT', $url, $options);
	}

	public function putUpdatePriority($params)
	{
		$url = $this->url . 'products/priority';
		$options = [
			'headers' => [
				'X-Location' => 'TH'
			],
			'json' => $params
		];

		return $this->guzzle->curl('PUT', $url, $options);
	}

	public function putUpdateStatus($id, $status , $reason_rejected = null)
	{
		$url = $this->url . 'products/status/' . $id;
		if($status == 'active' || $status == 'inactive'){
			$options = [
				'json' => [
					'published_status' => $this->product_status[$status]
				]
			];
		}
		else{
			$options = [
				'json' => [
					'action' => $this->getApproveStatus($status)
				]
			];
			if($status == 'reject') {
				$options['json']['reasons_rejected'] = $reason_rejected;
			}
		}
		
		return $this->guzzle->curl('PUT', $url, $options);
	}

	public function deleteProduct($id)
	{
		$url = $this->url . 'products/' . $id;
		return $this->guzzle->curl('DELETE', $url, []);
	}

	public function uploadImage($filename)
	{
		//Define output
		$outputs = [
			'success' => false,
			'image'   => ''
		];

		//get cdn service
		$cdbService = App::make('App\Services\CdnServices');
		$shortUrl = $cdbService->_uploadFileData($filename);

		if (!empty($shortUrl)) {
			$outputs['success'] = true;
			$outputs['image'] = $shortUrl;
		}

		return $outputs;
	}

	public function uploadImageByDump($filename)
	{
		//Define output
		$outputs = [
			'success' => false,
			'image'   => ''
		];

		//get cdn service
		$cdbService = App::make('App\Services\CdnServices');
		$shortUrl = $cdbService->_uploadFileDataByDump($filename);

		if (!empty($shortUrl)) {
			$outputs['success'] = true;
			$outputs['image'] = $shortUrl;
		}

		return $outputs;
	}

	public function getUnitType($type)
	{
		$url = $this->url . 'products/' . $type;
		return $this->guzzle->curl('GET', $url, []);
	}

	public function getDataProductReport(array $params)
    {	
        //$params['length'] = '100';
		$url = $this->url . 'products/search';
		$options = [
			'form_params'   => $this->setSearchData($params),
			'headers' => [
				'X-Language' => 'th|en',
				'X-Location' => 'TH'
			]
		];
		
		$result = $this->guzzle->curl('POST', $url, $options);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $categories = $result['data']['records'];

            $file = Excel::create('product_report_' . date('YmdHis'), function($excel) use ($categories) {
				header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Product', function($sheet) use ($categories) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'Item ID',
						'Published',
						'Approval Status',
						'RMS Status',
                        'Last Update',
                        'Product Name (TH)',
						'Product Name (EN)',
						'Supplier ID',
						'Supplier Name',
						'Buyer ID',
						'Buyer Name',
						'Image',
						'Detail',
                        'Normal Price',
                        'Category',
						'Priority'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($categories as $kData => $vData) {
                        ++$row;

                        $data = [
                            '="'.array_get($vData, 'item_id', '').'"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'published_status', '')=='Y'?'Publish':(array_get($vData, 'published_status', '')=='N'?'Unpublish': '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'approve_status', '')),
							$this->_unit->removeFirstInjection(array_get($vData, 'action_status', '')),
                            '="'. convertDateTime(array_get($vData, 'updated_at', ''), 'Y-m-d H:i:s', 'd/m/Y H:i:s') . '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.th', '')),
							$this->_unit->removeFirstInjection(array_get($vData, 'name.en', '')),
                            '="'.array_get($vData, 'supplier_id', ''). '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'supplier_name', '')),
                            '="'.array_get($vData, 'buyer_id', ''). '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'buyer_name', '')),
							$this->_unit->removeFirstInjection(array_get($vData, 'have_image', '')),
							$this->_unit->removeFirstInjection(array_get($vData, 'have_detail', '')),
                            $this->_unit->removeFirstInjection(number_format((float)$vData['normal_price'], 2, '.', ',')),
							$this->_unit->removeFirstInjection(array_get($vData, 'have_categories', '')),
							$this->_unit->removeFirstInjection(array_get($vData, 'priority', ''))
                        ];

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');

			return Response::download($file);
        }

        return false;
    }

}
