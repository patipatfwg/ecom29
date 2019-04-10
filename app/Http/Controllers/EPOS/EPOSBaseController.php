<?php
namespace App\Http\Controllers\EPOS;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\EPOS\Models\AdditionalAddress;
use App\Http\Controllers\EPOS\Models\BillingAddress;
use App\Http\Controllers\EPOS\Models\CustomerInformation;
use App\Http\Controllers\EPOS\Models\Invoice;
use App\Http\Controllers\EPOS\Models\Order;
use App\Http\Controllers\EPOS\Models\OrderPayment;
use App\Http\Controllers\EPOS\Models\OrderReturn;
use App\Http\Controllers\EPOS\Models\Product;
use App\Http\Controllers\EPOS\Models\RefundItem;
use App\Http\Controllers\EPOS\Models\ResultStatus;
use App\Http\Controllers\EPOS\Models\ShoppingAddress;
use App\Repositories\PermissionRepository;
use App\Repositories\UsersRepository;
use App\Services\Guzzle;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use SimpleXMLElement;

class EPOSBaseController extends BaseController
{
    public $api                          = [];
    protected static $attributeInvoiceNo = 'OrderInvoiceKey'; // OrderInvoiceKey - OMS attribute name
    protected $guzzle;

    public function __construct(UsersRepository $usersRepository, PermissionRepository $permissionRepository, Guzzle $guzzle)
    {
        parent::__construct();

        $this->guzzle               = $guzzle;
        $this->usersRepository      = $usersRepository;
        $this->permissionRepository = $permissionRepository;

        $this->api['makro_store_api']   = Config::get('api.makro_store_api');
        $this->api['makro_order_api']   = Config::get('api.makro_order_api');
        $this->api['makro_address_api'] = Config::get('api.makro_address_api');
        $this->api['makro_epos_api']    = Config::get('api.makro_epos_api');
        $this->api['makro_bss_api']     = Config::get('api.makro_bss_api');
        $this->api['makro_config_api']  = Config::get('api.makro_config_api');
    }

    protected function getSettlementDateTimeForOMS($paymentType, $invoiceType, $settlement_date = 'now')
    {
        $settlementDate = new DateTime($settlement_date);
        $settlementDate->setTimezone(new DateTimeZone(config('app.timezone')));

        // if($paymentType == 'CC' && $invoiceType == 'CREDIT_MEMO'){

        //     $config = $this->getConfig('epos', 'code', 'issue_date');

        //     $times  = explode(':', $config);
        //     $hour   = $times[0];
        //     $minute = $times[1];
        //     $second = $times[2];

        //     $checkTime = (new DateTime())->setTime($hour, $minute, $second);

        //     if($settlementDate > $checkTime){
        //         // New Day
        //         $settlementDate->add(new DateInterval('P1D'));
        //         $settlementDate->setTime(0, 0);
        //     }
        // }

        return $settlementDate->format('c');
    }

    protected function calculateSettlementDate($paymentType, $invoiceType, $settlement_date)
    {
        if (empty($settlement_date)) {
            return $settlement_date;
        }

        $settlementDate = new DateTime($settlement_date);
        $settlementDate->setTimezone(new DateTimeZone(config('app.timezone')));

        // cut of time
        // if($paymentType == 'CC' && ($invoiceType == 'CREDIT_MEMO' || $invoiceType == 'INFO')){

        //     $config = $this->getConfig('epos', 'code', 'issue_date');

        //     $times = explode(':', $config);
        //     $hour = $times[0];
        //     $minute = $times[1];
        //     $second = $times[2];

        //     $checkTime = clone $settlementDate;
        //     $checkTime->setTime($hour, $minute, $second);

        //     if($settlementDate > $checkTime){
        //         // New Day
        //         $settlementDate->add(new DateInterval('P1D'));
        //     }
        // }

        return $settlementDate->format('d/m/Y');
    }

    public function getAllProvinces()
    {
        try {
            $url     = $this->api['makro_bss_api'] . 'addresses/provinces';
            $options = [
                'headers' => ['api-key' => env('MSIS_APIKEY')],
            ];
            $result = $this->guzzle->eposCurl('GET', $url, $options);
            $result = json_decode($result->getBody());
            if (isset($result->data)) {
                return $result->data;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return '404';
        }
    }

    public function getAllDistricts($provinceId = 0)
    {
        try {
            $url     = $this->api['makro_bss_api'] . 'addresses/districts';
            $options = [
                'headers' => [
                    'api-key' => env('MSIS_APIKEY'),
                ],
                'query'   => [
                    'province_id' => $provinceId,
                ],
            ];
            $result = $this->guzzle->eposCurl('GET', $url, $options);
            $result = json_decode($result->getBody());
            if (isset($result->data)) {
                return $result->data;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return '404';
        }
    }

    public function getAllSubDistricts($districtId = 0)
    {
        try {
            $url     = $this->api['makro_bss_api'] . 'addresses/sub-districts';
            $options = [
                'headers' => [
                    'api-key' => env('MSIS_APIKEY'),
                ],
                'query'   => [
                    'district_id' => $districtId,
                ],
            ];
            $result = $this->guzzle->eposCurl('GET', $url, $options);
            $result = json_decode($result->getBody());
            if (isset($result->data)) {
                return $result->data;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return '404';
        }
    }

    public function getAllStores($makroStoreId = 0)
    {
        $url     = $this->api['makro_store_api'] . 'stores';
        $options = [
            'headers' => [
                'x-language' => 'th',
            ],
            'query'   => [
                'makro_store_id' => $makroStoreId,
            ],
        ];
        $result = $this->guzzle->eposCurl('GET', $url, $options);

        $result = json_decode($result->getBody());
        if ($result->status->code == 200) {
            return $result->data->records;
        } else {
            return [];
        }
    }

    public function getAllStoresAddress($id = 0)
    {
        $url     = $this->api['makro_address_api'] . 'addresses';
        $options = [
            'query' => [
                'content_id'       => $id,
                'content_type'     => 'store',
                'content_sub_type' => 'business',
                'limit'            => 1,
                'offset'           => 0,
            ],
        ];
        $result = $this->guzzle->eposCurl('GET', $url, $options);

        $result = json_decode($result->getBody());
        if ($result->status->code == 200) {
            return $result->data->records;
        } else {
            return [];
        }
    }

    public function getSatangRounding($items)
    {
        try {
            $url     = $this->api['makro_bss_api'] . 'products/calculate-price';
            $options = [
                'headers' => ['api-key' => env('MSIS_APIKEY')],
                'json'    => $items,
            ];
            $response = $this->guzzle->eposCurl('POST', $url, $options);
            $content  = $response->getBody()->getContents();
            $content  = json_decode($content);
            return $content;
        } catch (\Exception $e) {
            return '404';
        }
    }

    public function updateInvoice($orderNumber, CustomerInformation $address, $document_type)
    {
        // read xml

        $data = [
            "order_number"  => $orderNumber,
            "addressLine1"  => $address->addressLine1,
            //"addressLine2" =>$address->addressLine2,
            "addressLine4"  => $address->sub_districts,
            "city"          => $address->districts,
            "state"         => $address->province,
            "country"       => 'TH',
            "day_phone"     => $address->phone,
            "email_id"      => $address->email,
            "zipcode"       => $address->zipcode,
            "tax_payer_id"  => $address->taxId,
            "shop_name"     => $address->shopName,
            "addressLine5"  => $address->branchId,
            "document_type" => $document_type,
        ];
        $xmlRequest = View::make('epos.xml.invoice.update', $data)->render();

        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/updatecustomertaxid', $xmlRequest);
        } catch (\Exception $e) {
            return '404';
        }

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            if ($content != '') {
                $data      = new SimpleXMLElement($content);
                $errorCode = $data->xpath("//NS1:Error/@ErrorCode");
                if ($errorCode) {
                    $errorCode            = $errorCode[0];
                    $errorMessage         = (String) $data->xpath("//NS1:Error/@ErrorDescription")[0];
                    $result               = new ResultStatus();
                    $result->error        = true;
                    $result->errorCode    = $errorCode;
                    $result->errorMessage = $errorMessage;
                    return $result;
                }

                $orderNo = (String) $data->xpath("//NS1:Order/@OrderNo")[0];

                $order              = new Order();
                $order->orderNumber = $orderNo;
                $result             = new ResultStatus();
                $result->error      = false;
                $result->obj        = $order;
                return $result;
            }
        }

    }

    public function replaceInvoice($orderInvoiceKey)
    {

        $result = false;

        $xmlRequest = View::make('epos.xml.invoice.replace', [
            'orderInvoiceKey' => $orderInvoiceKey,
        ])->render();

        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/replaceinvoice', $xmlRequest);
        } catch (\Exception $e) {
            return '404';
        }

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            if ($content != '') {

                $data = new SimpleXMLElement($content);

                $errorCode = $data->xpath("//NS1:Error/@ErrorCode");

                if ($errorCode) {
                    $errorCode    = $errorCode[0];
                    $errorMessage = (String) $data->xpath("//NS1:Error/@ErrorDescription")[0];

                    $result               = new ResultStatus();
                    $result->error        = true;
                    $result->errorCode    = $errorCode;
                    $result->errorMessage = $errorMessage;

                    return $result;
                }

                $invoiceNo     = (String) $data->xpath("//NS1:InvoiceHeader/@InvoiceNo")[0];
                $new_invoiceNo = (String) $data->xpath("//NS1:Extn/@ExtnNewInvoiceNumber")[0];

                // Payment Type
                $paymentType = (String) $data->xpath('//NS1:Order/@SearchCriteria1')[0];

                // Invoice Type
                $invoiceType = (String) $data->xpath('//NS1:InvoiceHeader/@InvoiceType')[0];

                // Settlement Status
                $extnStatus = (String) $data->xpath('//NS1:Extn/@ExtnStatus')[0];

                // Settlement Date
                $settlementDate = (String) $data->xpath('//NS1:Extn/@ExtnSettlementDate')[0];

                // Issue Date
                $issue_date = (String) $data->xpath('//NS1:InvoiceHeader/@DateInvoiced')[0];
                if ($issue_date != '') {
                    $issueDate = new DateTime($issue_date);
                    $issueDate->setTimezone(new DateTimeZone(config('app.timezone')));
                    $issue_date = $issueDate->format('d/m/Y');
                }

                $result                     = new ResultStatus();
                $result->error              = false;
                $result->obj                = $invoiceNo;
                $result->new_invoiceNo      = $new_invoiceNo;
                $result->issueDate          = $issue_date;
                $result->extnStatus         = $extnStatus;
                $result->extnSettlementDate = $this->calculateSettlementDate($paymentType, $invoiceType, $settlementDate);
                return $result;

            }
        }

        return $result;

    }

    public function updatePersonBillingInfo($orderNumber, CustomerInformation $address)
    {
        $response = '404';

        try {
            $headers = [
                'content-type' => 'application/json',
            ];
            $body = [
                "first_name"    => isset($address->shopName) ? $address->shopName : '',
                "address_name"  => "additional_address",
                "address_type"  => "Tax",
                "address_line1" => isset($address->addressLine1) ? $address->addressLine1 : '',
                "address_line4" => isset($address->sub_districts) ? $address->sub_districts : '',
                "branch"        => isset($address->branchId) ? $address->branchId : '',
                "city"          => isset($address->districts) ? $address->districts : '',
                "state"         => isset($address->province) ? $address->province : '',
                "country"       => 'TH',
                "day_phone"     => isset($address->phone) ? $address->phone : '',
                "shop_name"     => isset($address->shopName) ? $address->shopName : '',
                "tax_payer_id"  => isset($address->taxId) ? $address->taxId : '',
                "zip_code"      => isset($address->zipcode) ? $address->zipcode : '',
            ];
            $url     = $this->api['makro_order_api'] . 'orders/' . $orderNumber . '/additional_addresses';
            $options = [
                'headers' => $headers,
                'body'    => json_encode($body),
            ];
            $result = $this->guzzle->curl('PUT', $url, $options);

            if (isset($result['status']['code']) && $result['status']['code'] == '200') {
                $response = $result;
            }

            return $response;
        } catch (\Exception $e) {
            return $response;
        }

    }

    public function sendLogInfo($param)
    {
        $url     = $this->api['makro_order_api'] . 'log';
        $options = [
            'headers' => [
                'content-type' => 'application/json',
            ],
            'body'    => json_encode($param),
        ];
        $result = $this->guzzle->curl('POST', $url, $options);
        return $result;
    }

    public function post($urlEndPoint, $inputRequest)
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/xml; charset=UTF8',
            ],
            'body'    => $inputRequest,
            'verify'  => false,
        ];
        $result = $this->guzzle->eposCurl('POST', $urlEndPoint, $options);
        return $result;
    }

    public function get($urlEndPoint)
    {
        $result = $this->guzzle->eposCurl('GET', $urlEndPoint, ['verify' => false]);
        return $result;
    }

    public function getOrder($orderNo)
    {
        $result = false;

        $userId         = \Session::get('userId');
        $userData       = $this->usersRepository->getUsers(['id' => $userId]);
        $makro_store_id = isset($userData['data'][0]['makro_store_id']) ? $userData['data'][0]['makro_store_id'] : '';

        $url = $this->api['makro_order_api'] . 'orders/?store_id=' . $makro_store_id . '&order_no=' . $orderNo;

        try {
            $res = $this->get($url);
        } catch (\Exception $e) {
            return '404';
        }

        if ($res->getStatusCode() === 200) {
            $responseJson = json_decode($res->getBody()->getContents());

            if ($dataRecords = $this->_getRecords($responseJson)) {

                $result            = new Order();
                $shoppingAddress   = new ShoppingAddress();
                $resultPayment     = new OrderPayment();
                $billingAddress    = new BillingAddress();
                $additionalAddress = new AdditionalAddress();

                $requestContent = ($this->getProp($dataRecords, 'request_content'));

                $result->orderNumber = $this->getProp($dataRecords, 'order_no');
                $result->buyer       = $this->getProp($dataRecords, 'buyer');

                $requestData  = $this->getProp($dataRecords, 'data');
                $result->data = $requestData;
//                $tempDate = explode(' ', $this->getProp($dataRecords, 'created_at'));
                //$tempDate = explode(' ', $this->getProp($requestData, 'order_date'));
                //dump($tempDate);
                //dump();

                //$orderDate = explode('-', $tempDate[0]);
                //$orderDate = $orderDate[2] . '/' . $orderDate[1] . '/' . $orderDate[0] . ' ' . $tempDate[1];
                $result->orderDate = date("d/m/Y", strtotime($this->getProp($requestData, 'order_date')));

                $result->orderStatus = $this->getProp($requestData, 'status');
                if ($result->orderStatus == '') {
                    $result->orderStatus = $this->getProp($dataRecords, 'status');
                }

                $result->branch           = $this->getProp($this->getProp($this->getProp($this->getProp($dataRecords, 'buyer'), 'information'), 'business'), 'branch');
                $result->search_criteria2 = $this->getProp($this->getProp($this->getProp($this->getProp($dataRecords, 'buyer'), 'information'), 'business'), 'shop_name');

                $result->customerName      = $this->getProp($requestData, 'customer_first_name');
                $result->customerLastName  = $this->getProp($requestData, 'customer_last_name');
                $result->makro_member_card = $this->getProp($requestData, 'buyer_user_id');
                $result->tax_payer_id      = $this->getProp($requestData, 'tax_payer_id');

                $result->customerMobile = $this->getProp($requestData, 'customer_phone_no');
                $result->customerEmail  = $this->getProp($requestData, 'customer_email_id');
                $result->pickupStore    = $this->getProp($dataRecords, 'store_id');

                $personInfoShipTo = $this->getProp($requestData, 'person_info_ship_to', false);
                if ($personInfoShipTo) {
                    $shoppingAddress->firstName    = $this->getProp($personInfoShipTo, 'first_name');
                    $shoppingAddress->lastName     = $this->getProp($personInfoShipTo, 'last_name');
                    $shoppingAddress->addressLine1 = $this->getProp($personInfoShipTo, 'address_line1');
                    $shoppingAddress->addressLine4 = $this->getProp($personInfoShipTo, 'address_line4');
                    $shoppingAddress->phone        = $this->getProp($personInfoShipTo, 'mobile_phone');
                    $shoppingAddress->city         = $this->getProp($personInfoShipTo, 'city');
                    $shoppingAddress->state        = $this->getProp($personInfoShipTo, 'state');
                    $shoppingAddress->country      = $this->getProp($personInfoShipTo, 'country');
                    $shoppingAddress->zipCode      = $this->getProp($personInfoShipTo, 'zip_code');
                }

                $personInfoBillTo = $this->getProp($requestData, 'person_info_bill_to', false);
                if ($personInfoBillTo) {
                    $billingAddress->firstName    = $this->getProp($personInfoBillTo, 'first_name');
                    $billingAddress->lastName     = $this->getProp($personInfoBillTo, 'last_name');
                    $billingAddress->addressLine1 = $this->getProp($personInfoBillTo, 'address_line1');
                    $billingAddress->addressLine4 = $this->getProp($personInfoBillTo, 'address_line4');
                    $billingAddress->phone        = $this->getProp($personInfoBillTo, 'mobile_phone');
                    $billingAddress->city         = $this->getProp($personInfoBillTo, 'city');
                    $billingAddress->state        = $this->getProp($personInfoBillTo, 'state');
                    $billingAddress->country      = $this->getProp($personInfoBillTo, 'country');
                    $billingAddress->zipCode      = $this->getProp($personInfoBillTo, 'zip_code');
                }

                $additional_addresses = $this->getProp($requestData, 'additional_addresses', false);
                if ($additional_addresses) {
                    $additional_address = $this->getProp($additional_addresses, 'additional_address', false);
                    if ($additional_address) {
                        $additionalAddress->address_type = $this->getProp($additional_address, 'address_type');
                        $person_info                     = $this->getProp($additional_address, 'person_info', false);
                        $additionalAddress->firstName    = $this->getProp($person_info, 'first_name');
                        $additionalAddress->lastName     = $this->getProp($person_info, 'last_name');
                        $additionalAddress->addressLine1 = $this->getProp($person_info, 'address_line1');
                        $additionalAddress->addressLine4 = $this->getProp($person_info, 'address_line4');
                        $additionalAddress->phone        = $this->getProp($person_info, 'day_phone');
                        $additionalAddress->city         = $this->getProp($person_info, 'city');
                        $additionalAddress->state        = $this->getProp($person_info, 'state');
                        $additionalAddress->country      = $this->getProp($person_info, 'country');
                        $additionalAddress->zipCode      = $this->getProp($person_info, 'zip_code');

                    }
                }
                $paymentMethods = $this->getProp($requestData, 'payment_methods', false);
                $paymentMethod  = $this->getProp($paymentMethods, 'payment_method', false);
                if ($paymentMethod) {
                    $resultPayment->status      = $this->getProp($dataRecords, 'payment_status');
                    $resultPayment->type        = $this->getProp($paymentMethod, 'payment_type');
                    $resultPayment->totalAmount = $this->getProp($paymentMethod, 'max_charge_limit');
                    $resultPayment->ref         = $this->getProp($dataRecords, 'payment_id');

                    //$expire_date = explode(' ', $this->getProp($dataRecords, 'created_at'));
                    //$expire_date = $expire_date[0];
                    //$expire_date = (new DateTime($expire_date))->modify('+2 day')->format('d/m/Y');
                    //$resultPayment->expired = $expire_date;

//                    $expire_date = $this->getProp($dataRecords, 'created_at');
                    $expire_date             = $this->getProp($requestData, 'order_date');
                    $expire_date             = date("Y-m-d", strtotime($expire_date));
                    $expire_date             = (new DateTime($expire_date))->modify('+2 day')->format('d/m/Y');
                    $resultPayment->expired  = $expire_date;
                    $priceInfo               = $this->getProp($requestData, 'price_info');
                    $resultPayment->currency = $this->getProp($priceInfo, 'currency');
                }
                $order_lines = $this->getProp($requestData, 'order_lines');
                $order_line  = $this->getProp($order_lines, 'order_line');

                if ($order_line) {
                    foreach ($order_line as $key => $val) {

                        if (strlen($key) < 6) {
                            $product = new Product();

                            $item = $this->getProp($val, 'item', false);

                            $line_price_info = $this->getProp($val, 'line_price_info', false);

                            if ($item) {
                                $product->id              = $this->getProp($item, 'item_id');
                                $product->name            = $this->getProp($item, 'item_desc');
                                $product->price           = $this->getProp($line_price_info, 'unit_price');
                                $product->origin_quantity = $this->getProp($val, 'original_ordered_qty', '');
                                $product->quantity        = $this->getProp($val, 'ordered_qty');
                                $product->status          = $this->getProp($val, 'status');

                            }

                            $line_taxes = $this->getProp($val, 'line_taxes', false);
                            $line_tax   = $this->getProp($line_taxes, 'line_tax', false);

                            if ($line_tax) {
                                foreach ($line_tax as $k => $v) {
                                    if ($v->charge_category == 'Price') {
                                        $product->vatpercent = round($v->tax_percentage, 0);
                                    }
                                }
                            }
                            $line_charges = $this->getProp($val, 'line_charges', false);
                            if ($line_charges) {
                                $line_charge = $this->getProp($line_charges, 'line_charge', false);
                                if ($line_charge) {
                                    foreach ($line_charge as $k => $v) {

                                        if ($v->charge_category == 'SIMPLE_DISCOUNT') {
                                            $product->simple_discount = $v->charge_per_unit;
                                        }
                                        if ($v->charge_category == 'COMPLEX_DISCOUNT') {
                                            $product->complex_discount = $v->charge_per_unit;
                                        }
                                    }
                                }
                            }

                            $shoppingCart_Array[$key] = $product;
                        }

                    }

                }

                $result->shoppingAddress = $shoppingAddress;

                $result->billingAddress    = $billingAddress;
                $result->additionalAddress = $additionalAddress;
                $result->orderPayment      = $resultPayment;

                $result->shoppingCart = $shoppingCart_Array;

                $result->orderProducts = $this->getProp($dataRecords, 'order_products', false);

            }

        }
        return $result;
    }

    public function getReturnOrder($orderNo)
    {
        $result = false;

        $userId         = \Session::get('userId');
        $userData       = $this->usersRepository->getUsers(['id' => $userId]);
        $makro_store_id = isset($userData['data'][0]['makro_store_id']) ? $userData['data'][0]['makro_store_id'] : '';

        if ($makro_store_id == '') {
            $shipNode = '';
        } else {
            $shipNode = 'ShipNode="' . $makro_store_id . '"';
        }

        $xmlRequest = View::make('epos.xml.return_order.search', ['orderNo' => $orderNo, 'shipNode' => $shipNode])->render();

        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/ordersearch', html_entity_decode($xmlRequest));
        } catch (\Exception $e) {
            return '404';
        }

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();

            if ($content != '') {
                $data = new SimpleXMLElement($content);
                $data->registerXPathNamespace('ns1', 'http://www.sterlingcommerce.com/documentation/YFS/getOrderInvoiceList/output');

                $dataPart = $data->xpath("//NS1:Order");

                $totalRecord = (String) $data->xpath('//NS1:OrderList/@TotalNumberOfRecords')[0];
                if ($totalRecord > 0) {

                    foreach ($dataPart as $key => $value) {

                        $resultRoot = $dataPart[$key]->children("NS1", true);

                        $returnOrder          = new OrderReturn();
                        $returnOrder->orderNo = isset($resultRoot->xpath('//NS1:Order/@OrderNo')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@OrderNo')[$key] : '';
                        //$return_date = explode('T', isset($resultRoot->xpath('//NS1:Order/@OrderDate')[$key]) ? (String)$resultRoot->xpath('//NS1:Order/@OrderDate')[$key] : '');
                        //$return_date = $return_date[0];
                        //$return_date = explode('-', $return_date);
                        //$return_date = $return_date[2] . '/' . $return_date[1] . '/' . $return_date[0];
                        //$returnOrder->date = $return_date;

                        $return_date = isset($resultRoot->xpath('//NS1:Order/@OrderDate')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@OrderDate')[$key] : '';
                        if ($return_date != '') {
                            $return_date = date("d/m/Y", strtotime($return_date));
                        }
                        $returnOrder->date = $return_date;

                        $returnOrder->type    = isset($resultRoot->xpath('//NS1:Order/@Status')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@Status')[$key] : '';
                        $returnOrder->amount  = isset($resultRoot->xpath('//NS1:Order/NS1:PriceInfo/@TotalAmount')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/NS1:PriceInfo/@TotalAmount')[$key] : '';
                        $returnOrder->channel = isset($resultRoot->xpath('//NS1:OrderLines/NS1:OrderLine/@ShipNode')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderLines/NS1:OrderLine/@ShipNode')[$key] : '';
                        $result[$key]         = $returnOrder;
                    }
                }

            }
        }

        return $result;
    }

    public function getOrderFromReturnOrder($returnOrderNo)
    {
        $result = false;

        $xmlRequest = View::make('epos.xml.return_order.order', ['returnOrderNo' => $returnOrderNo])->render();
        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/ordersearch', $xmlRequest);
        } catch (\Exception $e) {
            return '404';
        }

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            if ($content != '') {
                $data = new SimpleXMLElement($content);
                $data->registerXPathNamespace('ns1', 'http://www.sterlingcommerce.com/documentation/YFS/getOrderList/output');

                $dataPart = $data->xpath("//NS1:OrderList");

                $totalRecord = (String) $data->xpath('//NS1:OrderList/@TotalNumberOfRecords')[0];
                if ($totalRecord > 0) {
                    foreach ($dataPart as $key => $value) {

                        $resultRoot = $dataPart[$key]->children("NS1", true);

                        $returnOrder          = new OrderReturn();
                        $returnOrder->orderNo = isset($resultRoot->xpath('//NS1:DerivedFromOrder/@OrderNo')[0]) ? (String) $resultRoot->xpath('//NS1:DerivedFromOrder/@OrderNo')[0] : '';
                        $result[$key]         = $returnOrder;
                    }
                }
            }
        }

        return $result;
    }

    private function callToInvoiceListSearch($xmlRequest)
    {
        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/invoicelist', html_entity_decode($xmlRequest));
        } catch (\Exception $e) {
            return '404';
        }
        return $response;
    }

    public function getInvoices($keyNo, $searchType = '', $invoiceType = '', $invoiceNo = '')
    {
        $result = false;

        try {
            $userId         = \Session::get('userId');
            $userData       = $this->usersRepository->getUsers(['id' => $userId]);
            $makro_store_id = isset($userData['data'][0]['makro_store_id']) ? $userData['data'][0]['makro_store_id'] : '';

            $shipNode = '';
            if ($makro_store_id != '') {
                $shipNode = '<Order ShipNode="' . $makro_store_id . '"/>';
            }

            if ($searchType == 'sale_order_number' || $searchType == 'return_order_number') {
                $orderNo = $keyNo;
                $params  = [
                    'orderNo'     => $orderNo,
                    'shipNode'    => $shipNode,
                    'invoiceType' => $invoiceType,
                    'invoiceNo'   => $invoiceNo,
                ];
                $xml_search = 'epos.xml.invoice.search';

            } else {
                // invoice_number
                $invoiceNo = $keyNo;
                $params    = [
                    'invoiceNo' => $invoiceNo,
                    'shipNode'  => $shipNode,
                ];
                $xml_search = 'epos.xml.invoice.search_makroinvode';
            }
            $xmlRequest = View::make($xml_search, $params)->render();
            //  var_dump($xmlRequest); die();
            // call invoice list to oms
            $response = $this->callToInvoiceListSearch($xmlRequest);
     
            if (is_object($response) && $response->getStatusCode() == 200) {
                $content = $response->getBody()->getContents();
                if ($content != '') {
                    $invoice = [];
                    // see sample return data at 'view.epos.xml.invoice.mock.invoice.return_get_invoice.blade.php'
                    $data = new SimpleXMLElement($content);
                    $data->registerXPathNamespace('ns1', 'http://www.sterlingcommerce.com/documentation/YFS/getOrderInvoiceList/output');
                    $dataPart = $data->xpath("//NS1:OrderInvoiceList");
                    
                    foreach ($dataPart as $idx => $value) {
                        $resultRoot = $dataPart[$idx]->children("NS1", true);
                        $resultLine = $data->xpath("//NS1:OrderInvoice");
                       
                        foreach ($resultLine as $key => $value) {

                            $invoice[$key] = new Invoice();

                            $invoice[$key]->InvoiceNo       = isset($resultRoot->xpath('//NS1:OrderInvoice/@InvoiceNo')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@InvoiceNo')[$key] : '';
                            $invoice[$key]->OrderInvoiceKey = isset($resultRoot->xpath('//NS1:OrderInvoice/@OrderInvoiceKey')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@OrderInvoiceKey')[$key] : '';
                            $invoice[$key]->OrderNo         = isset($resultRoot->xpath('//NS1:OrderInvoice/@OrderNo')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@OrderNo')[$key] : '';

                            // Create Date
                            $create_date = isset($resultRoot->xpath('//NS1:OrderInvoice/@DateInvoiced')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@DateInvoiced')[$key] : '';
                            if ($create_date != '') {
                                $create_date = date("d/m/Y", strtotime($create_date));
                            }
                            $invoice[$key]->CreateDate = $create_date;

                            $invoice[$key]->InvoiceType     = isset($resultRoot->xpath('//NS1:OrderInvoice/@InvoiceType')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@InvoiceType')[$key] : '';
                            $invoice[$key]->TotalAmount     = isset($resultRoot->xpath('//NS1:OrderInvoice/@TotalAmount')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@TotalAmount')[$key] : '';
                            $invoice[$key]->MasterInvoiceNo = isset($resultRoot->xpath('//NS1:OrderInvoice/@MasterInvoiceNo')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@MasterInvoiceNo')[$key] : '';

                            $invoice[$key]->SearchCriteria1 = isset($resultRoot->xpath('//NS1:Order/@SearchCriteria1')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@SearchCriteria1')[$key] : '';

                            // Issue Date
                            $issue_date = isset($resultRoot->xpath('//NS1:OrderInvoice/@DateInvoiced')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderInvoice/@DateInvoiced')[$key] : '';
                            if ($issue_date != '') {
                                $issueDate = new DateTime($issue_date);
                                $issueDate->setTimezone(new DateTimeZone(config('app.timezone')));
                                $issue_date = $issueDate->format('d/m/Y');
                            }
                            $invoice[$key]->IssueDate = $issue_date;

                            $invoice[$key]->PrintCounter           = isset($resultRoot->xpath('//NS1:Extn/@ExtnRunningNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnRunningNumber')[$key] : "";
                            $invoice[$key]->ExtnNewInvoiceNumber   = isset($resultRoot->xpath('//NS1:Extn/@ExtnNewInvoiceNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnNewInvoiceNumber')[$key] : "";
                            $invoice[$key]->ExtnOldInvoiceNumber   = isset($resultRoot->xpath('//NS1:Extn/@ExtnOldInvoiceNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnOldInvoiceNumber')[$key] : "";
                            $invoice[$key]->ExtnMakroInvoiceNumber = isset($resultRoot->xpath('//NS1:Extn/@ExtnMakroInvoiceNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnMakroInvoiceNumber')[$key] : "";

                            // Settlement Status
                            $invoice[$key]->ExtnStatus = isset($resultRoot->xpath('//NS1:Extn/@ExtnStatus')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnStatus')[$key] : '';

                            // Settlement Date
                            $settlement_date = isset($resultRoot->xpath('//NS1:Extn/@ExtnSettlementDate')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnSettlementDate')[$key] : '';

                            $invoice[$key]->ExtnSettlementDate = $this->calculateSettlementDate($invoice[$key]->SearchCriteria1, $invoice[$key]->InvoiceType, $settlement_date);
                            $result[$key]                      = $invoice;
                        }

                        $result[$idx] = $invoice;

                    }
                }

            }
            return $result;
        } catch (\Exception $e) {
            return $result;
        }

    }

    public function getInvoicesDetail($invoice_number)
    {
        $result = false;

        $userId         = \Session::get('userId');
        $userData       = $this->usersRepository->getUsers(['id' => $userId]);
        $makro_store_id = isset($userData['data'][0]['makro_store_id']) ? $userData['data'][0]['makro_store_id'] : '';

        if ($makro_store_id == '') {
            $shipNode = '';
        } else {
            $shipNode = '<OrderLine ShipNode="' . $makro_store_id . '"/>';
        }

        $xmlRequest = View::make('epos.xml.invoice.detail', [
            'invoiceNo' => $invoice_number,
            'shipNode'  => $shipNode,
        ])->render();

        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/invoicedetails', html_entity_decode($xmlRequest));
        } catch (\Exception $e) {
            return '404';
        }

        if ($response->getStatusCode() == 200) {

            $content = $response->getBody()->getContents();
            if ($content != '') {

                $invoice = [];
                // see sample return data at 'view.epos.xml.invoice.mock.invoice.return_get_invoice.blade.php'
                $data = new SimpleXMLElement($content);
                $data->registerXPathNamespace('ns1', 'http://www.sterlingcommerce.com/documentation/YFS/getOrderInvoiceDetailList/output');
                $dataPart = $data->xpath("//NS1:OrderInvoiceDetailList");
                foreach ($dataPart as $idx => $value) {

                    $resultRoot = $dataPart[$idx]->children("NS1", true);
                    $resultLine = $data->xpath("//NS1:OrderInvoiceDetail");

                    foreach ($resultLine as $key => $value) {
                        $invoice[$key] = new Invoice();

                        // Create Date
                        $create_date = isset($resultRoot->xpath('//NS1:InvoiceHeader/@DateInvoiced')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@DateInvoiced')[$key] : '';
                        if ($create_date != '') {
                            $createDate = new DateTime($create_date);
                            $createDate->setTimezone(new DateTimeZone(config('app.timezone')));
                            $create_date = $createDate->format('d/m/Y');
                        }
                        $invoice[$key]->CreateDate = $create_date;

                        $invoice[$key]->InvoiceNo       = isset($resultRoot->xpath('//NS1:InvoiceHeader/@InvoiceNo')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@InvoiceNo')[$key] : '';
                        $invoice[$key]->InvoiceType     = isset($resultRoot->xpath('//NS1:InvoiceHeader/@InvoiceType')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@InvoiceType')[$key] : '';
                        $invoice[$key]->LineSubTotal    = isset($resultRoot->xpath('//NS1:InvoiceHeader/@LineSubTotal')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@LineSubTotal')[$key] : '';
                        $invoice[$key]->MasterInvoiceNo = isset($resultRoot->xpath('//NS1:InvoiceHeader/@MasterInvoiceNo')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@MasterInvoiceNo')[$key] : '';
                        $invoice[$key]->OrderInvoiceKey = isset($resultRoot->xpath('//NS1:InvoiceHeader/@OrderInvoiceKey')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@OrderInvoiceKey')[$key] : '';
                        $invoice[$key]->Reference1      = isset($resultRoot->xpath('//NS1:InvoiceHeader/@Reference1')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@Reference1')[$key] : '';
                        $invoice[$key]->TotalAmount     = isset($resultRoot->xpath('//NS1:InvoiceHeader/@TotalAmount')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@TotalAmount')[$key] : '';

                        //NS1:Order
                        $invoice[$key]->BuyerUserId       = isset($resultRoot->xpath('//NS1:Order/@BuyerUserId')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@BuyerUserId')[$key] : '';
                        $invoice[$key]->CustomerContactID = isset($resultRoot->xpath('//NS1:Order/@CustomerContactID')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@CustomerContactID')[$key] : '';
                        $invoice[$key]->CustomerFirstName = isset($resultRoot->xpath('//NS1:Order/@CustomerFirstName')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@CustomerFirstName')[$key] : '';
                        $invoice[$key]->CustomerLastName  = isset($resultRoot->xpath('//NS1:Order/@CustomerLastName')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@CustomerLastName')[$key] : '';
                        $invoice[$key]->CustomerPhoneNo   = isset($resultRoot->xpath('//NS1:Order/@CustomerPhoneNo')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@CustomerPhoneNo')[$key] : '';
                        $invoice[$key]->DocumentType      = isset($resultRoot->xpath('//NS1:Order/@DocumentType')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@DocumentType')[$key] : '';
                        $invoice[$key]->EnterpriseCode    = isset($resultRoot->xpath('//NS1:Order/@EnterpriseCode')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@EnterpriseCode')[$key] : '';

                        // Order Date
                        $order_date = isset($resultRoot->xpath('//NS1:Order/@OrderDate')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@OrderDate')[$key] : '';
                        if ($order_date != '') {
                            $orderDate = new DateTime($order_date);
                            $orderDate->setTimezone(new DateTimeZone(config('app.timezone')));
                            $order_date = $orderDate->format('d/m/Y');
                        }
                        $invoice[$key]->OrderDate = $order_date;

                        $invoice[$key]->OrderHeaderKey  = isset($resultRoot->xpath('//NS1:Order/@OrderHeaderKey')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@OrderHeaderKey')[$key] : '';
                        $invoice[$key]->OrderNo         = isset($resultRoot->xpath('//NS1:Order/@OrderNo')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@OrderNo')[$key] : '';
                        $invoice[$key]->SearchCriteria1 = isset($resultRoot->xpath('//NS1:Order/@SearchCriteria1')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@SearchCriteria1')[$key] : '';
                        $invoice[$key]->SearchCriteria2 = isset($resultRoot->xpath('//NS1:Order/@SearchCriteria2')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@SearchCriteria2')[$key] : '';
                        $invoice[$key]->ShipNode        = isset($resultRoot->xpath('//NS1:Order/@ShipNode')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@ShipNode')[$key] : '';
                        $invoice[$key]->TaxPayerId      = isset($resultRoot->xpath('//NS1:Order/@TaxPayerId')[$key]) ? (String) $resultRoot->xpath('//NS1:Order/@TaxPayerId')[$key] : '';

                        // Issue Date
                        $issue_date = isset($resultRoot->xpath('//NS1:InvoiceHeader/@DateInvoiced')[$key]) ? (String) $resultRoot->xpath('//NS1:InvoiceHeader/@DateInvoiced')[$key] : '';
                        if ($issue_date != '') {
                            $issueDate = new DateTime($issue_date);
                            $issueDate->setTimezone(new DateTimeZone(config('app.timezone')));
                            $issue_date = $issueDate->format('d/m/Y');
                        }
                        $invoice[$key]->IssueDate = $issue_date;

                        //NS1:OverallTotals
                        $invoice[$key]->AdditionalLinePriceTotal = isset($resultRoot->xpath('//NS1:OverallTotals/@AdditionalLinePriceTotal')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@AdditionalLinePriceTotal')[$key] : '';
                        $invoice[$key]->GrandCharges             = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandCharges')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandCharges')[$key] : '';
                        $invoice[$key]->GrandDiscount            = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandDiscount')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandDiscount')[$key] : '';
                        $invoice[$key]->GrandShippingBaseCharge  = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandShippingBaseCharge')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandShippingBaseCharge')[$key] : '';
                        $invoice[$key]->GrandShippingCharges     = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandShippingCharges')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandShippingCharges')[$key] : '';
                        $invoice[$key]->GrandShippingDiscount    = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandShippingDiscount')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandShippingDiscount')[$key] : '';
                        $invoice[$key]->GrandShippingTotal       = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandShippingTotal')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandShippingTotal')[$key] : '';
                        $invoice[$key]->GrandTax                 = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandTax')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandTax')[$key] : '';
                        $invoice[$key]->GrandTotal               = isset($resultRoot->xpath('//NS1:OverallTotals/@GrandTotal')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@GrandTotal')[$key] : '';
                        $invoice[$key]->HdrCharges               = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrCharges')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrCharges')[$key] : '';
                        $invoice[$key]->HdrDiscount              = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrDiscount')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrDiscount')[$key] : '';
                        $invoice[$key]->HdrShippingBaseCharge    = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrShippingBaseCharge')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrShippingBaseCharge')[$key] : '';
                        $invoice[$key]->HdrShippingCharges       = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrShippingCharges')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrShippingCharges')[$key] : '';
                        $invoice[$key]->HdrShippingDiscount      = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrShippingDiscount')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrShippingDiscount')[$key] : '';
                        $invoice[$key]->HdrShippingTotal         = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrShippingTotal')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrShippingTotal')[$key] : '';
                        $invoice[$key]->HdrTax                   = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrTax')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrTax')[$key] : '';
                        $invoice[$key]->HdrTotal                 = isset($resultRoot->xpath('//NS1:OverallTotals/@HdrTotal')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@HdrTotal')[$key] : '';
                        $invoice[$key]->LineSubTotal             = isset($resultRoot->xpath('//NS1:OverallTotals/@LineSubTotal')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@LineSubTotal')[$key] : '';
                        $invoice[$key]->ManualDiscountPercentage = isset($resultRoot->xpath('//NS1:OverallTotals/@ManualDiscountPercentage')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@ManualDiscountPercentage')[$key] : '';
                        $invoice[$key]->PercentProfitMargin      = isset($resultRoot->xpath('//NS1:OverallTotals/@PercentProfitMargin')[$key]) ? (String) $resultRoot->xpath('//NS1:OverallTotals/@PercentProfitMargin')[$key] : '';

                        //NS1:Note
                        $invoice[$key]->NoteText = isset($resultRoot->xpath('//NS1:Note/@NoteText')[$key]) ? (string) $resultRoot->xpath('//NS1:Note/@NoteText')[$key] : '';

                        //shippong fee
                        $chargeAmount = isset($resultRoot->xpath('//NS1:HeaderCharge/@ChargeAmount')[$key]) ? (string) $resultRoot->xpath('//NS1:HeaderCharge/@ChargeAmount')[$key] : 0;
                        $tax          = isset($resultRoot->xpath('//NS1:HeaderTaxes/@Tax')[$key]) ? (string) $resultRoot->xpath('//NS1:HeaderTaxes/@Tax')[$key] : 0;
                        $ShippingFee  = ($chargeAmount < 0) ? (-1) * $chargeAmount : $chargeAmount;
                        $ShippingVat  = ($tax < 0) ? (-1) * $tax : $tax;

                        if (isset($invoice[$key]->OrderNo) && (!empty($chargeAmount) || !empty($tax))) {
                            $delivery = $this->getDelivery($invoice[$key]->OrderNo);
                            $ShippingFee  = isset($delivery['delivery_fee_exc_vat']) ? $delivery['delivery_fee_exc_vat'] : 0;
                            $ShippingVat  = isset($delivery['delivery_fee_vat']) ? $delivery['delivery_fee_vat'] : 0;
                        }
                        $invoice[$key]->ShippingFee = $ShippingFee;
                        $invoice[$key]->ShippingVat = $ShippingVat;

                        $invoice[$key]->AmtShippingFee = $ShippingFee + $ShippingVat;

                        //NS1:PersonInfoShipTo
                        $invoice[$key]->ShipToAddressLine1 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine1')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine1')[$key] : '';
                        $invoice[$key]->ShipToAddressLine2 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine2')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine2')[$key] : '';
                        $invoice[$key]->ShipToAddressLine3 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine3')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine3')[$key] : '';
                        $invoice[$key]->ShipToAddressLine4 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine4')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine4')[$key] : '';
                        $invoice[$key]->ShipToAddressLine5 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine5')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine5')[$key] : '';
                        $invoice[$key]->ShipToAddressLine6 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine6')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine6')[$key] : '';
                        $invoice[$key]->ShipToCity         = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@City')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@City')[$key] : '';
                        $invoice[$key]->ShipToCountry      = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@Country')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@Country')[$key] : '';
                        $invoice[$key]->ShipToDayPhone     = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@DayPhone')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@DayPhone')[$key] : '';
                        $invoice[$key]->ShipToEMailID      = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@EMailID')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@EMailID')[$key] : '';
                        $invoice[$key]->ShipToFirstName    = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@FirstName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@FirstName')[$key] : '';
                        $invoice[$key]->ShipToLastName     = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@LastName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@LastName')[$key] : '';
                        $invoice[$key]->ShipToMiddleName   = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@MiddleName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@MiddleName')[$key] : '';
                        $invoice[$key]->ShipToState        = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@State')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@State')[$key] : '';
                        $invoice[$key]->ShipToZipCode      = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@ZipCode')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@ZipCode')[$key] : '';

                        //NS1:PersonInfoBillTo
                        $invoice[$key]->BillToAddressLine1 = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine1')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine1')[$key] : '';
                        $invoice[$key]->BillToAddressLine2 = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine2')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine2')[$key] : '';
                        $invoice[$key]->BillToAddressLine3 = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine3')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine3')[$key] : '';
                        $invoice[$key]->BillToAddressLine4 = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine4')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine4')[$key] : '';
                        $invoice[$key]->BillToAddressLine5 = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine5')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine5')[$key] : '';
                        $invoice[$key]->BillToAddressLine6 = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine6')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@AddressLine6')[$key] : '';
                        $invoice[$key]->BillToCity         = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@City')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@City')[$key] : '';
                        $invoice[$key]->BillToCountry      = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@Country')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@Country')[$key] : '';
                        $invoice[$key]->BillToDayPhone     = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@DayPhone')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@DayPhone')[$key] : '';
                        $invoice[$key]->BillToEMailID      = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@EMailID')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@EMailID')[$key] : '';
                        $invoice[$key]->BillToFirstName    = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@FirstName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@FirstName')[$key] : '';
                        $invoice[$key]->BillToLastName     = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@LastName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@LastName')[$key] : '';
                        $invoice[$key]->BillToMiddleName   = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@MiddleName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@MiddleName')[$key] : '';
                        $invoice[$key]->BillToState        = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@State')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@State')[$key] : '';
                        $invoice[$key]->BillToZipCode      = isset($resultRoot->xpath('//NS1:PersonInfoBillTo/@ZipCode')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoBillTo/@ZipCode')[$key] : '';

                        //NS1:PersonInfo
                        $invoice[$key]->PersonInfoAddressLine1 = isset($resultRoot->xpath('//NS1:PersonInfo/@AddressLine1')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@AddressLine1')[$key] : '';
                        $invoice[$key]->PersonInfoAddressLine2 = isset($resultRoot->xpath('//NS1:PersonInfo/@AddressLine2')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@AddressLine2')[$key] : '';
                        $invoice[$key]->PersonInfoAddressLine3 = isset($resultRoot->xpath('//NS1:PersonInfo/@AddressLine3')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@AddressLine3')[$key] : '';
                        $invoice[$key]->PersonInfoAddressLine4 = isset($resultRoot->xpath('//NS1:PersonInfo/@AddressLine4')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@AddressLine4')[$key] : '';
                        $invoice[$key]->PersonInfoAddressLine5 = isset($resultRoot->xpath('//NS1:PersonInfo/@AddressLine5')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@AddressLine5')[$key] : '';
                        $invoice[$key]->PersonInfoAddressLine6 = isset($resultRoot->xpath('//NS1:PersonInfo/@AddressLine6')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@AddressLine6')[$key] : '';
                        $invoice[$key]->PersonInfoCity         = isset($resultRoot->xpath('//NS1:PersonInfo/@City')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@City')[$key] : '';
                        $invoice[$key]->PersonInfoCountry      = isset($resultRoot->xpath('//NS1:PersonInfo/@Country')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@Country')[$key] : '';
                        $invoice[$key]->PersonInfoDayPhone     = isset($resultRoot->xpath('//NS1:PersonInfo/@DayPhone')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@DayPhone')[$key] : '';
                        $invoice[$key]->PersonInfoEMailID      = isset($resultRoot->xpath('//NS1:PersonInfo/@EMailID')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@EMailID')[$key] : '';
                        $invoice[$key]->PersonInfoFirstName    = isset($resultRoot->xpath('//NS1:PersonInfo/@FirstName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@FirstName')[$key] : '';
                        $invoice[$key]->PersonInfoLastName     = isset($resultRoot->xpath('//NS1:PersonInfo/@LastName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@LastName')[$key] : '';
                        $invoice[$key]->PersonInfoMiddleName   = isset($resultRoot->xpath('//NS1:PersonInfo/@MiddleName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@MiddleName')[$key] : '';
                        $invoice[$key]->PersonInfoState        = isset($resultRoot->xpath('//NS1:PersonInfo/@State')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@State')[$key] : '';
                        $invoice[$key]->PersonInfoZipCode      = isset($resultRoot->xpath('//NS1:PersonInfo/@ZipCode')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfo/@ZipCode')[$key] : '';

                        //NS1:PriceInfo
                        $invoice[$key]->Currency = isset($resultRoot->xpath('//NS1:PriceInfo/@Currency')[$key]) ? (String) $resultRoot->xpath('//NS1:PriceInfo/@Currency')[$key] : '';

                        //NS1:Extn
                        $invoice[$key]->ExtnMakroInvoiceNumber = isset($resultRoot->xpath('//NS1:Extn/@ExtnMakroInvoiceNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnMakroInvoiceNumber')[$key] : '';
                        $invoice[$key]->ExtnNewInvoiceNumber   = isset($resultRoot->xpath('//NS1:Extn/@ExtnNewInvoiceNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnNewInvoiceNumber')[$key] : '';
                        $invoice[$key]->ExtnOldInvoiceNumber   = isset($resultRoot->xpath('//NS1:Extn/@ExtnOldInvoiceNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnOldInvoiceNumber')[$key] : '';
                        $invoice[$key]->ExtnRunningNumber      = isset($resultRoot->xpath('//NS1:Extn/@ExtnRunningNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnRunningNumber')[$key] : '';

                        // Settlement Status
                        $invoice[$key]->ExtnStatus = isset($resultRoot->xpath('//NS1:Extn/@ExtnStatus')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnStatus')[$key] : '';

                        // Settlement Date
                        $settlement_date                   = isset($resultRoot->xpath('//NS1:Extn/@ExtnSettlementDate')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnSettlementDate')[$key] : '';
                        $invoice[$key]->ExtnSettlementDate = $this->calculateSettlementDate($invoice[$key]->SearchCriteria1, $invoice[$key]->InvoiceType, $settlement_date);

                        //NS1:OrderLine
                        $invoice[$key]->OrderedQty        = isset($resultRoot->xpath('//NS1:OrderLine/@OrderedQty')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderLine/@OrderedQty')[$key] : '';
                        $invoice[$key]->PrimeLineNo       = isset($resultRoot->xpath('//NS1:OrderLine/@PrimeLineNo')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderLine/@PrimeLineNo')[$key] : '';
                        $invoice[$key]->OrderLineShipNode = isset($resultRoot->xpath('//NS1:OrderLine/@ShipNode')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderLine/@ShipNode')[$key] : '';
                        $invoice[$key]->Status            = isset($resultRoot->xpath('//NS1:OrderLine/@Status')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderLine/@Status')[$key] : '';

                        //NS1:Item
                        $invoice[$key]->ItemID        = isset($resultRoot->xpath('//NS1:Item/@ItemID')[$key]) ? (String) $resultRoot->xpath('//NS1:Item/@ItemID')[$key] : '';
                        $invoice[$key]->ProductClass  = isset($resultRoot->xpath('//NS1:Item/@ProductClass')[$key]) ? (String) $resultRoot->xpath('//NS1:Item/@ProductClass')[$key] : '';
                        $invoice[$key]->UnitOfMeasure = isset($resultRoot->xpath('//NS1:Item/@UnitOfMeasure')[$key]) ? (String) $resultRoot->xpath('//NS1:Item/@UnitOfMeasure')[$key] : '';

                        //NS1:LinePriceInfo
                        $invoice[$key]->TaxableFlag = isset($resultRoot->xpath('//NS1:LinePriceInfo/@TaxableFlag')[$key]) ? (String) $resultRoot->xpath('//NS1:LinePriceInfo/@TaxableFlag')[$key] : '';
                        $invoice[$key]->UnitPrice   = isset($resultRoot->xpath('//NS1:LinePriceInfo/@UnitPrice')[$key]) ? (String) $resultRoot->xpath('//NS1:LinePriceInfo/@UnitPrice')[$key] : '';

                        //NS1:PersonInfoShipTo
                        $invoice[$key]->PersonInfoShipToAddressLine1 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine1')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine1')[$key] : '';
                        $invoice[$key]->PersonInfoShipToAddressLine2 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine2')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine2')[$key] : '';
                        $invoice[$key]->PersonInfoShipToAddressLine3 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine3')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine3')[$key] : '';
                        $invoice[$key]->PersonInfoShipToAddressLine4 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine4')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine4')[$key] : '';
                        $invoice[$key]->PersonInfoShipToAddressLine5 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine5')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine5')[$key] : '';
                        $invoice[$key]->PersonInfoShipToAddressLine6 = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine6')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@AddressLine6')[$key] : '';
                        $invoice[$key]->PersonInfoShipToCity         = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@City')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@City')[$key] : '';
                        $invoice[$key]->PersonInfoShipToCountry      = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@Country')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@Country')[$key] : '';
                        $invoice[$key]->PersonInfoShipToDayPhone     = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@DayPhone')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@DayPhone')[$key] : '';
                        $invoice[$key]->PersonInfoShipToEMailID      = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@EMailID')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@EMailID')[$key] : '';
                        $invoice[$key]->PersonInfoShipToFirstName    = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@FirstName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@FirstName')[$key] : '';
                        $invoice[$key]->PersonInfoShipToLastName     = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@LastName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@LastName')[$key] : '';
                        $invoice[$key]->PersonInfoShipToMiddleName   = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@MiddleName')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@MiddleName')[$key] : '';
                        $invoice[$key]->PersonInfoShipToState        = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@State')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@State')[$key] : '';
                        $invoice[$key]->PersonInfoShipToZipCode      = isset($resultRoot->xpath('//NS1:PersonInfoShipTo/@ZipCode')[$key]) ? (String) $resultRoot->xpath('//NS1:PersonInfoShipTo/@ZipCode')[$key] : '';

                        //NS1:Note
                        $invoice[$key]->NoteNoteText = isset($resultRoot->xpath('//NS1:OrderLine//NS1:Notes//NS1:Note/@NoteText')[$key]) ? (String) $resultRoot->xpath('//NS1:OrderLine//NS1:Notes//NS1:Note/@NoteText')[$key] : '';

                        //NS1:LineDetailTranQuantity
                        $invoice[$key]->PricingQty       = isset($resultRoot->xpath('//NS1:LineDetailTranQuantity/@PricingQty')[$key]) ? (String) $resultRoot->xpath('//NS1:LineDetailTranQuantity/@PricingQty')[$key] : '';
                        $invoice[$key]->Quantity         = isset($resultRoot->xpath('//NS1:LineDetailTranQuantity/@Quantity')[$key]) ? (String) $resultRoot->xpath('//NS1:LineDetailTranQuantity/@Quantity')[$key] : '';
                        $invoice[$key]->ShippedQty       = isset($resultRoot->xpath('//NS1:LineDetailTranQuantity/@ShippedQty')[$key]) ? (String) $resultRoot->xpath('//NS1:LineDetailTranQuantity/@ShippedQty')[$key] : '';
                        $invoice[$key]->TransactionalUOM = isset($resultRoot->xpath('//NS1:LineDetailTranQuantity/@TransactionalUOM')[$key]) ? (String) $resultRoot->xpath('//NS1:LineDetailTranQuantity/@TransactionalUOM')[$key] : '';

                        //NS1:LineCharge
                        $invoice[$key]->LineChargeChargeAmount          = isset($resultRoot->xpath('//NS1:LineCharge/@ChargeAmount')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@ChargeAmount')[$key] : '';
                        $invoice[$key]->LineChargeChargeCategory        = isset($resultRoot->xpath('//NS1:LineCharge/@ChargeCategory')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@ChargeCategory')[$key] : '';
                        $invoice[$key]->LineChargeChargeName            = isset($resultRoot->xpath('//NS1:LineCharge/@ChargeName')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@ChargeName')[$key] : '';
                        $invoice[$key]->LineChargeChargePerLine         = isset($resultRoot->xpath('//NS1:LineCharge/@ChargePerLine')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@ChargePerLine')[$key] : '';
                        $invoice[$key]->LineChargeChargePerUnit         = isset($resultRoot->xpath('//NS1:LineCharge/@ChargePerUnit')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@ChargePerUnit')[$key] : '';
                        $invoice[$key]->LineChargeIsBillable            = isset($resultRoot->xpath('//NS1:LineCharge/@IsBillable')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@IsBillable')[$key] : '';
                        $invoice[$key]->LineChargeIsDiscount            = isset($resultRoot->xpath('//NS1:LineCharge/@IsDiscount')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@IsDiscount')[$key] : '';
                        $invoice[$key]->LineChargeIsShippingCharge      = isset($resultRoot->xpath('//NS1:LineCharge/@IsShippingCharge')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@IsShippingCharge')[$key] : '';
                        $invoice[$key]->LineChargeOriginalChargePerLine = isset($resultRoot->xpath('//NS1:LineCharge/@OriginalChargePerLine')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@OriginalChargePerLine')[$key] : '';
                        $invoice[$key]->LineChargeOriginalChargePerUnit = isset($resultRoot->xpath('//NS1:LineCharge/@OriginalChargePerUnit')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@OriginalChargePerUnit')[$key] : '';
                        $invoice[$key]->LineChargeReference             = isset($resultRoot->xpath('//NS1:LineCharge/@Reference')[$key]) ? (String) $resultRoot->xpath('//NS1:LineCharge/@Reference')[$key] : '';

                        //NS1:LineTax
                        $invoice[$key]->ChargeCategory = isset($resultRoot->xpath('//NS1:LineTax/@ChargeCategory')[$key]) ? (String) $resultRoot->xpath('//NS1:LineTax/@ChargeCategory')[$key] : '';
                        $invoice[$key]->ChargeName     = isset($resultRoot->xpath('//NS1:LineTax/@ChargeName')[$key]) ? (String) $resultRoot->xpath('//NS1:LineTax/@ChargeName')[$key] : '';
                        $invoice[$key]->TaxName        = isset($resultRoot->xpath('//NS1:LineTax/@TaxName')[$key]) ? (String) $resultRoot->xpath('//NS1:LineTax/@TaxName')[$key] : '';
                        $invoice[$key]->TaxPercentage  = isset($resultRoot->xpath('//NS1:LineTax/@TaxPercentage')[$key]) ? (String) $resultRoot->xpath('//NS1:LineTax/@TaxPercentage')[$key] : '';

                        //NS1:TaxSummaryDetail
                        $invoice[$key]->TaxSummaryDetailTax     = isset($resultRoot->xpath('//NS1:TaxSummaryDetail/@Tax')[$key]) ? (String) $resultRoot->xpath('//NS1:TaxSummaryDetail/@Tax')[$key] : '';
                        $invoice[$key]->TaxSummaryDetailTaxName = isset($resultRoot->xpath('//NS1:TaxSummaryDetail/@TaxName')[$key]) ? (String) $resultRoot->xpath('//NS1:TaxSummaryDetail/@TaxName')[$key] : '';

                        $invoice[$key]->PrintCounter = isset($resultRoot->xpath('//NS1:Extn/@ExtnRunningNumber')[$key]) ? (String) $resultRoot->xpath('//NS1:Extn/@ExtnRunningNumber')[$key] : '';
                    }

                    $result[$idx] = $invoice;
                }

            } else {
                return '500';
            }

        }

        return $result;
    }

    public function getInvoicesPrint($orderInvoiceKey, $print_inc)
    {

        $result = false;

        $xmlRequest = View::make('epos.xml.invoice.reprint', ['invoiceNo' => $orderInvoiceKey, 'print_inc' => $print_inc])->render();

        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/reprintinvoice', $xmlRequest);
        } catch (\Exception $e) {
            return '404';
        }

        if ($response->getStatusCode() == 200) {

            $content = $response->getBody()->getContents();

            if ($content != '') {
                $data = new SimpleXMLElement($content);

                $errorCode = $data->xpath("//NS1:Error/@ErrorCode");

                if ($errorCode != []) {
                    $errorCode            = $errorCode[0];
                    $errorMessage         = (String) $data->xpath("//NS1:Error/@ErrorDescription")[0];
                    $result               = new ResultStatus();
                    $result->error        = true;
                    $result->errorCode    = $errorCode;
                    $result->errorMessage = $errorMessage;
                    return $result;
                }

                $result        = new ResultStatus();
                $result->error = false;
                return $result;

            }
        }

        return $result;
    }

    public function getRefund($fromDate = '', $endDate = '', $status = '', $invoiceType)
    {
        $result = false;

        $userId         = \Session::get('userId');
        $userData       = $this->usersRepository->getUsers(['id' => $userId]);
        $makro_store_id = isset($userData['data'][0]['makro_store_id']) ? $userData['data'][0]['makro_store_id'] : '';

        if ($makro_store_id == '') {
            $shipNode = '';
        } else {
            $shipNode = '<Order ShipNode="' . $makro_store_id . '"/>';
        }

        if ($fromDate != '') {
            $fromDate = explode('/', $fromDate);
            $fromDate = $fromDate[2] . '-' . $fromDate[1] . '-' . $fromDate[0];
        }

        if ($endDate != '') {
            $endDate = explode('/', $endDate);
            $endDate = $endDate[2] . '-' . $endDate[1] . '-' . $endDate[0];
        }

        $xmlRequest = View::make('epos.xml.refund.search', [
            'fromDate'    => $fromDate,
            'endDate'     => $endDate,
            'status'      => $status,
            'invoiceType' => $invoiceType,
            'shipNode'    => $shipNode,
        ])->render();

        try {
            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/invoicelist', html_entity_decode($xmlRequest));
        } catch (\Exception $e) {
            return '404';
        }

        if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();
            if ($content) {
                // see sample return data at 'view.epos.xml.invoice.mock.refund.return_search.blade.php'
                $data = new SimpleXMLElement($content);
                $data->registerXPathNamespace('ns1', 'http://www.sterlingcommerce.com/documentation/YFS/getOrderInvoiceList/output');
                $dataPart = $data->xpath("//NS1:OrderInvoiceList");

                foreach ($dataPart as $key => $value) {
                    $resultRoot = $dataPart[$key]->children("NS1", true);
                    $resultLine = $data->xpath("//NS1:OrderInvoice");
                    foreach ($resultLine as $key => $value) {
                        $item            = new RefundItem();
                        $item->invoiceNo = (String) $resultRoot->xpath('//NS1:OrderInvoice/@InvoiceNo')[$key];
                        //$create_date = explode('T', (String)$resultRoot->xpath('//NS1:OrderInvoice/@DateInvoiced')[$key]);
                        //$create_date = $create_date[0];
                        //$create_date = explode('-', $create_date);
                        //$item->createDate = $create_date[2] . '/' . $create_date[1] . '/' . $create_date[0];

                        $item->createDate = date("d/m/Y", strtotime((String) $resultRoot->xpath('//NS1:OrderInvoice/@DateInvoiced')[$key]));

                        $item->shipNode    = (String) $resultRoot->xpath('//NS1:Order/@ShipNode')[$key];
                        $item->paymentType = (String) $resultRoot->xpath('//NS1:PaymentMethod/@PaymentType')[$key];
                        $item->amount      = (String) $resultRoot->xpath('//NS1:OrderInvoice/@TotalAmount')[$key];
                        //$modify_date = explode('T', (String)$resultRoot->xpath('//NS1:OrderInvoice/@Modifyts')[$key]);
                        //$modify_date = $modify_date[0];
                        //$modify_date = explode('-', $modify_date);
                        //$modify_date = $modify_date[2] . '/' . $modify_date[1] . '/' . $modify_date[0];
                        $item->modifyDate = date("d/m/Y", strtotime((String) $resultRoot->xpath('//NS1:OrderInvoice/@Modifyts')[$key]));
                        $item->status     = (String) $resultRoot->xpath('//NS1:OrderInvoice/NS1:Extn/@ExtnStatus')[$key];
                        //$item->order = $create_date[0] . $create_date[1] . $create_date[2];
                        $item->order  = date("Ymd", strtotime((String) $resultRoot->xpath('//NS1:OrderInvoice/@DateInvoiced')[$key]));
                        $result[$key] = $item;
                    }

                }
            }
        }

        return $result;
    }

    public function updateRefund($invoiceNo, $status, $settlementDate)
    {

        $result     = false;
        $xmlRequest = View::make('epos.xml.invoice.refund', ['invoiceNo' => $invoiceNo, 'status' => $status, 'settlementDate' => $settlementDate])->render();

        try {

            $response = $this->post($this->api['makro_epos_api'] . 'eai/order/refundupdate', $xmlRequest);

            $result = true;

        } catch (\Exception $e) {
            return '404';
        }

        return $result;
    }

    public function safeValue($value, $default = '')
    {
        return isset($value) ? $value : $default;
    }

    public function getProp($src, $propName, $default = '')
    {
//        dump($propName);
        return (property_exists($src, $propName) && isset($src->{$propName})) ? $src->{$propName} : $default;
    }

    private function _getRecords($responseData)
    {

        if (!isset($responseData->data)
            || !isset($responseData->data->records)
            || count($responseData->data->records) < 1) {

            return false;
        }

        return $responseData->data->records[0];
    }

    public function getInvoiceType($value)
    {
        if ($value == 'INFO') {
            return 'Deposit Tax Invoice';
        }
        if ($value == 'CREDIT_MEMO') {
            return 'Credit Note (Cancel)';
        }
        if ($value == 'SHIPMENT') {
            return 'Normal Tax Invoice';
        }
        if ($value == 'RETURN' || $value == 'Return Invoiced') {
            return 'Credit Note (Return)';
        }

        return '';

    }

    public function getInvoiceTypeName($type, $tax_id, $useLongForm)
    {

        if ($type == 'INFO') {
            if ($useLongForm) {
                return array("TH" => "ใบเสร็จรับเงิน/ใบกำกับภาษี", "EN" => "RECEIPT / TAX INVOICE");
            } else {
                return array("TH" => "ใบเสร็จรับเงิน/ใบกำกับภาษีอย่างย่อ", "EN" => "RECEIPT / TAX INVOICE (ABB.)");
            }
        }

        if ($type == 'SHIPMENT') {
            if ($useLongForm) {
                return array("TH" => "ใบกำกับภาษี / ใบเสร็จรับเงิน / ใบส่งสินค้า", "EN" => "TAX INVOICE / RECEIPT / DELIVERY ORDER");
            } else {
                return array("TH" => "ใบกำกับภาษีอย่างย่อ / ใบเสร็จรับเงิน / ใบส่งสินค้า", "EN" => "TAX INVOICE (ABB.) / RECEIPT / DELIVERY ORDER");
            }
        }

        if ($type == 'CREDIT_MEMO' || $type == 'RETURN' || $type == 'Return Invoiced') {
            return array("TH" => "ใบลดหนี้", "EN" => "CREDIT NOTE");
        }

        return '';

    }

    public function addIssueDate($issueDate, $searchCriteria1, $invoiceType)
    {

        $config = $this->getConfig('epos', 'code', 'issue_date');

        $workTime = date('Y-m-d', strtotime($issueDate)) . ' ' . $config;

        if ($issueDate != '') {
            if ($searchCriteria1 == 'CC' && (($invoiceType == 'INFO') || ($invoiceType == 'CREDIT_MEMO'))) {
                if (strtotime($workTime) < strtotime($issueDate)) {
                    return Config::get('invoice.add_issue_day', 0);
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getConfig($config_type = '', $order = '', $name = '')
    {

        $config = Config::get('invoice.add_issue_date');

        $url = $this->api['makro_config_api'] . 'configs?config_type=' . $config_type . '&order=' . $order . '|ASC&name=' . $name;

        try {
            $res = $this->get($url);
        } catch (\Exception $e) {
            return '404';
        }

        if ($res->getStatusCode() === 200) {
            $responseJson = json_decode($res->getBody()->getContents());

            if ($dataRecords = $this->_getRecords($responseJson)) {

                $status = $this->getProp($dataRecords, 'status', '');
                if ($status == 'active') {
                    $config = $this->getProp($dataRecords, 'value', '');
                } else {
                    $config = Config::get('invoice.add_issue_date');
                }
            }
        }

        return $config;
    }

    public function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array      = array();
        $sortable_array = array();
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
//                if (is_array($v)) {
                if (isset($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function getAmountDetail($orderNumber)
    {
        $url           = $this->api['makro_order_api'] . 'orders/' . $orderNumber . '/invoiceAmount';
        $amount_detail = array();

        try {
            $client  = new Client;
            $request = $client->request('GET', $url);
            $result  = json_decode($request->getBody());

            if ($result->status->code == 200) {
                $amount_detail['amountExcVat'] = $result->data->summary->sellingPrice;
                $amount_detail['sellingVat']   = $result->data->summary->vatAmount;
                $amount_detail['amountIncVat'] = $result->data->summary->totalPrice;
                return $amount_detail;
            }

        } catch (\Exception $e) {
            return '404';
        }
    }

    public function getCouponDiscount($orderNumber)
    {
        $discount = 0;
        $url      = $this->api['makro_order_api'] . 'orders?exclude_oms_content=1&order_no=' . $orderNumber;

        $coupons = array();
        try {
            $client  = new Client;
            $request = $client->request('GET', $url);
            $result  = json_decode($request->getBody());

            if ($result->status->code == 200) {
                if (isset($result->data->records[0]->coupons)) {
                    $coupons = $result->data->records[0]->coupons;
                    foreach ($coupons as $coupon) {
                        $discount += $coupon->amount;
                    }
                }

            }
            return $discount;

        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getLatestReplaceInvoiceDate($order_number)
    {
        $url         = $this->api['makro_order_api'] . 'orders/last/replace/' . $order_number;
        $latest_date = (new DateTime())->format('Y-m-d H:i:s');

        try {
            $client  = new Client;
            $request = $client->request('GET', $url);
            $result  = json_decode($request->getBody());

            if ($result->status->code == 200) {
                if (isset($result->data->records)) {
                    $latest_date = $result->data->records->created_at;
                }
            }
            return $latest_date;
        } catch (\Exception $e) {
            return $latest_date;
        }
    }

    public function getDelivery($orderNumber)
    {
        $url    = $this->api['makro_order_api'] . 'orders/' . $orderNumber;

        $output = [
            'delivery_vat_percentage' => 0,
            'delivery_fee_exc_vat'    => 0,
            'delivery_fee_vat'        => 0,
            'delivery_fee'            => 0,
        ];

        try {
            $client  = new Client;
            $request = $client->request('GET', $url);
            $result  = json_decode($request->getBody());

            if ($result->status->code == 200) {
                $output = [
                    'delivery_vat_percentage' => isset($result->data->records[0]->delivery_vat_percentage) ? $result->data->records[0]->delivery_vat_percentage : 0,
                    'delivery_fee_exc_vat'    => isset($result->data->records[0]->delivery_fee_exc_vat) ? $result->data->records[0]->delivery_fee_exc_vat : 0,
                    'delivery_fee_vat'        => isset($result->data->records[0]->delivery_fee_vat) ? $result->data->records[0]->delivery_fee_vat : 0,
                    'delivery_fee'            => isset($result->data->records[0]->delivery_fee) ? $result->data->records[0]->delivery_fee : 0,
                ];
            }
            return $output;

        } catch (\Exception $e) {
            return $output;
        }
    }
}
