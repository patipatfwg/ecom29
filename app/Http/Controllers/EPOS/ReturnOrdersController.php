<?php
namespace App\Http\Controllers\EPOS;

use App\Http\Controllers\EPOS\Models\OrderReturn;
use Illuminate\Http\Request;

class ReturnOrdersController extends EPOSBaseController
{
    public function search(Request $request) {
 
        // get query string for search filters
        $orderNumber = $request->get('order_number', '');
        $ordersDataSet = ''; // for dataTable
        if ($orderNumber != '') {
            $orderReturn = $this->getReturnOrder($orderNumber);
            if ($orderReturn == '404') {

                return redirect('epos/return_order')->with('msg', 'Connection error!');
            }
            if ($orderReturn) {
                $ordersDataSet = $this->_turnArrayOrderReturnToDataSet($orderReturn,$orderNumber);
                if ($orderReturn[0]->orderNo === '') {
 
                    return redirect('epos/return_order')->with('msg', 'invalid Sale Order!');
                }
            }
            else {
 
                return redirect('epos/return_order')->with('msg', 'invalid Sale Order!');
            }
        }

        return view('epos.return_order.index', [
            'order_number' => $orderNumber,
            'order_dataset' => $ordersDataSet
        ]);
    }

    private function _turnArrayOrderReturnToDataSet($arr , $orderNumber)
    {
        $result = [];
        foreach ($arr as $key => $returnOrder) {
            $result[$key][] = $returnOrder->orderNo;
            $result[$key][] = $returnOrder->date;
            $result[$key][] = $returnOrder->amount;
            $result[$key][] = $returnOrder->channel;
            $result[$key][] = '<a href="/epos/invoice?search_type=return_order_number&search_value='.$returnOrder->orderNo.'&order_no='.$orderNumber.'">Invoice</a>'; // manage
        }
        return $result;
    }
}
