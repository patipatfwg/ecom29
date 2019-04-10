<?php
namespace App\Http\Controllers;

use App\Repositories\CouponsRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReportRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Validator;
use App;
use App\Http\Requests\CouponRequest;

class CouponsController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'coupon',
        'coupon_report' => 'coupon_report',
        'usage_report' => 'usage_report'
    ];

    protected $view = [
        'index' => 'coupon.index',
        'edit'  => 'coupon.edit',
        'usage'  => 'coupon.usage',
        'usage_report'  => 'coupon.usage_report',
        'coupon_report' => 'coupon.coupon_report'
    ];

    public function __construct(ReportRepository $reportRepository,CouponsRepository $couponsRepository ,ProductRepository $productRepository)
    {
        parent::__construct();
        $this->messages          = config('message');
        $this->reportRepository  = $reportRepository;
        $this->productRepository = $productRepository;
        $this->couponsRepository = $couponsRepository;
    }

    /**
     * Method for any index
     */
    public function anyData(Request $request)
    {
        return $this->couponsRepository->getDataCoupons($request->input());
    }

    /**
     * Method for report excel
     */
    public function report(Request $request)
    {
  
        $result = $this->couponsRepository->getDataReport($request->input());
        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return view($this->view['index']);
        }
    }

    /**
     * Method for change status
    */
    public function updateStatus(Request $request){
        $params = $request->input();
        return $result = $this->couponsRepository->setStatus($params);
    }

    /**
     * Method for report excel
     */
    public function reportHistory($id,Request $request)
    {
        $result = $this->couponsRepository->getDataHistoryReport($id,$request->input());
        if (!$result) {
            $request->session()->flash('messages', [
                'type' => 'error',
                'text' => 'No Data'
            ]);
            return view($this->view['usage'], [
                'id' => $id
            ]);
        }
    }

    /**
     * page index
     */
    public function index()
    {
        return view($this->view['index']);
    }

    /**
     * page usage
     */
    public function usage($id,Request $request)
    {
        $param = $request->input();
        $result = $this->couponsRepository->getDataCouponByCouponCode($id);
        return view($this->view['usage'], [
            'id' => $id,
            'coupon' => $result['data']['records'][0]
        ]);
    }

    /**
     * Method for get update
     */
    public function edit($id, Request $request)
    {
        $result = $this->couponsRepository->getDataCoupon($id);

        if(!empty($result['data']['records'][0]['started_date'])) {
            if(strlen($result['data']['records'][0]['started_date']) < 12) {
                $result['data']['records'][0]['started_date'] .= ' 00:00';
                $result['data']['records'][0]['started_date'] = convertDateTime($result['data']['records'][0]['started_date'], 'Y-m-d H:i', 'd/m/Y H:i');
            } else if(strlen($result['data']['records'][0]['started_date']) > 16) {
                $result['data']['records'][0]['started_date'] = convertDateTime($result['data']['records'][0]['started_date'], 'Y-m-d H:i:s', 'd/m/Y H:i');
            }
        }
        
        if(!empty($result['data']['records'][0]['end_date'])) {
            if(strlen($result['data']['records'][0]['end_date']) < 12) {
                $result['data']['records'][0]['end_date'] .= ' 00:00';
                $result['data']['records'][0]['end_date'] = convertDateTime($result['data']['records'][0]['end_date'], 'Y-m-d H:i', 'd/m/Y H:i');
            } else if(strlen($result['data']['records'][0]['end_date']) > 16) {
                $result['data']['records'][0]['end_date'] = convertDateTime($result['data']['records'][0]['end_date'], 'Y-m-d H:i:s', 'd/m/Y H:i');
            }
        }
        if($result['data']['records'][0]['coupon_type'] == 'cart discount'){
            $result['data']['records'][0]['coupon_type'] = 'Fixed Cart Discount';
        }else {
            $result['data']['records'][0]['coupon_type'] = 'Fixed Product Discount';
        }
        $result['data']['records'][0]['discount'] = $result['data']['records'][0]['amount'];
        $result['data']['records'][0]['least_amount'] = $result['data']['records'][0]['least_amount'];

        $json[0] = [];
        if(isset($result['data']['records'][0]['products']) && !empty($result['data']['records'][0]['products'])) {
            $query = [
                'ids' => $result['data']['records'][0]['products'],
                'offset' => 0,
                'limit' => 100,
                'order' => 'item_id|desc'
            ];
            
            $resultProduct = $this->productRepository->getProducts($query);
            $product_list = [];
            $count = 0;
            
    
            foreach($resultProduct['data']['records'] as $val) {
                $product_list[$count]['item_id'] = $val['item_id'];
                $product_list[$count]['name_th'] = $val['name']['th'];
                $product_list[$count]['name_en'] = $val['name']['en'];
    
                $json[0] = [
                    'id' => $val['id'],
                    'name' => '['.$val['item_id'].'] '.$val['name']['th'].' '.$val['name']['en']
                ];
                $count++;
            }
        }
        return view($this->view['edit'], [
            'coupon_id' => $id,
            'couponId' => $id,
            'coupon' => $result['data']['records'][0],
            'product'=> $json[0],
            'method' => 'PUT',
            'language' => config('language.coupon'),
        ]);
    }

    /**
     * Method for get create
     */
    public function create()
    {
        $coupon = [
            'coupon_code' => '',
            'status' => '',
            'thumbnail_display' => ''
        ];
        return view($this->view['edit'], [
            'coupon_id' => "Create",
            'couponId' => '',
            'result' => ['coupon_id' => ''],
            'coupon' => $coupon,
            'method' => 'POST',
            'language' => config('language.coupon'),
        ]);
    }

    /**
     * Method for Post create
     */
     public function store(CouponRequest $request)
     {
        $inputs = $request->input();

        $inputs['coupon_type'] = $inputs['discount_type'];
        ## I don't know what are value of discount_type.
        $inputs['discount_type'] = 'fix amount';
        $inputs['amount'] = $inputs['discount'];

        if($inputs['coupon_type'] == 'cart discount'){
            unset($inputs['product']);
            unset($inputs['product_threshold']);
            unset($inputs['thumbnail_display']);
        }else {
            unset($inputs['least_amount']);
        }
        if(!empty($inputs['status'])) {
            $inputs['status'] = 'active';
        } else {
            $inputs['status'] = 'inactive';
        }

        if(!empty($inputs['thumbnail_display'])) {
            $inputs['thumbnail_display'] = 'Y';
        } else {
            $inputs['thumbnail_display'] = 'N';
        }

        if(!empty($inputs['product'])) {
            $inputs['products'] = [
                $inputs['product_id']
            ];
        }
        
        if(empty($inputs['limit_per_customer'])){
            $inputs['limit_per_customer'] = '';
        } else if(!is_numeric($inputs['limit_per_customer'])){
            return [
                'status' => false,
                'messages' => 'limit_per_customer could be numeric or blank.'
            ];
        }

        if(empty($inputs['limit_per_coupon'])){
            $inputs['limit_per_coupon'] = '';
        } else if(!is_numeric($inputs['limit_per_coupon'])){
            return [
                'status' => false,
                'messages' => 'limit_per_coupon could be numeric or blank.'
            ];
        }
        
        if(!is_numeric($inputs['discount']) && !empty($inputs['discount'])) {
            return [
                'status' => false,
                'messages' => 'discount could be numeric.'
            ];
        }
        
        if(isset($inputs['started_date']) && !empty($inputs['started_date'])) {
            $inputs['started_date'] = $inputs['started_date'].':00';
            $inputs['started_date'] = convertDateTime($inputs['started_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        }

        if(isset($inputs['end_date']) && !empty($inputs['end_date'])) {
            $inputs['end_date'] = $inputs['end_date'].':00';
            $inputs['end_date'] = convertDateTime($inputs['end_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        }

        $result = $this->couponsRepository->createCoupon($inputs);
        
        return $result;
     }

    /**
     * Method for put update
     */
    public function update(CouponRequest $request, $id)
    {
        $inputs = $request->input();

        $inputs['coupon_type'] = $inputs['discount_type'];
        ## I don't know what are value of discount_type.
        $inputs['discount_type'] = 0;
        $inputs['amount'] = $inputs['discount'];

        if($inputs['coupon_type'] == 'cart discount'){
            unset($inputs['product']);
            unset($inputs['product_threshold']);
            unset($inputs['thumbnail_display']);
        }else {
            unset($inputs['least_amount']);
        }

        if(!empty($inputs['thumbnail_display'])) {
            $inputs['thumbnail_display'] = 'Y';
        } else {
            $inputs['thumbnail_display'] = 'N';
        }

        if(!empty($inputs['status'])) {
            $inputs['status'] = 'active';
        } else {
            $inputs['status'] = 'inactive';
        }

        if(!empty($inputs['product'])) {
            $inputs['products'] = [
                $inputs['product_id']
            ];
        }
        
        if(empty($inputs['limit_per_customer'])){
            $inputs['limit_per_customer'] = '';
        } else if(!is_numeric($inputs['limit_per_customer'])){
            return [
                'status' => false,
                'messages' => 'limit_per_customer could be numeric or blank.'
            ];
        }

        if(empty($inputs['limit_per_coupon'])){
            $inputs['limit_per_coupon'] = '';
        } else if(!is_numeric($inputs['limit_per_coupon'])){
            return [
                'status' => false,
                'messages' => 'limit_per_coupon could be numeric or blank.'
            ];
        }
        if(isset($inputs['discount'])) {
            if(!is_numeric($inputs['discount']) && !empty($inputs['discount'])) {
                return [
                    'status' => false,
                    'messages' => 'discount could be numeric.'
                ];
            }
        }
        if(isset($inputs['started_date']) && !empty($inputs['started_date'])) {
            $inputs['started_date'] = $inputs['started_date'].':00';
            $inputs['started_date'] = convertDateTime($inputs['started_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        }

        if(isset($inputs['end_date']) && !empty($inputs['end_date'])) {
            $inputs['end_date'] = $inputs['end_date'].':00';
            $inputs['end_date'] = convertDateTime($inputs['end_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        }

        $inputs['id'] = $id;
        $result = $this->couponsRepository->updateCoupon($inputs);
        
        return $result;
    }

    /**
     * Method for update my profile or shop profile
     */
    private function updateAll($id, $request, $formRequest, $validator)
    {

    }

    /**
     * Method for post address
     */
    public function address(Request $request)
    {

    }

    public function usageData($id,Request $request)
    {
        $params = $request->input();
        return $this->couponsRepository->getUsage($id,$params);
    }

    public function destroy($id)
    {
        return $this->couponsRepository->delete($id);
    }

}
?>
