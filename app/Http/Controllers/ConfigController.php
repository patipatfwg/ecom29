<?php
namespace App\Http\Controllers;

use App\Events\ProductUpdated;
use Illuminate\Http\Request;
use App\Http\Requests\ContentRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\ConfigPaymentMethodEditRequest;
use App\Http\Controllers\BaseController;
use Response;
use Validator;
use App;
class ConfigController extends \App\Http\Controllers\BaseController {

    protected $redirect = [
        'login' => '/',
        'index' => 'config/payment_method',
        'edit' => 'config/payment_method/edit'
    ];

    protected $view = [
        'payment_method'  => 'config.payment_method',
        'managing_items' => 'config/managing_items',
        'create_payment' => 'config/create_payment',
        'edit_payment_method' => 'config.edit_payment_method'
    ];

    protected $config_type = 'Payment Method';

    public function __construct()
    {
        parent::__construct();
        $this->messages = config('message');
        $this->paymentRepository  = App::make('App\Repositories\PaymentRepository');
        $this->categoryRepository = App::make('App\Repositories\CategoryRepository');
        $this->productRepository  = App::make('App\Repositories\ProductRepository');
    }
    /**
     * page index
     */
    public function paymentMethod()
    {
        $configs = [];
        $result  = [];

        $configs = $this->paymentRepository->getPaymentMethod();
        return view($this->view['payment_method'], [
            'language'     => config('language.content'),
            'configs' 	   => $configs
        ]);
    }

    public function paymentMethodDataTable(Request $request)
    {

        $configs = $this->paymentRepository->getPaymentMethodTable($request->input());

        return $configs;
    }


    public function managingItems($id)
    {
        //	$configs = $this->paymentRepository->getPaymentMethod();
        $items = [[
            'item_id' => '1101',
            'item_type' => 'product'
        ], [
            'item_id' => '1102',
            'item_type' => 'campaign'
        ]];

        $install = $this->paymentRepository->getInstallmentOptionDataById($id);

         // Product Category List
        $type = 'product';
        $productCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);

        // Business Category List
        $type = 'business';
        $businessCategoryList = $this->categoryRepository->getRootCategoryIncludeChild($type);
        return view($this->view['managing_items'], [
            'language'     => config('language.content'),
            'install'	   => $install['data']['records'][0],
            'items' 	   => $items,
            'businessCategoryList' => $businessCategoryList,
            'productCategoryList' => $productCategoryList
        ]);
    }

    public function installmentItemsData($id, Request $request)
    {
        $params = $request->input();
        $return_data = $this->paymentRepository->getInstallmentData($id);
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
            $type_ = [];
            foreach ($data as $key => $value) {
                $ids[] =  $value['content_id'];
                $type_[$value['content_id']] = $value['content_type'];
            }
            $option = [
                'fields' => 'name.th,name.en,item_id',
                'name' => $params['search_text_input'],
                'ids' => $ids,
                'order' => $params['order']
            ];

            // dd($option);
          
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
                                'itemType' 	=>	$type_[$value['id']],
                                'item_id' 	=>	isset($value['item_id']) ? $value['item_id'] : "-",
                                'name_th'	=>	$value['name']['th'],
                                'name_en'	=>	$value['name']['en']
                            ];
            }

            if (
                    isset($result['status']) && 
                    !empty($result['status']) && 
                    $result['status']['code'] == 200
                ){
                $count_page = count($result['data']['records']); //count page
                $count_all = $result['data']['pagination']['total_records']; //count all
            }

            $total = $result['data']['pagination']['total_records'];

            $output = [
                'recordsTotal'    => $count_page, //count page
                'recordsFiltered' => $count_all, //count all
                'data'            => $data_table,
            ];

            return json_encode($output);
        }
    }

    public function installmentItemsDataReport($id,Request $request)
    {
        $params = $request->input();
        $result = $this->paymentRepository->getDataInstallmentDataReport($id,$params);


        if ( ! $result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);

            return redirect($this->redirect['index']);
        }
    }
    public function deleteInstallmentContent($id, $contentIds)
    {
        $product_id = $this->getProductInInstallment($id);
        $result = $this->paymentRepository->deleteContent($id, $contentIds);
        event(new ProductUpdated( implode(',', $product_id) ));

        return $result;
    }

    public function getData(Request $request)
    {
        $param = $request->input();
        return $this->paymentRepository->getInstallmentOption($param);
    }

    public function updateEnableType($id, Request $request)
    {
        $params = $request->input();
        $enableType = $params['enableType'];
        return $this->paymentRepository->updateEnableType($id, $enableType);
    }

    public function updateStatus(Request $request)
    {
        $params = $request->input();

        $dataOld = json_decode(base64_decode($params['dataOld']));
        $dataNew = json_decode($params['dataNew']);

        foreach($dataOld as $okData => $ovData) {
            foreach($dataNew as $nkData => $nvData) {
                if($ovData->id == $nvData->value) {
                    $ovData->status = "active";
                    break;
                }else {
                    $ovData->status = "inactive";
                }
            }
            $result = $this->paymentRepository->updateStatusPayment($ovData->id, $ovData->status);
        }
        return $result;
    }

    public function updateStatusPayments($id, Request $request)
    {
        $params = $request->input();

        $ids = explode(',',$id);

             foreach($ids as $okData => $ovData) {
                 $result = $this->paymentRepository->updateStatusPayment($ovData, $params['status']);
             }

        return $result;
    }

    public function updatePriorityPayments(Request $request)
    {

        $params = $request->input();

        $result = $this->paymentRepository->updatePriorityPayments($params);

        return $result;

    }

    public function destroy($id)
    {
        return $this->paymentRepository->getDeleteInstallmentOption($id);
    }


    public function report(Request $request)
    {
        $result = $this->paymentRepository->getDataInstallmentOptionReport($request->input());

        if ( ! $result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);

            return redirect($this->redirect['index']);
        }
    }



    public function create(){

        $bank = $this->paymentRepository->getBankData();

        return view($this->view['create_payment'], [
            'language'     => config('language.content'),
            'bank'     => $bank,
        ]);
    }

    public function store(PaymentRequest $request){

         $params = $request->input();
         if(isset($params['status'])){
             $status = 'active' ;
         }else{
             $status = 'inactive';
         }

            $data = [
                "option_name" => [
                    "th" => $params['name_']['th'],
                    "en" => $params['name_']['en']
                ],
                "description" => [
                    "th" => $params['description_']['th'],
                    "en" => $params['description_']['en']
                ],
                "bank_id" => $params['bank'],
                "installment_term" => $params['installment'],
                "interest_rate" => $params['interest_rate'],
                "cart_threshold" => $params['threshold'],
                "status" => $status,
                "started_date" => convertDateTime($params['start_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s'),
                "end_date" => convertDateTime($params['end_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s')
            ];

        $result = $this->paymentRepository->create($data);

        return $result;
    }

    public function edit($id){
         
        $bank = $this->paymentRepository->getBankData();
        $payment = $this->paymentRepository->getPayment($id);
        $products = $this->paymentRepository->getInstallmentData($id);
        $products['total'] = $products['data']['pagination']['total_records'];
        return view($this->view['create_payment'], [
            'language'     => config('language.content'),
            'bank'     => $bank,
            'payment'     => $payment['data']['records'][0],
            'products'     => $products,
        ]);
        
    }

    public function updateStatusInstallmentOption($id, Request $request)
    {
        $params = $request->input();
        $updateData = [
            'id' => $id,
            'status' => $params['status']
        ];
        return $this->paymentRepository->updateStatusInstallmentOption($updateData);
    }

    public function update($id , Request $request)
    {
            
         $params = $request->input();
         if(isset($params['status'])){
             $status = 'active' ;
         }else{
             $status = 'inactive';
         }
         
        $data = [
            "option_name" => [
                "th" => $params['name_']['th'],
                "en" => $params['name_']['en']
            ],
            "description" => [
                "th" => $params['description_']['th'],
                "en" => $params['description_']['en']
            ],
            "bank_id" => $params['bank'],
            "installment_term" => $params['installment'],
            "interest_rate" => $params['interest_rate'],
            "cart_threshold" => $params['threshold'],
            "status" => $status,
            "started_date" => convertDateTime($params['start_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s'),
            "end_date" => convertDateTime($params['end_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s')
        ];
        $result = $this->paymentRepository->update($id,$data);
        $result_sync = $this->productSyncSearch($id);

        return $result;
    }

    public function input_item(Request $request)
    {

        $input = $request->input();
        $ids = $input['item'];
        $result = $this->paymentRepository->addItemToInstallmentOption($input);
         // if($result){
        event(new ProductUpdated( implode(',', $ids) ));
         // }
         
         return $result;
    }

	public function installmentProductData($id, Request $request){

		$inputs = $request->input();
        $product_ids = [];
        $inputs['fields'] = 'item_id,name.th,name.en';
        if($inputs['tapSearch']  == 0){
        	$inputs['ids'] = [];
        	return $this->productRepository->getProductList($inputs);
        }
        	
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

	protected function filterProductId($data)
    {
        $output = [];
        $output = array_filter($data, function ($content) {
            return $content['content_type'] == 'product' ? true : false;
        });
        
        return array_column($output, 'content_id');
    }

    protected function productSyncSearch($installment_id) 
    {
        $product_data = $this->paymentRepository->getInstallmentData($installment_id);
        
        $product_id = [];
        if(count($product_data['data']['records']) > 0) {
            foreach($product_data['data']['records'] as $val) {
                $product_id[] = $val['content_id'];
            }
        }
        
        event(new ProductUpdated( implode(',', $product_id) ));
        return [
            'code' => 200
        ];
    }

    protected function getProductInInstallment($installment_id)
    {
        $product_data = $this->paymentRepository->getInstallmentData($installment_id);
        
        $product_id = [];
        if(count($product_data['data']['records']) > 0) {
            foreach($product_data['data']['records'] as $val) {
                $product_id[] = $val['content_id'];
            }
        }

        return $product_id;
    }

    public function editView($id)
    {
        $paymentGatewayData = $this->paymentRepository->curlGetConfigById($id);
        return view($this->view['edit_payment_method'], [
            'id'        => $id,
            'url'       => $this->redirect,
            'language'  => config('language.content'),
            'data'      => $paymentGatewayData
        ]);
    }

    public function updatePaymentGateway($id, Request $request, Validator $validator)
    {
        $formValidator = new ConfigPaymentMethodEditRequest();
        $checkValidator = $validator::make($request->all(), $formValidator->rules(), $formValidator->messages());

        if ($checkValidator->fails()) {

            $errors   = $checkValidator->messages ();
            $messages = implode("\n", array_unique($errors->all()));

            return Response::json([
                'status'   => false,
                'messages' => $messages
            ]);

        } else {

            $result = $this->paymentRepository->updatePaymentMethodById($id, $request->input('data'));

            return Response::json(['status' => true]);
        }
    }

}
