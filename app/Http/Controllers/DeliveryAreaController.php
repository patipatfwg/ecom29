<?php
namespace App\Http\Controllers;

use App\Repositories\DeliveryAreaRepository;
use App\Http\Requests\DeliveryAreaSearchRequest;
use Illuminate\Http\Request;

use Validator,App,Response;

class DeliveryAreaController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'delivery_area'
    ];

    protected $view = [
        'index' => 'delivery_area.index'
    ];

    public function __construct(DeliveryAreaRepository $deliveryAreaRepository)
    {
        parent::__construct();
        $this->messages               = config('message');
        $this->deliveryAreaRepository = $deliveryAreaRepository;
        $this->_bss_cache_service     = App::make('App\Services\Bss\CacheServices');
    }

    public function index(Request $request)
    {   
        $inputs = $request->all();

        $province_id = array_get($inputs, 'province_id', '');
        $district_id = array_get($inputs, 'district_id', '');

        $data['status'] = (!isset($inputs['status'])) ? 'Y,N' : '';

        return view($this->view['index'], [
            'province' => $this->deliveryAreaRepository->getProvince(),
            'district' => json_decode($this->deliveryAreaRepository->getDistrict($province_id), true),
            'subdistrict' => json_decode($this->deliveryAreaRepository->getSubDistrict($district_id), true),
            'status' => $this->deliveryAreaRepository->getStatus(true),
            'data' => $data,
            'can_edit' => $this->deliveryAreaRepository->getEditPermission(),
            'message' => $this->messages
        ]);
    }

    public function anyData(Request $request, Validator $validator)
    {
        $inputs = $request->all();

        $inputsValidate = [];
        foreach($inputs['search'] as $eachSearch){
            $inputsValidate[$eachSearch['name']] = $eachSearch['value'];
        }
        
        $error_message = '';
        $formValidator = new DeliveryAreaSearchRequest();
        $checkValidator = $validator::make($inputsValidate, $formValidator->rules(), $formValidator->messages());

        if ($checkValidator->fails()) {
            $errors   = $checkValidator->messages ();
            $error_message = implode("\n", array_unique($errors->all()));
        }

        if($error_message != ''){
            return Response::json([
                'status'   => false,
                'messages' => $error_message
            ]);
        }
        
        return $this->deliveryAreaRepository->getDeliveryAreaData($request->input());
    }

    public function getDistrict(Request $request, $province_id = '')
    {
        return $this->deliveryAreaRepository->getDistrict($province_id);
    }

    public function getSubDistrict(Request $request, $district_id = '')
    {
        return $this->deliveryAreaRepository->getSubDistrict($district_id);
    }

    public function getSubDistrictAll(Request $request, $province_id = '')
    {
        return $this->deliveryAreaRepository->getSubDistrictAll($province_id);
    }

    public function saveData(Request $request)
    {
        $result = ['status' => false];
        if ($request->has('data')) {
            $result['status'] = $this->deliveryAreaRepository->saveData($request->input('data'));
            if ($result['status']) {
                // clear cache by service
                $this->_bss_cache_service->flushCacheByService('api-address');
            }
        }
        return $result;
    }
}
?>