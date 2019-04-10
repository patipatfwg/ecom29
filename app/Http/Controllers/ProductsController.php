<?php
namespace App\Http\Controllers;

use App\Events\ProductUpdated;
use App\Repositories\ProductRepository;
use App\Repositories\StoreRepository;
use App\Repositories\TagRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AttributeRepository;
use Satung\SatungRounding;
use Illuminate\Http\Request;
use Response;
use Validator;
use Input;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductsController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'product'
    ];

    protected $view = [
        'index' => 'product.index',
        'edit' => 'product.edit',
        'approve' => 'product.approve'
    ];

    public function __construct(TagRepository $tagRepository,StoreRepository $storeRepository,AttributeRepository $attributeRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->messages             = config('message');
        $this->productRepository    = $productRepository;
        $this->categoryRepository   = $categoryRepository;
        $this->attributeRepository  = $attributeRepository;
        $this->storeRepository      = $storeRepository;
        $this->tagRepository        = $tagRepository;
    }

    /**
     * Method for any index
     */

    protected function filterProductId($data)
    {
        $output = [];
        $output = array_filter($data, function ($content) {
            return $content['content_type'] == 'product' ? true : false;
        });
        
        return array_column($output, 'content_id');
    }
	
    protected function isNeedUpdateProduct($params)
    {
        $product_fields = [
            'name', 
            'description', 
            'seo_subject', 
            'seo_explanation', 
            'start_date', 
            'end_date',
            'sub_makro_unit',
            'unit_type',
            'makro_unit',
            'suggest_price',
            'profit_per_unit',
            'total_profit',
            'height',
            'width',
            'length',
            'lwh_uom',
            'weight_uom',
            'images',
            'minimum_order_limit',
            'maximum_order_limit'
        ];

        $productIntermediateData_old = json_decode(base64_decode($params['product_intermediate_data_old']), true);

        unset($params['product_intermediate_data_old']);

        $productIntermediateData_old['description'] = $params['description_old'];
        unset($params['description_old']);

        $productIntermediateData_old['images'] = $productIntermediateData_old['image'];
        unset($productIntermediateData_old['image']);

        $productIntermediateData_old['start_date'] = empty($productIntermediateData_old['published']['started_date'])? '' : convertDateTime($productIntermediateData_old['published']['started_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s');
        $productIntermediateData_old['end_date'] = empty($productIntermediateData_old['published']['end_date'])? '' : convertDateTime($productIntermediateData_old['published']['end_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s');  
        unset($productIntermediateData_old['published']);
        
        $productIntermediateData_old['seo_subject'] = empty($productIntermediateData_old['seo']['title'])? '' : $productIntermediateData_old['seo']['title'];
        $productIntermediateData_old['seo_explanation'] = empty($productIntermediateData_old['seo']['description'])? '' : $productIntermediateData_old['seo']['description'];
        unset($productIntermediateData_old['seo']);

        $keys = array_keys($params);
        foreach($keys as $key){
            if(in_array($key, $product_fields)){
                if($key == 'unit_type' || $key == 'lwh_uom' || $key == 'weight_uom'){
                    $params[$key] = json_decode($params[$key], true);
                }
            }
            else{
                unset($params[$key]);
            }
        }
        $keys = array_keys($productIntermediateData_old);
        foreach($keys as $key){
            if(in_array($key, $product_fields)){
               
            }
            else{
                unset($productIntermediateData_old[$key]);
            }
        }

        $isDataChanged = false;
        foreach($params as $key => $value){
            if($params[$key] != $productIntermediateData_old[$key]){
                $isDataChanged = true;
                break;
            }
        }

        return $isDataChanged;
    }

    protected function isUpdateHaveCategories($params)
    {
        $productIntermediateData_old = json_decode($params['product_intermediate_data_old'], true);

        $haveCategories_old = $productIntermediateData_old['have_categories'];
        $haveCategories_new = 'N';
        if(isset($params['productCategory_id']) && count($params['productCategory_id']) > 0){
            $haveCategories_new = 'Y';
        }

        return ($haveCategories_new != $haveCategories_old)? true : false;
    }

    protected function updateBindingCategory($params, $id, $type, &$isNeedSyncToSolr)
    {
        $old_key = sprintf('old_%s_category', $type);
        $new_key = sprintf('%sCategory_id', $type);

        if($type == 'brand'){
            $old_key = sprintf('old_%s_id', $type);
            $new_key = sprintf('%s_id', $type);
        }

        $newCategory = $oldCategory = [];
        if(isset($params[$old_key]) && !empty($params[$old_key])){
            $oldCategory = explode(',', $params[$old_key]);
        }

        if(isset($params[$new_key]) && !empty($params[$new_key])){
            $newCategory = $params[$new_key];

            if($type == 'brand'){
                $newCategory = explode(',', $params[$new_key]);
            }
        }

        if($oldCategory != $newCategory) {

            // Delete Old Category
            foreach($oldCategory as $category_id){
                $this->categoryRepository->deleteContentCategoryByCategoryId($category_id, $id, 'product');
            }

            // Add New Category
            foreach($newCategory as $category_id) {
                if(empty($category_id)) {
                    continue;
                }

                $productCategoryResult = $this->categoryRepository->addContentCategory([
                    'category_id' => $category_id,
                    'content_type' => 'product',
                    'content_id' => $id
                ]);
            }

            // Need Sync Product to Solr
            if(!empty($newCategory)){
                $isNeedSyncToSolr = true;
            }
        }
    }

    protected function updateBindingAttribute($params, $id, &$isNeedSyncToSolr)
    {
        if(!isset($params['attribute_id'])) {
            $params['attribute_id'] = [];
        }
        
        $saveData = [
            'content_id' => $id, 
            'content_type' => 'product', 
            'attribute' => []
        ];
            
        foreach($params['attribute_id'] as $attribute_id){
            if(isset( $params['attribute_value'][$attribute_id]))
            {
                $saveData['attribute'][] = [
                    'attribute_id' => $attribute_id,
                    'sub_attribute_id' => $params['attribute_value'][$attribute_id]
                ];
            }     
        }

        $this->attributeRepository->addAttributeContent($saveData);

        $isNeedSyncToSolr = true;
    }

    protected function updateBindingTag($params, $id)
    {
        $isEmptyTag = true;

        $tagParams = [];
        $tagParams['content_id'] = $id;
        $tagParams['content_type'] = 'product';

        $languages = config('language.product');
        foreach ($languages as $language){
            if(!empty($params['tags'][$language])){
                $tagParams['name_' . $language] = $params['tags'][$language];
                $isEmptyTag = false;
            }
        }

        if(empty($params['tag_id']) && !$isEmptyTag){
            // Create tag
            $result = $this->tagRepository->createTag($tagParams);
        }
        else if(!empty($params['tag_id']) && !$isEmptyTag){
            // Update tag
            $result = $this->tagRepository->updateTag($params['tag_id'], $tagParams);				
        }
        else if(!empty($params['tag_id']) && $isEmptyTag){
            // Delete tag
            $tagId = $params['tag_id'];
            $result = $this->tagRepository->deleteTag($tagId);
        }
    }

    public function anyData(Request $request)
    {
        $inputs = $request->input();

        $product_ids = [];

        $isSelectedCategory = false;
        
        if(isset($inputs['category']) && !empty($inputs['category'])){

            foreach($inputs['category'] as $key => $value){

                // Empty data
                if(empty($value) || $value == 'undefine'){
                    continue;
                }

                // Has selected category
                $isSelectedCategory = true;

                $result = $this->categoryRepository->getContentsByCategory(array($value));
                if (isset($result['status']['code']) && $result['status']['code'] == 200) {
                    $contents = $result['data'][0]['contents'];
                    $ids = $this->filterProductId($contents);
                    $product_ids = array_merge($product_ids, $ids);
                }
            }

        }

        // Selected category and no product
        if($isSelectedCategory && empty($product_ids)){
            return [
                'draw'            => $inputs['draw'],
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => array(),
                'input'           => $inputs
            ];
        }

        // Filter by product id in category
        $inputs['ids'] = $product_ids;

        return $this->productRepository->getProductList($inputs);
    }

    /**
     * save status approve, buyer, show && hide && delete
     */
    public function postStatus(Request $request)
    {
        $params = $request->input();
        $params['product_ids'] = array_unique($params['product_ids']);
        $result = $this->productRepository->setStatusProduct($params);

        // Sync Product To Solar Search
        if (!empty($params['product_ids'])) {
            if($params['status'] == 'approved' || $params['status'] == 'inactive'){
                event(new ProductUpdated(implode(',', $params['product_ids'])));
            }
        }

        if($result['status']['code'] == 200){
            return Response::json(['status' => true, 'data' => $result['data']]);
        }
        else{
            return Response::json(['status' => false, 'messages' => $result['errors']['message']]);
        }
    }

    /**
     * save priority
     */
    public function postPriority(Request $request)
    {   
        $params = $request->input();
        $result = $this->productRepository->putUpdatePriority($params);

        if($result['status']['code'] == 200){
            // Sync product to solar after updated
            // $product_ids = array_column($params, 'product_id');
            // foreach ($product_ids as $product_id) {
            //     event(new ProductUpdated($product_id));
            // }
            return Response::json(['status' => true, 'data' => $result['data']]);
        }
        else{
            return Response::json(['status' => false, 'messages' => $result['errors']['message']]);
        }
    }

    /**
     * sync to search engine
     */
    public function postSyncSearch()
    {
        $synced_product_ids = [];
        $offset = 0;
        $limit = 1000;
        $total_records = PHP_INT_MAX;

        while ($offset < $total_records) {
            $params = [
                'start' => $offset,
                'length' => $limit
            ];

            $products = $this->productRepository->getProducts($params);
            $total_records = $products['data']['pagination']['total_records'];

            $offset += $limit;

            foreach ($products['data']['records'] as $product) {
                $synced_product_ids[] = $product['id'];
            }
        }
        
        event(new ProductUpdated(implode(',', $synced_product_ids)));

        return $synced_product_ids;
    }

    /**
     * page index
     */
    public function index()
    {   
        // Approve Status
        $approve_status = [
            'approved' => 'Approved', 
            'ready to approve' => 'Ready to approve', 
            'editing' => 'Editing'
        ];

        // Publish Status
        $publish_status = [
            'Y' => 'Published',
            'N' => 'Unpublished'
        ];

        // Product Category List
        $type = 'product';
        $productCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);

        // Business Category List
        $type = 'business';
        $businessCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);
        
        $stores = [];

        $getStoreParams  = [
            'limit' => 9999,
            'offset' => 0,
            'order' => 'makro_store_id|ASC',
        ];

        $storeResult = $this->storeRepository->getStores($getStoreParams);

        if(isset($storeResult['data']['records'])&&count($storeResult['data']['records'])>0) {
            foreach ($storeResult['data']['records'] as $store) {
                $stores[$store['makro_store_id']] = $store['name']['th'] . " (" . $store['makro_store_id'] . ")";
            }
        }

        return view($this->view['index'],[
            'product_categories' => $productCategoryList,
            'business_categories' => $businessCategoryList,
            'publish_status' => $publish_status,
            'approve_status' => $approve_status,
            'stores' => $stores
        ]);
    }
    /**
     * Method for post update
     */
    public function putApprove(Request $request,$id)
    {
        $params = Input::all();
        if($params['setStatus'] == 'reject') {
            $result = $this->productRepository->putUpdateStatus($id, $params['setStatus'], $params['rejectReason']);
            return Response::json(array('status' => true , 'messages' => 'Success'));
        } else if ($params['setStatus'] == 'approved'){
            $result = $this->productRepository->putUpdateStatus($id,$params['setStatus']);

            // Sync Product To Solar Search
            event(new ProductUpdated($id));

            return Response::json(array('status' => true , 'messages' => 'Success'));
        }
    }

    public function update(ProductRequest $request, $id)
    {
        $params = Input::all();

        $isNeedSyncToSolr = false;

        // Update Product Status
        if($params['setStatus'] == 'ready') {
            $result = $this->productRepository->putUpdateStatus($id,$params['setStatus']);

            if(isset($result['errors'])) {
                return Response::json(array('status' => false , 'messages' => $result['errors']['message']));
            }
            return Response::json(array('status' => true , 'messages' => ''));
        }

        if(!isset($params['images'])) {
            $params['images'] = [];
        }

        $params['seo_subject'] = $params['seo_subject-productIntermediate'];
        $params['seo_explanation'] = $params['seo_explanation-productIntermediate'];
        unset($params['seo_subject-productIntermediate']);
        unset($params['seo_explanation-productIntermediate']);

        // Update Product Data
        if($this->isNeedUpdateProduct($params)){
            $result = $this->productRepository->putUpdateProduct($id, $params);
            if(isset($result['status']['code']) && $result['status']['code'] != 200){
                $msg = !empty($result['errors']['message'])? $result['errors']['message'] : '';
                return Response::json(['status' => false, 'error' => $msg]);
            }
        }
        // Update Have Categories
        else if($this->isUpdateHaveCategories($params)){
            $result = $this->productRepository->putUpdateProduct($id, $params, true);
            if(isset($result['status']['code']) && $result['status']['code'] != 200){
                $msg = !empty($result['errors']['message'])? $result['errors']['message'] : '';
                return Response::json(['status' => false, 'error' => $msg]);
            }
        }

        // Update Binding Tag
        $this->updateBindingTag($params, $id);

        // Update Binding Category
        $this->updateBindingCategory($params, $id, 'product', $isNeedSyncToSolr);
        $this->updateBindingCategory($params, $id, 'business', $isNeedSyncToSolr);

        // Check value brand_id
        if (!empty($params['brand_id'])) {
            $params['brand_id'] = ($params['brand_id'] != 'NULL' && $params['brand_id'] != 'null') ? $params['brand_id'] : '';
        }
        $this->updateBindingCategory($params, $id, 'brand', $isNeedSyncToSolr);

        // Update Binding Category
        $this->updateBindingAttribute($params, $id, $isNeedSyncToSolr);

        // Product Published Status
        if(isset($params['status']) && $params['published_status'] == 'N'){
            $statusResult = $this->productRepository->putUpdateStatus($id, 'active');
        }
        else if(!isset($params['status']) && $params['published_status'] == 'Y'){
            $statusResult = $this->productRepository->putUpdateStatus($id, 'inactive');
            $isNeedSyncToSolr = true;
        }

        // Sync Product To Solr Search
        if($isNeedSyncToSolr){
            event(new ProductUpdated($id));
        }

        return Response::json(array('status' => true , 'messages' => ''));
    }

    /**
     * Method for post create
     */
    protected function flattenArray($array)
    {
        $output = [];
        foreach ($array as $record) {
            $output[] = $record;
            if (isset($record['children'])) {
                $output = array_merge($output, $this->flattenArray($record['children']));
            }
        }
        return $output;
    }
    public function getStorePrice()
    {
        $params = Input::all();
        $result = $this->getStorePriceData($params);
        return $result;
    }

    protected function getStorePriceData($params)
    {
        $getStoreParams  = [
            'limit' => $params['limit'],
            'offset' => $params['offset'],
            'order' => 'makro_store_id|ASC',
        ];
        $storeResult = $this->storeRepository->getStores($getStoreParams);
        $result = [
            'pagination' => $getStoreParams,
            'data' => []
        ];
        $result['pagination']['total_records'] = isset($storeResult['data']['pagination']['total_records'])? $storeResult['data']['pagination']['total_records']: 0;
        if(isset($storeResult['data']['records'])&&count($storeResult['data']['records'])>0) {
            $item_id = $params['item_ids'];
            $storePriceResult = $this->productRepository->getStorePrice($params['item_ids']);
            $productResult = $this->productRepository->getProductIntermediate($params['item_ids']);
            if ($productResult['status']['code'] == 200) {
                $productIntermediateData = $productResult['data']['records'][0];
                $this->decodeProductData($productIntermediateData);
                if(!isset($productIntermediateData['last_update_status']))
                {
                    $productIntermediateData['last_update_status'] = '';
                }
            }
            if(!isset($storePriceResult['data']))
            {
                $storePriceResult['data']['records'] = [];
            }
            foreach($storeResult['data']['records'] as $store)
            {
                $storePrice = false;
                $promotionPrice = false;
                foreach($storePriceResult['data']['records'] as $productStore)
                {
                    if($store['makro_store_id']==$productStore['makro_store_id']) {
                        $storePrice = $productStore['store_price'];
                        $promotionPrice = $productStore['store_promo_retail'];
                        //unset($productStore);
                        break;
                    }
                }
                $result['data'][] = array_merge($store,[
                    'store_price' => $storePrice,
                    'promotion_price' => $promotionPrice
                ]);

            }
            foreach($result['data'] as $key => $value) {
                if($value['store_price'] !== false){
                    $result['data'][$key]['store_price_vat_rate'] = SatungRounding::calculate($value['store_price'], $productIntermediateData['vat_rate'], 1);
                }
               
                if($value['promotion_price'] !== false){
                    $result['data'][$key]['promotion_price_vat_rate'] = SatungRounding::calculate($value['promotion_price'], $productIntermediateData['vat_rate'], 1);
                }
            }
        }

        return $result;
    }

    public function searchProducts(Request $request) 
    {
        $params = Input::all();
        $query = [
            'fields' => 'name.th,name.en,item_id',
            'name' => $params['query'],
            'offset' => 0,
            'limit' => 100,
            'order' => 'item_id|desc'
        ];
        
        $result = $this->productRepository->getProducts($query);
        
        $product_list = [];
        $count = 0;
        $json = [];
        
        foreach($result['data']['records'] as $val) {
            $product_list[$count]['item_id'] = $val['item_id'];
            $product_list[$count]['name_th'] = $val['name']['th'];
            $product_list[$count]['name_en'] = $val['name']['en'];

            $json[] = [
                'id' => $val['id'],
                'name' => '['.$val['item_id'].'] '.$val['name']['th'].' '.$val['name']['en']
            ];
            $count++;
        }
        return response()->json($json);
    }

    public function edit($id, Request $request)
    {
        $data = [];

        $params = [
            'action_status' => 'RMS No Update',
            'action_status_datetime' => 'set'
        ];

        // Update Action Status
        $this->productRepository->putUpdateProductActionStatus($id, $params);

        $productIntermediateData = [
            'sub_makro_unit' => '',
            'makro_unit' => '',
            'suggest_price' => '',
            'profit_per_unit' => '',
            'total_profit' => '',
            'unit_type' => [
                'id' => '',
                'short_name' => ''
            ],
            'item_id' => '',
            'original_name' => [
                'th' => ''
            ],
            'normal_price' => '',

            'last_flag' => '',
            'published' => [
                'start_date' => '',
                'end_date' => ''
            ],

            'name' => [
                'th' => '',
                'en' => ''
            ],
            'description' => [
                'th' => '',
                'en' => ''
            ],
            'last_update_status' => '',
            'height' => '',
            'width' => '',
            'length' => '',
            'lwh_uom' => [
                'id' => ''
            ],
            'weight_uom' => [
                'id' => ''
            ],
            'weight' => '',
            'approve_status' => '',
            'image' => [],
            'location' => '',
            'published_status' => '',
            'cost_currency' => '',
            'seo_subject' => '',
            'seo_explanation' => '',
            'rms_status' => ''
        ];

        $productOnlineData = [
            'sub_makro_unit' => '',
            'makro_unit' => '',
            'suggest_price' => '',
            'profit_per_unit' => '',
            'total_profit' => '',
            'unit_type' => [
                'id' => '',
                'short_name' => ''
            ],
            'item_id' => '',
            'original_name' => [
                'th' => ''
            ],
            'normal_price' => '',

            'last_flag' => '',
            'published' => [
                'start_date' => '',
                'end_date' => ''
            ],
            'name' => [
                'th' => '',
                'en' => ''
            ],
            'description' => [
                'th' => '',
                'en' => ''
            ],
            'last_update_status' => '',
            'height' => '',
            'width' => '',
            'length' => '',
            'lwh_uom' => [
                'id' => ''
            ],
            'weight_uom' => [
                'id' => ''
            ],
            'weight' => '',
            'approve_status' => '',
            'image' => [],
            'location' => '',
            'published_status' => '',
            'cost_currency' => '',
            'seo_subject' => '',
            'seo_explanation' => '',
            'rms_status' => ''
        ];

        //default data
        $unitType  = '';
        $lwhUom    = '';
        $weightUom = '';

        start_measure('product', 'Product');
        $resultProductOnline = $this->productRepository->getProduct($id);
        if ($resultProductOnline['status']['code'] == 200) {
            $productOnlineData = $resultProductOnline['data']['records'][0];
            $this->decodeProductData($productOnlineData);
            if(!isset($productOnlineData['last_update_status']))
            {
                $productOnlineData['last_update_status'] = '';
            }
        }

        $resultProductIntermediate = $this->productRepository->getProductIntermediate($id);
        if ($resultProductIntermediate['status']['code'] == 200) {
            $productIntermediateData = $resultProductIntermediate['data']['records'][0];
            $this->decodeProductData($productIntermediateData);
            if(!isset($productIntermediateData['last_update_status']))
            {
                $productIntermediateData['last_update_status'] = '';
            }
        }
        stop_measure('product');

        $getStoreParams = [
            'limit' => 10,
            'offset' => 0,
            'order' => 'name|ASC',
            'item_ids' => $productIntermediateData['item_id']
        ];

        start_measure('store_price', 'Store Price');
        $storePriceData = $this->getStorePriceData($getStoreParams);
        stop_measure('store_price');

        start_measure('category', 'Category');

        // Product Category List
        start_measure('product_category', 'Product Category');
        $type = 'product';
        $productCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);
        stop_measure('product_category');

        // Business Category List
        start_measure('business_category', 'Business Category');
        $type = 'business';
        $businessCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);
        stop_measure('business_category');

        // Brand List
        start_measure('brand', 'Brand');
        $type = 'brand';
        $brandList = $this->categoryRepository->getRootCategoryIncludeChild($type);
        stop_measure('brand');

        // Product Category Data
        $strProductCategoryData = [];
        $productCategoryData = [];

        $businessCategoryData = [];
        $strBusinessCategoryData = [];

        $brandData = '';

        start_measure('bind_category', 'Bind Category');
        $productCategoryResult = $this->categoryRepository->getCategoryByContent($id, "product");

        if (isset($productCategoryResult['data'])) {
            foreach ($productCategoryResult['data'] as $categoryData) {
                if($categoryData['type']=='product') {
                    $productCategoryData[] = $categoryData;
                    $strProductCategoryData[] = $categoryData['id'];
                } else if ($categoryData['type']=='business') {
                    $businessCategoryData[] = $categoryData;
                    $strBusinessCategoryData[] = $categoryData['id'];
                } else if ($categoryData['type']=='brand') {
                    $brandData = $categoryData['id'];
                }
            }
        }

        if(!empty($strProductCategoryData))
            $strProductCategoryData = implode(',',$strProductCategoryData);
        else
            $strProductCategoryData = '';

        if(!empty($strBusinessCategoryData))
            $strBusinessCategoryData = implode(',',$strBusinessCategoryData);
        else
            $strBusinessCategoryData = '';
        stop_measure('bind_category');
        stop_measure('category');

        // Attribute Data
        start_measure('attribute', 'Attribute');
        $attributeResult = $this->attributeRepository->getAllAttribute();

        if (isset($attributeResult['data']['records'])) {
            $attributeList = $attributeResult['data']['records'];
            foreach ($attributeList as &$attribute) {
                $subAttributeResult = $this->attributeRepository->getSubAttribute($attribute['id']);
                if (isset($subAttributeResult['records']))
                    $attribute['subAttribute'] = $subAttributeResult['records'];
            }
        } else {
            $attributeList = [];
        }

        $images = [];

        foreach($productIntermediateData['image'] as $image)
        {
            $images[] = [
                'url' => $image
            ];
        }
        $productAttributeDataResult = $this->attributeRepository->getAttributeContent($id,"product");
        $productAttributeData = [];
        if(isset($productAttributeDataResult['records'])) {
            foreach($productAttributeDataResult['records'] as $productAttribute)
            {
                $productAttributeData[] = [
                    'attribute_id' => $productAttribute['attribute']['attribute_id'] , 
                    'name' => $productAttribute['attribute']['name']['th'],
                    'attribute_value_id' => $productAttribute['attribute']['sub_attribute']['sub_attribute_id'],
                    'attribute_value_name' => $productAttribute['attribute']['sub_attribute']['name']['th']
                ];
            }
        }
        stop_measure('attribute');

	    // Unit Type
        $result = $this->productRepository->getUnitType('unit_type');
        if(isset($result['status']['code']) && $result['status']['code'] == 200){
            $unitType = $result['data']['records'];
        }
        else{
            $unitType = [];
        }

        // Lwh uom
        $result = $this->productRepository->getUnitType('lwh_uom');
        if(isset($result['status']['code']) && $result['status']['code'] == 200){
            $lwhUom = $result['data']['records'];
        }
        else{
            $lwhUom = [];
        }

        // Weight uom
        $result = $this->productRepository->getUnitType('weight_uom');
        if(isset($result['status']['code']) && $result['status']['code'] == 200){
            $weightUom = $result['data']['records'];
        }
        else{
            $weightUom = [];
        }

        start_measure('tag', 'Tag');
        $tags = $this->tagRepository->getTags($id);
        stop_measure('tag');
        
        foreach($storePriceData['data'] as $key => $value) {
            if($value['store_price'] !== false){
                $storePriceData['data'][$key]['store_price_vat_rate'] = SatungRounding::calculate($value['store_price'], $productIntermediateData['vat_rate'], 1);
            }
        }
        $productIntermediateData['vat_rate'] =  SatungRounding::calculate($productIntermediateData['normal_price'], $productIntermediateData['vat_rate'], 1);
        $productIntermediateData['vat_rate'] = ($productIntermediateData['vat_rate']!='')? number_format((float) $productIntermediateData['vat_rate'], 2, '.', ',') : '';
        $productIntermediateData['normal_price'] = number_format((float) $productIntermediateData['normal_price'], 2, '.', ',');

        $productIntermediateData['brand_id'] = $brandData;
        $productOnlineData['brand_id'] = $brandData;

        $productOnlineData['vat_rate'] =  SatungRounding::calculate($productOnlineData['normal_price'], $productOnlineData['vat_rate'], 1);
        $productOnlineData['vat_rate'] = ($productOnlineData['vat_rate']!='')? number_format((float) $productOnlineData['vat_rate'], 2, '.', ',') : '';
        $productOnlineData['normal_price'] = ($productOnlineData['normal_price']!='')? number_format((float) $productOnlineData['normal_price'], 2, '.', ',') : '';

        $productIntermediateData['lwh_uom'] = (is_array($productIntermediateData['lwh_uom']))? $productIntermediateData['lwh_uom'] : json_decode($productIntermediateData['lwh_uom'],true);
        $productIntermediateData['unit_type'] = (is_array($productIntermediateData['unit_type']))? $productIntermediateData['unit_type'] : json_decode($productIntermediateData['unit_type'],true);
        $productIntermediateData['weight_uom'] = (is_array($productIntermediateData['weight_uom']))? $productIntermediateData['weight_uom'] : json_decode($productIntermediateData['weight_uom'],true);

        if(!empty($productIntermediateData['description']) && is_array($productIntermediateData['description'])){
            foreach ($productIntermediateData['description'] as $key => $value){
                $productIntermediateData['description'][$key] = str_replace('"','\"',$value);
                $productIntermediateData['description'][$key] = str_replace("\n", "",$productIntermediateData['description'][$key]);

            }
        }

        if(!empty($productOnlineData['description']) && is_array($productOnlineData['description'])){
            foreach ($productOnlineData['description'] as $key => $value){
                $productOnlineData['description'][$key] = str_replace('"','\"',$value);
                $productOnlineData['description'][$key] = str_replace("\n","",$productOnlineData['description'][$key]);

            }
        }

        if(isset($productIntermediateData['approve_status']) && $productIntermediateData['approve_status'] === 'ready to approve') {

            return view($this->view['approve'], [
                'editAble' => false,
                'item_id' => $productIntermediateData['item_id'],
                'product_id' => $id,
                'language' => config('language.category'),
                'businessCategoryData' => $businessCategoryData,
                'productCategoryData' => $productCategoryData,
                'businessCategoryList' => $businessCategoryList,
                'productCategoryList' => $productCategoryList,
                'businessCategoryFlattenList' => $this->flattenArray($businessCategoryList),
                'productCategoryFlattenList' => $this->flattenArray($productCategoryList),
                'attributeList' => $attributeList,
                'attributeData' => $productAttributeData,
                'productIntermediateData' => $productIntermediateData,
                'productOnlineData' => $productOnlineData,
                'images' => $images,
                'unitType' => $unitType,
                'lwhUom' => $lwhUom,
                'weightUom' => $weightUom,
                'storePriceData' => $storePriceData,
                'tags' => $tags,
                'brandList' => $brandList,
                'old_business_category' => $strBusinessCategoryData,
                'old_product_category' => $strProductCategoryData,
            ]);
        }

        return view($this->view['edit'],[
            'editAble' => true,
            'item_id' => $productIntermediateData['item_id'],
            'product_id' => $id,
            'language' => config('language.category'),
            'businessCategoryData' => $businessCategoryData,
            'productCategoryData' => $productCategoryData,
            'businessCategoryList' => $businessCategoryList,
            'productCategoryList' => $productCategoryList,
            'businessCategoryFlattenList' => $this->flattenArray($businessCategoryList),
            'productCategoryFlattenList' => $this->flattenArray($productCategoryList),
            'attributeList' => $attributeList,
            'attributeData' => $productAttributeData,
            'productIntermediateData' => $productIntermediateData,
            'productIntermediateData_old' => base64_encode(json_encode($productIntermediateData)),
            'productOnlineData' => $productOnlineData,
            'images' => $images,
            'unitType' => $unitType,
            'lwhUom' => $lwhUom,
            'weightUom' => $weightUom,
            'storePriceData' => $storePriceData,
            'tags' => $tags,
            'brandList' => $brandList,
            'old_business_category' => $strBusinessCategoryData,
            'old_product_category' => $strProductCategoryData,
        ]);
    }

    public function destroy($id)
    {
        $result = $this->productRepository->deleteProduct($id);
        if($result['status']['code'] == 200){

            // Sync Product To Solar Search
            event(new ProductUpdated($id));

            return Response::json(['status' => true]);
        }
        else{
            return Response::json(['status' => false, 'messages' => 'Delete Error']);
        }
    }

    /*
    * Method for upload image
    */
    public function postUploadImage()
    {
        $output = $this->productRepository->uploadImage('file');

        return response()->json($output);
    }

    public function deleteImage(Request $request)
    {
        $output = $this->productRepository->deleteImage($request->input('id'));

        return response()->json($output);
    }

    public function moveImage(Request $request)
    {
        $output = $this->productRepository->moveImage($request->input('items'));

        return response()->json($output);
    }

    public function postApprove(Request $request)
    {
        return response()->json($request->input());
    }

    public function exportProducts(Request $request)
	{   
        $inputs = $request->input();
        $product_ids = [];
        
        $isSelectedCategory = false;
        
        if(isset($inputs['category']) && !empty($inputs['category'])){

            foreach($inputs['category'] as $key => $value){

                // Empty data
                if(empty($value) || $value == 'undefine'){
                    continue;
                }

                // Has selected category
                $isSelectedCategory = true;

                $result = $this->categoryRepository->getContentsByCategory(array($value));
                if (isset($result['status']['code']) && $result['status']['code'] == 200) {
                    $contents = $result['data'][0]['contents'];
                    $ids = $this->filterProductId($contents);
                    $product_ids = array_merge($product_ids, $ids);
                }
            }

        }

        if(!empty($product_ids)){
            $inputs['ids'] = $product_ids;
        }
        
		$result = $this->productRepository->getDataProductReport($inputs);
        
		if (!$result) {
			$request->session()->flash('messages', [
				'type' => 'error',
				'text' => 'No Data'
			]);

			return redirect($this->redirect['index']);
		}
	}

    public function dumpImage(){
        $data = [];
        $count = 0;
        $files = Storage::allFiles('img');
        //echo "start ".date('Y-m-d H:i:s')."<br />";
        foreach ($files as $file){
            
            if(preg_match('/DS_Store/', (string)$file)) continue;
            
            $name_tmp = explode('/', (string)$file);
            $filename_tmp = $name_tmp[count($name_tmp) - 1];
            $file_name = explode('.', $filename_tmp);
            dumpAgain:
            $output = $this->productRepository->uploadImageByDump($file);
            //dd($output);
            $link = $output['image'];
            $item_id = $file_name[0];
            $data[$count]['item_id'] = $item_id;
            $data[$count]['link'] = $link;
            if($link == '') goto dumpAgain;
            //dd($data);
            //break;
            $count++;
        }
        // echo "start ".date('Y-m-d H:i:s')."<br />";
        //dd($data);
        
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
        ,   'Content-type'        => 'text/csv'
        ,   'Content-Disposition' => 'attachment; filename=cdnImage.csv'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
    ];
        array_unshift($data, array_keys($data[0]));

        $callback = function() use ($data) 
            {
                $FH = fopen('php://output', 'w');
                foreach ($data as $row) { 
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };

        return Response::stream($callback, 200, $headers);
        
    }

    private function decodeProductData(&$product) {
        $fields = ['original_name', 'name'];
        $languages = config('language.product');

        foreach ($fields as $field) {
            foreach ($languages as $language) {
                $product[$field][$language] = html_entity_decode($product[$field][$language]);
            }
        }
    }
}

?>
