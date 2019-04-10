<?php

namespace App\Http\Controllers;

use App\Events\ProductUpdated;
use App\Repositories\CampaignRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\PositionsRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignRequest;
use App\Http\Requests\CampaignUpdateRequest;
use Response;
use DateTime;
use App;

class CampaignController extends \App\Http\Controllers\BaseController
{
    public $position = [
        'page' => 'campaign',
        'title' => 'Campaign'
    ];

    protected $redirect = [
		'login' => '/',
		'index' => 'campaign'
	];

    protected $view = [
        'index' => 'campaign.index',
        'show' => 'campaign.show',
        'product' => 'campaign.product.index',
        'edit' => 'campaign.edit'
    ];

    public function __construct(CampaignRepository $campaignRepository, CategoryRepository $categoryRepository, TagRepository $tagRepository, PositionsRepository $PositionsRepository)
    {
        parent::__construct();
        $this->messages = config('message');
        $this->campaignRepository = $campaignRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->positionsRepository = $PositionsRepository;
    }

    public function index()
    {
        return view($this->view['index']);
    }

    public function create()
    {
        $promotionData = $this->campaignRepository->getPromotionData();
        $ribbonData = $this->campaignRepository->getRibbonData();
        return view($this->view['edit'], [
            'language' => config('language.campaign'),
            'ribbonData' => $ribbonData,
            'promotionData' => $promotionData,
            'slug_input_name' => 'name_en'
        ]);
    }

    public function store(CampaignRequest $request)
    {
        //get data input
        $inputs = $request->input();

        if ($request->hasFile('thumb')) {
            $result = $this->campaignRepository->uploadImage('thumb');
            $inputs['bannerA'] = $result['image'];
        }

        if ($request->hasFile('thumb2')) {
            $result = $this->campaignRepository->uploadImage('thumb2');
            $inputs['bannerB'] = $result['image'];
        }
        
        $inputs['status'] = isset($inputs['status']) ? 'active' : 'inactive';

        $tag = [];
        $inputs['seo_subject'] = $this->setDefaultSEO_Subject($inputs['seo_subject'], $inputs['name_en'], $inputs['name_th']);
        $inputs['seo_explanation'] = $this->setDefaultSEO_Explanation($inputs['seo_explanation'], $inputs['description_en'], $inputs['description_th']);

        foreach (config("language.campaign") as $language) {
            if (!empty($inputs["tag_name_$language"])) {
                $tag[$language] = $inputs["tag_name_$language"];
            }
            unset($inputs["tag_name_$language"]);
        }

        $inputs['start_date'] = convertDateTime($inputs['start_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        $inputs['end_date'] = convertDateTime($inputs['end_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');        

        $campaignResult = $this->campaignRepository->createCampaign($inputs);

        if ($campaignResult['status'] == false) {
            return $campaignResult;
        }

        if (!empty($tag)) {
            $params = [];
            $params['content_id'] = $campaignResult['campaignId'];
            $params['content_type'] = 'campaign';
            foreach (config("language.campaign") as $language) {
                if (!empty($tag[$language])) {
                    $params['name_'.$language] = $tag[$language];
                }
            }
            $result = $this->tagRepository->createTag($params);

            if ($result['status']['code'] == '200') {
                return array("status" => true);
            } else {
                return array("status" => false);
            }
        }

        return $campaignResult;
    }

    public function show($id)
    {
        $language = App::getLocale();
        $campaign = $this->campaignRepository->getCampaign($id);
        $campaign['startDateTimestamp'] = strtotime($campaign['start_date']);
        $campaign['endDateTimestamp'] = strtotime($campaign['end_date']);
        $campaign['currentDateTimestamp'] = strtotime(date('Y-m-d H:i:s'));
      
        // Product Category List
        $type = 'product';
        $productCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);

        // Business Category List
        $type = 'business';
        $businessCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);

        //dd($productCategoryList);

        return view($this->view['show'], [
            'campaign_id'          => $id,
            'campaign_name'        => $campaign['name'][$language],
            'campaign'             => $campaign,
            'businessCategoryList' => $businessCategoryList,
            'productCategoryList'  => $productCategoryList
        ]);
    }

    public function edit($id)
    {
        $promotionData = $this->campaignRepository->getPromotionData();
        $ribbonData    = $this->campaignRepository->getRibbonData();
        $campaignData  = $this->campaignRepository->getCampaign($id);
        $tagData       = $this->tagRepository->getTags($id);

        return view($this->view['edit'], [
            'language'        => config('language.campaign'),
            'ribbonData'      => $ribbonData,
            'campaignId'      => $id,
            'campaignData'    => $campaignData,
            'promotionData'   => $promotionData,
            'tags'            => $tagData,
            'slug_input_name' => 'name_en'
        ]);
    }

    public function update(CampaignUpdateRequest $request, $id)
    {
        $inputs = $request->input();

        if ($request->hasFile('thumb')) {
            $result = $this->campaignRepository->uploadImage('thumb');
            $inputs['bannerA'] = $result['image'];
        } else {
            $inputs['bannerA'] = $inputs['thumb_old'];
        }

        if ($request->hasFile('thumb2')) {
            $result = $this->campaignRepository->uploadImage('thumb2');
            $inputs['bannerB'] = $result['image'];
        } else {
            $inputs['bannerB'] = $inputs['thumb_old2'];
        }

        if (isset($inputs['status'])) {
            $inputs['status'] = 'active';
        } else {
            $inputs['status'] = 'inactive';
        }

        $inputs['seo_subject'] = $this->setDefaultSEO_Subject($inputs['seo_subject'], $inputs['name_en'], $inputs['name_th']);
        $inputs['seo_explanation'] = $this->setDefaultSEO_Explanation($inputs['seo_explanation'], $inputs['description_en'], $inputs['description_th']);

        $inputs['start_date'] = convertDateTime($inputs['start_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        $inputs['end_date'] = convertDateTime($inputs['end_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');

        $params = [];
        $params['content_id'] = $id;
        $params['content_type'] = 'campaign';

        $isEmptyTag = true;
        foreach (config('language.campaign') as $language) {
            if(!empty($inputs["tag"][$language])){
                $params['name_'.$language] = $inputs["tag"][$language];
                $isEmptyTag = false;
            }
        }

        if (empty($inputs["tag_id"]) && !$isEmptyTag) {
            // Create tag
            $result = $this->tagRepository->createTag($params);

        } else if (!empty($inputs["tag_id"]) && !$isEmptyTag) {
            // Update tag
            $result = $this->tagRepository->updateTag($inputs["tag_id"], $params);

        } else if (!empty($inputs["tag_id"]) && $isEmptyTag) {
            // Delete tag
            $tagResult = $this->tagRepository->deleteTag($inputs["tag_id"]);
        }
        
        return $this->campaignRepository->updateCampaign($id, $inputs);
    }

    public function updateStatus(Request $request)
    {
        $param = $request->input();
        $ids = implode(",",$param['user_group_ids']);
        return $this->campaignRepository->updateStatusCampaign($ids, $param['status']);
    }

    public function destroy(Request $request, $ids)
    {
        return $this->campaignRepository->deleteCampaign($ids);
    }

    public function getProducts(Request $request, $id)
    {
        $param = $request->input();
        return $this->campaignRepository->getCampaignProducts($id, $param);
    }

    public function addProducts($id)
    {

        $campaign = $this->campaignRepository->getCampaign($id);

        // Product Category List
        $type = 'product';
        $productCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);

        // Business Category List
        $type = 'business';
        $businessCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);

        return view($this->view['product'], [
            'campaign_id' => $id,
            'campaign'             => $campaign,
            'businessCategoryList' => $businessCategoryList,
            'productCategoryList' => $productCategoryList,
        ]);
    }

    public function deleteProducts($id, $campaign_product_ids)
    {
        $campaign_products = $this->campaignRepository->doGetCampaignProducts($id)['data']['records'];
        $product_ids = [];

        $campaign_product_ids = explode(',', $campaign_product_ids);

        foreach ($campaign_product_ids as $campaign_product_id) {
            $result = $this->campaignRepository->deleteProductCampaign($campaign_product_id);

            foreach ($campaign_products as $campaign_product) {
                if ($campaign_product['id'] == $campaign_product_id) {
                    $product_ids[] = $campaign_product['product_id'];
                }
            }
        }

        event(new ProductUpdated(implode(',', $product_ids)));

        return $result;
    }

    public function getData(Request $request)
    {
        $param = $request->input();
        return $this->campaignRepository->getCampaigns($param);
    }

    public function getProductsData(Request $request, $id)
    {
        $params = $request->input();
        $output = $this->campaignRepository->getMappingProductList($params);
        return $output;
    }

    public function addProductToCampaign(Request $request, $id)
    {
        $param = $request->input();
 
        if(is_array($param['product_id'])){
            $product_ids = $param['product_id'];
        }else if(!is_array($param['product_id'])){
            $product_ids = explode(',', $param['product_id']);
        }

        $params = [];

        foreach ($product_ids as $product_id) {
            if($product_id!="") {
                array_push($params, [
                    'product_id' => $product_id
                ]);
            }
        }

        $output = $this->campaignRepository->addProductCampaign($id, $params);

        event(new ProductUpdated(implode(',', $product_ids)));

        return $output;
    }

    public function updatePriorityCampaign(Request $request, $id)
    {
        $params = $request->input();
        $data = [];
        
        foreach($params['priority'] as $key => $val){
            if($val != $params['priority_old'][$key]){
                $data['data'][] = [
                    'product_id' => $key,
                    'priority' => $val
                    ];
            }
        }

        $result = $this->campaignRepository->updateCampaignProductPriority($id, $data);

        $output = [
            'status' => false,
            'message' => 'update error'
        ];

        if(isset($result['status'])&&$result['status']['code']==200) {
            $output = [
                'success' => $result['data']['success'],
                'errors' => $result['data']['errors']
            ];
        }

        return $output;
    }
    /**
	 * Method for report excel
	 */
	public function exportCampaigns(Request $request)
	{
		$result = $this->campaignRepository->getDataCampaignReport($request->input());

		if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);

			return redirect($this->redirect['index']);
		}
	}

}
?>
