<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Services\Guzzle;

class DeliveryFeeRepository extends BaseRepository
{
    private $messages;

    public function __construct(Guzzle $guzzle)
    {
        parent::__construct();
        $this->messages   = config('message');
        $this->guzzle     = $guzzle;
        $this->urlConfig  = env('CURL_API_CONFIG');
    }

    public function getNormalFee($sort = 'ASC')
    {
        $normal_fee = $this->guzzle->curl('GET',$this->urlConfig.'configs?config_type=Delivery%20Fee&status=active&order=value.min%7C' . $sort);
        return $normal_fee;
    }

    public function getNormalFeeText()
    {
        $normal_fee = $this->getNormalFee();

        $texts = [
            'en' => [],
            'th' => []
        ];
   
        if(isset($normal_fee['data']) && isset($normal_fee['data']['pagination']) && $normal_fee['data']['pagination']['total_records'] > 0){
            $total_records = $normal_fee['data']['pagination']['total_records'];
            $shippingRates = $normal_fee['data']['records'];
            
            foreach($shippingRates as $index => $shippingRate){
                $min = $shippingRate['value']['min'];
                $fee = $shippingRate['value']['fee'];
                $feeFormat = number_format($fee);
                $minFormat = number_format($min);

                $th = $en = '';
                if ($total_records == 1) {
                    //Flat rate
                    if ($fee <= 0) {
                        $texts['th'][] = 'ทุกรายการสั่งซื้อสินค้า ฟรีค่าจัดส่ง!';
                        $texts['en'][] = 'FREE delivery! for any order total.';
                    } else {
                        $texts['th'][] = 'ทุกรายการสั่งซื้อสินค้า ค่าจัดส่ง ' . $feeFormat . ' บาท';
                        $texts['en'][] = 'Delivery fee is ' . $feeFormat . ' baht for any order total.';
                    }
                } else {
                    if ($index == 0) {
                        if ($fee <= 0) {
                            $texts['th'][] = 'ซื้อสินค้าต่ำกว่า ' . number_format($shippingRates[$index + 1]['value']['min']) . ' บาท ฟรีค่าจัดส่ง!';
                            $texts['en'][] = 'Delivery fee for order total less than ' . number_format($shippingRates[$index + 1]['value']['min']);
                        } else {
                            $texts['th'][] = 'ซื้อสินค้าต่ำกว่า ' . number_format($shippingRates[$index + 1]['value']['min']) . ' บาท ค่าจัดส่ง ' . $feeFormat . ' บาท';
                            $texts['en'][] = 'Delivery fee for order total less than ' . number_format($shippingRates[$index + 1]['value']['min']) . ' baht is ' . $feeFormat . ' baht.';
                        }
                    } else {
                        //Middle level
                        if (isset($shippingRates[$index + 1])) {
                            if ($fee <= 0) {
                                $texts['th'][] = 'ซื้อสินค้าตั้งแต่ ' . $minFormat . ' บาท ขึ้นไป แต่ไม่เกิน ' . number_format($shippingRates[$index + 1]['value']['min']) . ' บาท ฟรีค่าจัดส่ง!';
                                $texts['en'][] = 'Order total between ' . $minFormat . ' and ' . number_format($shippingRates[$index + 1]['value']['min']) . ' baht, FREE delivery!';
                            } else {
                                $texts['th'][] = 'ซื้อสินค้าตั้งแต่ ' . $minFormat . ' บาท ขึ้นไป แต่ไม่เกิน ' . number_format($shippingRates[$index + 1]['value']['min']) . ' บาท ค่าจัดส่ง ' . $feeFormat . ' บาท';
                                $texts['en'][] = 'Order total between ' . $minFormat . ' and ' . number_format($shippingRates[$index + 1]['value']['min']) . ' baht, delivery fee is ' . $feeFormat . ' baht.';
                            }
                        } else {
                            //Last level
                            if ($fee <= 0) {
                                $texts['th'][] = 'ซื้อสินค้าตั้งแต่ ' . $minFormat . ' บาท ขึ้นไป ฟรีค่าจัดส่ง!';
                                $texts['en'][] = 'Order total more than ' . $minFormat . ' baht, FREE delivery!';
                            } else {
                                $texts['th'][] = 'ซื้อสินค้าตั้งแต่ ' . $minFormat . ' บาท ขึ้นไป ค่าจัดส่ง ' . $feeFormat . ' บาท';
                                $texts['en'][] = 'Order total more than ' . $minFormat . ' baht, delivery fee is ' . $feeFormat . ' baht.';
                            }
                        }
                    }
                }
            }
        }
        return $texts;
    }

    public function getNormalFeeEditData()
    {
        $normal_fee = $this->getNormalFee();
        $result = [];
        
        if(isset($normal_fee['data']) && isset($normal_fee['data']['pagination']) && $normal_fee['data']['pagination']['total_records'] > 0){
            $result = $normal_fee['data']['records'];
        }

        return $result;
    }

    public function editNormalFee($data)
    {
        $normal_db = $this->getNormalFee();
        $normal_fee = [];

        if(isset($normal_db['data']) && isset($normal_db['data']['pagination']) && $normal_db['data']['pagination']['total_records'] > 0){
            foreach($normal_db['data']['records'] as $eachFee){
                $normal_fee[$eachFee['id']] = [
                    'min' => $eachFee['value']['min'],
                    'fee' => $eachFee['value']['fee']
                ];
            }
        }
        
        foreach($data as $key => $value){
            $data[$key] = [
                'min' => str_replace(',', '', $value['min']),
                'fee' => str_replace(',', '', $value['fee'])
            ];
        }

        $updateData = $this->generateUpdateNormalFeeData($normal_fee, $data);

        foreach($updateData['delete'] as $id => $eachDelete){
            $this->deleteNormalFee($id, $eachDelete);
        }

        foreach($updateData['create'] as $eachCreate){
            $this->addNormalFee($eachCreate);
        }

        foreach($updateData['update'] as $id => $eachUpdate){
            $this->updateNormalFee($id, $eachUpdate);
        }

        return [
            'status'   => true,
            'messages' => $this->messages['database']['success']
        ];
    }

    public function addNormalFee($data)
    {
        $response = ['status' => true];

        $comment = 'Min amount ' . $data['min'] . ' baht';
        $time = date('Y-m-d H:i:s');
        $result = $this->guzzle->curl('POST', $this->urlConfig . 'configs', [
            'headers' => [
                'content-type' => 'application/json',
            ],
            'body'    => json_encode([
                'code' => $comment,
                'name' => $comment,
                'value' => [
                    'min' => intval($data['min']),
                    'fee' => intval($data['fee'])
                ],
                'config_type' => 'Delivery Fee',
                'status' => 'active',
                'created_at' => $time,
                'updated_at' => $time,
                'deleted_at' => null
            ])
        ]);

        if($result['status'] != '200' ){
            $response['status'] = false;
            if(isset($response['error']) && isset($response['error']['message'])){
                $response['message'] = $response['error']['message'];
            }
        }

        return $response;
    }

    public function updateNormalFee($id, $data)
    {
        $comment = 'Min amount ' . $data['min'] . ' baht';
        $result = $this->guzzle->curl('PUT', $this->urlConfig . 'configs/' . $id, [
            'headers' => [
                'content-type' => 'application/json',
            ],
            'body'    => json_encode([
                'code' => $comment,
                'name' => $comment,
                'value' => [
                    'min' => intval($data['min']),
                    'fee' => intval($data['fee'])
                ]
            ])
        ]);

        return ['status' => true];
    }

    public function deleteNormalFee($id, $data)
    {
        $result = $this->guzzle->curl('DELETE', $this->urlConfig . 'configs/' . $id);

        return ($result['status']['text'] == 'OK' && $result['data']['records']['deleted_at'] !== null);
    }

    public function generateUpdateNormalFeeData($dbData, $formData)
    {
        $result = [
            'create' => array_diff_key($formData, $dbData),
            'delete' => array_diff_key($dbData, $formData),
            'update' => []
        ];

        foreach($formData as $id => $eachData){
            if(array_key_exists($id, $result['create']) || array_key_exists($id, $result['delete']))
                continue;

            if($eachData['min'] != $dbData[$id]['min'] || $eachData['fee'] != $dbData[$id]['fee'])
                $result['update'][$id] = $eachData;
        }

        return $result;
    }
}