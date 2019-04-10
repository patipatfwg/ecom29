<?php
namespace App\Http\Controllers\EPOS;

use App\Http\Requests\PayStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class PayStoreController extends EPOSBaseController
{
    public $sub_payment_types = [
        'cash' => 'Cash',
        'cc'   => 'Credit Card'
    ];

    public function search(Request $request) {
        // get query string, if exist will auto fill to the form
        $orderNumber = $request->get('order_number', '');
        $pay_amount = $request->get('amount', '');
 
        return view('epos.paystore.index', [
            'order_number'      => $orderNumber,
            'pay_amount'        => $pay_amount,
            'sub_payment_types'  => $this->sub_payment_types
        ]);
    }

    private function _getRecords($responseData) {

        if ( !isset($responseData->data)
            || !isset($responseData->data->records)
            || count($responseData->data->records) < 1 ) {

            return false;
        }

        return $responseData->data->records[0];
    }

    public function save(PayStoreRequest $formRequest) {

        $orderNumber  = $formRequest->get('order_number');
        $depositInvoice  = $formRequest->get('deposit_invoice');
        $pay_amount  = $formRequest->get('pay_amount');
        $amount  = $formRequest->get('amount');
        $sub_payment_type  = $formRequest->get('sub_payment_type');  // payment_reference5
        $url = $this->api['makro_order_api'] . 'orders/' .$orderNumber;
        $res = $this->get($url);
        if ($res->getStatusCode() === 200) {
            $responseJson = json_decode($res->getBody()->getContents());
            if ($responseJson->status->code === 200 && $dataRecords = $this->_getRecords($responseJson)) {
                // update data
                $requestContent = ($this->getProp($dataRecords, 'request_content'));
                $requestContentProps = ($this->getProp($requestContent, 'props'));
                $requestContentChild = ($this->getProp($requestContent, 'children'));

                $orderDate = $this->getProp($requestContentProps, 'OrderDate');
                $enterpriseCode = $this->getProp($requestContentProps, 'EnterpriseCode');
                $documentType = $this->getProp($requestContentProps, 'DocumentType');
                $paymentStatus = $this->getProp($requestContentProps, 'PaymentStatus');
                //dd($paymentStatus);

                if ($requestContentChild != '' && count($requestContentChild) > 0) foreach($requestContentChild as $ch) {
                    if ($ch->name == 'PaymentMethods') {
                        $reqChildrenProps = isset($ch->children[0]->props) ? $ch->children[0]->props : null;

                        if(isset($reqChildrenProps) && !empty($reqChildrenProps)) {
                            $first_name = $this->getProp($reqChildrenProps, 'FirstName');
                            $last_name = $this->getProp($reqChildrenProps, 'LastName');
                            //$max_charge_limit = $this->getProp($reqChildrenProps, 'MaxChargeLimit');
                            $payment_type_group = $this->getProp($reqChildrenProps, 'PaymentTypeGroup');
                            $payment_type = $this->getProp($reqChildrenProps, 'PaymentType');
                            $payment_reference3 = $this->getProp($reqChildrenProps, 'PaymentReference3');
                            $payment_reference4 = $this->getProp($reqChildrenProps, 'PaymentReference4');
                            // create xml data for request to save
                            $xmlRequest = View::make('epos.xml.payment.update', [
                                'orderNumber' => $orderNumber,
                                'depositInvoice' => $depositInvoice,
                                'amount' => $amount,
                                'orderDate' => $orderDate,
                                'enterpriseCode' => $enterpriseCode,
                                'documentType' => $documentType,
                                'paymentStatus' => $paymentStatus,
                                'first_name' => $first_name,
                                'last_name' => $last_name,
                                'payment_type_group' => $payment_type_group,
                                'payment_type' => $payment_type,
                                'payment_reference3' => $payment_reference3,
                                'payment_reference4' => $payment_reference4,
                                'payment_reference5' => $sub_payment_type
                            ])->render();

                            //dd($xmlRequest);
                            //if ($max_charge_limit == $amount) {
                            //if ($amount >= ($max_charge_limit - 1) && ($amount <= ($max_charge_limit + 1))) {
                            if ($amount >= ($pay_amount - 1) && ($amount <= ($pay_amount + 1))) {
                                $url = $this->api['makro_epos_api'] . 'eai/order/paymentupdate';
                                //  dd($url);

                                try {
                                    $response = $this->post($url, $xmlRequest);
                                }
                                catch (\Exception $e)
                                {
                                    if($formRequest->ajax()){
                                        return Response::json(['status' => false, 'messages' => '[Paymentupdate] Connection error!']);
                                    } else {
                                        return redirect()->back()->withErrors('[Paymentupdate] Connection error!')->withInput();
                                    }

                                }

                                //dd($response->getStatusCode());
                                if ($response->getStatusCode() == 200) {
                                    $responseXml = simplexml_load_string($response->getBody()->getContents());

//                                    if(isset($responseXml->Error) && !empty($responseXml->Error)) {
                                    if(isset($responseXml->Error)) {
                                        $errorCode = (string)$responseXml->Error['ErrorCode'];
                                        $errorDesc = (string)$responseXml->Error['ErrorDescription'];
                                        if($formRequest->ajax()){
                                            return Response::json(['status' => false, 'messages' => $errorDesc]);
                                        } else {
                                            return redirect()->back()->withErrors($errorDesc)->withInput();
                                        }

                                    } else {
                                        $documentType = (string)$responseXml['DocumentType'];
                                        $enterpriseCode = (string)$responseXml['EnterpriseCode'];
                                        $orderHeaderKey = (string)$responseXml['OrderHeaderKey'];
                                        $orderNo = (string)$responseXml['OrderNo'];
                                        if($formRequest->ajax()){
                                            return Response::json(['status' => true]);
                                        } else {

                                            // pass
                                            $formRequest->session()->flash('messages', [
                                                'type' => 'success',
                                                'text' => "Order $orderNo updated"
                                            ]);

                                            return redirect('/epos/order/paystore');
                                        }
                                    }
                                }
                            } else {
                                if($formRequest->ajax()){
                                    return Response::json(['status' => false, 'messages' => 'Invalid Amount']);
                                } else {
                                    return redirect()->back()->withErrors('Invalid Amount')->withInput();
                                }
                            }
                        }
                    }
                }

            }
            if($formRequest->ajax()){
                return Response::json(['status' => false, 'messages' => 'Data Not Found']);
            } else {
                return redirect()->back()->withErrors('Data Not Found')->withInput();
            }
        } else {
            if($formRequest->ajax()){
                return Response::json(['status' => false, 'messages' => 'Internal wrong, please try again']);
            } else {
                return redirect()->back()->withErrors('Internal wrong, please try again')->withInput();
            }
        }
    }
}