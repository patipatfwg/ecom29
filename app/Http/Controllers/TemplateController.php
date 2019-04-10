<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Services\Guzzle;
use GuzzleHttp\Client;
use App\Services\ThaiString;
use App\Http\Controllers\EPOS\EPOSBaseController;
use App\Repositories\UsersRepository;
use PDF;

class TemplateController extends \App\Http\Controllers\BaseController
{
    private $raw_shipping = 0;
    private $vat_shipping = 0;
    private $shipping_fee = 0;
    protected $view = [
        'index'            => 'template.index',
        'print'            => 'template.print',
        'preview'          => 'template.preview',
        'test_preview'     => 'template.test_preview',
        'raw_print'        => 'template.raw_print',
        'test_invoice'     => 'template.test_invoice',
        'test_invoice_pdf' => 'template_pdf.test_invoice_pdf'
    ];

    protected $detailMaxLine   = 14;
    protected $deliveryFeeLine = 1 ;

    public function __construct(Guzzle $guzzle, EPOSBaseController $eposBaseController, UsersRepository $user)
    {
        $this->guzzle = $guzzle;
        $this->eposBaseController = $eposBaseController;
        $this->userRepository = $user;
    }

    protected function informationMock()
    {
        return [
            'customer_no'        => '05815660670710',
            'customer_name'      => 'name no tax buyer last no tax buyer',
            'customer_address1'  => 'ซอย ประชาสงเคราะห์ 41 no tax buyer คันนายาว',
            'customer_address2'  => 'คันนายาว กรุงเทพมหานคร 10230',
            'customer_tax_id'    => '1100900220031',
            'customer_branch'    => '00001',
            'receipt_branch'     => '00077',
            'receipt_address'    => 'บมจ.สยามแม็คโคร สาขามุกดาหาร-test 4/9 ถ.ชยางกูร ก ต.มุกดาหาร อ.เมือง มุกดาหาร 49000',
            'receipt_post_id'    => '',
            'tax_invoice_no'     => '058351001241',
            'tax_invoice_date'   => '16/10/2017',
            'order_date'         => '16/10/2017',
            'order_no'           => '1000004071',
            'payment_type'       => 'CC',
            'deposit_receipt'    => '058351001227',
            'shipping_address'   => '',
            'address'            => '',
            'receiver_name'      => '',
            'receiver_email'     => '',
            'receiver_telephone' => ''
        ];
    }

    protected function templateDataMock()
    {
        return [
            'invoices' => [
                [
                'invoice_type'  => 'credit-note',
                'format_type'   => 'short',
                'template_type' => 'replace',
                'reprint' => [
                    'count' => '1',
                    'date'  => 'วันที่ 10 ตุลาคม 2560'
                ],
                'replace' => [
                    'old_invoice_number' => '058351001241'
                ],
                'pagination' => [
                    'current_page' => '1',
                    'total_page'   => '2'
                ],
                'copy_for' => 'company',
                'information' => $this->informationMock(),
                'reference' => [
                    'deposit_invoice_number' => '107',
                    'deposit_invoice_date' => '10/8/2017'
                ],
                'item_lines' => [
                    [
                        'type' => 'item-line-header',
                        'text' => 'รับชำระราคาสินค้า'
                    ],
                    [
                        'type'         => 'item-line',
                        'no'           => '1',
                        'item_id'      => '10240',
                        'description'  => 'xxxx',
                        'quantity'     => '5',
                        'unit'         => 'EACH',
                        'unit_price'   => '300.00',
                        'vat_code'     => '2',
                        'total_amount' => '1,500.00'
                    ],
                    [
                        'type'         => 'item-line',
                        'no'           => '2',
                        'item_id'      => '10240',
                        'description'  => 'xxxx',
                        'quantity'     => '5',
                        'unit'         => 'EACH',
                        'unit_price'   => '300.00',
                        'vat_code'     => '2',
                        'total_amount' => '1,500.00'
                    ],
                    [
                        'type' => 'empty',
                    ],
                    [
                        'type' => 'discount-header'
                    ],
                    [
                        'type'                 => 'discount-line',
                        'no'                   => '1',
                        'discount_description' => 'Buy A 3 items get 50Bath discount',
                        'discount_amount'      => '50.00'
                    ],
                    [
                        'type'                 => 'discount-line',
                        'no'                   => '2',
                        'discount_description' => 'Buy C 4 items get 20% discount',
                        'discount_amount'      => '300.00'
                    ],
                    [
                        'type'                  => 'discount-summary',
                        'discount_total_amount' => '350.00'
                    ],
                    [
                        'type' => 'empty'
                    ],
                    [
                        'type' => 'item-groups-header'
                    ],
                    [
                        'type'         => 'item-groups-line',
                        'quantity'     => '0',
                        'vat_code'     => '1',
                        'unit_price'   => '0.00',
                        'vat_amount'   => '0.00',
                        'total_amount' => '0.00'
                    ],
                    [
                        'type'         => 'item-groups-line',
                        'quantity'     => '10',
                        'vat_code'     => '2',
                        'unit_price'   => '600.00',
                        'vat_amount'   => '0.00',
                        'total_amount' => '3,000.00'
                    ],
                    [
                        'type'         => 'item-groups-summary',
                        'unit_price'   => '600.00',
                        'vat_amount'   => '0.00',
                        'total_amount' => '3,000.00'
                    ]
                ],
                'item_groups' => [
                    [
                        'type' => 'groups-header'
                    ],
                    [
                        'type'         => 'groups-line',
                        'quantity'     => '0',
                        'vat_code'     => '1',
                        'unit_price'   => '0.00',
                        'vat_amount'   => '0.00',
                        'total_amount' => '0.00'
                    ],
                    [
                        'type'         => 'groups-line',
                        'quantity'     => '10',
                        'vat_code'     => '2',
                        'unit_price'   => '600.00',
                        'vat_amount'   => '0.00',
                        'total_amount' => '3,000.00'
                    ],
                    [
                        'type'         => 'groups-summary',
                        'unit_price'   => '600.00',
                        'vat_amount'   => '0.00',
                        'total_amount' => '3,000.00'
                    ]
                ],
                'payment' => [
                    'cash'        => '',
                    'credit-card' => '2,930.00',
                    'coupon'      => '',
                    'total'       => '2,930.00'
                ],
                'summary' => [
                    'total-text' => 'สองพันเก้าร้อยสามสิบบาทถ้วน',
                    'total'      => '2,930.00',
                    'discount'   => '999,999,999.00',
                    'amount'     => '2,930.00',
                    'deposit'    => '0.00',
                    'sub-total'  => '2,738.32',
                    'vat'        => '191.68',
                    'net-amount' => '2,930.00'
                ],
                'remark' => 'ซื้อสินค้าผิดรายการ',
                'summary-credit-note' => [
                    'total-text'    => 'หนึ่งร้อยห้าสิบบาทถ้วน',
                    'amount'        => '150.00',
                    'vat'           => '9.81',
                    'sub-total'     => '140.19',
                    'credit-amount' => '150.00'
                ],
            ]
            ]
        ];
    }

    public function index()
    {
        return view($this->view['index']);
    }

    public function print()
    {
        return view($this->view['print'], $this->templateDataMock());
    }

    public function getMasterInvoice($order_number,$InvoiceType,$MasterInvoiceNo=""){

        $masterInvoiceData = NULL;
        switch ($InvoiceType) {
            case 'INFO':        // deposit invoice
                $masterInvoiceType = 'INFO';
                $searchType = 'sale_order_number';
                break; 
            case 'SHIPMENT':    // normal/delivery invoice
                $masterInvoiceType = 'INFO';
                $searchType = 'sale_order_number';
                break;  
            case 'CREDIT_MEMO': // cancel/refund invoice
                $masterInvoiceType = 'INFO';
                $searchType = 'sale_order_number';
                break;    
            case 'RETURN':      // return invoice
                $masterInvoiceType = 'SHIPMENT';
                $searchType = 'sale_order_number';
                break;             
            default:
                $masterInvoiceType = '';
                $searchType = 'sale_order_number';
                break;
        }

        $masterInvoiceList = $this->eposBaseController->getInvoices($order_number,$searchType,$masterInvoiceType,$MasterInvoiceNo);

        if(isset($masterInvoiceList[0][0]->OrderInvoiceKey)){
            $orderInvoiceKey   = $masterInvoiceList[0][0]->OrderInvoiceKey;
            $masterInvoiceData = $this->eposBaseController->getInvoicesDetail($orderInvoiceKey);  
        }

        return $masterInvoiceData;
    }

    public function getOrderByReturnOrderNo($order_number)
    {

        if (substr($order_number, 0, 1) == 'Y') {
            // Get Customer Order Number
           $orderFromReturn = $this->eposBaseController->getOrderFromReturnOrder($order_number);
           $order_number = $orderFromReturn[0]->orderNo;
        }

        return $order_number;

    }

    public function getInvoicePrint($invoiceNumber,$order_number)
    {
        $order_number = $this->getOrderByReturnOrderNo($order_number);
        $invoiceData  = $this->eposBaseController->getInvoicesDetail($invoiceNumber);

        if ($invoiceData == '404') {
            return redirect()->back()->with('msg', 'Connection error!')->withInput();
        }

        // Invoice Ship Node (Makro Store Id)
        $makro_store_id = $invoiceData[0][0]->ShipNode;

        // Invoice Type
        $invoiceType = $this->mappingInvoiceType($invoiceData[0][0]->InvoiceType);

        // Master Invoice
        $masterInvoiceData = NULL;
        if (($invoiceData[0][0]->InvoiceType == 'RETURN' || $invoiceData[0][0]->InvoiceType == 'CREDIT_MEMO') || !empty($invoiceData[0][0]->MasterInvoiceNo)) {
            $masterInvoiceData = $this->getMasterInvoice($order_number,$invoiceData[0][0]->InvoiceType,$invoiceData[0][0]->MasterInvoiceNo);
        }

        // Order Number
        $orderNo = $order_number;

        // Order
        $orderData = $this->getOrderData($orderNo);

        if(!$orderData){
            return redirect()->back()->with('msg', 'Empty Order Data!')->withInput();
        }

        // Order Invoice Amount Data
        $orderInvoiceAmountData = $this->getOrderInvoiceAmountData($invoiceNumber,$invoiceData,$orderData);

        if(!$orderInvoiceAmountData){
            return redirect()->back()->with('msg', 'Empty Order Invoice Data!')->withInput();
        }

        // Store
        $storeInfo = $this->getStoreInformation($makro_store_id);

        if(!$storeInfo){
            return redirect()->back()->with('msg', 'Empty Store Data!')->withInput();
        }

        // Store Address
        $storeAddress = $this->getStoreAddress($storeInfo['id']);
        if(!$storeAddress){
            //return redirect()->back()->with('msg', 'Empty Address Data!')->withInput();
        }

        //get coupon discount
        $couponDiscount = $this->eposBaseController->getCouponDiscount($orderNo);

        //invoice Template
        $summary = $this->getSummary($orderInvoiceAmountData, $invoiceType);

        return view($this->view['preview'], [
            'invoice_number'            => isset($invoiceData[0][0]->ExtnNewInvoiceNumber)? $invoiceData[0][0]->ExtnNewInvoiceNumber : isset($invoiceData[0][0]->ExtnOldInvoiceNumber)? $invoiceData[0][0]->ExtnOldInvoiceNumber : $invoiceData[0][0]->ExtnMakroInvoiceNumber,
            'order_number'              => $orderNo,
            'customer_invoice_number'   => (isset($invoiceData[0][0]->ExtnNewInvoiceNumber) && !empty($invoiceData[0][0]->ExtnNewInvoiceNumber))? $invoiceData[0][0]->ExtnNewInvoiceNumber : $invoiceData[0][0]->ExtnMakroInvoiceNumber ,
            'replace_invoice_number'    => isset($invoiceData[0][0]->ExtnNewInvoiceNumber)? $invoiceData[0][0]->ExtnNewInvoiceNumber : isset($invoiceData[0][0]->ExtnOldInvoiceNumber)? $invoiceData[0][0]->ExtnOldInvoiceNumber : $invoiceData[0][0]->ExtnMakroInvoiceNumber,
            'order_invoice_key'         => $invoiceData[0][0]->OrderInvoiceKey,
            're_print'                  => $invoiceData[0][0]->ExtnRunningNumber,
            'invoice_type'              => $invoiceData[0][0]->InvoiceType,
            'invoice_date'              => $invoiceData[0][0]->CreateDate,
            'payment_type'              => $invoiceData[0][0]->SearchCriteria1,
            'order_date'                => $invoiceData[0][0]->OrderDate,
            'store_id'                  => $storeInfo['makro_store_id'],
            'subtotal'                  => isset($summary['sub-total']) ? $summary['sub-total'] : 0,
            'vat'                       => isset($summary['vat']) ? $summary['vat'] : 0,
            'net_amount'                => ($invoiceType == 'credit-note-refund' || $invoiceType == 'credit-note-return') ? (isset($summary['diff-order-amount']) ? $summary['diff-order-amount'] : 0) : (isset($summary['net-amount']) ? $summary['net-amount'] : 0),
            'shipping_fee'              => $this->shipping_fee,
            'shop_name'                 => isset($orderData['buyer']['information']['business']['shop_name'])? $orderData['buyer']['information']['business']['shop_name'] : '',
            'makro_member_card'         => isset($orderData['data']['buyer_user_id'])? $orderData['data']['buyer_user_id'] : '',
            'tax_id'                    => (isset($orderData['data']['tax_payer_id']) && $orderData['data']['tax_payer_id'] != '-')? $orderData['data']['tax_payer_id'] : '',
            'branch_id'                 => isset($orderData['buyer']['information']['business']['branch'])? $orderData['buyer']['information']['business']['branch'] : '',
            'address_line1'             => isset($orderData['data']['additional_addresses']['additional_address']['person_info']['address_line1'])? $orderData['data']['additional_addresses']['additional_address']['person_info']['address_line1'] : '',
            'mobile_phone'              => '',
            'provinces'                 => isset($orderData['data']['additional_addresses']['additional_address']['person_info']['state'])? $orderData['data']['additional_addresses']['additional_address']['person_info']['state'] : '',
            'districts'                 => isset($orderData['data']['additional_addresses']['additional_address']['person_info']['city'])? $orderData['data']['additional_addresses']['additional_address']['person_info']['city'] : '',
            'sub_districts'             => isset($orderData['data']['additional_addresses']['additional_address']['person_info']['address_line4'])? $orderData['data']['additional_addresses']['additional_address']['person_info']['address_line4'] : '',
            'zip_code'                  => isset($orderData['data']['additional_addresses']['additional_address']['person_info']['zip_code'])? $orderData['data']['additional_addresses']['additional_address']['person_info']['zip_code'] : '',
        ]);
    }

    protected function getStoreInformation($store_id)
    {
        $url = env('CURL_API_STORE') . 'stores';
        $options = [
            'query' => [
                'makro_store_id' => $store_id
            ]
        ];

        $result = $this->guzzle->curl('GET', $url, $options);

        if(isset($result['status']['code']) && $result['status']['code'] == 200){
            return !empty($result['data']['records'])? $result['data']['records'][0] : false;
        }
        else{
            return false;
        }
    }

    protected function getStoreAddress($store_mongo_id)
    {
        $url = env('CURL_API_ADDRESS') . 'addresses';
        $options = [
            'query' => [
                'content_id' => $store_mongo_id,
                'content_type' => 'store'
            ]
        ];

        $result = $this->guzzle->curl('GET', $url, $options);

        if(isset($result['status']['code']) && $result['status']['code'] == 200){
            return !empty($result['data']['records'])? $result['data']['records'][0] : false;
        }
        else{
            return false;
        }
    }

    protected function getInvoiceFormat($orderData)
    {
        if(isset($orderData['data']['division']) == 'Long'){
            return 'long'; 
        }
        else{
            return 'short'; 
        }
       
    }

    protected function mappingStoreInformation($output, $storeInfo, $storeAddress)
    {
        $output['receipt_branch'] = (string)$storeInfo['store_legal_no'];
        
        $thaiString                 = new ThaiString();
        $receipt_address            = $this->getStoreAddressText($storeInfo, $storeAddress);
        $descriptionInArray         = $thaiString->addLineStringTH($receipt_address, config('invoice.address_length'), 2);
        $output['receipt_address1'] = isset($descriptionInArray[0]) ? trim($descriptionInArray[0]): '';
        $output['receipt_address2'] = isset($descriptionInArray[1]) ? trim($descriptionInArray[1]): '';
        
        $output['receipt_post_id'] = '';
        return $output;
    }

    protected function getStoreAddressText($storeInfo, $storeAddress, $language = 'th')
    {
        $output = '';

        // Line 1
        if(!empty($storeInfo['name'][$language])){
            $output .= $storeInfo['name'][$language] . " ";
        }

        // Line 2
        if(!empty($storeAddress['address'][$language])){
            $output .= $storeAddress['address'][$language] . " ";
        }
        if(!empty($storeAddress['address2'][$language])){
            $output .= $storeAddress['address2'][$language] . " ";
        }
        if(!empty($storeAddress['address3'][$language])){
            $output .= $storeAddress['address3'][$language] . " ";
        }
        if(!empty($storeAddress['district'][$language])){
            $output .= $storeAddress['district'][$language] . " ";
        }

        // Line 3
        if(!empty($storeAddress['province'][$language])){
            $output .= $storeAddress['province'][$language] . " ";
        }
        if(!empty($storeAddress['postcode'])){
            $output .= $storeAddress['postcode'];
        }
        return trim($output);
    }

    public function getOrderData($order_no)
    {
        $url = env('CURL_API_ORDER') . 'orders';
        $options = [
            'query' => [
                'order_no' => $order_no
            ]
        ];

        $result = $this->guzzle->curl('GET', $url, $options);

        if(isset($result['status']['code']) && $result['status']['code'] == 200){

            return !empty($result['data']['records'][0])? $result['data']['records'][0] : false;
        }
        else{
            return false;
        }
    }

    protected function mappingCustomerInformation($output, $orderData)
    {
        $output['customer_no'] = isset($orderData['makro_member_card'])? $orderData['makro_member_card'] : '';
        $output['customer_tax_id'] = (isset($orderData['data']['tax_payer_id']) && $orderData['data']['tax_payer_id'] != '-')? $orderData['data']['tax_payer_id'] : '';
        $output['customer_branch'] = isset($orderData['buyer']['information']['business']['branch'])? $orderData['buyer']['information']['business']['branch'] : '';
        $output = $this->getCustomerAddressText($output, $orderData);
        $output = $this->getCustomerNameText($output, $orderData);
        return $output;
    }

    protected function getCustomerAddressText($customerInformation, $orderData)
    {
        $str_address = '';

        $customerInformation['customer_address1'] = '';
        $customerInformation['customer_address2'] = '';

        if(empty($orderData['data']['additional_addresses']['additional_address']['person_info'])){
            return $customerInformation;
        }

        $person_info = $orderData['data']['additional_addresses']['additional_address']['person_info'];
    
        if(!empty($person_info['address_line1'])){
            $str_address .= $person_info['address_line1'] . " ";
        }
        if(!empty($person_info['address_line2'])){
            $str_address .= $person_info['address_line2'] . " ";
        }
        if(!empty($person_info['address_line4'])){
            $str_address .= $person_info['address_line4'] . " ";
        }
        if(!empty($person_info['city'])){
            $str_address .= $person_info['city'] . " ";
        }
        if(!empty($person_info['state'])){
            $str_address .= $person_info['state'] . " ";
        }
        if(!empty($person_info['zip_code'])){
            $str_address .= $person_info['zip_code'];
        }

        $thaiString = new ThaiString();
        $descriptionInArray = $thaiString->addLineStringTH($str_address, config('invoice.address_length'), 2);
        $customerInformation['customer_address1'] = isset($descriptionInArray[0]) ? trim($descriptionInArray[0]) : '';
        $customerInformation['customer_address2'] = isset($descriptionInArray[1]) ? trim($descriptionInArray[1]) : '';

        return $customerInformation;
    }

    protected function getCustomerNameText($customerInformation, $orderData)
    {
        $str_name                              = '';
        $customerInformation['customer_name1'] = '';
        $customerInformation['customer_name2'] = '';

        if(empty($orderData['buyer']['information']['business'])){
            return $customerInformation;
        }

        $person_info = $orderData['buyer']['information']['business'];

        if(!empty($person_info['shop_name'])){
            $str_name .= $person_info['shop_name'];
        }

        $thaiString         = new  ThaiString();
        $descriptionInArray = $thaiString->addLineStringTH($str_name, config('invoice.customer_name_length'), 2);
        $customerInformation['customer_name1'] = isset($descriptionInArray[0]) ? trim($descriptionInArray[0]) : '';
        $customerInformation['customer_name2'] = isset($descriptionInArray[1]) ? trim($descriptionInArray[1]) : '';
        
        return $customerInformation;
    }

     // split a long string without breaking words
     protected function splitLongString($string, $maxLineLength = 50){

        $lines = explode("\n", wordwrap($string, $maxLineLength));

        return $lines;

    }

    protected function mappingShippingInformation($information, $orderData, $invoiceData)
    {
        $thaiString = new  ThaiString();

        $information['shipping_address1']   = '';
        $information['shipping_address2']   = '';
        $information['address']             = '';
        $information['address2']            = '';
        $information['receiver_name']       = '';
        $information['receiver_email']      = '';
        $information['receiver_telephone']  = '';

        if (empty($orderData['delivery_method']) || (isset($orderData['delivery_method']) && $orderData['delivery_method'] != 'PICK')) {
            // split a long shipping address to 2 lines
            $address = !empty($invoiceData[0][0]->ShipToAddressLine1)  ? trim($invoiceData[0][0]->ShipToAddressLine1) . " " : "";
            $address .= !empty($invoiceData[0][0]->ShipToAddressLine2) ? trim($invoiceData[0][0]->ShipToAddressLine2) . " " : "";
            $address .= !empty($invoiceData[0][0]->ShipToAddressLine3) ? trim($invoiceData[0][0]->ShipToAddressLine3) . " " : "";
            $address .= !empty($invoiceData[0][0]->ShipToAddressLine4) ? trim($invoiceData[0][0]->ShipToAddressLine4) . " " : "";
            $address .= !empty($invoiceData[0][0]->ShipToCity)         ? trim($invoiceData[0][0]->ShipToCity) . " " : "";
            $address .= !empty($invoiceData[0][0]->ShipToState)        ? trim($invoiceData[0][0]->ShipToState) . " " : "";
            $address .= !empty($invoiceData[0][0]->ShipToZipCode)      ? trim($invoiceData[0][0]->ShipToZipCode) . " " : "";

            if (!empty($address)) {
                $addressArray            = $thaiString->addLineStringTH($address, config('invoice.customer_name_length'), 2);
                $information['address']  = isset($addressArray[0]) ? trim($addressArray[0]): '';
                $information['address2'] = isset($addressArray[1]) ? trim($addressArray[1]): '';
            }

            if (!empty($orderData['delivery_address']['shop_name'])){
                $descriptionInArray                 = $thaiString->addLineStringTH($orderData['delivery_address']['shop_name'], config('invoice.customer_name_length'), 2);
                $information['shipping_address1']   = isset($descriptionInArray[0]) ? trim($descriptionInArray[0]) : '';
                $information['shipping_address2']   = isset($descriptionInArray[1]) ? trim($descriptionInArray[1]) : '';
            }

            if (!empty($address)) {
                $addressArray            = $thaiString->addLineStringTH($address, config('invoice.customer_name_length'), 2);
                $information['address']  = isset($addressArray[0]) ? trim($addressArray[0]): '';
                $information['address2'] = isset($addressArray[1]) ? trim($addressArray[1]): '';
            }

            if (isset($invoiceData[0][0]->ShipToFirstName) && isset($invoiceData[0][0]->ShipToLastName)) {
                $receiver_name                = $thaiString->addLineStringTH(trim($invoiceData[0][0]->ShipToFirstName." ".$invoiceData[0][0]->ShipToLastName), config('invoice.customer_name_length'), 1);
                $information['receiver_name'] = !empty($receiver_name[0]) ? $receiver_name[0] : '';
            }

            if (isset($invoiceData[0][0]->ShipToEMailID)) {
                $receiver_email                 = $thaiString->addLineStringTH(trim($invoiceData[0][0]->ShipToEMailID), config('invoice.customer_name_length'), 1);
                $information['receiver_email']  = !empty($receiver_email[0]) ? $receiver_email[0] : '';
            }

            if (isset($invoiceData[0][0]->ShipToDayPhone)) {
                $receiver_telephone                = $thaiString->addLineStringTH(trim($invoiceData[0][0]->ShipToDayPhone), config('invoice.customer_name_length'), 1);
                $information['receiver_telephone'] = !empty($receiver_telephone[0]) ? $receiver_telephone[0] : '';
            }

        }

        return $information;
    }

    protected function mappingInformation($invoiceData, $masterInvoiceData, $orderData, $storeInfo, $storeAddress)
    {
        $information = [];
        // Tax Invoice No
        $information['tax_invoice_no'] = (isset($invoiceData[0][0]->ExtnNewInvoiceNumber) && !empty($invoiceData[0][0]->ExtnNewInvoiceNumber))? $invoiceData[0][0]->ExtnNewInvoiceNumber : $invoiceData[0][0]->ExtnMakroInvoiceNumber;

        // Tax Invoice Date
        // $information['tax_invoice_date'] = $invoiceData[0][0]->IssueDate;ExtnSettlementDate
        $information['tax_invoice_date'] = $invoiceData[0][0]->ExtnSettlementDate;

        // Customer Info
        $information = $this->mappingCustomerInformation($information, $orderData);     

        // Shipping Info
        $information = $this->mappingShippingInformation($information, $orderData, $invoiceData);

        // Store Receiptor Info
        $information = $this->mappingStoreInformation($information, $storeInfo, $storeAddress);

        // Payment Type
        $information['payment_type'] = isset($orderData['payment_config']['name']['en'])?$orderData['payment_config']['name']['en']:'';

        // Order No
        $information['order_no'] = $orderData['order_no'];

        // Order Date
        $information['order_date'] = convertDateTime($orderData['created_at'], 'Y-m-d H:i:s', 'd/m/Y');

        // Deposit Invoice Number (Normal Only)
        if($invoiceData[0][0]->InvoiceType == 'SHIPMENT'){
            $information['deposit_receipt'] = (is_null($masterInvoiceData))? '' : $masterInvoiceData[0][0]->ExtnMakroInvoiceNumber;
        }

        return $information;
    }

    protected function getOrderInvoiceData($order_no, $type, $invoiceItems)
    {

        switch ($type) {
            case 'credit-note-refund':
                $api_type = 'refundInvoice';
                $action = 'POST';
                $options = [
                    'headers' => [
                        'Content-Type'=>'application/json'
                    ],
                    'json' =>[ 
                        'type' => 'refund',
                        'invoiceItems' => $invoiceItems 
                    ]
                ];
                break;
            case 'credit-note-return':
                $api_type = 'refundInvoice';
                $action = 'POST';
                $options = [
                    'headers' => [
                        'Content-Type'=>'application/json'
                    ],
                    'json' =>[ 
                        'type' => 'return',
                        'invoiceItems' => $invoiceItems 
                    ]
                ];
                break;
            case 'normal':
                $api_type = 'normalInvoice';
                $action = 'POST';
                $options = [
                    'headers' => [
                        'Content-Type'=>'application/json'
                    ],
                    'json' => $invoiceItems
                ];
                break;
            default:
                $action   = 'GET';
                $api_type = 'invoiceAmount';
                $options  = [];
                break;
        }
 
        $url = env('CURL_API_ORDER') . 'orders/' . $order_no . '/'. $api_type;
        $result = $this->guzzle->curl($action, $url, $options);
 
        if(isset($result['status']['code']) && $result['status']['code'] == 200){
            return !empty($result['data'])? $result['data'] : false;
        }
        else{
            return false;
        }
    }

    protected function getCreditNoteList($invoiceData, $type)
    {
        $output = [];
        foreach($invoiceData as $invoice){
            if($invoice['type'] == $type){
                $output[] = $invoice;
            }
        }
        return $output;
    }

    protected function getCreditNoteData($invoiceData, $type, $round)
    {
        $creditNoteList = $this->getCreditNoteList($invoiceData, $type);
       // if($round === 0 ) return $creditNoteList[$round];
       // else return $creditNoteList[$round - 1];
        return  $creditNoteList[$round];
    }

    protected function getDeliveryFee($groups,$key,$numberCounter){

        $item_delivery_fee = [
            'type'         => 'item',
            // 'lineCount' => (string)$numberCounter,
            'lineCount'    => $this->deliveryFeeLine,
            'lines'        => [ 
                0 => [
                    'type' => 'item-line',
                    'no'   =>  '',
                    'item_id' =>  '',
                    'description' =>  'ค่าจัดส่งสินค้า',
                    'quantity'=>'',
                    'unit'=>'',
                    'unit_price'=>'',
                    'vat_code'=>'',
                    'total_amount'=>  number_format($this->shipping_fee,2),
                ]
            ]

        ];
        $groups[$key] =  $item_delivery_fee; 

        return $groups;
    }

    protected function getItemLines($invoiceType, $orderInvoiceAmountData, $orderData, $unitOfMeasure)
    {
        $numberCounter = 1;
        $itemLines = [];

        $groups = [];

        // Item Lines Header
        if($invoiceType != 'normal'){
            $groups[] = [
                'type' => 'item',
                'lineCount' => 1,
                'lines' => [
                    [
                        'type' => 'item-line-header',
                        'text' => $this->getItemLinesHeaderText($invoiceType)
                    ]
                ]
            ];
        }
        
        // Item Lines
        foreach($orderInvoiceAmountData['item_lines'] as $itemLine){

            $thaiString = new ThaiString();

            $description = $this->getItemName($orderData, $itemLine['item_id']);
            
            $descriptionInArray = $thaiString->addLineStringTH($description, config('invoice.product_name_length'));

            $group = [
                'type' => 'item',
                'lineCount' => count($descriptionInArray)
            ];

            for($i=0; $i < count($descriptionInArray); $i++){

                if($i == 0){
                    $group['lines'][] = [
                        'type' => 'item-line',
                        'no' => (string)$numberCounter,
                        'item_id' => (string)$itemLine['item_id'],
                        'description' => $descriptionInArray[$i],
                        'quantity' => (string)$itemLine['quantity'],
                        'unit' => $unitOfMeasure,
                        'unit_price' => number_format($itemLine['sellingPrice_rounded'], 2),
                        'vat_code' => $this->getVatCode($itemLine['vatRate']),
                        'total_amount' => number_format($itemLine['lineTotal'], 2)
                    ];
                }
                else{
                    $group['lines'][] = [
                        'type' => 'item-line',
                        'description' => $descriptionInArray[$i]
                    ];
                }

            }

            $groups[] = $group;
            $numberCounter += 1;

        }

        // Delivery Fee
        $displayShipping = ['credit-note-refund','normal','deposit'];
        if(($this->shipping_fee > 0) && in_array($invoiceType, $displayShipping) ){
            $groups = $this->getDeliveryFee($groups, count($groups), $numberCounter);
            $numberCounter += 1;
        }

        // Discount Condition
        $discountCondition = $this->getDiscountCondition($orderInvoiceAmountData, $orderData);

        $groups = array_merge($groups, $discountCondition);

        // Item Group Lines
        $itemGroupsLines = $this->getItemGroups($invoiceType, $orderInvoiceAmountData);
 
        $groups[] = $itemGroupsLines;

        return $groups;
    }

    protected function getItemLinesHeaderText($invoiceType)
    {
        if($invoiceType == 'credit-note-return' || $invoiceType == 'credit-note-refund'){
            return 'รับคืนค่าสินค้า';
        }
        else if($invoiceType == 'deposit'){
            return 'รับชำระราคาค่าสินค้า';
        }
        else{
            return '';
        }
    }

    protected function getItemName($orderData, $itemId, $language = 'th')
    {
        foreach($orderData['order_products'] as $order_product){
            if($order_product['item_id'] == $itemId){
                return $order_product['item_name'][$language];
            }
        }
    }

    protected function getVatCode($vatRate)
    {
        return $vatRate > 0 ? '2' : '1';
    }

    protected function getUnitPrice($totalAmount, $quantity)
    {
        return $totalAmount / $quantity;
    }

    protected function getItemGroups($invoiceType, $orderInvoiceAmountData)
    {
        $itemGroups = [];

        // Header
        $itemGroups[] = ['type' => 'item-groups-header'];

        // Non-Vat
        foreach($orderInvoiceAmountData['item_groups'] as $itemGroup){
            if($itemGroup['type'] == 'non-vat'){
                $itemGroups[] = [
                    'type' => 'item-groups-line',
                    'quantity' => $itemGroup['quantity'],
                    'vat_code' => '1',
                    'unit_price' => $invoiceType == 'normal'? number_format(0, 2) : number_format($itemGroup['sellingPrice'], 2),
                    'vat_amount' => $invoiceType == 'normal'? number_format(0, 2) : number_format($itemGroup['vatAmount'], 2),
                    'total_amount' => $invoiceType == 'normal'? number_format(0, 2) : number_format($itemGroup['totalPrice'], 2),
                ];
            }
        }

        // Vat
        foreach($orderInvoiceAmountData['item_groups'] as $itemGroup){
            if($itemGroup['type'] == 'vat'){
                $itemGroups[] = [
                    'type' => 'item-groups-line',
                    'quantity' => $itemGroup['quantity'],
                    'vat_code' => '2',
                    'unit_price' => $invoiceType == 'normal'? number_format(0, 2) : number_format($itemGroup['sellingPrice']+$this->raw_shipping, 2),
                    'vat_amount' => $invoiceType == 'normal'? number_format(0, 2) : number_format($itemGroup['vatAmount']+$this->vat_shipping, 2),
                    'total_amount' => $invoiceType == 'normal'? number_format(0, 2) : number_format($itemGroup['totalPrice']+$this->shipping_fee, 2),
                ];
            }
        }

        // Summary
        $itemGroups[] = [
            'type' => 'item-groups-summary',
            'unit_price' => $invoiceType == 'normal'? number_format(0, 2) : number_format($orderInvoiceAmountData['summary']['sellingPrice']+$this->raw_shipping, 2),
            'vat_amount' => $invoiceType == 'normal'? number_format(0, 2) : number_format($orderInvoiceAmountData['summary']['vatAmount']+$this->vat_shipping, 2),
            'total_amount' => $invoiceType == 'normal'? number_format(0, 2) : number_format($orderInvoiceAmountData['summary']['totalPrice']+$this->shipping_fee, 2)
        ];

        $group = [
            'type' => 'summary',
            'lineCount' => count($itemGroups),
            'lines' => $itemGroups
        ];

        return $group;
    }

    public function getSummary($orderInvoiceAmountData, $invoiceType)
    {
        $thaiString = new ThaiString();

        $totalPriceBeforeDiscount        = number_format($orderInvoiceAmountData['summary']['totalPriceBeforeDiscount'] + $this->shipping_fee, 2);
        $totalPriceBeforeDiscountReplace = str_replace(',', '', $totalPriceBeforeDiscount);
        $totalPriceThaiBeforeDiscount    = $thaiString->num2wordsThai($totalPriceBeforeDiscountReplace);

        $totalPrice   = number_format($orderInvoiceAmountData['summary']['totalPrice'] + $this->shipping_fee, 2);
        $vatAmount    = $orderInvoiceAmountData['summary']['vatAmount'] + $this->vat_shipping;
        $sellingPrice = $orderInvoiceAmountData['summary']['sellingPrice'] + $this->raw_shipping;

        $totalPriceReplace = str_replace(',', '', $totalPrice);
        $totalPriceThai    = $thaiString->num2wordsThai($totalPriceReplace);

        if ($invoiceType == 'credit-note-refund' || $invoiceType == 'credit-note-return') {

            $invoiceSummary = [
                'total-text'            => $totalPriceThai,
                'amount'                => $totalPrice,
                'vat'                   => number_format($vatAmount, 2),
                'sub-total'             => number_format($sellingPrice, 2),
                'credit-amount'         => $totalPrice,
                'original-order-amount' => number_format($orderInvoiceAmountData['summary']['orderAmountOld'], 2),
                'correct-order-amount'  => number_format($orderInvoiceAmountData['summary']['orderAmountNew'], 2),
                'diff-order-amount'     => $totalPrice
            ];

        } else if($invoiceType == 'deposit') {

            $invoiceSummary = [
                'total-text'    => $totalPriceThai,
                'total'         => $totalPriceBeforeDiscount,
                'discount'      => number_format($orderInvoiceAmountData['summary']['discount'], 2),
                'amount'        => $totalPrice,
                'deposit'       => number_format(0, 2),
                'sub-total'     => number_format($sellingPrice, 2),
                'vat'           => number_format($vatAmount, 2),
                'net-amount'    => $totalPrice
            ];

        } else if($invoiceType == 'normal') {

            $invoiceSummary = [
                'total-text'    => $thaiString->num2wordsThai(0),
                'total'         => $totalPriceBeforeDiscount,
                'discount'      => number_format($orderInvoiceAmountData['summary']['discount'], 2),
                'amount'        => $totalPrice,
                'deposit'       => $totalPrice,
                'sub-total'     => number_format(0, 2),
                'vat'           => number_format(0, 2),
                'net-amount'    => number_format(0, 2)
            ];
        }

        return $invoiceSummary;
    }

    protected function getPaymentChanel($invoiceOrderData, $orderData, $invoiceType, $couponDiscount = 0)
    {
        $payment = [
            'amount'      => '',
            'payment_type'=> $orderData['payment_type'],
            'coupon'      => '',
            'total'       => ''
        ];

        if ($invoiceType == 'deposit') {

            $payment['amount'] = number_format($invoiceOrderData['summary']['totalPrice'] - $couponDiscount + $this->shipping_fee, 2);
            $payment['total']  = number_format($invoiceOrderData['summary']['totalPrice']+$this->shipping_fee, 2);

        } elseif ($invoiceType == 'credit-note-refund' || $invoiceType == 'credit-note-return') {

            $payment['amount'] = number_format($invoiceOrderData['summary']['totalPrice']+$this->shipping_fee, 2);
            $payment['total']  = number_format($invoiceOrderData['summary']['totalPrice']+$this->shipping_fee, 2);
        }

        return $payment;
    }

    protected function getDiscountCondition($orderInvoiceAmountData, $orderData)
    {
        $discountCondition = [];
        $discount = $this->getDiscountData($orderInvoiceAmountData, $orderData);

        if(isset($discount['promotions']) && $discount['total_discount_amount'] > 0){

            // Header
            $discountCondition[] = ['type' => 'discount-header'];

            // Discount Line
            $lineCounter = 1;

            foreach($discount['promotions'] as $promotionId => $promotion){

                if($promotion['discount_amount'] > 0){
                    // check length promotion name
                    $thaiString     = new  ThaiString();
                    $promotion_name = $thaiString->addLineStringTH($promotion['name'], config('invoice.promotion_name_length'));

                    if (!empty($promotion_name)) {
                        foreach ($promotion_name as $key => $name) {
                            $discountCondition[] = [
                                'type'                  => 'discount-line',
                                'no'                    => ($key == 0) ? (string)$lineCounter : '',
                                'discount_description'  => $name,
                                'discount_amount'       => ($key == 0) ? number_format($promotion['discount_amount'], 2) : ''
                            ];
                        }
                        $lineCounter += 1;
                    }
                }
            }

            // Total
            $discountCondition[] = [
                'type' => 'discount-summary',
                'discount_total_amount' => number_format($discount['total_discount_amount'], 2)
            ];
        }

        $groups = [];

        if(count($discountCondition) == 3){
            $groups[] = [
                'type' => 'discount-all',
                'lineCount' => count($discountCondition),
                'lines' => $discountCondition
            ];
        }
        else if(count($discountCondition) > 3){

            $counter = 0;
            $length = count($discountCondition);

            while($counter < $length){

                // Header
                if($counter == 0){
                    $group = [
                        'type' => 'discount-header',
                        'lineCount' => 2,
                        'lines' => [$discountCondition[$counter], $discountCondition[$counter + 1]]
                    ];
                    $groups[] = $group;
                    $counter += 2;
                }
                else if($counter == $length - 2){
                    $group = [
                        'type' => 'discount-line',
                        'lineCount' => 2,
                        'lines' => [$discountCondition[$counter], $discountCondition[$counter + 1]]
                    ];
                    $groups[] = $group;
                    $counter += 2;
                }
                else{
                    $group = [
                        'type' => 'discount-summary',
                        'lineCount' => 1,
                        'lines' => [$discountCondition[$counter]]
                    ];
                    $groups[] = $group;
                    $counter += 1;
                }
            }
        }

        return $groups;
    }

    protected function getDiscountData($orderInvoiceAmountData, $orderData)
    {
        $discount = [];
        $discount['total_discount_amount'] = 0;

        foreach($orderInvoiceAmountData['item_lines'] as $itemLine){

            // Promotion ID
            $promotionId = $this->getPromotionId($orderData, $itemLine['item_id']);

            if(!is_null($promotionId)){

                if(!isset($discount['promotions'][$promotionId]['discount_amount'])){
                    $discount['promotions'][$promotionId]['discount_amount'] = 0;
                }

                $discount['promotions'][$promotionId]['name'] = $this->getPromotionName($orderData, $itemLine['item_id']);
                $discount['promotions'][$promotionId]['discount_amount'] += $itemLine['totalComplexDiscount_rounded'];
                $discount['total_discount_amount'] += $itemLine['totalComplexDiscount_rounded'];
            }
        }

        return $discount;
    }

    protected function getReference($masterInvoiceData,$invoiceType)
    {   
        $reference = [];

        switch ($invoiceType) {
            case 'credit-note-return':
                $reference = [
                    'deposit_invoice_number' => (@$masterInvoiceData[0][0]->ExtnNewInvoiceNumber)? @$masterInvoiceData[0][0]->ExtnNewInvoiceNumber : @$masterInvoiceData[0][0]->ExtnMakroInvoiceNumber,
                    'deposit_invoice_date' => @$masterInvoiceData[0][0]->IssueDate
                ];
                break;

            case 'credit-note-refund':
                $reference = [
                    'deposit_invoice_number' => (@$masterInvoiceData[0][0]->ExtnNewInvoiceNumber)? @$masterInvoiceData[0][0]->ExtnNewInvoiceNumber : @$masterInvoiceData[0][0]->ExtnMakroInvoiceNumber, 
                    'deposit_invoice_date' => @$masterInvoiceData[0][0]->IssueDate
                ];
                break;
            default:
                $reference = [
                    'deposit_invoice_number' => (@$masterInvoiceData[0][0]->ExtnNewInvoiceNumber)? @$masterInvoiceData[0][0]->ExtnNewInvoiceNumber : @$masterInvoiceData[0][0]->ExtnMakroInvoiceNumber,
                    'deposit_invoice_date' => @$masterInvoiceData[0][0]->IssueDate
                ];
                break;
        }

        return $reference;
    }

    protected function getPromotionId($orderData, $itemId)
    {
        foreach($orderData['data']['order_lines']['order_line'] as $orderLine){
            if($orderLine['item']['item_id'] == $itemId){
                if(!empty($orderLine['promotions']['promotion'])){
                    return $orderLine['promotions']['promotion'][0]['promotion_id'];
                }
            }
        }

        return NULL;
    }

    protected function getPromotionName($orderData, $itemId)
    {
        foreach($orderData['data']['order_lines']['order_line'] as $orderLine){
            if($orderLine['item']['item_id'] == $itemId){
                if(!empty($orderLine['notes']['note'])){
                    return $orderLine['notes']['note'][0]['note_text'];
                }
            }
        }

        return NULL;
    }

    protected function addPageEmptyLine(&$counter, $page, $line)
    {
        for($i=0; $i < $line; $i++){
            $page[] = ['type' => 'empty'];
        }
        $counter += $line;
        return $page;
    }

    protected function addPageLine(&$counter, $page, $group)
    {   
        if($group['type'] == 'summary'){
            $emptyLineCount = $this->detailMaxLine - $counter - $group['lineCount'];
            for($i=0; $i < $emptyLineCount; $i++){
                $page[] = ['type' => 'empty'];
            }
            $counter += $emptyLineCount;
        }

        foreach($group['lines'] as $line){
            $page[] = $line;
        }
        $counter += $group['lineCount'];

        return $page;
    }

    protected function getPages($groups)
    {
        $page = [];
        $pages = [];

        $counter = 0;
        foreach($groups as $group){
            
            // Add empty line between section
            if($counter > 0){
                if($group['type'] == 'discount-all' || $group['type'] == 'discount-header'){
                    if($counter + $group['lineCount'] + 1 <= $this->detailMaxLine){
                        $page = $this->addPageEmptyLine($counter, $page, 1);
                    }
                    else{
                        // Next page
                        $pages[] = $page;
                        $page = [];
                        $counter = 0;
                    }
                }
                else if($group['type'] == 'summary'){

                    if($counter + $group['lineCount'] > $this->detailMaxLine){
                        // Next page
                        $pages[] = $page;
                        $page = [];
                        $counter = 0;
                    }
                    else{
                        $emptyLineCount = $this->detailMaxLine - $counter - $group['lineCount'];
                        $emptyLineCount = $emptyLineCount == 0? 1 : $emptyLineCount;
                        if($counter + $group['lineCount'] + $emptyLineCount <= $this->detailMaxLine){
                            $page = $this->addPageEmptyLine($counter, $page, $emptyLineCount);
                        }
                        else{
                            // Next page
                            $pages[] = $page;
                            $page = [];
                            $counter = 0;
                        }
                    }  
                }
            }

            if($counter + $group['lineCount'] <= $this->detailMaxLine){
                $page = $this->addPageLine($counter, $page, $group);
            }
            else{
                // Next page
                $pages[] = $page;
                $page = [];
                $counter = 0;

                $page = $this->addPageLine($counter, $page, $group);
            }
        }

        $pages[] = $page;

        return $pages;
    }

    protected function getInvoicesDataSet($template)
    {
        $invoices   = [];
        $totalPages = count($template['pages']);
        $copyFor    = ['customer', 'company'];

        foreach ($copyFor as $copy) {

            foreach ($template['pages'] as $index => $page) {

                $currentPage = $index + 1;

                $invoice = [
                    'invoice_type'  => $template['invoice_type'],
                    'format_type'   => $template['format_type'],
                    'template_type' => $template['template_type'],
                    'copy_for'      => $copy,
                    'reference'     => $template['reference'],
                    'information'   => $template['information'],
                    'item_lines'    => $page
                ];

                $invoice['replace'] = isset($template['replace'])? $template['replace'] : [];
                $invoice['reprint'] = isset($template['reprint'])? $template['reprint'] : [];

                $invoice['pagination'] = [
                    'current_page' => $currentPage,
                    'total_page'   => $totalPages
                ];

                if ($currentPage == $totalPages) {

                    $invoice['payment'] = $template['payment'];

                    if ($template['invoice_type'] == 'credit-note') {

                        $invoice['summary-credit-note'] = $template['summary'];
                        $invoice['remark'] = $template['remark'];

                    } else {

                        $invoice['summary'] = $template['summary'];
                    }
                }

                $invoices[] = $invoice;
            }
        }

        return $invoices;
    }

    public function mappingInvoiceType($type)
    {
        switch(strtoupper($type)){
            case 'INFO': return 'deposit';
            case 'SHIPMENT' : return 'normal';
            case 'CREDIT_MEMO' : return 'credit-note-refund';
            case 'RETURN' : return 'credit-note-return';  
        }
    }

    protected function mappingReprintOrReplace($template, $invoiceData)
    {   
        $template['template_type'] = '';
        // Replace
        if($invoiceData[0][0]->ExtnOldInvoiceNumber != ''){
            $template['template_type'] = 'replace';
            $template['replace'] = [
                'old_invoice_number' => $invoiceData[0][0]->ExtnOldInvoiceNumber
            ];
        }

        // Reprint
        if($invoiceData[0][0]->ExtnRunningNumber > 0){
            
            $thaiString = new ThaiString();
            $now = (new \DateTime())->format('d/m/Y');
            
            $template['template_type'] = 'reprint';
            $template['reprint'] = [
                'count' => (string)$invoiceData[0][0]->ExtnRunningNumber,
                'date' => 'วันที่ ' . $thaiString->ThaiDate($now)
            ];
        }

        return $template;
    }

    protected function getInvoiceTemplate($invoiceData, $masterInvoiceData, $orderData, $orderInvoiceAmountData, $storeInfo, $storeAddress, $couponDiscount)
    {
        $template = [];

        $invoiceType   = $this->mappingInvoiceType($invoiceData[0][0]->InvoiceType);
        $unitOfMeasure = $invoiceData[0][0]->UnitOfMeasure;

        if ($invoiceType == 'credit-note-refund' || $invoiceType == 'credit-note-return') {

            $template['invoice_type'] = 'credit-note';

            $template['format_type'] = $this->getInvoiceFormat($orderData);

            $template = $this->mappingReprintOrReplace($template, $invoiceData);

            // Detail Lines
            $creditNoteItemLines = $this->getItemLines($invoiceType, $orderInvoiceAmountData, $orderData, $unitOfMeasure);

            // Information
            $template['information'] = $this->mappingInformation($invoiceData, $masterInvoiceData, $orderData, $storeInfo, $storeAddress);

            // Detail pages
            $template['pages'] = $this->getPages($creditNoteItemLines);

            // Reference
            $template['reference'] = $this->getReference($masterInvoiceData,$invoiceType);

            // Remark
            $template['remark'] = 'ซื้อสินค้าผิดรายการ';

            // Summary
            $template['summary'] = $this->getSummary($orderInvoiceAmountData, $invoiceType);

            // Payment Chanel
            $template['payment'] = $this->getPaymentChanel($orderInvoiceAmountData, $orderData, $invoiceType);

        } elseif ($invoiceType == 'deposit') {

            $template['invoice_type'] = 'deposit';

            $template['format_type'] = $this->getInvoiceFormat($orderData);

            $template = $this->mappingReprintOrReplace($template, $invoiceData);

            // Detail Lines
            $creditNoteItemLines = $this->getItemLines($invoiceType, $orderInvoiceAmountData, $orderData, $unitOfMeasure);

            // Information
            $template['information'] = $this->mappingInformation($invoiceData, $masterInvoiceData, $orderData, $storeInfo, $storeAddress);

            // Detail pages
            $template['pages'] = $this->getPages($creditNoteItemLines);

            // Reference
            $template['reference'] = $this->getReference($masterInvoiceData,$invoiceType);

            // Summary
            $template['summary'] = $this->getSummary($orderInvoiceAmountData, $invoiceType);

            // Payment Chanel
            $template['payment'] = $this->getPaymentChanel($orderInvoiceAmountData, $orderData, $invoiceType);

        } elseif ($invoiceType == 'normal') {

            $template['invoice_type'] = 'normal';

            $template['format_type'] = $this->getInvoiceFormat($orderData);

            $template = $this->mappingReprintOrReplace($template, $invoiceData);

            // Detail Lines
            $creditNoteItemLines = $this->getItemLines($invoiceType, $orderInvoiceAmountData, $orderData, $unitOfMeasure);

            // Information
            $template['information'] = $this->mappingInformation($invoiceData, $masterInvoiceData, $orderData, $storeInfo, $storeAddress);

            // Detail pages
            $template['pages'] = $this->getPages($creditNoteItemLines);

            // Reference
            $template['reference'] = $this->getReference($masterInvoiceData,$invoiceType);

            // Summary
            $template['summary'] = $this->getSummary($orderInvoiceAmountData, $invoiceType);

            // Payment Chanel
            $template['payment'] = $this->getPaymentChanel($orderInvoiceAmountData, $orderData, $invoiceType);
        }

        return $template;
    }

    protected function mappingInvoiceItems($invoiceData){

        $invoiceItems = [];
        foreach ($invoiceData[0] as $key => $line) {
            $invoiceItems[$key]['ItemID'] = $line->ItemID;
            $invoiceItems[$key]['OrderedQty'] = (int)$line->OrderedQty;
            $invoiceItems[$key]['Quantity'] = (int)$line->Quantity;
        }

        return $invoiceItems;
    }

    public function getOrderInvoiceAmountData($invoiceNumber, $invoiceData, $orderData)
    {   
        $orderNo = $invoiceData[0][0]->OrderNo;

        $invoiceType = $this->mappingInvoiceType($invoiceData[0][0]->InvoiceType);

        $invoiceItems = $this->mappingInvoiceItems($invoiceData);
 
        // Order Invoice Data
        $orderInvoiceData = $this->getOrderInvoiceData($orderData['order_no'], $invoiceType, $invoiceItems);

        if(!$orderInvoiceData)
            return false;

        if($invoiceType == 'credit-note-refund'){
            $round = 0;
            // All Invoice
            //$invoiceList = $this->getInvoiceList($orderNo);

            // Find Refund Round
            //$round = $this->findInvoiceRound($invoiceList, $invoiceNumber);
 
            // Credit Note (Refund) Data
            $orderInvoiceAmountData = $this->getCreditNoteData($orderInvoiceData, 'refund', $round);
        }
        else if($invoiceType == 'credit-note-return'){
            $round = 0;
            // All Return
            //$returnList = $this->getReturnOrderList($orderData['order_no']);
            
            // Find Refund Round
            //$round = $this->findReturnRound($returnList, $orderNo);
 
            // Credit Note (Return) Data
            $orderInvoiceAmountData = $this->getCreditNoteData($orderInvoiceData, 'return', $round);
        }
        else if($invoiceType == 'deposit'){
            $orderInvoiceAmountData = $orderInvoiceData;
            
        }
        else if($invoiceType == 'normal'){
            $orderInvoiceAmountData = $orderInvoiceData;
        }

        //shipping fee
        $this->shipping_fee = isset($invoiceData[0][0]->AmtShippingFee) ? $invoiceData[0][0]->AmtShippingFee : 0;
        $this->raw_shipping = isset($invoiceData[0][0]->ShippingFee) ? $invoiceData[0][0]->ShippingFee : 0;
        $this->vat_shipping = isset($invoiceData[0][0]->ShippingVat) ? $invoiceData[0][0]->ShippingVat : 0;

        return $orderInvoiceAmountData;
    }

    protected function getInvoiceList($orderNo)
    {
        $client = new Client();

        $userId = \Session::get('userId');
        $userData = $this->userRepository->getUsers(['id' => $userId]);
        $makro_store_id = isset($userData['data'][0]['makro_store_id']) ? $userData['data'][0]['makro_store_id'] : '';

        if ($makro_store_id == '') {
            $shipNode = '';
        } else {
            $shipNode = '<Order ShipNode="'.$makro_store_id.'"/>';
        }

        $xmlRequest = View::make('epos.xml.invoice.search', [
            'orderNo' => $orderNo,
            'shipNode' => $shipNode,
            'invoiceType' => '',
            'invoiceNo' => ''
        ])->render();

        $url = config('api.makro_epos_api') . 'eai/order/invoicelist';
        $options = [
            'body' => html_entity_decode($xmlRequest)
        ];
        $response = $client->request('POST', $url, $options);

        if($response->getStatusCode() == 200){
            $xml = $response->getBody();
            $xml = simplexml_load_string($xml, "SimpleXMLElement", 0 ,'NS1', true);
            return $xml;
        }
        else{
            return NULL;
        }
    }

    protected function getReturnOrderList($orderNo) 
    {
        $client = new Client();

        $userId = \Session::get('userId');
        $userData = $this->userRepository->getUsers(['id' => $userId]);
        $makro_store_id = isset($userData['data'][0]['makro_store_id']) ? $userData['data'][0]['makro_store_id'] : '';

        if ($makro_store_id == '') {
            $shipNode = '';
        } else {
            $shipNode = '<Order ShipNode="'.$makro_store_id.'"/>';
        }

        $xmlRequest = View::make('epos.xml.return_order.search', [
            'orderNo' => $orderNo,
            'shipNode' => $shipNode
        ])->render();

        $url = config('api.makro_epos_api') . 'eai/order/ordersearch';
        $options = [
            'body' => html_entity_decode($xmlRequest)
        ];
        $response = $client->request('POST', $url, $options);

        if($response->getStatusCode() == 200){
            $xml = $response->getBody();
            $xml = simplexml_load_string($xml, "SimpleXMLElement", 0 ,'NS1', true);
            return $xml;
        }
        else{
            return NULL;
        }
    }

    protected function findInvoiceRound($invoiceList, $invoiceNumber)
    {   
        $count = 0;

        foreach($invoiceList->OrderInvoice as $orderInvoice){
            $attributes = $orderInvoice->attributes();

            if($attributes['InvoiceType'] == 'CREDIT_MEMO'){
                if($attributes['InvoiceNo'] == $invoiceNumber){
                    $count += 1;
                    break;
                }
                else{
                    $count += 1;
                }
            }
        }

        return $count;
    }

    protected function findReturnRound($returnList, $returnOrderNo)
    {   
        $count = 0;

        foreach($returnList->Order as $order){
            $attributes = $order->attributes();

            if($attributes['Status'] == 'Return Invoiced'){
                if($attributes['OrderNo'] == $returnOrderNo){
                    $count += 1;
                    break;
                }
                else{
                    $count += 1;
                }
            }
        }

        return $count;
    }

    public function getDataInvoice($invoiceNumber,$order_number)
    {
        $datas = [];
        $order_number = $this->getOrderByReturnOrderNo($order_number);
        $invoiceData  = $this->eposBaseController->getInvoicesDetail($invoiceNumber);

        if ($invoiceData == '404' || empty($invoiceData[0])) {
            return [
                'status'  => false,
                'message' => 'data not found'
            ];
        }

        // Invoice Ship Node (Makro Store Id)
        $makro_store_id = $invoiceData[0][0]->ShipNode;

        // Invoice Type
        $invoiceType = $this->mappingInvoiceType($invoiceData[0][0]->InvoiceType);

        // Master Invoice
        $masterInvoiceData = NULL;
        if (($invoiceData[0][0]->InvoiceType == 'RETURN' || $invoiceData[0][0]->InvoiceType == 'CREDIT_MEMO') || !empty($invoiceData[0][0]->MasterInvoiceNo)) {
            $masterInvoiceData = $this->getMasterInvoice($order_number,$invoiceData[0][0]->InvoiceType,$invoiceData[0][0]->MasterInvoiceNo);
        }

        // Order Number
        $orderNo = $order_number;

        // Order
        $orderData = $this->getOrderData($orderNo);

        if(!$orderData){
            return [
                'status'  => false,
                'message' => 'Empty Order Data!'
            ];
        }

        // Order Invoice Amount Data
        $orderInvoiceAmountData = $this->getOrderInvoiceAmountData($invoiceNumber,$invoiceData,$orderData);

        if(!$orderInvoiceAmountData){
            return [
                'status'  => false,
                'message' => 'Empty Order Invoice Data!'
            ];
        }

        // Store
        $storeInfo = $this->getStoreInformation($makro_store_id);

        if(!$storeInfo){
            return [
                'status'  => false,
                'message' => 'Empty Store Data!'
            ];
        }

        // Store Address
        $storeAddress = $this->getStoreAddress($storeInfo['id']);
        if(!$storeAddress){
            // return [
            //     'status'  => false,
            //     'message' => 'Empty Address Data!'
            // ];
        }

        //get coupon discount
        $couponDiscount = $this->eposBaseController->getCouponDiscount($orderNo);

        //invoice Template
        $template = $this->getInvoiceTemplate($invoiceData, $masterInvoiceData, $orderData, $orderInvoiceAmountData, $storeInfo, $storeAddress, $couponDiscount);
        $invoices = $this->getInvoicesDataSet($template);

        $datas['invoices'] = $invoices;

        return [
            'status' => true,
            'datas'  => $datas
        ];
    }


    protected function strlenStoreId($data_pdf){

        if(strlen($data_pdf['store_id']) > 3){
            $store_id = substr($data_pdf['store_id'],strlen($data_pdf['store_id'])-3);
        }
        elseif(strlen($data_pdf['store_id']) == 3){
            $store_id = $data_pdf['store_id'];
        }
        else{
            $store_id = str_pad($data_pdf['store_id'], 3, "0", STR_PAD_LEFT);
        }
        
        return $store_id;

    }

    protected function strlenMakroCard($data_pdf){

        if(strlen($data_pdf['tax_info']['makro_member_card']) == 14){
            $makro_card = $data_pdf['tax_info']['makro_member_card'];
        }
        else{
            $makro_card = str_pad($data_pdf['tax_info']['makro_member_card'], 14, "0", STR_PAD_LEFT);
        }
        
        return $makro_card;
    }

    public function getInvoiceCode($invoice_type, $format_invoice)
    {
        switch($invoice_type){
            case 'INFO':
                if($format_invoice == 'short') return '101';
                elseif($format_invoice == 'long') return '103';
            case 'CREDIT_MEMO':
            case 'RETURN':
            case 'Return Invoiced':
                return '402';
        }

        return '';
    }
    
    // FileName from E-doc only
    public function generateFileNamePdf($data_pdf, $format_type, $invoice_code)
    {
        $file_name                  = '';
        $invoice_date               =  convertDateTime($data_pdf['invoice_date'], 'd/m/Y', 'Ymd');
        $store_id                   =  $this->strlenStoreId($data_pdf);
        $makro_card                 =  $this->strlenMakroCard($data_pdf);
        $data_pdf['format_invoice'] =  $format_type['invoices'][0]['format_type'];
    
        //Deposit
        if($invoice_code == '101'){
            $file_name = 'EFR101_'.$invoice_date.'_'.$store_id.'_'.$data_pdf['invoice_no'].'.pdf';
        }elseif($invoice_code == '103'){
            $file_name = 'EFR103_'.$invoice_date.'_'.$store_id.'_'.$data_pdf['invoice_no'].'_'.$makro_card.'.pdf';
        } 
        // Delivery note
        elseif($invoice_code == '402'){
            $file_name = 'EFR402_'.$invoice_date.'_'.$store_id.'_'.$data_pdf['invoice_no'].'.pdf' ; 
        }
        return $file_name;
    }

    //Create New Folder 
    public function manageFolder($config, $param = '')
    {
        $path = $config.$param."/";

        if (!file_exists($path)) {
            //create new
            mkdir($path, 0777, true);
        }

        return $path;

    }
}