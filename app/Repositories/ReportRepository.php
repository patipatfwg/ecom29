<?php
namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Services\Guzzle;
use Illuminate\Support\Facades\Config;
use Excel;
use App;
use Satung\SatungRounding;
use App\Library\Unit;
class ReportRepository extends BaseRepository
{
    private $guzzle;
    private $urlOrder;
    public $report_name;
    public $header;
    public $client;
    public $language;
    public $urlStore;
    public $urlConfig;

    public function __construct(Guzzle $guzzle)
    {
        parent::__construct();

        $this->guzzle     = $guzzle;
        $this->messages   = config('message');
        $this->urlStore   = config('api.makro_store_api');
        $this->urlConfig  = config('api.makro_config_api');
        $this->urlOrder   = config('api.makro_order_api');
        $this->urlPayment = config('api.makro_payment_api');
        $this->urlCoupon  = env('CURL_API_COUPON');
        $this->api['makro_bss_api'] = Config::get('api.makro_bss_api');
        $this->_unit     = new Unit;
    }
    public $coupon_type = [
        'cart discount' => 'Fixed Cart Discount',
        'product discount' => 'Fixed Product Discount',
        '' => 'No Type'
    ];

    protected $delivery_method  = 'SHP,PICK' ;
    protected $payment_type     = 'CC,PayAtStore,Wallet,Banking,OverCounter';
    protected $customer_channel     = 'normal,professional';

    private function getProductHeader($language)
    {
        switch ($language) {
            case 'en':
                $header = array('Makro item ID', 'Online SKU', 'Product Name', 'Group Product', 'E-Commerce Buyer', 'Image', 'Detail', 'Normal Price', 'Category', 'Approve Status', 'Hide', 'Priority');
                break;
            case 'th':
                $header = array('Makro item ID', 'Online SKU', 'Product Name', 'Group Product', 'E-Commerce Buyer', 'Image', 'Detail', 'Normal Price', 'Category', 'Approve Status', 'Hide', 'Priority');
                break;
            default:
                $header = array('Makro item ID', 'Online SKU', 'Product Name', 'Group Product', 'E-Commerce Buyer', 'Image', 'Detail', 'Normal Price', 'Category', 'Approve Status', 'Hide', 'Priority');
                break;
        }

        return $header;
    }

    private function getHeader($report_type, $language)
    {
        switch ($report_type) {
            case 'products':
                return self::getProductHeader($language);
                break;
        }
    }

    public function setReportOption($report_name, $report_type, $language = 'en')
    {
        $this->report_name = $report_name;
        $this->language = strtolower($language);
        $this->header = self::getHeader($report_type, $this->language);
    }

    public function exportProducts($filter)
    {
        $header = $this->header;
        $data = self::getProduct($filter);
        $products = $data['products'];

        $report = Excel::create($this->report_name, function ($excel) use ($header, $products) {

            $excel->sheet('Sheetname', function ($sheet) use ($header, $products) {

                $sheet->setOrientation('landscape');
                $sheet->setAutoFilter('A1:L10');
                $sheet->freezeFirstRow();
                $sheet->row(1, $header);
                $row = 2;

                foreach ($products as $key => $product) {

                    $data = [
                        $product['product_code'],
                        $product['online_sku'],
                        @$product['name'],
                        @$product['group_product'],
                        $product['ecom_buyer_name'],
                        $product['have_image'],
                        $product['have_detail'],
                        $product['normal_price'],
                        $product['have_category'],
                        $product['approve_status'],
                        $product['status'],
                        $product['priority']
                    ];

                    $sheet->row($row, $data);

                    $row++;
                }

            });
        });

        return $report;
    }

    private function getProduct($filter)
    {
        $params = [
            'query' => [
                'product_name' => @$filter['product_name'],
                'approve_status' => @$filter['approve_status'],
                'category_id' => @$filter['category_id'],
                'products' => @$filter['products'],
                'update_from' => @$filter['update_from'],
                'update_to' => @$filter['update_to'],
                'offset' => '',
                'limit' => ''
            ],
            'headers' => [
                'x-language' => $this->language,
                'access-token' => 'f8079146bd37dfded0a9554217d72c42'
            ]
        ];

        //$search = 'product_code='.@$filter['product_code'].'&approve_status='.@$filter['approve_status'].'&category_id='.@$filter['category_id'].'&products='.@$filter['products'].'&start_date='.@$filter['start_date'].'&end_date='.@$filter['end_date'].'&offset=&limit=';
        $url = env('CURL_API_PRODUCT') . '/product/search';
        $products = self::curl($url, $params);

        return $products['data'];
    }

    public function getStores()
    {
        //default data
        $data = [];

        //default params
        $params = [
            'limit' => 500,
            'offset' => 0,
            'order' => 'name|ASC'
        ];

        $result = $this->curlStores($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $stores = $result['data']['records'];

            foreach ($stores as $store) {
                $data[$store['makro_store_id']] = $store['name']['th'] . " (" . $store['makro_store_id'] . ")";
            }
        }

        return $data;
    }

    public function curlStores($params)
    {
        $url = $this->urlStore . 'stores';
        $options = [
            'query' => $params,
            'headers' => [
                'X-Language' => 'th|en'
            ]
        ];
        return $this->guzzle->curl('GET', $url, $options);
    }

    public function getPayments()
    {
        $data   = [];
        $params = [
            'config_type' => 'Payment Method',
            'status'      => 'active,inactive',
            'order'       => 'created_at|ASC'
        ];

        $result = $this->curlPayment($params);

        if (isset($result['data']['records']) && !empty($result['data']['records'])) {

            $configs = $result['data']['records'];

            if (!empty($configs)) {

                foreach ($configs as $config) {

                    $gateway = ((isset($config['payment_gateway']) && !empty($config['payment_gateway']))) ? $config['payment_gateway'] . ' - ' : '';
                    $nameEn  = ((isset($config['name']['en']) && !empty($config['name']['en']))) ? $gateway . $config['name']['en'] : $gateway;

                    $data[$config['code']] = $nameEn;
                }
            }
        }

        return $data;
    }

    public function curlPayment($params)
    {
        $url = $this->urlPayment . 'configs';

        $options = [
            'query' => $params
        ];

        return $this->guzzle->curl('GET', $url, $options);
    }

    public function getConfigs()
    {
        $data   = [];
        $params = [
            'config_type' => 'Payment Method',
            'status'      => 'active,inactive',
            'order'       => 'created_at|ASC'
        ];

        $result = $this->curlConfigs($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $configs = $result['data']['records'];

            foreach ($configs as $config) {
                $data[$config['code']] = $config['name'];
            }
        }

        return $data;
    }

    public function curlConfigs($params)
    {
        $url = $this->urlConfig . 'configs';

        $options = [
            'query' => $params
        ];

        return $this->guzzle->curl('GET', $url, $options);
    }

    //======================================//
    //============= Set Search =============//
    //======================================//

    /**
     * set data order
     */
    private function setOrderData(array $params)
    {
        $order = [];

        if (isset($params['order']) && count($params['order']) > 0) {
            foreach ($params['order'] as $kData => $vData) {
                if (isset($vData['column']) && isset($vData['dir'])) {
                    if($vData['column'] == 'store_name_th') {
                        $vData['column'] = 'store_name.th';
                    }
                    $order[] = $vData['column'] . '|' . $vData['dir'];
                }
            }
        }

        return implode(',', $order);
    }

    /**
     * set search text all
     */
    private function setSearchExact(array $params)
    {
        $search = [];
        if (isset($params['search']) && is_array($params['search'])) {

            $paymentType = [];
            $deliveryMethod = [];
            $customerChannel = [];

            foreach ($params['search'] as $kData => $vData) {
               
                if (isset($vData['name']) && $vData['name'] !== '_token') {
                    
                    if ($vData['name'] === 'payment_type') {
                        $paymentType[] = $vData['value'];
                    }
                    else if ($vData['name'] === 'delivery_method') {
                        $deliveryMethod[] = $vData['value'];
                    }
                    else if ($vData['name'] === 'customer_channel') {
                        $customerChannel[] = $vData['value'];
                    }
                    else {
                        $search[] = $vData['name'] . '=' . $vData['value'];
                    }
                }
            }

            if (count($paymentType) > 0) {
                $search[] = 'payment_type=' . implode($paymentType, ',');
            }else{
                $search[] = 'payment_type='    . $this->payment_type;
            } 
            
            if (count($deliveryMethod) > 0) {
                $search[] = 'delivery_method=' . implode($deliveryMethod, ',');
            } else {
                $search[] = 'delivery_method=' . $this->delivery_method;
            }

            if (count($customerChannel) > 0) {
                $search[] = 'customer_channel=' . implode($customerChannel, ',');
            } else {
                $search[] = 'customer_channel=' . $this->customer_channel;
            }
        }
      
        return $search;
    }

    /**
     * set search text all
     */
    private function setSearchText(array &$params)
    {
        $search = [];

        $searchTextFields = [
            'order_no',
            'customer_firstname',
            'customer_lastname',
            'customer_phone',
            'customer_email',
            'customer_type',
            'buyer_name',
            'item_name.th'
        ];

        if (isset($params['search']) && is_array($params['search'])) {
            foreach ($params['search'] as $kData => $vData) {
                if (isset($vData['name']) && in_array($vData['name'], $searchTextFields)) {
                    $search[] = explode('.', $vData['name'])[0] . '_fields=' . $vData['name'];
                    $search[] = explode('.', $vData['name'])[0] . '_search=' . $vData['value'];
                    unset($params['search'][$kData]);
                }
            }
        }

        return $search;
    }

    /**
     * set search date
     */
    private function setSearchDate(array &$params)
    {
        $search = [];

        if (isset($params['search']) && is_array($params['search'])) {
            $payment_date_from = '';
            $payment_date_to = '';
            $refund_date_from = '';
            $refund_date_to = '';

            foreach ($params['search'] as $kData => $vData) {
                if (isset($vData['name']) && !empty($vData['value'])) {

                    if ($vData['name'] == 'payment_date_from') {
                        $payment_date_from = convertDateTime($vData['value'], 'd/m/Y H:i', 'Y-m-d H:i:00');
                        unset($params['search'][$kData]);
                    } else if ($vData['name'] == 'refund_date_from') {
                        $refund_date_from = convertDateTime($vData['value'], 'd/m/Y H:i', 'Y-m-d H:i:00');
                        unset($params['search'][$kData]);
                    } else if ($vData['name'] == 'create_date_from') {
                        $create_date_from = convertDateTime($vData['value'], 'd/m/Y H:i', 'Y-m-d H:i:00');
                        unset($params['search'][$kData]);
                    }

                    if ($vData['name'] == 'payment_date_to' && !empty($vData['value'])) {
                        $payment_date_to = convertDateTime($vData['value'], 'd/m/Y H:i', 'Y-m-d H:i:00');
                        unset($params['search'][$kData]);
                    } else if ($vData['name'] == 'refund_date_to' && !empty($vData['value'])) {
                        $refund_date_to = convertDateTime($vData['value'], 'd/m/Y H:i', 'Y-m-d H:i:00');
                        unset($params['search'][$kData]);
                    } else if ($vData['name'] == 'create_date_to' && !empty($vData['value'])) {
                        $create_date_to = convertDateTime($vData['value'], 'd/m/Y H:i', 'Y-m-d H:i:00');
                        unset($params['search'][$kData]);
                    }
                }
            }

            if (!empty($payment_date_from) || !empty($payment_date_to)) {
                $search[] = 'payment_date=' . sprintf('[%s,%s]', $payment_date_from, $payment_date_to);
            }

            if (!empty($refund_date_from) || !empty($refund_date_to)) {
                $search[] = 'datetime=' . sprintf('[%s,%s]', $refund_date_from, $refund_date_to);
            }

            if (!empty($create_date_from) || !empty($create_date_to)) {
                $search[] = 'created_at=' . sprintf('[%s,%s]', $create_date_from, $create_date_to);
            }
        }

        return $search;
    }

    /**
     * set search data
     */
    private function setSearch(array $params)
    {
        $search = implode('&', array_merge([
            'order=' . $this->setOrderData($params),
            'fields=' . array_get($params, 'fields', ''),
            'offset=' . array_get($params, 'start', 0),
            'limit=' . array_get($params, 'length', 20)
        ], $this->setSearchDate($params), $this->setSearchText($params), $this->setSearchExact($params)));
       
        return $search;
    }

    //======================================//
    //============= Data Order =============//
    //======================================//

    /**
     * get curl data order
     */
    public function getDataOrder(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders?exclude_oms_content=1&payment_status=PAID,PAYMENT_SUCCESS&' . $getUrl);

        $output = [
            'draw' => isset($params['draw']) ? $params['draw'] : 0,
            'recordsTotal' => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data' => isset($result['data']['records']) ? $this->setDataTableOrder($result['data']['records'], $params) : [],
            'input' => $params
        ];
    
        return json_encode($output);
    }

    /**
     * get curl data replace
     */
    public function getDataReplace(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'replace?' . $getUrl);

        $output = [
            'draw'            => isset($params['draw']) ? $params['draw'] : 0,
            'recordsTotal'    => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data'            => isset($result['data']['records']) ? $this->setDataTableReplace($result['data']['records'], $params) : [],
            'input'           => $params
        ];

        return json_encode($output);
    }

    /**
     * curl api replace report
     */
    public function getDataReplaceReport(array $params, $stores)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'replace?' . $getUrl);

        $result['store'] = '-';
        $result['date']  = '-';

        if (isset($stores[$params['search'][1]['value']])) {
            if (preg_match('/^(.*) ([(]{1})([\d]{1,10})([)]{1})$/', $stores[$params['search'][1]['value']], $match)) {
                $result['store'] = sprintf("%03d", $match[3]) . ' ' . $match[1];
            }
        }

        if (isset($params['search'][2]['value']) && isset($params['search'][3]['value'])) {
            $s = explode(' ', $params['search'][2]['value']);
            $e = explode(' ', $params['search'][3]['value']);
            $result['date'] = $s[0] . ' - ' . $e[0];
        }

        if (isset($result['data']['records']) && !empty($result['data']['records'])) {

            return Excel::create('replace_log_report_' . date('YmdHis'), function ($excel) use ($result) {

                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";

                $excel->sheet('Order', function ($sheet) use ($result) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:N1');
                    $sheet->freezeFirstRow();

                    $sheet->mergeCells('A1:E1');
                    $sheet->row(1, [
                        'Replace log report : ',
                        $result['store']
                    ]);
                    $sheet->mergeCells('A2:E2');
                    $sheet->row(2, [
                        'Date : ',
                        $result['date']
                    ]);

                    $row = 3;
                    $start = (int) $result['data']['pagination']['offset'];
                    $sheet->row($row, function ($row) {
                        $row->setBackground('#dddddd');
                    });
                    $sheet->row($row, [
                        'No.',
                        'New Issued Date',
                        'Old Issued Date',
                        'Old Invoice No.',
                        'Old Name/Company',
                        'Old Tax ID',
                        'Old Branch No.',
                        'New Invoice No.',
                        'New Name/Company',
                        'New Tax ID',
                        'New Branch No.',
                        'Invoice Exvat Amount',
                        'Tax',
                        'Invoice Invat Amount'
                    ]);

                    foreach ($result['data']['records'] as $kData => $vData) {

                        ++$row;

                        $data = [
                            $kData + $start + 1,
                            isset($vData['new_issued_date']) ? date('d/m/Y H:i:s', strtotime($vData['new_issued_date'])) : '',
                            isset($vData['old_issued_date']) ? date('d/m/Y H:i:s', strtotime($vData['old_issued_date'])) : '',
                            isset($vData['old_invoice']) ? $vData['old_invoice'] : '',
                            isset($vData['old_company_name']) ? $vData['old_company_name'] : '',
                            isset($vData['old_tax_id']) ? '="' . $vData['old_tax_id'] . '"' : '',
                            isset($vData['old_branch_id']) ? '="' . $vData['old_branch_id'] . '"' : '',
                            isset($vData['new_invoice']) ? $vData['new_invoice'] : '',
                            isset($vData['new_company_name']) ? $vData['new_company_name'] : '',
                            isset($vData['new_tax_id']) ? '="' . $vData['new_tax_id'] . '"' : '',
                            isset($vData['new_branch_id']) ? '="' . $vData['new_branch_id'] . '"' : '',
                            isset($vData['subtotal']) ? $vData['subtotal'] : '',
                            isset($vData['vat']) ? $vData['vat'] : '',
                            isset($vData['net_amount']) ? $vData['net_amount'] : ''
                        ];

                        $sheet->row($row, $data);
                    }
                });

            })->export('csv');
        }

        return false;
    }

    /**
     * get curl data order status
     */
    public function getDataOrderStatus(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders?exclude_oms_content=1&' . $getUrl);

        $output = [
            'draw'            => isset($params['draw']) ? $params['draw'] : 0,
            'recordsTotal'    => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data'            => isset($result['data']['records']) ? $this->setDataTableOrderStatus($result['data']['records'], $params) : [],
            'input'           => $params
        ];

        return json_encode($output);
     }

    /**
     * curl api order report
     */
    public function getDataOrderReport(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders?exclude_oms_content=1&payment_status=PAID,PAYMENT_SUCCESS&' . $getUrl);
        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users = $result['data'];

            return Excel::create('order_report_' . date('YmdHis'), function ($excel) use ($users) {

                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";

                $excel->sheet('Order', function ($sheet) use ($users) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:U1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Order Number',
                        'Create Date',
                        'Customer Name',
                        'Customer Mobile',
                        'Customer Email',
                        'Customer Type',
                        'Order Amount (vat items)',
                        'Order Amount (vat free items)',
                        'Delivery Fee',
                        'Coupon Discount',
                        'Order Amount',
                        'Order Amount Exc Vat',
                        'Payment Fee',
                        'VAT of Payment Fee',
                        'Net Amount',
                        'W.H. TAX',
                        'Payment Gateway',
                        'Payment Channel',
                        'Payment ID',
                        'Payment Date',
                        'Store No.',
                        'Store Name',
                        'Shipping Method',
                        'Customer Channel'
                    ]);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $start = (int)$users['pagination']['offset'];
                    $row = 1;
                    foreach ($users['records'] as $kData => $vData) {
                        ++$row;

                        $amount_vat_items      = isset($vData['amount_vat_items']) ? number_format($vData['amount_vat_items'], 2) : '';
                        $amount_vat_free_items = isset($vData['amount_vat_free_items']) ? number_format($vData['amount_vat_free_items'], 2) : '';
                        $discount              = isset($vData['discount']) ? number_format($vData['discount'], 2) : '';
                        $total_amount          = isset($vData['total_amount']) ? number_format(round($vData['total_amount'], 2), 2) : '';
                        $total_amount_exc_vat  = isset($vData['total_amount_exc_vat']) ? number_format(round($vData['total_amount_exc_vat'], 2), 2) : '';
                        $payment_fee           = isset($vData['payment_fee']) ? number_format(round($vData['payment_fee'], 2), 2) : '';
                        $payment_fee_vat       = isset($vData['payment_fee_vat']) ? number_format(round($vData['payment_fee_vat'], 2), 2) : '';
                        $net_amount            = isset($vData['net_amount']) ? number_format(round($vData['net_amount'], 2), 2) : '';
                        $w_h_tax               = isset($vData['w_h_tax']) ? number_format(round($vData['w_h_tax'], 2), 2) : '';
                        $customer_channel      = isset($vData['customer_channel']) ? config('config.customer_channel.'.$vData['customer_channel']) : '';

                        $paymentType = array_get($vData, 'payment_type', '-');

                        if ($paymentType === 'CC') {
                            $paymentType = 'Credit Card';
                        } else if ($paymentType === 'PayAtStore') {
                            $paymentType = 'Pay@Store';
                        }

                        $customer_type_id = (isset($vData['customer_type_id']) && !empty($vData['customer_type_id'])) ? ' (' . $vData['customer_type_id'] . ')' : '';
                        $customer_type    = isset($vData['customer_type']) ? $vData['customer_type'] . $customer_type_id : '';
                        $deliveryFee      = isset($vData['delivery_fee']) ? number_format($vData['delivery_fee'], 2) : 0;

                        $paymentGateway = '-';

                        if (isset($vData['payment_gateway'])) {

                            if ($vData['payment_gateway'] == 'TMN') {
                                $paymentGateway = 'True Money';
                            } else {
                                $paymentGateway = $vData['payment_gateway'];
                            }
                        }

                        if (isset($vData['delivery_method'])) {

                            if ($vData['delivery_method'] == 'PICK') {
                                $deliveryMethod = 'Pickup';
                            } else if ($vData['delivery_method'] == 'SHP') {
                                $deliveryMethod = 'Delivery';
                            }
                        }

                        $data = [
                            $kData + $start + 1,
                             '="' . array_get($vData, 'order_no', ''). '"',
                            !empty($vData['created_at'])? '="'.convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s').'"' : '' ,
                            $this->_unit->removeFirstInjection(array_get($vData, 'customer_firstname', '') . ' ' . array_get($vData, 'customer_lastname', '')),
                            '="' . array_get($vData, 'customer_phone', '') . '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'customer_email', '')),
                            $this->_unit->removeFirstInjection($customer_type),
                            $this->_unit->removeFirstInjection($amount_vat_items . ' '),
                            $this->_unit->removeFirstInjection($amount_vat_free_items . ' '),
                            $this->_unit->removeFirstInjection($deliveryFee . ' '),
                            $this->_unit->removeFirstInjection($discount . ' '),
                            $this->_unit->removeFirstInjection($total_amount . ' '),
                            $this->_unit->removeFirstInjection($total_amount_exc_vat . ' '),
                            $this->_unit->removeFirstInjection($payment_fee . ' '),
                            $this->_unit->removeFirstInjection($payment_fee_vat . ' '),
                            $this->_unit->removeFirstInjection($net_amount . ' '),
                            $this->_unit->removeFirstInjection($w_h_tax . ' '),
                            $this->_unit->removeFirstInjection($paymentGateway),
                            $this->_unit->removeFirstInjection($paymentType),
                            '="' . array_get($vData, 'payment_id', ''). '"',
                            !empty($vData['payment_date'])? '="'.convertDateTime($vData['payment_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s').'"' : '',
                            '="' . array_get($vData, 'store_id', ''). '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'store_name.th', '')),
                            $this->_unit->removeFirstInjection($deliveryMethod),
                            $this->_unit->removeFirstInjection($customer_channel),
                        ];
                        $sheet->row($row, $data);
                    }

                    $sumRow = $row + 1;

                    //$sheet->getActiveSheet()->getStyle('H')->getNumberFor‌​mat()->setFormatCode‌​("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
                    //$sheet->getStyle('H2:H' . $sumRow)->getNumberFormat()->setFormatCode('#,##0.00');

                    $sumDeliveryFee = isset($users['summary']['delivery_fee']) ? number_format($users['summary']['delivery_fee'], 2) : 0;

                    $sheet->row($sumRow, [
                        'Summary Data',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        number_format(round($users['summary']['amount_vat_items'], 2), 2) . ' ',
                        number_format(round($users['summary']['amount_vat_free_items'], 2), 2) . ' ',
                        $sumDeliveryFee,
                        number_format(round($users['summary']['discount'], 2), 2) . ' ',
                        number_format(round($users['summary']['total_amount'], 2), 2) . ' ',
                        number_format(round($users['summary']['total_amount_exc_vat'], 2), 2) . ' ',
                        number_format(round($users['summary']['payment_fee'], 2), 2) . ' ',
                        number_format(round($users['summary']['payment_fee_vat'], 2), 2) . ' ',
                        number_format(round($users['summary']['net_amount'], 2), 2) . ' ',
                        number_format(round($users['summary']['w_h_tax'], 2), 2) . ' ',
                        '',
                        '',
                        '',
                        '',
                        '',
                        ''
                    ]);
                });

            })->export('csv');
        }

        return false;
    }

    /**
     * curl api order status report
     */
    public function getDataOrderStatusReport(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders?exclude_oms_content=1&' . $getUrl);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users = $result['data'];

            return Excel::create('order_report_' . date('YmdHis'), function ($excel) use ($users) {

                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";

                $excel->sheet('Order', function ($sheet) use ($users) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:U1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Order Number',
                        'Create Date',
                        'Customer Name',
                        'Customer Mobile',
                        'Customer Email',
                        'Customer Type',
                        'Order Amount (vat items)',
                        'Order Amount (vat free items)',
                        'Discount',
                        'Order Amount',
                        'Order Amount Exc Vat',
                        'Payment Fee',
                        'VAT of Payment Fee',
                        'Net Amount',
                        'W.H. TAX',
                        'Payment Channel',
                        'Payment ID',
                        'Payment Date',
                        'Store No.',
                        'Store Name',
                        'Status'
                    ]);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $start = (int)$users['pagination']['offset'];
                    $row   = 1;

                    foreach ($users['records'] as $kData => $vData) {
                
                        ++$row;
                        $amount_vat_items = isset($vData['amount_vat_items']) ? number_format($vData['amount_vat_items'], 2) : '';
                        $amount_vat_free_items = isset($vData['amount_vat_free_items']) ? number_format($vData['amount_vat_free_items'], 2) : '';
                        $discount = isset($vData['discount']) ? number_format($vData['discount'], 2) : '';
                        $total_amount = isset($vData['total_amount']) ? number_format($vData['total_amount'], 2) : '';
                        $total_amount_exc_vat = isset($vData['total_amount_exc_vat']) ? number_format($vData['total_amount_exc_vat'], 2) : '';
                        $payment_fee = isset($vData['payment_fee']) ? number_format($vData['payment_fee'], 2) : '';
                        $payment_fee_vat = isset($vData['payment_fee_vat']) ? number_format($vData['payment_fee_vat'], 2) : '';
                        $net_amount = isset($vData['net_amount']) ? number_format($vData['net_amount'], 2) : '';
                        $w_h_tax = isset($vData['w_h_tax']) ? number_format($vData['w_h_tax'], 2) : '';
                        $paymentType = array_get($vData, 'payment_type', '');
                        
                        if ($paymentType === 'CC') {
                            $paymentType = 'Credit Card';
                        } else if ($paymentType === 'PayAtStore') {
                            $paymentType = 'Pay@Store';
                        }

                        $customer_type_id = (isset($vData['customer_type_id']) && !empty($vData['customer_type_id'])) ? ' (' . $vData['customer_type_id'] . ')' : '';
                        $customer_type    = isset($vData['customer_type']) ? $vData['customer_type'] . $customer_type_id : '';
                        $delivery_fee     = isset($vData['delivery_fee']) ? number_format($vData['delivery_fee'], 0) : 0;

                        $paymentGateway = '-';
                        $payment_date = (isset($vData['payment_date']) && !empty($vData['payment_date'])) ? convertDateTime($vData['payment_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '' ;


                        if (isset($vData['payment_gateway'])) {

                            if ($vData['payment_gateway'] == 'TMN') {
                                $paymentGateway = 'True Money';
                            } else {
                                $paymentGateway = $vData['payment_gateway'];
                            }
                        }

                        $data = [
                            $kData + $start + 1,
                            '="' . array_get($vData, 'order_no', '') . '"',
                            '="' . !empty($vData['created_at'])? convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '' . '"',
                            array_get($vData, 'customer_firstname', '') . ' ' . array_get($vData, 'customer_lastname', ''),
                            '="' . array_get($vData, 'customer_phone', '') . '"',
                            array_get($vData, 'customer_email', ''),
                            $customer_type,
                            '="' . $amount_vat_items . '"',
                            '="' . $amount_vat_free_items . '"',
                            '="' . $discount . '"',
                            '="' . $total_amount . '"',
                            '="' . $total_amount_exc_vat . '"',
                            '="' . $payment_fee . '"',
                            '="' . $payment_fee_vat . '"',
                            '="' . $net_amount . '"',
                            '="' . $w_h_tax . '"',
                            $paymentType,
                            '="' . array_get($vData, 'payment_id', '') . '"',
                            '="' .  $payment_date  . '"',
                            array_get($vData, 'store_id', ''),
                            array_get($vData, 'store_name.th', ''),
                            isset($vData['status']) ? $vData['status'] : ''
                        ];
                        $sheet->row($row, $data);
                    }

                    $sumRow = $row + 1;

                    //$sheet->getActiveSheet()->getStyle('H')->getNumberFor‌​mat()->setFormatCode‌​("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
                    //$sheet->getStyle('H2:H' . $sumRow)->getNumberFormat()->setFormatCode('#,##0.00');

                    $sheet->row($sumRow, [
                        'Summary Data',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '="' . number_format($users['summary']['amount_vat_items'], 2) . '"',
                        '="' . number_format($users['summary']['amount_vat_free_items'], 2) . '"',
                        '="' . number_format($users['summary']['discount'], 2) . '"',
                        '="' . number_format($users['summary']['total_amount'], 2) . '"',
                        '="' . number_format($users['summary']['total_amount_exc_vat'], 2) . '"',
                        '="' . number_format($users['summary']['payment_fee'], 2) . '"',
                        '="' . number_format($users['summary']['payment_fee_vat'], 2) . '"',
                        '="' . number_format($users['summary']['net_amount'], 2) . '"',
                        '="' . number_format($users['summary']['w_h_tax'], 2) . '"',
                        '',
                        '',
                        '',
                        '',
                        ''
                    ]);
                });

            })->export('csv');
        }

        return false;
    }

    /**
     * set data replace
     */
    public function setDataTableReplace($data, array $params)
    {
        $dataTable = [];

        if (!empty($data)) {

            foreach ($data as $kData => $vData) {

                $nData = ($kData + 1) + $params['start'];

                $dataTable[] = [
                    'number'               => $nData,
                    'new_issued_date'      => isset($vData['new_issued_date']) ? date('d/m/Y', strtotime($vData['new_issued_date'])) : '',
                    'old_issued_date'      => isset($vData['old_issued_date']) ? date('d/m/Y', strtotime($vData['old_issued_date'])) : '',
                    'old_invoice'          => isset($vData['old_invoice']) ? $vData['old_invoice'] : '',
                    'old_company_name'     => isset($vData['old_company_name']) ? $vData['old_company_name'] : '',
                    'old_tax_id'           => isset($vData['old_tax_id']) ? $vData['old_tax_id'] : '',
                    'old_branch_id'        => isset($vData['old_branch_id']) ? $vData['old_branch_id'] : '',
                    'new_invoice'          => isset($vData['new_invoice']) ? $vData['new_invoice'] : '',
                    'new_company_name'     => isset($vData['new_company_name']) ? $vData['new_company_name'] : '',
                    'new_tax_id'           => isset($vData['new_tax_id']) ? $vData['new_tax_id'] : '',
                    'new_branch_id'        => isset($vData['new_branch_id']) ? $vData['new_branch_id'] : '',
                    'invoice_exvat_amount' => isset($vData['subtotal']) ? number_format((float)$vData['subtotal'],2) : "",
                    'tax'                  => isset($vData['vat']) ? number_format((float)$vData['vat'],2) : "",
                    'invoice_invat_amount' => isset($vData['net_amount']) ? number_format((float)$vData['net_amount'],2) : ""
                ];
            }
        }

        return $dataTable;
    }

    /**
     * set data order
     */
    public function setDataTableOrder($data, array $params)
    {
        $dataTable = [];

        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {
                $vData            = $this->setFormatData($vData);
                $nData            = ($kData + 1) + $params['start'];
                $firstname        = (isset($vData['customer_firstname']))?$vData['customer_firstname']: '';
                $lastname         = (isset($vData['customer_lastname']))?$vData['customer_lastname']: '';
                $customer_type_id = (isset($vData['customer_type_id'])&& !empty($vData['customer_type_id'])) ? '(' . $vData['customer_type_id'] . ')' : '';
                $customer_type    = isset($vData['customer_type'])?$vData['customer_type'].$customer_type_id :  '';
                $paymentGateway   = '-';

                if (isset($vData['payment_gateway'])) {

                    if ($vData['payment_gateway'] == 'TMN') {
                        $paymentGateway = 'True Money';
                    } else {
                        $paymentGateway = $vData['payment_gateway'];
                    }
                }

                $dataTable[] = [
                    'number'                => $nData,
                    'order_no'              => (string) $vData['order_no'],
                    'created_at'            => convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                    'customer_name'         => $firstname . ' ' . $lastname,
                    'customer_phone'        => isset($vData['customer_phone']) ? $vData['customer_phone'] : '',
                    'customer_email'        => isset($vData['customer_email']) ? $vData['customer_email'] : '',
                    'customer_type'         => $customer_type,
                    'amount_vat_items'      => isset($vData['amount_vat_items']) ? number_format($vData['amount_vat_items'], 2) : '',
                    'amount_vat_free_items' => isset($vData['amount_vat_free_items']) ? number_format($vData['amount_vat_free_items'], 2) : '',
                    'discount'              => isset($vData['discount']) ? number_format($vData['discount'], 2) : '',
                    'total_amount'          => isset($vData['total_amount']) ? round($vData['total_amount'], 2) : '',
                    'total_amount_exc_vat'  => isset($vData['total_amount_exc_vat']) ? round($vData['total_amount_exc_vat'], 2) : '',
                    'payment_fee'           => isset($vData['payment_fee']) ? round($vData['payment_fee'], 2) : '',
                    'payment_fee_vat'       => isset($vData['payment_fee_vat']) ? round($vData['payment_fee_vat'], 2) : '',
                    'net_amount'            => isset($vData['net_amount']) ? round($vData['net_amount'], 2) : '',
                    'w_h_tax'               => isset($vData['w_h_tax']) ? round($vData['w_h_tax'], 2) : '',
                    'payment_type'          => isset($vData['payment_type']) ? $vData['payment_type'] : '-',
                    'payment_id'            => isset($vData['payment_id']) ? $vData['payment_id'] : '',
                    'payment_date'          => isset($vData['payment_date']) ? convertDateTime($vData['payment_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '',
                    'store_id'              => isset($vData['store_id']) ? $vData['store_id'] : '',
                    'store_name_th'         => isset($vData['store_name']['th']) ? $vData['store_name']['th'] : '',
                    'delivery_fee'          => isset($vData['delivery_fee']) ? number_format($vData['delivery_fee'], 2) : 0,
                    'payment_gateway'       => $paymentGateway,
                    'delivery_method'       => isset($vData['delivery_method']) ? $vData['delivery_method'] : '',
                    'customer_channel'      => isset($vData['customer_channel']) ? config('config.customer_channel.'.$vData['customer_channel']): ''
                ];
            }
        }

        return $dataTable;
    }

    /**
     * set data order
     */
     public function setDataTableOrderStatus($data, array $params)
     {
         $dataTable = [];
 
         if (count($data) > 0) {
             foreach ($data as $kData => $vData) {
 
                 if($vData['payment_type'] == "CC"){
                     $vData['payment_type'] = "Credit Card";
                 }else if($vData['payment_type'] == "PayAtStore"){
                     $vData['payment_type'] = "Pay@Store";
                 }
                 $nData = ($kData + 1) + $params['start'];
 
                 $firstname = (isset($vData['customer_firstname'])) ? $vData['customer_firstname'] : '';
                 $lastname = (isset($vData['customer_lastname'])) ? $vData['customer_lastname'] : '';
 
                 $customer_type_id = (isset($vData['customer_type_id']) && !empty($vData['customer_type_id'])) ? ' (' . $vData['customer_type_id'] . ')' : '';
                 $customer_type = isset($vData['customer_type']) ? $vData['customer_type'] . $customer_type_id : '';
 
                 $dataTable[] = [
                     'number' => $nData,
                     'order_no' => (string) $vData['order_no'],
                     'created_at' => convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y H:i:s'),
                     'customer_name' => $firstname . ' ' . $lastname,
                     'customer_phone' => isset($vData['customer_phone']) ? $vData['customer_phone'] : '',
                     'customer_email' => isset($vData['customer_email']) ? $vData['customer_email'] : '',
                     'customer_type' => $customer_type,
                     'amount_vat_items' => isset($vData['amount_vat_items']) ? round($vData['amount_vat_items'], 2) : '',
                     'amount_vat_free_items' => isset($vData['amount_vat_free_items']) ? round($vData['amount_vat_free_items'], 2) : '',
                     'discount' => isset($vData['discount']) ? number_format($vData['discount'], 2) : '',
                     'total_amount' => isset($vData['total_amount']) ? round($vData['total_amount'], 2) : '',
                     'total_amount_exc_vat' => isset($vData['total_amount_exc_vat']) ? round($vData['total_amount_exc_vat'], 2) : '',
                     'payment_fee' => isset($vData['payment_fee']) ? round($vData['payment_fee'], 2) : '',
                     'payment_fee_vat' => isset($vData['payment_fee_vat']) ? round($vData['payment_fee_vat'], 2) : '',
                     'net_amount' => isset($vData['net_amount']) ? round($vData['net_amount'], 2) : '',
                     'w_h_tax' => isset($vData['w_h_tax']) ? round($vData['w_h_tax'], 2) : '',
                     'payment_type' => isset($vData['payment_type']) ? $vData['payment_type'] : '-',
                     'payment_id' => isset($vData['payment_id']) ? $vData['payment_id'] : '',
                     'payment_date' => isset($vData['payment_date']) ? convertDateTime($vData['payment_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '',
                     'store_id' => isset($vData['store_id']) ? $vData['store_id'] : '',
                     'store_name_th' => isset($vData['store_name']['th']) ? $vData['store_name']['th'] : '',
                     'status'   => isset($vData['status']) ? $vData['status'] : '',
                     'delivery_method'         => isset($vData['delivery_method']) ? $vData['delivery_method'] : ''
                     
                 ];
             }
         }
 
         return $dataTable;
     }

    //==========================================//
    //============= Data dailysale =============//
    //==========================================//

    /**
     * get curl data dailysale
     */
    public function getDataDailysale(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders?exclude_oms_content=1&payment_status=PAID,PAYMENT_SUCCESS&' . $getUrl);

        $output = [
            'draw'            => isset($params['draw']) ? $params['draw'] : 0,
            'recordsTotal'    => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data'            => isset($result['data']['records']) ? $this->setDataTableDailysale($result['data']['records'], $params) : [],
            'input'           => $params
        ];

        return json_encode($output);
    }

    /**
     * set data dailysale
     */
    public function setDataTableDailysale($data, array $params)
    {
        $dataTable = [];

        if (!empty($data)) {
            foreach ($data as $kData => $vData) {
                $nData = ($kData + 1) + $params['start'];
                $customer_name = $vData['customer_firstname'] . ' ' . $vData['customer_lastname'];

                $dataTable[] = [
                    'number'            => $nData,
                    'store_id'          => isset($vData['store_id']) ? $vData['store_id'] : '',
                    'store_name_th'     => isset($vData['store_name']['th']) ? $vData['store_name']['th'] : '',
                    'payment_date'      => isset($vData['payment_date']) ? date('d/m/Y H:i:s', strtotime($vData['payment_date'])) : '',
                    'payment_id'        => isset($vData['payment_id']) ? $vData['payment_id'] : '',
                    'order_no'          => isset($vData['order_no']) ? $vData['order_no'] : '',
                    'makro_member_card' => isset($vData['makro_member_card']) ? $vData['makro_member_card'] : '',
                    'customer_name'     => $customer_name,
                    'amount'            => isset($vData['total_amount']) ? number_format($vData['total_amount'], 2) : '',
                    'payment_type'      => isset($vData['payment_type']) ? $vData['payment_type'] : '-',
                ];
            }
        }

        return $dataTable;
    }

    public function getDataDailySaleReport(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders?exclude_oms_content=1&payment_status=PAID,PAYMENT_SUCCESS&' . $getUrl);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users = $result['data'];

            return Excel::create('daily_sale_report_' . date('YmdHis'), function ($excel) use ($users) {

                $excel->sheet('Daily Sale', function ($sheet) use ($users) {

                    $sheet->setOrientation('landscape');

                    $sheet->row(1, ['Daily E-Commerce Sales']);
                    $sheet->row(2, [ date('d/m/Y H:i:s') ]);

                    $sheet->setAutoFilter('A1:J1');

                    $sheet->row(3, [
                        'No.',
                        'Store ID',
                        'Store Name',
                        'Payment Datetime',
                        'Ref ID',
                        'Order No',
                        'Member Number',
                        'Customer Name',
                        'Amount',
                        'Payment Channel'
                    ]);
                    $sheet->row(3, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $start = (int)$users['pagination']['offset'];
                    $row = 3;

                    foreach ($users['records'] as $kData => $vData) {
                        ++$row;
                        $customer_name = $vData['customer_firstname'] . ' ' . $vData['customer_lastname'];
                        $data = [
                            $kData + $start + 1,
                            '="' . isset($vData['store_id']) ? $vData['store_id'] : '' . '"',
                            isset($vData['store_name']['th']) ? $vData['store_name']['th'] : '',
                            isset($vData['payment_date']) ? date('d/m/Y H:i:s', strtotime($vData['payment_date'])) : '',
                            '="'.isset($vData['payment_id']) ? $vData['payment_id'] : ''. '"',
                            '="'.isset($vData['order_no']) ? $vData['order_no'] : ''. '"',
                            '="'.isset($vData['makro_member_card']) ? $vData['makro_member_card'] : ''. '"',
                            $customer_name,
                            isset($vData['total_amount']) ? number_format($vData['total_amount'], 2) : '',
                            isset($vData['payment_type']) ? $vData['payment_type'] : '-',
                        ];

                        $sheet->row($row, $data);
                    }

                    $sheet->row($row + 1, [
                        'TOTAL',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        isset($users['summary']['total_amount']) ? number_format($users['summary']['total_amount'], 2) : '',
                        ''
                    ]);
                });

            })->export('csv');
        }

        return false;
    }

    //========================================//
    //============= Data Product =============//
    //========================================//

    public function getDataProduct(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orderproducts?' . $getUrl);
        $output = [
            'draw' => $params['draw'],
            'recordsTotal' => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data' => isset($result['data']['records']) ? $this->setDataTableProduct($result['data']['records'], $params) : [],
            'input' => $params
        ];
        return json_encode($output);
    }

    /**
     * set data product
     */
    public function setDataTableProduct($data, array $params)
    {
        $dataTable = [];

        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {
                $nData = ($kData + 1) + $params['start'];
                $dataTable[] = [
                    'number' => $nData,
                    'item_id' => $vData['item_id'],
                    'item_name_th' => $vData['item_name']['th'],
                    'buyer_name' => $vData['buyer_name'],
                    'quantity' => number_format($vData['quantity']),
                    'amount' => number_format($vData['amount'], 2),
                    'vat_rate' => $vData['vat_rate']
                ];
            }
        }

        return $dataTable;
    }

    public function getDataProductReport(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orderproducts?' . $getUrl);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users = $result['data'];

            return Excel::create('products_report_' . date('YmdHis'), function ($excel) use ($users) {

                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";

                $excel->sheet('Order', function ($sheet) use ($users) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:K1');
                    $sheet->row(1, [
                        'No.',
                        'Item ID',
                        'Product Name',
                        'Qty',
                        'Amount',
                        'Vat free items (Y/N)',
                        'Buyer Group'
                    ]);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $start = (int)$users['pagination']['offset'];
                    $row = 1;
                    foreach ($users['records'] as $kData => $vData) {
                        ++$row;
                        $data = [
                            $kData + $start + 1,
                            '="' . $vData['item_id'] . '"',
                            $vData['item_name']['th'],
                            $vData['quantity'],
                            number_format((float)$vData['amount'], 2),
                            $vData['vat_rate'],
                            $vData['buyer_name']
                        ];

                        $sheet->row($row, $data);
                    }

                    $sheet->row($row + 1, [
                        'Summary Data',
                        '',
                        '',
                        $this->_unit->removeFirstInjection($users['summary']['quantity']),
                        number_format((float)$users['summary']['amount'],2),
                        '',
                        ''
                    ]);
                });

            })->export('csv');
        }

        return false;
    }

    //========================================//
    //============= Data Treasury =============//
    //========================================//

    public function getDataTreasury(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'ordertreasury?' . $getUrl);

        $output = [
            'draw' => $params['draw'],
            'recordsTotal' => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data' => isset($result['data']['records']) ? $this->setDataTableTreasury($result['data']['records'], $params) : [],
            'input' => $params
        ];

        return json_encode($output);
    }

    public function setDataTableTreasury($data, array $params)
    {
        $dataTable = [];

        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {

                if ($vData['payment_type'] == 'CC') {
                    $vData['payment_type'] = 'Credit Card';
                } elseif ($vData['payment_type'] == 'PayAtStore') {
                    $vData['payment_type'] = 'Pay@Store';
                }

                $paymentGateway = '-';

                if (isset($vData['payment_gateway'])) {

                    if ($vData['payment_gateway'] == 'TMN') {
                        $paymentGateway = 'True Money';
                    } else {
                        $paymentGateway = $vData['payment_gateway'];
                    }
                }

                $nData = ($kData + 1) + $params['start'];
                $dataTable[] = [
                    'number'                     => $nData,
                    'store_id'                   => isset($vData['store_id']) ? $vData['store_id'] : '',
                    'store_name_th'              => isset($vData['store_name']['th']) ? $vData['store_name']['th'] : '',
                    'number_of_transaction'      => $vData['number_of_transaction'],
                    'total_amount_vat_items'     => number_format(round($vData['total_amount_vat_items'], 2), 2),
                    'total_amount_exc_vat_items' => number_format(round($vData['total_amount_exc_vat_items'], 2), 2),
                    'discount'                   => number_format($vData['discount'],2),
                    'total_amount'               => number_format(round($vData['total_amount'], 2), 2),
                    'total_amount_exc_vat'       => number_format(round($vData['total_amount_exc_vat'], 2), 2),
                    'payment_fee'                => number_format(round($vData['payment_fee'], 2), 2),
                    'vat_payment_fee'            => number_format(round($vData['vat_payment_fee'], 2), 2),
                    'net_amount'                 => number_format(round($vData['net_amount'], 2), 2),
                    'w_h_tax'                    => number_format(round($vData['w_h_tax'], 2), 2),
                    'payment_type'               => $vData['payment_type'],
                    'delivery_fee'               => isset($vData['delivery_fee']) ? number_format($vData['delivery_fee'], 2) : 0,
                    'payment_gateway'            => $paymentGateway
                ];
            }
        }

        return $dataTable;
    }

    /**
     * curl api treasury report
     */
    public function getDataTreasuryReport(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'ordertreasury?' . $getUrl);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users = $result['data'];

            return Excel::create('treasury_report_' . date('YmdHis'), function ($excel) use ($users) {

                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";

                $excel->sheet('Treasury', function ($sheet) use ($users) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:U1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Store No.',
                        'Store Name',
                        'Number of Transaction',
                        'Order Amount (vat items)',
                        'Order Amount (vat free items)',
                        'Delivery Fee',
                        'Coupon Discount',
                        'Order Amount',
                        'Order Amount Exc Vat',
                        'Payment Fee',
                        'VAT of Payment Fee',
                        'Net Amount',
                        'W.H. TAX',
                        'Payment Gateway',
                        'Payment Channel'
                    ]);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $start = (int)$users['pagination']['offset'];
                    $row = 1;
                    foreach ($users['records'] as $kData => $vData) {
                        ++$row;

                        $paymentType = array_get($vData, 'payment_type', '');
                        $deliveryFee = isset($vData['delivery_fee']) ? number_format($vData['delivery_fee'], 2) : 0;

                        if ($paymentType === 'CC') {
                            $paymentType = 'Credit Card';
                        } elseif ($paymentType === 'PayAtStore') {
                            $paymentType = 'Pay@Store';
                        }

                        $paymentGateway = '-';

                        if (isset($vData['payment_gateway'])) {

                            if ($vData['payment_gateway'] == 'TMN') {
                                $paymentGateway = 'True Money';
                            } else {
                                $paymentGateway = $vData['payment_gateway'];
                            }
                        }

                        $data = [
                            $kData + $start + 1,
                            '="' . array_get($vData, 'store_id', '') . '"',
                            $this->_unit->removeFirstInjection(array_get($vData, 'store_name.th', '')),
                            $this->_unit->removeFirstInjection(array_get($vData, 'number_of_transaction', '')),
                            number_format(round(array_get($vData, 'total_amount_vat_items', '0'), 2), 2),
                            number_format(round(array_get($vData, 'total_amount_exc_vat_items', '0'), 2), 2),
                            $this->_unit->removeFirstInjection($deliveryFee),
                            number_format(array_get($vData, 'discount', '0'), 2),
                            number_format(round(array_get($vData, 'total_amount', '0'), 2), 2),
                            number_format(round(array_get($vData, 'total_amount_exc_vat', '0'), 2), 2),
                            number_format(round(array_get($vData, 'payment_fee', '0'), 2), 2),
                            number_format(round(array_get($vData, 'vat_payment_fee', '0'), 2), 2),
                            number_format(round(array_get($vData, 'net_amount', '0'), 2), 2),
                            number_format(round(array_get($vData, 'w_h_tax', '0'), 2), 2),
                            $this->_unit->removeFirstInjection($paymentGateway),
                            $this->_unit->removeFirstInjection($paymentType)
                        ];

                        $sheet->row($row, $data);
                    }

                    $sumRow = $row + 1;
                    $sumDeliveryFee = isset($users['summary']['delivery_fee']) ? number_format($users['summary']['delivery_fee'], 2) : 0;

                    $sheet->row($sumRow, [
                        'Summary Data',
                        '',
                        '',
                        '',
                        number_format($users['summary']['total_amount_vat_items'], 2),
                        number_format($users['summary']['total_amount_exc_vat_items'], 2),
                        $this->_unit->removeFirstInjection($sumDeliveryFee),
                        number_format($users['summary']['discount'], 2),
                        number_format($users['summary']['total_amount'], 2),
                        number_format($users['summary']['total_amount_exc_vat'], 2),
                        number_format($users['summary']['payment_fee'], 2),
                        number_format($users['summary']['vat_payment_fee'], 2),
                        number_format($users['summary']['net_amount'], 2),
                        number_format($users['summary']['w_h_tax'], 2),
                        '',
                        ''
                    ]);
                });

            })->export('csv');
        }

        return false;
    }

    //========================================//
    //============= Data Return And Refund =============//
    //========================================//

    public function getDataReturnAndRefund(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orderrefund?' . $getUrl);

        // dd($result);
        $output = [
            'draw' => $params['draw'],
            'recordsTotal' => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data' => isset($result['data']['records']) ? $this->setDataTableReturnAndRefund($result['data']['records'], $params) : [],
            'input' => $params
        ];
        return json_encode($output);
    }

    /**
     * set data return and refund
     */
    public function setDataTableReturnAndRefund($data, array $params)
    {
        $dataTable = [];

        if (count($data) > 0) {
            foreach ($data as $kData => $vData) {

                // $vData['refund_amount'] = SatungRounding::calculate($vData['order_line_unit_price'], $vData['order_line_tax_percentage'][0], $vData['refund_qty']);
                
                // if(isset($vData['order_line_charge_per_unit'][0])) {
                //     $satangRounding = SatungRounding::calculate($vData['order_line_charge_per_unit'][0], $vData['order_line_tax_percentage'][0], $vData['refund_qty']);
                //     $vData['refund_amount'] = $vData['refund_amount'] - $satangRounding;
                // }

                $nData = ($kData + 1) + $params['start'];
                $dataTable[] = [
                    'number'                => $nData,
                    'order_no'              => $vData['order_no'],
                    'store_no'              => $vData['store_id'],
                    'store_name_th'         => $vData['store_name']['th'],
                    'item_id'               => $vData['item_id'],
                    'datetime'              => date("d/m/Y H:i:s",strtotime($vData['datetime'])),
                    'payment_id'            => $vData['payment_id'],
                    'quantity'              => isset($vData['quantity'])? number_format($vData['quantity']) : '',
                    'amount'                => isset($vData['amount'])? number_format((float)$vData['amount'], 2) : '',
                ];
            }
        }

        return $dataTable;
    }

    public function getDataReturnAndRefundReport(array $params)
    {
        $getUrl = $this->setSearch($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orderrefund?' . $getUrl);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {

            $users = $result['data'];

            return Excel::create('return_and_refund_report_' . date('YmdHis'), function ($excel) use ($users) {

                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";

                $excel->sheet('Return and Refund', function ($sheet) use ($users) {

                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:K1');
                    $sheet->row(1, [
                        'No.',
                        'Order No.',
                        'Store No.',
                        'Store Name',
                        'Item ID',
                        'Date',
                        'Ref Id',
                        'Return/Refund Qty',
                        'Amount'
                    ]);
                    $sheet->row(1, function ($row) {
                        $row->setBackground('#dddddd');
                    });

                    $start = (int)$users['pagination']['offset'];
                    $row = 1;
                    foreach ($users['records'] as $kData => $vData) {
                        ++$row;

                        // $vData['refund_amount'] = SatungRounding::calculate($vData['order_line_unit_price'], $vData['order_line_tax_percentage'][0], $vData['refund_qty']);
                        
                        // if(isset($vData['order_line_charge_per_unit'][0])) {            
                        //     $satangRounding = SatungRounding::calculate($vData['order_line_charge_per_unit'][0], $vData['order_line_tax_percentage'][0], $vData['refund_qty']);
                        //     $vData['refund_amount'] = $vData['refund_amount'] - $satangRounding;
                        // }
                        // $users['summary']['total_refund_amount'] = $users['summary']['total_refund_amount'] + $vData['refund_amount'];
                        $data = [
                            $kData + $start + 1,
                            $this->_unit->removeFirstInjection($vData['order_no']),
                            $this->_unit->removeFirstInjection($vData['store_id']),
                            $this->_unit->removeFirstInjection($vData['store_name']['th']),
                            $this->_unit->removeFirstInjection($vData['item_id']),
                            (!empty($vData['datetime'])) ? '="'.date("d/m/Y H:i:s",strtotime($vData['datetime'])).'"' : '',
                            $this->_unit->removeFirstInjection($vData['payment_id']),
                            $this->_unit->removeFirstInjection($vData['quantity']),
                            isset($vData['amount'])? number_format((float)$vData['amount'], 2) : '',
                        ];

                        $sheet->row($row, $data);
                    }

                    $sheet->row($row + 1, [
                        'Total',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        number_format((float)$users['summary']['amount'], 2),
                    ]);
                });

            })->export('csv');
        }

        return false;
    }

    public function setSortSearchOrderPrint($params) 
    {
        $condition = [];
        $store_search = [];
        $start_date = [];
        $end_date = [];
        $sort = [];
        $invoice_no = [];
        $running_number = [];

        for($i=0; $i<count($params['search']); $i++) {
            $param = $params['search'][$i];
            $name = $param['name'];
            $value = $param['value'];
            switch($name){
                case 'store_id': $store_search = ['store_id' => $value]; break;
                case 'invoice_no':
                    if(!empty($value))
                        $invoice_no = ['invoice_no' => $value];
                    break;
                case 'reprint_date_start':
                    if(!empty($value))
                        $start_date = ['start_date' => convertDateTime($value, 'd/m/Y', 'Y-m-d')." 00:00:00"];
                    break;
                case 'reprint_date_end':
                    if(!empty($value))
                        $end_date = ['end_date' => convertDateTime($value, 'd/m/Y', 'Y-m-d')." 23:59:59"];
                    break;
                case 'running_number':
                    if(!empty($value))
                        $running_number = ['running_number' => $value];
            }
        }
 
        for($i=0; $i<count($params['order']); $i++) {
            if($params['order'][$i]['dir'] != 'false'){
                $sort = [
                    'column' => $params['order'][$i]['column'],
                    'dir' => $params['order'][$i]['dir']
                ];
            }
        }

        if(isset($params['length'])) {
            $limit = ['limit' => $params['length']];
        }

        if(isset($params['start'])) {
            $offset = ['offset' => $params['start']];
        }

        $condition = array_merge($store_search, $invoice_no, $start_date, $end_date , $running_number, $sort, $limit, $offset);

        return [
            'query' => $condition
            
        ];
    }

    public function getDataOrderPrint(array $params)
    {
        $options = $this->setSortSearchOrderPrint($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders/print' , $options);

        $output = [
            'draw' => isset($params['draw']) ? $params['draw'] : 0,
            'recordsTotal' => isset($result['data']['records']) ? count($result['data']['records']) : 0, //count page
            'recordsFiltered' => isset($result['data']['pagination']['total_records']) ? $result['data']['pagination']['total_records'] : 0, //count all
            'data' => isset($result['data']['records']) ? $this->setDataTableOrderPrint($result['data']['records']) : [],
            'input' => $params
        ];
        
        return json_encode($output);
    }

    public function setDataTableOrderPrint($data) {
        $dataTable = [];
        foreach($data as $val) {

            $dataTable[] = [
                'reprinted_date'        => date('d/m/Y', strtotime($val['created_at'])),
                'reprint'               => isset($val['running_number'])? $val['running_number'] : '',
                'issue_date'            => isset($val['issue_date'])? date("d/m/Y",strtotime($val['issue_date'])):'',
                'settlement_date'       => isset($val['settlement_date'])? date("d/m/Y",strtotime($val['settlement_date'])):'',
                'invoice_no'            => isset($val['invoice_no'])? $val['invoice_no'] : '',
                'name_company'          => isset($val['tax_info']['shop_name'])? $val['tax_info']['shop_name'] : '',
                'tax_id'                => isset($val['tax_info']['tax_id'])? $val['tax_info']['tax_id'] : '',
                'branch_no'             => isset($val['tax_info']['branch_id'])? $val['tax_info']['branch_id'] : '',
                'invoice_exvat_amount'  => isset($val['subtotal']) ? number_format((float)$val['subtotal'],2) : "",
                'tax'                   => isset($val['vat']) ? number_format((float)$val['vat'],2) : "",
                'invoice_invat_amount'  => isset($val['net_amount']) ? number_format((float)$val['net_amount'],2) : ""
            ];
        }

        return $dataTable;
    }
    public function getDataCouponReport($params)
    {
        $url = $this->urlCoupon . 'coupons/report';
        $result = $this->guzzle->curl('GET', $url,$params);
        return $result;
    }

    public function couponReportData($inputs)
    {
        $started_date = '';
        if(!empty($inputs['start_date'])||!empty($inputs['end_date']))
        {
            $started_date = '['.convertDateTime($inputs['start_date'],'d/m/Y H:i:s','Y-m-d H:i:s').','.convertDateTime($inputs['end_date'],'d/m/Y H:i:s','Y-m-d H:i:s').']';
        }
        $params = [
            'query' => [
                   'coupon_code'    => $inputs['coupon_code'],
                   'ref_code'       => $inputs['ref_code'],
                   'store_id'       => $inputs['store_id'],
                   'started_date'   => $started_date,
                   'limit'          => $inputs['length'],
                   'offset'         => $inputs['start'],  
            ]
        ];
        if($inputs['order'][0]['dir'] != 'false') {
            if(preg_match('/coupon_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
                $language = substr($inputs['order'][0]['column'], -2);
                $fieldName = "coupon_name.$language";
            } else {
                $fieldName = $inputs['order'][0]['column'];
            }   
            $direction = $inputs['order'][0]['dir'];
            $params['query']['order']= "$fieldName|$direction";
        }
        $result = $this->getDataCouponReport($params);
        $dataTable = [];
        $count_page = 0;
        $count_all = 0;
       
        if (isset($result['data']['records']) && !empty($result['data']['records'])) {
            $dataTable = $this->setDataTableCouponReport($result['data']['records'], $inputs);
            $count_page = count($result['data']['records']); //count page
            $count_all = $result['data']['pagination']['total_records']; //count all
        }

        $output = [
            'draw'            => $inputs['draw'],
            'recordsTotal'    => $count_page, //count page
            'recordsFiltered' => $count_all, //count all
            'data'            => $dataTable,
            'input'           => $inputs
        ];
        
        return json_encode($output);
    }

    public function setDataTableCouponReport($data,$params)
    {
        $dataTable = [];
        $language = App::getLocale();
        
        foreach ($data as $kData => $vData) {
            $numberData = ($kData + 1) + $params['start'];
            $coupon_type_name = $this->coupon_type[ $vData['coupon_type'] ];
            $dataTable[] = [
                    'store_id'                      => $vData['store_id'],
                    'coupon_code'                   => $vData['coupon_code'],
                    'ref_code'                      => isset($vData['ref_code'])? $vData['ref_code'] : '',
                    'coupon_name_th'                => $vData['coupon_name']['th'],
                    'coupon_name_en'                => $vData['coupon_name']['en'],
                    'division'                      => isset($vData['division'])? $vData['division'] : '',
                    'total_discount'                => number_format((float)$vData['total_discount'], 2, '.', ','),        
                    'usage_count'                   => $vData['usage_count']
            ];
        }

        return $dataTable;
    }
    // Export Coupon Report

	public function getCouponReportDataReport($inputs) 
    {
        $started_date = '';
        if(!empty($inputs['start_date'])||!empty($inputs['end_date']))
        {
            $started_date = '['.convertDateTime($inputs['start_date'],'d/m/Y H:i:s','Y-m-d H:i:s').','.convertDateTime($inputs['end_date'],'d/m/Y H:i:s','Y-m-d H:i:s').']';
        }
        $params = [
            'query' => [
                   'coupon_code' => $inputs['coupon_code'],
                   'ref_code'   => $inputs['ref_code'],
                   'store_id'   => $inputs['store_id'],
                   'started_date'   => $started_date,
                   'limit'  => $inputs['length'],
                   'offset' => $inputs['start'],  
            ]
        ];

        if($inputs['order'][0]['dir'] != 'false') {
            if(preg_match('/coupon_name_[a-z]{2}/', $inputs['order'][0]['column'])) {
                $language = substr($inputs['order'][0]['column'], -2);
                $fieldName = "coupon_name.$language";
            } else {
                $fieldName = $inputs['order'][0]['column'];
            }   
            $direction = $inputs['order'][0]['dir'];
            $params['query']['order']= "$fieldName|$direction";
        }
        $result = $this->getDataCouponReport($params);

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $start = $result['data']['pagination']['offset'] + 1;
            $coupon_report = $result['data']['records'];
            return Excel::create('coupon_report_' . date('YmdHis'), function($excel) use ($coupon_report,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Coupon Report', function($sheet) use ($coupon_report,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->mergeCells('A1:I1');
                    $sheet->row(1, [
                        'รายงานการใช้คูปอง (Voucher)',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ]);
 
                    $sheet->row(2, [
                        'วันที่',
                        date('d/m/Y'),
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ]);

                    $sheet->row(4, [
                        'No.',
                        'Store No.',
                        'Ref. Code',
                        'Coupon Code',
                        'Coupon Name (TH)',
                        'Coupon Name (EN)',
                        'Division',
                        'จำนวนใบที่ใช้',
						'จำนวนเงิน',
                
                    ]);

                    $sheet->row(4, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 4;
                    $total_discount = 0;
                    $usage_count = 0;
                    foreach ($coupon_report as $kData => $vData) {
                        ++$row;
                        
                        $data = [
                            $start,
                            isset($vData['store_id'])? $this->_unit->removeFirstInjection($vData['store_id']) : '',
                            isset($vData['ref_code'])? '="' .$vData['ref_code'].'"' : '',
                            isset($vData['coupon_code'])? '="' .$vData['coupon_code'].'"' : '',
                            isset($vData['coupon_name']['th'])?  $this->_unit->removeFirstInjection($vData['coupon_name']['th']) : '',
                            isset($vData['coupon_name']['en'])? $this->_unit->removeFirstInjection($vData['coupon_name']['en']) : '',
                            isset($vData['division'])? $this->_unit->removeFirstInjection($vData['division']) : '',
                            isset($vData['usage_count'])? '="' .$vData['usage_count'].'"' : '',
                            isset($vData['total_discount'])?  '="' .number_format((float)$vData['total_discount'], 2, '.', ',').'"' : '',
                        ];
                        $usage_count = $usage_count + $vData['usage_count'];
                        $total_discount = $total_discount + $vData['total_discount'];
                        $start++;

                        $sheet->row($row, $data);
                    }
                    $sheet->row($row + 1, [
                        'Total',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $usage_count,
                        number_format((float)$total_discount,2),
                    ]);

                });
            })->export('csv');
        }
    }
    public function getDataUsageReport($params)
	{
        $url = $this->urlCoupon . 'coupons/usage';
        $result = $this->guzzle->curl('GET', $url, $params);
		return $result; 
    }

    public function getUsageReportData($inputs)
    {
        $used_date = '';
        if(!empty($inputs['start_date'])||!empty($inputs['end_date']))
        {
            $used_date = '['.convertDateTime($inputs['start_date'],'d/m/Y H:i:s','Y-m-d H:i:s').','.convertDateTime($inputs['end_date'],'d/m/Y H:i:s','Y-m-d H:i:s').']';
        }
        if($inputs['status']=='all') {
            $status = '';
        }else {
            $status = $inputs['status'];
        }
        $params = [
			'query' => [
				'fields'    => 'order_no',
				'search'    => $inputs['full_text'],
				'offset'    => $inputs['start'],
				'limit'     => $inputs['length'],
                'status'    => $status,
                'used_date' => $used_date,
                'coupon_id' => $inputs['coupon_code']
			]
		];
        if($inputs['order'][0]['dir'] != 'false') {
			$fieldName = $inputs['order'][0]['column'];
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
        } 

        $result = $this->getDataUsageReport($params);
            
		$dataTable = [];
		$count_page = 0;
		$count_all = 0;
		if (isset($result['status']) && !empty($result['status']) && $result['status']['code'] == 200) {
			$dataTable = $this->setDataTableUsage($result['data']['records'], $inputs);
			$count_page = count($result['data']['records']); //count page
			$count_all = $result['data']['pagination']['total_records']; //count all
		}

		$output = [
			'draw'            => $inputs['draw'],
			'recordsTotal'    => $count_page, //count page
			'recordsFiltered' => $count_all, //count all
			'data'            => $dataTable,
			'input'           => $inputs
		];
		
		return json_encode($output);
    }

    public function setDataTableUsage($data,$params)
    {
        $dataTable = [];
		$language = App::getLocale();
		foreach ($data as $kData => $vData) {
			$numberData = ($kData + 1) + $params['start'];
			$dataTable[] = [
                'number'            => $numberData,
                'coupon_code'       => isset($vData['coupon_code'])? $vData['coupon_code'] : '',
				'used_date'         => isset($vData['used_date'])? date("d/m/Y H:i:s",strtotime($vData["used_date"])) : '',
				'order_no'          => isset($vData['order_no'])? $vData['order_no'] : '',
				'order_amount'      => isset($vData['order_amount'])? number_format((float)$vData['order_amount'], 2, '.', ',') : '',
				'makro_member_card' => isset($vData['makro_member_card'])? $vData['makro_member_card'] : '',
				'first_name'        => isset($vData['customer_firstname'])? $vData['customer_firstname'] : '',
				'last_name'         => isset($vData['customer_lastname'])? $vData['customer_lastname'] : '',
                'customer_type'     => isset($vData['customer_type'])? $vData['customer_type'] . ' (' . $vData['customer_type_id'] . ')' : '',
				'mobile_number'     => isset($vData['mobile_number'])? $vData['mobile_number'] : '',
                'email'             => isset($vData['email'])? $vData['email'] : '',
                'status'            => isset($vData['status'])? $vData['status'] : '',
			];
		}

		return $dataTable;
    }

        // Export Usage Report

	public function getReportUsageData($inputs) 
    {
		
        $used_date = '';
        if(!empty($inputs['start_date'])||!empty($inputs['end_date']))
        {
            $used_date = '['.convertDateTime($inputs['start_date'],'d/m/Y H:i:s','Y-m-d H:i:s').','.convertDateTime($inputs['end_date'],'d/m/Y H:i:s','Y-m-d H:i:s').']';
        }
        if($inputs['status']=='all') {
            $status = '';
        }else {
            $status = $inputs['status'];
        }
        $params = [
			'query' => [
				'fields'    => 'order_no',
				'search'    => $inputs['full_text'],
				'offset'    => $inputs['start'],
				'limit'     => $inputs['length'],
                'status'    => $status,
                'used_date' => $used_date,
                'coupon_id' => $inputs['coupon_code']	
			]
		];
        if($inputs['order'][0]['dir'] != 'false') {
			$fieldName = $inputs['order'][0]['column'];
			$direction = $inputs['order'][0]['dir'];
			$params['query']['order']= "$fieldName|$direction";
		}
		$result = $this->getDataUsageReport($params);
        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $start = $result['data']['pagination']['offset'] + 1;
            $usage = $result['data']['records'];
            return Excel::create('usage_report_' . date('YmdHis'), function($excel) use ($usage,$start) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";
                $excel->sheet('Usage Report', function($sheet) use ($usage,$start) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A1:L1');

                    $sheet->freezeFirstRow();
                    $sheet->row(1, [
                        'No.',
                        'Coupon Code',
                        'Used Date',
                        'Order Number',
                        'Order Amount',
                        'Makro ID',
						'First Name',
                        'Last Name',
						'Customer Type',
                        'Mobile Number',
                        'Email',
                        'Status'
                
                    ]);

                    $sheet->row(1, function($row) {
                        $row->setBackground('#dddddd');
                    });

                    $row = 1;

                    foreach ($usage as $kData => $vData) {
                        ++$row;

                        $data = [
                            $start,
                            isset($vData['coupon_code'])? '="'.$vData['coupon_code'].'"' : '',
                            isset($vData['used_date'])? date("d/m/Y H:i:s",strtotime($vData["used_date"])) : '',
                            isset($vData['order_no'])? '="'.$vData['order_no'].'"' : '',
                            isset($vData['order_amount'])? number_format((float)$vData['order_amount'], 2, '.', ',') : '',
                            isset($vData['makro_member_card'])? '="'.$vData['makro_member_card'].'"' : '',
                            isset($vData['customer_firstname'])? $this->_unit->removeFirstInjection($vData['customer_firstname']):'',
                            isset($vData['customer_lastname'])? $this->_unit->removeFirstInjection($vData['customer_lastname']):'',
                            isset($vData['customer_type'])? $vData['customer_type'] . ' (' . $vData['customer_type_id'] . ')' : '',
                            isset($vData['mobile_number'])? sprintf('="%s"', array_get($vData, 'mobile_number', '')) : '',
                            isset($vData['email'])? $this->_unit->removeFirstInjection($vData['email']) : '',
                            isset($vData['status'])? $this->_unit->removeFirstInjection($vData['status']) : '',
                        ];

                        $start++;

                        $sheet->row($row, $data);
                    }

                });
            })->export('csv');
        }
	}
    /**
    * curl api order report
    */
    private function getStoreName($store_id){

        $store_info = ""; 
        if($store_id) {
                $store_params = [
                    'limit' =>  1,
                    'offset' => 0,
                    'order' => 'name|ASC',
                    'makro_store_id' => $store_id
                ];
        
                $store_result = $this->curlStores($store_params);
                if(isset($store_result['data']['records'][0])) {
                    $id_len = strlen($store_result['data']['records'][0]['makro_store_id']);
                    switch ($id_len) {
                        case 1:
                            $store_info = "00".$store_result['data']['records'][0]['makro_store_id']." ".$store_result['data']['records'][0]['name']['th'];
                            break;
                        case 2:
                            $store_info = "0".$store_result['data']['records'][0]['makro_store_id']." ".$store_result['data']['records'][0]['name']['th'];
                            break;
                        default:
                            $store_info = $store_result['data']['records'][0]['makro_store_id']." ".$store_result['data']['records'][0]['name']['th'];
                    }
                }
        }

        return $store_info;
    }
    public function getDataOrderPrintReport(array $params, $first_print = false)
    {
        $search_params = [];
        $options = $this->setSortSearchOrderPrint($params);
        $result = $this->guzzle->curl('GET', $this->urlOrder . 'orders/print' , $options);
    
        $store_info = "";      
        $reprint_date_start=isset($params['search'][2]['value'])?$params['search'][2]['value']:"";
        $reprint_date_end  =isset($params['search'][3]['value'])?$params['search'][3]['value']:"";

        foreach($params['search'] as $eachParam){
            $search_params[$eachParam['name']] = $eachParam['value'];
        }
        if(isset($search_params['store_id'])){
            $store_info = $this->getStoreName($search_params['store_id']);
        }   
        

        if (isset($result['data']['records']) && count($result['data']['records']) > 0) {
            $users = $result['data'];

            $fileName = ($first_print) ? 'print' : 'reprint';

            return Excel::create($fileName . '_log_report_' . date('YmdHis'), function ($excel) use ($users, $options, $store_info, $reprint_date_start, $reprint_date_end, $first_print) {
                header("Content-type: application/vnd.ms-excel; charset=UTF-8");
                echo "\xEF\xBB\xBF";


                $headers = [
                    'No.',
                    ($first_print) ? 'Printed Date' : 'Reprinted Date',
                    'Reprint',
                    'Issued Date',
                    // 'Settlement Date',
                    'Invoice No.',
                    'Name/Company',
                    'Tax ID',
                    'Branch No.',
                    'Invoice exvat amount',
                    'Tax',
                    'Invoice invat amount'
                ];

                if($first_print){
                    unset($headers[2]);
                }

                $excel->sheet('Order', function ($sheet) use ($users, $options, $store_info, 
                    $reprint_date_start, $reprint_date_end, $first_print, $headers) {
                    $sheet->setOrientation('landscape');
                    $sheet->setAutoFilter('A4:K4');

                    $sheet->freezeFirstRow();
                    $sheet->row(4, $headers);
                    $sheet->row(4, function ($row) {
                        $row->setBackground('#dddddd');
                    });
                    $start = (int)$users['pagination']['offset'];
                    $row = 4;
                    $print_text = ($first_print) ? 'Print' : 'Reprint';
                    foreach ($users['records'] as $kData => $vData) {
                        ++$row;

                        $subtotal =(isset($vData['subtotal']))? number_format((float)$vData['subtotal'], 2): "";
                        $vat =(isset($vData['vat']))? number_format((float)$vData['vat'], 2): "";
                        $net_amount =(isset($vData['net_amount']))? number_format((float)$vData['net_amount'], 2): "";

                        $data = [
                            $kData + $start + 1,
                            (isset($vData['created_at']))? convertDateTime($vData['created_at'], 'Y-m-d H:i:s', 'd/m/Y') : "",
                            array_get($vData, 'running_number', ''),
                            // isset($vData['issue_date'])? date("d/m/Y",strtotime($vData['issue_date'])):'',
                            isset($vData['settlement_date'])? date("d/m/Y",strtotime($vData['settlement_date'])):'',
                            '="' . array_get($vData, 'invoice_no', '') . '"',
                            $vData['tax_info']['shop_name'],
                            '="' .$vData['tax_info']['tax_id'].'"',
                            '="' .$vData['tax_info']['branch_id'].'"',
                            '="' .$subtotal.'"',
                            '="' .$vat.'"',
                            '="' .$net_amount.'"'

                        ];

                        if($first_print){
                            unset($data[2]);
                        }
                        $sheet->row($row, $data);
                    }
                    $sheet->row(1, [
                        $print_text . ' log report : ',
                        $store_info,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ]);



                    $sheet->row(2, [
                        'Date : ',
                        $reprint_date_start.' - '.$reprint_date_end,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                    ]);
                });
            })->export('csv');
            return false;
        }
    }

    public function setFormatData($params){

        $vData = '';
        if(!empty($params)){

            if( !empty($params['payment_type']) && $params['payment_type'] == "CC"){

                $params['payment_type'] = "Credit Card";

            } else if( !empty($params['payment_type']) && $params['payment_type'] == "PayAtStore"){

                $params['payment_type'] = "Pay@Store";
            } 
        
            if ( !empty($params['delivery_method']) && $params['delivery_method']  == "SHP" ) {

                $params['delivery_method'] = "Delivery";

            } else if ( !empty($params['delivery_method']) && $params['delivery_method']  == "PICK" ) {

                $params['delivery_method'] = "Pickup ";
            }
           
            $vData = $params;
        }

        return $vData ;
    }
}