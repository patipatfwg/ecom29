<?php
namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UsersRepository;
use App\Repositories\CouponsRepository;
use App\Repositories\ConfigRepository;
use Illuminate\Http\Request;

class ReportController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'order_status'      => 'report/order_status555',
        'order'             => 'report/order',
        'treasury'          => 'report/treasury',
        'product'           => 'report/product',
        'dailysale'         => 'report/dailysale',
        'replace'           => 'report/replace',
        'return_and_refund' => 'report/return_and_refund',
        'coupon'            => 'report/coupon',
        'usage'             => 'report/usage',
        'invoice'           => 'report/invoice',
        'order_print'       => 'report/order_print'
    ];

    protected $view = [
        'order_status'      => 'report.order_status',
        'order'             => 'report.order',
        'product'           => 'report.product',
        'treasury'          => 'report.treasury',
        'dailysale'         => 'report.dailysale',
        'replace'           => 'report.replace',
        'return_and_refund' => 'report.return_and_refund',
        'order_print'       => 'report.order_print',
        'coupon'            => 'report.coupon',
        'usage'             => 'report.usage',
        'invoice'           => 'report/invoice'
    ];

    protected $delivery_method       = ['SHP','PICK'];
    protected $delivery_type         = [ 
        'SHP'              => 'Delivery',
        'PICK'             => 'Pickup'
    ];

	public function __construct(ConfigRepository $configRepository,ReportRepository $reportRepository, CategoryRepository $categoryRepository, UsersRepository $usersRepository, CouponsRepository $couponsRepository)
    {
        parent::__construct();
        $this->reportRepository   = $reportRepository;
        $this->categoryRepository = $categoryRepository;
        $this->usersRepository    = $usersRepository;
        $this->couponsRepository = $couponsRepository;
        $this->_config = $configRepository;
    }

	public function exportProducts(Request $request)
	{
		$filter   = $request->all();
		$language = app()->getLocale();

		$report_name = 'Product List';
		$report_type = 'products';

		$this->reportRepository->setReportOption($report_name, $report_type, $language);

		if (isset($filter['category_id'])) {

			$data = $this->categoryRepository->getProductsByCategoryId($filter['category_id']);

            if (isset($data[0]['products'])) {

                $products = $data[0]['products'];

		        foreach ($products as $kData => $vData) {

			        $product_ids[$kData] = $vData['id'];

			        if ($kData > 0) {
				        $product_str .= ',' . $vData['id'];
			        } else {
				        $product_str = $vData['id'];
			        }
		        }

		        $filter['product_ids'] = $product_ids;
		        $filter['products']    = $product_str;
            }
		}

		$report = $this->reportRepository->exportProducts($filter);
		$report->export('csv');
	}

    /**
     * page replace
     */
    public function replace()
    {
        $userData = $this->usersRepository->getUsers(['id' => \Session::get('userId')]);
        $stores   = $this->reportRepository->getStores();

        if(isset($userData['data'][0]['makro_store_id']) && !empty($userData['data'][0]['makro_store_id'])) {

            $currentStore = [];
            $userStoreId  = $userData['data'][0]['makro_store_id'];

            if (!empty($stores) && isset($stores[$userStoreId])) {
                $currentStore[$userStoreId] = $stores[$userStoreId];
                $stores = $currentStore;
            }
        }

        return view($this->view['replace'], [
            'stores' => $stores
        ]);
    }

    /**
     * page replace data tables
     */
    public function anyDataReplace(Request $request)
    {
        return $this->reportRepository->getDataReplace($request->input());
    }

    /**
     * report replace excel
     */
    public function printReplace(Request $request)
    {
        $stores = $this->reportRepository->getStores();
        $result = $this->reportRepository->getDataReplaceReport($request->input(), $stores);

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['replace']);
        }
    }

    /**
     * page order
     */
	public function order()
	{
        $configs               = [];
        $stores                = [];
        $default_config_select = [];
        $user_store_id         = '';
        $delivery_type         = $this->delivery_type;
        $delivery_method       = $this->delivery_method;
        

        $userId   = \Session::get('userId');
        $stores   = $this->reportRepository->getStores();
        $configs  = $this->reportRepository->getPayments();
        $userData = $this->usersRepository->getUsers(['id' => $userId]);

        if( isset($userData['data'][0]['makro_store_id']) && !empty($userData['data'][0]['makro_store_id']) && count($stores) > 0) {
            $user_store_id = $userData['data'][0]['makro_store_id'];
            $current_store[$user_store_id] = $stores[$user_store_id];
            $stores = $current_store;
        }

        if (!empty($configs)) {
            foreach($configs as $key => $val){
                $default_config_select[] = $key;
            }
        }

		$records = [
            'stores'                => $stores,
            'current_store'         => $user_store_id,
            'configs'               => $configs,
            'default_config_select' => $default_config_select,
            'delivery_method'       => $delivery_method,
            'delivery_type'         => $delivery_type
        ];

        return view($this->view['order'], $records);
    }

    /**
     * page order status
     */
	public function orderStatus()
	{
        $configs = [];
        $stores  = [];
        $user_store_id = '';
        $default_config_select = [];

        $userId   = \Session::get('userId');
        $stores   = $this->reportRepository->getStores();
        $configs  = $this->reportRepository->getPayments();
        $userData = $this->usersRepository->getUsers(['id' => $userId]);

        if( isset($userData['data'][0]['makro_store_id']) && !empty($userData['data'][0]['makro_store_id']) && count($stores) > 0 ) {
            $user_store_id = $userData['data'][0]['makro_store_id'];
            $current_store[$user_store_id] = $stores[$user_store_id];
            $stores = $current_store;
        }

        if (!empty($configs)) {
            foreach($configs as $key => $val){
                $default_config_select[] = $key;
            }
        }

		$records = [
            'stores'                => $stores,
            'current_store'         => $user_store_id,
            'configs'               => $configs,
            'default_config_select' => $default_config_select
        ];


        $param_config = [
            'config_type' => 'Admin',
            'status' => 'active',
            'name'=>'report_order_status'
        ];


        $users = $this->_config->getConfigs($param_config);

        if (in_array(\Session::get('userName'),$users[0]['value'])){

            return view($this->view['order_status'], $records);

        } else {

            return redirect('/dashboard');

        }

	}

    /**
     * page order data tables
     */
    public function anyDataOrder(Request $request)
    {
        return $this->reportRepository->getDataOrder($request->input());
    }

    /**
     * page order data tables
     */
     public function anyDataOrderStatus(Request $request)
     {
         return $this->reportRepository->getDataOrderStatus($request->input());
     }
         /**
     * report order excel
     */
    public function printOrderStatus(Request $request)
    {
        $result = $this->reportRepository->getDataOrderStatusReport($request->input());

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['order_status']);
        }
    }

    /**
     * report order excel
     */
    public function printOrder(Request $request)
    {
        $result = $this->reportRepository->getDataOrderReport($request->input());

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['order']);
        }
    }

    /**
     * page dailysale
     */
    public function dailysale()
    {
        $stores = [];
        $user_store_id = '';

        $userId = \Session::get('userId');
        $stores  = $this->reportRepository->getStores();
        $configs = $this->reportRepository->getPayments();
        $userData = $this->usersRepository->getUsers(['id' => $userId]);

        if( isset($userData['data'][0]['makro_store_id'])  && !empty($userData['data'][0]['makro_store_id']) && count($stores)>0 ){
            $user_store_id = $userData['data'][0]['makro_store_id'];
            $current_store[$user_store_id] = $stores[$user_store_id];
            $stores = $current_store;
        }

        $records = [
            'stores'  => $stores,
            'current_store' => $user_store_id,
            'configs' => $configs
        ];

        return view($this->view['dailysale'], $records);
    }

    /**
     * page dailysale data tables
     */
    public function anyDataDailysale(Request $request)
    {
        return $this->reportRepository->getDataDailysale($request->input());
    }

    /**
     * page product data tables
     */
    public function printDailysale(Request $request)
    {
        $result = $this->reportRepository->getDataDailySaleReport($request->input());

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['product']);
        }
    }

    /**
     * page product
     */
	public function product()
	{
        return view($this->view['product']);
	}

	/**
     * page product data tables
     */
	public function anyDataProduct(Request $request)
	{
		return $this->reportRepository->getDataProduct($request->input());
	}

	/**
     * page product data tables
     */
	public function printProduct(Request $request)
	{
		$result = $this->reportRepository->getDataProductReport($request->input());

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['product']);
        }
	}

    /**
     * page treasury
     */
	public function treasury()
	{
        //default value
        $configs = [];
        $stores = [];
        $default_config_select = [];
        $user_store_id = '';

        $userId = \Session::get('userId');
		$stores  = $this->reportRepository->getStores();
		$configs = $this->reportRepository->getPayments();
        $userData = $this->usersRepository->getUsers(['id' => $userId]);

        if(isset($userData['data'][0]['makro_store_id']) && !empty($userData['data'][0]['makro_store_id']) && count($stores)>0){
            $user_store_id = $userData['data'][0]['makro_store_id'];
            $current_store[$user_store_id] = $stores[$user_store_id];
            $stores = $current_store;
        }

        if(count($configs)>0) {
            foreach($configs as $key => $val){
                $default_config_select[] = $key;
            }
        }

		$records = [
            'stores'  => $stores,
            'current_store' => $user_store_id,
            'configs' => $configs,
            'default_config_select' => $default_config_select
        ];

        return view($this->view['treasury'], $records);
	}

	/**
     * page product data tables
     */
	public function anyDataTreasury(Request $request)
	{
		return $this->reportRepository->getDataTreasury($request->input());
	}

    /**
     * page product data tables
     */
    public function printTreasury(Request $request)
    {
        $result = $this->reportRepository->getDataTreasuryReport($request->input());

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['treasury']);
        }
    }

    /**
     * page return and refund
     */
	public function returnAndRefund()
	{
        $configs = [];
        $stores = [];
        $default_config_select = [];
        $user_store_id = '';

        $userId = \Session::get('userId');
		$stores  = $this->reportRepository->getStores();
		$configs = $this->reportRepository->getPayments();
        $userData = $this->usersRepository->getUsers(['id' => $userId]);

        if(isset($userData['data'][0]['makro_store_id']) && !empty($userData['data'][0]['makro_store_id']) && count($stores)>0){
            $user_store_id = $userData['data'][0]['makro_store_id'];
            $current_store[$user_store_id] = $stores[$user_store_id];
            $stores = $current_store;
        }
        
        $records = [
            'stores'  => $stores,
            'current_store' => $user_store_id,
        ];
        return view($this->view['return_and_refund'],$records);
	}

    /**
     * page return and refund data tables
     */
	public function anyDataReturnAndRefund(Request $request)
	{
		return $this->reportRepository->getDataReturnAndRefund($request->input());
	}

    /**
     * page return and refund data tables
     */
    public function printReturnAndRefund(Request $request)
    {
        $result = $this->reportRepository->getDataReturnAndRefundReport($request->input());

        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['return_and_refund']);
        }
    }

    /**
     * page order_print
     */
	public function orderPrint()
	{
        //default value
        $configs = [];
        $stores = [];
        $default_config_select = [];
        $user_store_id = '';

        $userId = \Session::get('userId');
		$stores  = $this->reportRepository->getStores();
		$configs = $this->reportRepository->getPayments();
        $userData = $this->usersRepository->getUsers(['id' => $userId]);

        if( isset($userData['data'][0]['makro_store_id'])  && !empty($userData['data'][0]['makro_store_id']) && count($stores)>0 ){
            $user_store_id = $userData['data'][0]['makro_store_id'];
            $current_store[$user_store_id] = $stores[$user_store_id];
            $stores = $current_store;
        }
 
        if(count($configs)>0) {
            foreach($configs as $key => $val){
                $default_config_select[] = $key;
            }
        }

		$records = [
            'stores'  => $stores,
            'current_store' => $user_store_id,
            'configs' => $configs,
            'default_config_select' => $default_config_select,
            'first_print' => (\Request::route()->getName() == 'report.print')
        ];

        return view($this->view['order_print'], $records);
    }

    /**
     * page order print data tables
     */
     public function anyDataOrderPrint(Request $request)
     {
         return $this->reportRepository->getDataOrderPrint($request->input());
     }


            /**
     * page coupon report
     */
    public function coupon(Request $request)
    {
        $stores     = $this->reportRepository->getStores();
        $coupon     = $this->couponsRepository->getCouponCode();
        $records = [
            'stores'  => $stores,
            'coupon' => $coupon
        ];
 
        return view($this->view['coupon'],$records);
    }

             /**
     * Method for any index
     */
    public function anyDataCoupon(Request $request)
    {
        return $this->reportRepository->couponReportData($request->input());
    }

        /**
     * Method for report excel
     */
    public function printCoupon(Request $request)
    {
        $result = $this->reportRepository->getCouponReportDataReport($request->input());
        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['coupon']);
        }
    }

    /**
    * page usage report
    */
    public function usage(Request $request)
    {
        $default_coupon_select = [];
        $param = $request->input();
        $coupon = $this->couponsRepository->getCoupon();
        $records = [
            'coupon' => $coupon
        ];

        return view($this->view['usage'],$records);
    }

    public function anyDataUsage(Request $request)
    {
        $params = $request->input();
        return $this->reportRepository->getUsageReportData($params);
    }

    /**
     * Method for report excel
     */
    public function printUsage(Request $request)
    {
        $result = $this->reportRepository->getReportUsageData($request->input());
        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['usage']);
        }
    }

     /**
     * report order excel
     */
    public function printOrderPrint(Request $request)
    {
        $first_print = false;
        foreach($request->input()['search'] as $eachInput){
            if($eachInput['name'] == 'running_number'){
                $first_print = $eachInput['value'];
                break;
            }
        }

        $result = $this->reportRepository->getDataOrderPrintReport($request->input(), $first_print);
        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return redirect($this->redirect['order_print']);
        }
    }

    public function invoice()
    {
        //default value
        $configs = [];
        $stores = [];
        $default_config_select = [];
        $user_store_id = '';

        $userId = \Session::get('userId');
        $stores  = $this->reportRepository->getStores();
        $configs = $this->reportRepository->getPayments();
        $userData = $this->usersRepository->getUsers(['id' => $userId]);

        if(isset($userData['data'][0]['makro_store_id']) && !empty($userData['data'][0]['makro_store_id']) && count($stores)>0){
            $user_store_id = $userData['data'][0]['makro_store_id'];
            $current_store[$user_store_id] = $stores[$user_store_id];
            $stores = $current_store;
        }

        if(count($configs)>0) {
            foreach($configs as $key => $val){
                $default_config_select[] = $key;
            }
        }

        $records = [
            'stores'  => $stores,
            'current_store' => $user_store_id,
            'configs' => $configs,
            'default_config_select' => $default_config_select
        ];

        return view($this->view['invoice'], $records);
    }
    public function anyDataInvoice()
    {
        //default value
        $data = [
                    "data" => [
                         "records" => []
                    ]
                ];
        return $data;
    }
    
    
}
