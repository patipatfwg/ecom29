<?php
namespace App\Repositories;

use App\Services\Guzzle;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App;
use DateTime;
use Excel;
use App\Library\Unit;
class CampaignRepository
{
    protected $guzzle;
    protected $messages;
    private $orderBy = [
        '', '', 'id'
    ];

    public function __construct(Guzzle $guzzle, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->url = config('api.makro_campaign_api');
        $this->guzzle = $guzzle;
        $this->productRepository  = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->_unit              = new Unit;
    }

    protected function getProductIdsByCategory($categoryId)
    {
        $output = array();
        $result = $this->categoryRepository->getContentsByCategory(array($categoryId));

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $contents = $result['data'][0]['contents'];
            $contents = array_filter($contents, function ($content) {
                return $content['content_type'] == 'product' ? true : false;
            });
            $output = array_column($contents, 'content_id');
        }

        return $output;
    }

    protected function getBusinessIdsByCategory($categoryId)
    {
        $output = array();
        $result = $this->categoryRepository->getContentsByCategory(array($categoryId));

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $contents = $result['data'][0]['contents'];
            $contents = array_filter($contents, function ($content) {
                return $content['content_type'] == 'product' ? true : false;
            });
            $output = array_column($contents, 'content_id');
        }

        return $output;
    }

    protected function setDataForTable($data, $start)
    {
        $dataModel = [];
        $language = App::getLocale();
        foreach ($data as $kData => $vData) {
            $numberData = ($kData + 1) + $start;
            $dataModel[] = [
                'id' => $vData['id'],
                'number' => $numberData,
                'item_id' => $vData['item_id'],
                'name_th' => $vData['name']['th'],
                'name_en' => $vData['name']['en'],
                'normal_price' => number_format((float)$vData['normal_price'], 2, '.', ','),
                'approve_status' => $vData['approve_status'],
                'published_status' => $vData['published_status']
            ];
        }
        return $dataModel;
    }

    public function updateCampaign($id, $params)
    {
        $options = [
            'json' => $params
        ];

        $url = $this->url . 'campaigns/' . $id;
        $result = $this->guzzle->curl('PUT', $url, $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }

    public function createCampaign($params)
    {
        $options = [
            'json' => $params
        ];
        $url = $this->url . 'campaigns/';
        $result = $this->guzzle->curl('POST', $url, $options);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            $campaignId = $result['data']['id'];
            return array('status' => true, 'campaignId' => $campaignId);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }

    public function getPromotionData()
    {
        $url = $this->url . 'promotions';
        $result = $this->guzzle->curl('GET', $url);
        $output = [];
        if (isset($result['status']['code']) && $result['status']['code'] == '200') {
            $output = $result['data']['records'];
        }
        return $output;
    }

    public function getRibbonData()
    {
        $url = $this->url . 'ribbons';
        $result = $this->guzzle->curl('GET', $url);
        $output = [];
        if (isset($result['status']['code']) && $result['status']['code'] == '200') {
            $output = $result['data']['records'];
        }
        return $output;
    }

    public function getCampaign($id)
    {
        $url = $this->url . 'campaigns/' . $id;
        $output = [];
        $result = $this->guzzle->curl('GET', $url);
        if (isset($result['status']['code']) && $result['status']['code'] == '200') {
            $output = $result['data']['records'][0];
        }
        return $output;
    }

    public function getCurlDataCampaign($params) 
    {
        $url = $this->url . 'campaigns';
        $result = $this->guzzle->curl('GET', $url, $params);
        return $result;
    }

    public function getCampaigns($inputs)
    {   
        $language = App::getLocale();

        $fields = ['campaign_code'];
        foreach (config("language.campaign") as $lang) {
            $fields[] = 'name.' . $lang;
        }

        $params = [
            'query' => [
                'offset' => $inputs['start'],
                'limit' => $inputs['length'],
                'column' => $inputs['order'][0]['column'],
                'dir' => $inputs['order'][0]['dir'],
                'date' => isset($inputs['launch_date_input']) ? convertDateTime($inputs['launch_date_input'], 'd/m/Y', 'Y-m-d') : '',
                'text' => isset($inputs['search_text_input']) ? $inputs['search_text_input'] : '',
                'fields' => implode(',', $fields)
            ]
        ];
               
        $result = $this->getCurlDataCampaign($params);

        // loop get data
        $dataModel = [];
        $count_page = 0;
        $count_all = 0;

        if (isset($result['status']) && isset($result['status']['code'])) {
            if ($result['status']['code'] == 200) {
                foreach ($result['data']['records'] as $kData => $vData) {
                    $numberData = ($kData + 1) + $inputs['start'];
                    $dataModel[] = [
                        'id' => $vData['id'],
                        'number' => $numberData,
                        'campaignCode' => $vData['campaign_code'],
                        'name_th' => $vData['name']['th'],
                        'name_en' => $vData['name']['en'],
                        'startDate' => convertDateTime($vData['start_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                        'endDate' => convertDateTime($vData['end_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                        'createdAt' => convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                        'edit' => url('/campaign/' . $vData['id'] . '/edit'),
                        'status' => $vData['status'], 
                        'current_date' => date('Y-m-d'),
                        'startDateTimestamp' => strtotime($vData['start_date']),
                        'endDateTimestamp' => strtotime($vData['end_date']),
                        'currentDateTimestamp' => strtotime(date('Y-m-d H:i:s'))

                    ];
                }
                $count_page = count($result['data']['records']); //count page
                $count_all = $result['data']['pagination']['total_records']; //count all
            }
        }

        $output = [
            'draw' => $inputs['draw'],
            'recordsTotal' => $count_page, //count page
            'recordsFiltered' => $count_all, //count all
            'data' => $dataModel,
            'input' => $inputs
        ];

        return json_encode($output);
    }

    public function uploadImage($filename)
    {
        //Define output
        $outputs = [
            'success' => false,
            'image' => ''
        ];

        //get cdn service
        $cdbService = App::make('App\Services\CdnServices');

        try {
            $shortUrl = $cdbService->_uploadFileData($filename);
        } catch (Exception $e) {
            $shortUrl = array();
        }


        if (!empty($shortUrl)) {
            $outputs['success'] = true;
            $outputs['image'] = $shortUrl;

        }

        return $outputs;
    }

    public function updateStatusCampaign($ids, $status)
    {
        $options = [
            'json' => [
                'status' => $status
            ]
        ];

        $url = $this->url . 'campaigns/status/' . $ids;
        $result = $this->guzzle->curl('PUT', $url, $options);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }

    public function deleteCampaign($ids)
    {
        $url = $this->url . 'campaigns/' . $ids;
        $result = $this->guzzle->curl('DELETE', $url);
        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }
    private function filterCategory($inputs)
    {
        $ids_by_category = false;

        if ($inputs['category']['product'] != 'undefine' || $inputs['category']['business'] != 'undefine') {

            $products_id = [];
            $business_id = [];
            // Get Content Category (Type = Product)
            if ($inputs['category']['product'] != 'undefine') {
                $category_id = $inputs['category']['product'];
                $ids_by_category = $this->getProductIdsByCategory($category_id);
            }

            // Get Content Category (Type = Business)
            if ($inputs['category']['business'] != 'undefine') {

                $category_id = $inputs['category']['business'];
                $business_id = $this->getBusinessIdsByCategory($category_id);

                $ids_by_category = ($ids_by_category != false) ? array_merge($ids_by_category, $business_id) : $business_id;

            }

        }

        return $ids_by_category;
    }
    private function getCountProductCampaignAll($campaign_id)
    {
        $count = 0;

        if (!empty($campaign_id)) {
            $result = $this->doGetCampaignProducts($campaign_id);
            if (isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data']['pagination']['total_records'])) {

                $count = $result['data']['pagination']['total_records'];
            }
        }

        return $count;
    }

    private function getCountProductAll($inputs)
    {
        $count = 0;

        if (!empty($inputs)) {
            $inputs['start']   = 0;
            $inputs['length']  = 10;

            $result = $this->productRepository->getProducts($inputs);
            if(isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data']['records'])){
                    $count = $result['data']['pagination']['total_records'];
            }
        }

        return $count;
    }

    private function getProductIdListByCampaignId($campaign_id)
    {
        $product_ids = [];
        if (!empty($campaign_id)) {
            $params['start']   = 0;
            $params['length']  = $this->getCountProductCampaignAll($campaign_id);
            $result = $this->doGetCampaignProducts($campaign_id, $params);
            if (isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data']['records'])) {

                $product_ids = array_column($result['data']['records'], 'product_id');
            }
        }
        return $product_ids;
    }
    public function getCampaignProducts($campaign_id, $inputs)
    {
        $output = [
            'draw' => $inputs['draw'],
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'input' => $inputs
        ];

        $startRecord = $inputs['start'];

        if ($inputs['length'] == 0) {
            return json_encode($output);
        }

        $dataModel   = [];
        $products    = [];
        $totalRecord = 0;
        // data campaign
        $campaign = $this->getCampaign($campaign_id);

        // order priority not search name and item id

        if (empty($inputs['name']) && empty($inputs['item_id']) && isset($inputs['order'][0]['column']) && $inputs['order'][0]['column'] == 'priority' &&
            isset($inputs['order'][0]['dir'])) {

            $paramsCampaignProduct['order']   = "priority|" . $inputs['order'][0]['dir'];
            $paramsCampaignProduct['start']   = isset($inputs['start'])  ? $inputs['start']  : 0;
            $paramsCampaignProduct['length']  = isset($inputs['length']) ? $inputs['length'] : 10;

            // get product id list by campaign id
            $productIdByCampaign = $this->getProductIdListByCampaignId($campaign_id);

            // Filter Category
            $ids_by_category = $this->filterCategory($inputs);
            if ($ids_by_category !== false) {

                $productIdByCampaign = array_intersect($productIdByCampaign, $ids_by_category);

                $productIdByCampaign = !empty($productIdByCampaign) ? $productIdByCampaign : false;

                if ($productIdByCampaign !== false) {
                    $paramsCampaignProduct['product_ids'] = $productIdByCampaign;
                }
            }

            // check product
            if ($productIdByCampaign !== false) {

                // get data product campaign
                $result = $this->doGetCampaignProducts($campaign_id, $paramsCampaignProduct);
                // remove field order
                unset($inputs['order']);
                if (isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data']['records'])) {

                    $campaign_products = $result['data']['records'];
                    // set start '0' because where ids
                    $inputs['start'] = 0;
                    $inputs['ids']   = array_column($campaign_products, 'product_id');

                    // get detail product
                    $result = $this->productRepository->getProducts($inputs);

                    if(isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data']['records'])){
                        // get total record all
                        $inputs['ids']  = $productIdByCampaign;
                        $totalRecord    = $this->getCountProductAll($inputs);

                        $products       = $result['data']['records'];

                        foreach ($campaign_products as $vCampaignProduct) {

                            foreach ($products as $vProduct) {

                                if ($vCampaignProduct['product_id'] === $vProduct['id']) {
                                    $dataModel[] = [
                                        'number'               => ((isset($dataModel)? count($dataModel):0 ) + 1) + $startRecord,
                                        'id'                   => $vCampaignProduct['id'],
                                        'priority'             => $vCampaignProduct['priority'],
                                        'delete'               => $vCampaignProduct['id'],

                                        'Product_id'           => $vProduct['id'],
                                        'item_id'              => $vProduct['item_id'],
                                        'name_th'              => $vProduct['name']['th'],
                                        'name_en'              => $vProduct['name']['en'],
                                        'normal_price'         => number_format((float)$vProduct['normal_price'], 2, '.', ','),
                                        'approve_status'       => $vProduct['approve_status'],
                                        'startDateTimestamp'   => isset($campaign['start_date'])?strtotime($campaign['start_date']):'',
                                        'endDateTimestamp'     => isset($campaign['end_date'])?strtotime($campaign['end_date']):'',
                                        'currentDateTimestamp' => strtotime(date('Y-m-d H:i:s')),
                                        'campaignStatus'       => isset($campaign['status'])?$campaign['status']:''
                                    ];
                                    continue;
                                }
                            }
                        }
                    }
                }
            }

        } else {

            // get product id list by campaign id
            $product_ids = $this->getProductIdListByCampaignId($campaign_id);

            // Filter Category
            $ids_by_category = $this->filterCategory($inputs);

            if ($ids_by_category !== false) {
                $ids_by_category = array_intersect($product_ids, $ids_by_category);
                $product_ids = !empty($ids_by_category) ? $ids_by_category : false;
            }

            // check product
            if ($product_ids !== false) {
                if (!empty($product_ids)) {
                    $inputs['ids'] = $product_ids;
                }

                // get product
                $result = $this->productRepository->getProducts($inputs);

                if(isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data']['records'])){
                    $totalRecord = $result['data']['pagination']['total_records'];
                    $products    = $result['data']['records'];
                    // get priority by product id list
                    $getCampaignProductParams['product_ids'] = array_column($products, 'id');
                    $getCampaignProductParams['length']      = isset($inputs['length']) ? $inputs['length'] : 10;
                    $result = $this->doGetCampaignProducts($campaign_id, $getCampaignProductParams);

                    if (isset($result['status']['code']) && $result['status']['code'] == 200 && !empty($result['data']['records'])) {

                        $campaign_products = $result['data']['records'];

                        foreach ($products as $vProduct) {

                            foreach ($campaign_products as $vCampaignProduct) {

                                if ($vProduct['id'] == $vCampaignProduct['product_id']) {
                                    $dataModel[] = [
                                        'number'               => ((isset($dataModel)? count($dataModel):0 ) + 1) + $startRecord,
                                        'id'                   => $vCampaignProduct['id'],
                                        'priority'             => $vCampaignProduct['priority'],
                                        'delete'               => $vCampaignProduct['id'],

                                        'Product_id'           => $vProduct['id'],
                                        'item_id'              => $vProduct['item_id'],
                                        'name_th'              => $vProduct['name']['th'],
                                        'name_en'              => $vProduct['name']['en'],
                                        'normal_price'         => number_format((float)$vProduct['normal_price'], 2, '.', ','),
                                        'approve_status'       => $vProduct['approve_status'],
                                        'startDateTimestamp'   => isset($campaign['start_date'])?strtotime($campaign['start_date']):'',
                                        'endDateTimestamp'     => isset($campaign['end_date'])?strtotime($campaign['end_date']):'',
                                        'currentDateTimestamp' => strtotime(date('Y-m-d H:i:s')),
                                        'campaignStatus'       => isset($campaign['status'])?$campaign['status']:''
                                    ];
                                    continue;
                                }
                            }
                        }
                    }
                }
            }
        }

        $output = [
            'draw'            => $inputs['draw'],
            'recordsTotal'    => count($products),
            'recordsFiltered' => $totalRecord,
            'data'            => $dataModel,
            'input'           => $inputs
        ];

        return json_encode($output);
    }

    public function doGetCampaignProducts($id, $inputs = [])
    {
        $url = $this->url . 'searchcampaignproducts/' . $id;

        $options = [
            'json' => [
                'limit'  => isset($inputs['length']) ? $inputs['length'] : 10,
                'offset' => isset($inputs['start'])  ? $inputs['start']  : 0,
            ]
        ];

        if(isset($inputs['order'])) {
            $options['json']['order'] = $inputs['order'];
        } else{
            $options['json']['order'] = 'priority|asc';
        }
        if (!empty($inputs['product_ids'])) {
            $options['json']['product_ids'] = implode(",",$inputs['product_ids']);
        }

        return $this->guzzle->curl('POST', $url, $options);
    }

    public function getMappingProductList($inputs)
    {
        $output = [
            'draw' => $inputs['draw'],
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'input' => $inputs
        ];

        if ($inputs['category']['product'] != 'undefine' || $inputs['category']['business'] != 'undefine') {
            $products_id = [];
            $business_id = [];
            if ($inputs['category']['product'] != 'undefine') {
                // Get Content Category (Type = Product)
                $category_id = $inputs['category']['product'];
                $products_id = $this->getProductIdsByCategory($category_id);
            }
            if ($inputs['category']['business'] != 'undefine') {
                // Get Content Category (Type = Business)
                $category_id = $inputs['category']['business'];
                $business_id = $this->getBusinessIdsByCategory($category_id);
            }

            if (empty($products_id) && empty($business_id)) {
                return json_encode($output);
            }

            $ids = array_merge($products_id, $business_id);

            $inputs['ids'] = $ids;

            // Get product by id
            $result = $this->productRepository->getProducts($inputs);

            $products = $result['data']['records'];

            $dataModel = $this->setDataForTable($products, $inputs['start']);
            $output = [
                'draw' => $inputs['draw'],
                'recordsTotal' => $result['data']['pagination']['total_records'],
                'recordsFiltered' => count($products),
                'data' => $dataModel,
                'input' => $inputs
            ];

            return json_encode($output);

        } else {
            $result = $this->productRepository->getProducts($inputs);

            $products = $result['data']['records'];
            $dataModel = $this->setDataForTable($products, $inputs['start']);

            $output = [
                'draw' => $inputs['draw'],
                'recordsTotal' => count($products),
                'recordsFiltered' => $result['data']['pagination']['total_records'],
                'data' => $dataModel,
                'input' => $inputs
            ];

            return json_encode($output);
        }
    }

    public function addProductCampaign($id, $inputs)
    {
        $url = $this->url . 'campaignproducts/' . $id;
        $options = [
            'json' => $inputs
        ];
        $result = $this->guzzle->curl('POST', $url, $options);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }

    public function deleteProductCampaign($product_campaign_id)
    {
        $url = $this->url . 'campaignproducts/' . $product_campaign_id;

        $result = $this->guzzle->curl('DELETE', $url, []);

        if (isset($result['status']['code']) && $result['status']['code'] == 200) {
            return array('status' => true);
        } else {
            return array('status' => false, 'messages' => $result['error']['message']);
        }
    }

    public function updateCampaignProductPriority($id, $inputs)
    {
        $options = [
            'json' => $inputs
        ];

        $url = $this->url . 'campaigns/' . $id. '/product/priority';
        $result = $this->guzzle->curl('PUT', $url, $options);

        return $result;
    }
    /**
     * Method for curl api campaign report
     */
    public function getDataCampaignReport(array $inputs)
    {
        $params['length'] = '9999';

        $language = App::getLocale();

        $fields = ['campaign_code'];
        foreach (config("language.campaign") as $lang) {
            $fields[] = 'name.' . $lang;
        }


         $params = [
            'query' => [
                'offset' => $inputs['start'],
                'limit' => $inputs['length'],
                'column' => $inputs['order'][0]['column'],
                'dir' => $inputs['order'][0]['dir'],
                'date' => isset($inputs['launch_date_input']) ? convertDateTime($inputs['launch_date_input'], 'd/m/Y', 'Y-m-d') : '',
                'text' => isset($inputs['search_text_input']) ? $inputs['search_text_input'] : '',
                'fields' => implode(',', $fields)
            ]
        ];
        $url = $this->url . 'campaigns';
        $result = $this->guzzle->curl('GET', $url, $params);


        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $campaigns = $result['data']['records'];

            return Excel::create('campaign_report_' . date('YmdHis'), function($excel) use ($campaigns) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Campaign', function($sheet) use ($campaigns) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');
                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Campaign Code',
                        'Campaign Name (TH)',
                        'Campaign Name (EN)',
                        'Create Date',
                        'Start Date',
                        'End Date',
                        'Published'
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($campaigns as $kData => $vData) {
                        ++$row;

                        $data = [
                            $kData + 1,
                            $this->_unit->removeFirstInjection(array_get($vData, 'campaign_code', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.th', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'name.en', '')),
                            empty($vData['created_at'])? '' : convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                            empty($vData['start_date'])? '' : convertDateTime($vData['start_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                            empty($vData['end_date'])? '' : convertDateTime($vData['end_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                            (array_get($vData, 'status', '')=='active'?'Publish':'Unpublish')
                        ];

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }

        return false;
    }


}
