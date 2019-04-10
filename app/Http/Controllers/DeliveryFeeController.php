<?php
namespace App\Http\Controllers;

use App\Repositories\DeliveryFeeRepository;
use App\Http\Requests\DeliveryFeeEditRequest;
use Illuminate\Http\Request;

use Validator,App,Response;

class DeliveryFeeController extends \App\Http\Controllers\BaseController
{
    protected $redirect = [
        'login' => '/',
        'index' => 'delivery_fee',
        'normal' => [
            'index' => 'delivery_fee/normal',
            'edit' => 'delivery_fee/normal/edit'
        ]
    ];

    protected $view = [
        'normal' => [
            'index' => 'delivery_fee.normal.index',
            'edit' => 'delivery_fee.normal.edit'
        ]
    ];

    public function __construct(DeliveryFeeRepository $deliveryFeeRepository)
    {
        parent::__construct();
        $this->messages          = config('message');
        $this->deliveryFeeRepository = $deliveryFeeRepository;
        $this->_bss_cache_service = App::make('App\Services\Bss\CacheServices');
    }

    public function normalFeeIndex()
    {   
        $normal_fee = $this->deliveryFeeRepository->getNormalFeeText();
        return view($this->view['normal']['index'], ['normal_fee' => $normal_fee, 'url' => $this->redirect]);
    }

    public function normalFeeEdit()
    {
        $editData   = $this->deliveryFeeRepository->getNormalFeeEditData();
        return view($this->view['normal']['edit'], ['data' => $editData, 'url' => $this->redirect]);
    }

    public function normalFeeEditSave(Request $request, Validator $validator)
    {
        $error_message = '';
        $formValidator = new DeliveryFeeEditRequest();
        $inputs = $request->all();
        $checkValidator = $validator::make($inputs, $formValidator->rules(), $formValidator->messages());

        if ($checkValidator->fails()) {
            $errors   = $checkValidator->messages ();
            $error_message = implode("\n", array_unique($errors->all()));
        }

        $duplicateValidate = [];
        foreach($inputs['data'] as $eachData){
            if(!in_array($eachData['min'], $duplicateValidate)){
                array_push($duplicateValidate, $eachData['min']);
            } else {
                $error_message .= 'Minimum threshold cannot be duplicate' . "\n";
                break;
            }
        }


        if($error_message != ''){
            return Response::json([
                'status'   => false,
                'messages' => $error_message
            ]);
        } else {

            $result = $this->deliveryFeeRepository->editNormalFee($request->input('data'));

            if (isset($result['status']) && $result['status']) {
                $this->_bss_cache_service->flushCache('Delivery Fee');
                
                return Response::json(['status' => true]);
            } else {
                return Response::json([
                    'status'   => false,
                    'messages' => $result['messages']
                ]);
            }
        }
    }
}
?>