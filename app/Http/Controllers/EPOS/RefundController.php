<?php
namespace App\Http\Controllers\EPOS;

use App\Http\Controllers\EPOS\Helper\NumberThai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Route;

class RefundController extends EPOSBaseController
{
    //http://makro-ecommerce-admin.dev:8054/epos/refund?start_date=2017-06-2&end_date=2017-06-24&status=
    public function search(Request $request) {
        // get query string for search filters
        $status = $request->get('status', '');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');

        // default result

        //dd($status);
        $refundsDataSet = '';
        $username = $request->session()->get('userName');
		$route = Route::getCurrentRoute()->getName();

        $permission = $this->permissionRepository->canList($username);

//        if ($status != '' || $startDate != '' || $endDate != '') {
        if ($startDate != '' || $endDate != '') {
            if ($status == 1) {
                $status = 'NotSettled';
            } elseif ($status == 2) {
                $status = 'InProgress';
            } elseif ($status == 3) {
                $status = 'Settled';
            } else {
                $status = '';
            }

            $credit_memos = $this->getRefund($startDate, $endDate, $status, 'CREDIT_MEMO');
            if ($credit_memos == '404') {
//                $request->session()->flash('messages', [
//                    'type' => 'error',
//                    'text' => 'Connection error!'
//                ]);
//                return redirect('/epos/refund')->withInput();
                return redirect('epos/refund')->with('msg', 'Connection error!');
            }

            $returns = $this->getRefund($startDate, $endDate, $status, 'RETURN');
            if ($returns == '404') {
//                $request->session()->flash('messages', [
//                    'type' => 'error',
//                    'text' => 'Connection error!'
//                ]);
//                return redirect('/epos/refund')->withInput();
                return redirect('epos/refund')->with('msg', 'Connection error!');
            }

            $refunds = false;
            if ($credit_memos && $returns) {
                $refunds = array_merge($credit_memos,$returns);
            } elseif  ($credit_memos && !$returns) {
                $refunds = $credit_memos;
            } else {
                $refunds = $returns;
            }

            if ($refunds) {

                $refunds = $this->array_sort($refunds, 'order', SORT_DESC);
                $refundsDataSet = $this->_turnArrayRefundToDataSet($refunds);
                if ($refunds[0]->invoiceNo === '') {
//                    $request->session()->flash('messages', [
//                        'type' => 'error',
//                        'text' => 'invalid Invoice Number!'
//                    ]);
//                    return redirect('/epos/refund')->withInput();
                    return redirect('epos/refund')->with('msg', 'invalid Invoice Number!');
                }
            }
        }

        return view('epos.refund.index', [
            'status'=> $status,
            'refund_dataset' => $refundsDataSet,
            'permission'    => $permission['data']['permissions']['EPOS']
        ]);
    }

    private function _turnArrayRefundToDataSet($arr)
    {
        $result = [];
        if (is_array($arr))
            $idx = 0;
            foreach ($arr as $key => $refundItem) {
                $result[$idx][] = $idx + 1;
                $result[$idx][] = $refundItem->invoiceNo;
                $result[$idx][] = $refundItem->createDate;
                $result[$idx][] = $refundItem->shipNode;
                $result[$idx][] = $refundItem->paymentType;
                $result[$idx][] = $refundItem->amount;
                $result[$idx][] = '';
                $result[$idx][] = $refundItem->modifyDate;
                $result[$idx][] = $refundItem->status;
                if ($refundItem->status == 'Settled') {
                    $result[$idx][] = '';
                } else {
                    $result[$idx][] = '<a class="btnUpdate" href="javascript:void(0)" onclick="update(\''.$refundItem->invoiceNo.'\', \''. $refundItem->status.'\');">Update</a>'; // status
                }
                //$result[$idx][] = $refundItem->order;
                $idx += 1;
            }
        return $result;
    }
 
     public function updateDetail(Request $updateRequest) {

        $invoiceNo  = $updateRequest->get('invoiceNo');
        $status  = $updateRequest->get('status');
        $invoiceType = $updateRequest->get('invoiceType');
        $paymentType = $updateRequest->get('paymentType');

        if ($invoiceNo != '') {
            if ($status == 2) {
                $status = 'InProgress';
            } elseif ($status == 3) {
                $status = 'Settled';
            }

            $settlementDate = $this->getSettlementDateTimeForOMS($paymentType, $invoiceType);


            $invoices = $this->updateRefund($invoiceNo, $status, $settlementDate);
 
            if ($invoices) {
                if($updateRequest->ajax()){
                    return Response::json([
                        'status'  => true,
                        'success' => $invoices
                    ]);
                } else {
                    // pass
                    $updateRequest->session()->flash('messages', [
                        'type' => 'success',
                        'text' => "Invoice #$invoiceNo updated"
                    ]);

                    return redirect('invoice/'.$invoiceNo.'/replace');
                }

            } else {
                if($updateRequest->ajax()){
                    return Response::json(['status' => false, 'messages' => 'Internal wrong, please try again']);
                } else {
                    return redirect()->back()->withErrors('Internal wrong, please try again')->withInput();
                }
            }
        }
    }

    public function export(Request $request)
    {
        // get query string for search filters
        $status = $request->get('status', '');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');
        $sortColumn = $request->get('sort_column', '2');
        $sortType = $request->get('sort_type', 'desc');

        // default result

        $refundsDataSet = '';

//        if ($status != '' || $startDate != '' || $endDate != '') {
        if ($startDate != '' || $endDate != '') {
            if ($status == 1) {
                $status = 'NotSettled';
            } elseif ($status == 2) {
                $status = 'InProgress';
            } elseif ($status == 3) {
                $status = 'Settled';
            } else {
                $status = '';
            }
            $credit_memos = $this->getRefund($startDate, $endDate, $status,'CREDIT_MEMO');
            if ($credit_memos == '404') {
//                $request->session()->flash('messages', [
//                    'type' => 'error',
//                    'text' => 'Connection error!'
//                ]);
//                return redirect('/epos/refund')->withInput();
                return redirect('epos/refund')->with('msg', 'Connection error!');
            }

            $returns = $this->getRefund($startDate, $endDate, $status,'RETURN');
            if ($returns == '404') {
//                $request->session()->flash('messages', [
//                    'type' => 'error',
//                    'text' => 'Connection error!'
//                ]);
//                return redirect('/epos/refund')->withInput();
                return redirect('epos/refund')->with('msg', 'Connection error!');
            }

            $refunds = false;
            if ($credit_memos && $returns) {
                $refunds = array_merge($credit_memos,$returns);
            } elseif  ($credit_memos && !$returns) {
                $refunds = $credit_memos;
            } else {
                $refunds = $returns;
            }

            if ($refunds) {
                $refunds = $this->array_sort($refunds, 'order', SORT_DESC);
                $refundsDataSet = $this->_turnArrayRefundToCSV($refunds);

                if ($refunds[0]->invoiceNo === '') {
//                    $request->session()->flash('messages', [
//                        'type' => 'error',
//                        'text' => 'invalid Invoice Number!'
//                    ]);
//                    return redirect('/epos/refund')->withInput();
                    return redirect('epos/refund')->with('msg', 'invalid Invoice Number!');
                }
            }

            if (is_array($refundsDataSet)) {
                $headers = [
                    'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
                    ,   'Content-type'        => 'text/csv'
                    ,   'Content-Disposition' => 'attachment; filename=refund.csv'
                    ,   'Expires'             => '0'
                    ,   'Pragma'              => 'public'
                ];

                $callback = function() use ($refundsDataSet)
                {
                    $FH = fopen('php://output', 'w');

                    fputcsv($FH, [
                        'No.', 'Credit Note Number', 'Created Date', 'Store', 'Payment Type',
                        'Refund Amount', 'Refund Reason', 'Modify Date', 'Status'
                    ]);

                    foreach ($refundsDataSet as $row) {
                        fputcsv($FH, $row);
                    }
                    fclose($FH);
                };

                return Response::stream($callback, 200, $headers);
            }

        }
    }

    private function _turnArrayRefundToCSV($arr)
    {
        $result = [];
        if (is_array($arr))
            $idx = 0;
            foreach ($arr as $key => $refundItem) {
                $result[$idx][] = $idx + 1;
                $result[$idx][] = '="'.$refundItem->invoiceNo.'"';
                $result[$idx][] = $refundItem->createDate;
                $result[$idx][] = $refundItem->shipNode;
                $result[$idx][] = $refundItem->paymentType;
                $result[$idx][] = $refundItem->amount;
                $result[$idx][] = '';
                $result[$idx][] = $refundItem->modifyDate;
                $result[$idx][] = $refundItem->status;
                $idx += 1;
            }

        return $result;
    }

}