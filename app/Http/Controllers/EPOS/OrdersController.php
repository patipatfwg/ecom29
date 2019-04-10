<?php
namespace App\Http\Controllers\EPOS;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;

class OrdersController extends EPOSBaseController
{
    // for test use order_number=0000000670

    public function search(Request $request) {
        // get query string for search filters
        $orderNumber = $request->get('order_number', '');
        // default result
        $result = [];
        $pay_amount = 0;
        $productDataSet = ''; // for dataTable
        if ($orderNumber != '') {
            // get data via API

            $result = $this->getOrder($orderNumber);
//             dump($result);
            if ($result == '404') {
//                $request->session()->flash('messages', [
//                    'type' => 'error',
//                    'text' => 'Connection error!'
//                ]);
//                return back()->withInput();
                return back()->with('msg', 'Connection error!')->withInput();
            }
//            dump($result);
            if ($result) {
                if (!isset($result->shoppingAddress) || !isset($result->billingAddress) || !isset($result->orderPayment) || !isset($result->shoppingCart)) {
//                    $request->session()->flash('messages', [
//                        'type' => 'error',
//                        'text' => 'invalid order number!'
//                    ]);
//                    return back()->withInput();
//                    return back()->withErrors(['invalid order number'])->withInput();
                    return back()->with('msg', 'invalid order number')->withInput();
                }

                if (isset($result->shoppingCart)) {
                    foreach ($result->shoppingCart as $key => $value) {

//                        if ($invoices[0][$idx]->ItemID == $value->id) {

//                            $invoices[0][$idx]->ItemName = $value->name;

                            $itemtoSatang[$value->id] = [
                                'item_id'           => $value->id,
                                'price'             => $value->price,
                                'vat_rate'          => $value->vatpercent,
                                'quantity'          => $value->quantity
                            ];

//                        }
                    }
                }
//                dump($itemtoSatang);

                $items_price = $this->getSatangRounding($itemtoSatang);

                if ($items_price == '404') {
                    return redirect()->back()->with('msg', '[API] Satang Rounding error, please try again')->withInput();
                }

//                dump($items_price);
                foreach ($result->shoppingCart as $idx => $cart) {
//                    dump($cart->status);
//                    if ($cart->status != 'Cancelled') {
                    if (!($cart->status == 'Cancelled' || $cart->status == 'CANCEL_ORDER')) {
//                        foreach ($result->orderProducts as $key => $product) {
//                            if ($cart->id == $product->item_id) {
//                                $pay_amount += ($product->amount) - (isset($product->detail->price->total_discount) ? $product->detail->price->total_discount : 0);
//                            }
//                        }

                        foreach ($items_price as $key => $product) {
                            if ($cart->id == $product->item_id) {
//                                dump($product->total);
                                $pay_amount +=  ($product->total);
//                                dump('----------');
//                                dump($cart->id);
//                                dump('ITEM_PRICE');
//                                dump($product->total);
                                // minus simple discount
                                if (isset($cart->simple_discount)) {
                                    //if ($cart->origin_quantity != '') {
                                        //$csi = round(($cart->simple_discount) / ($cart->origin_quantity),2);
                                    //} else {
                                        //$csi = round(($cart->simple_discount) / ($cart->quantity),2);
                                    //}
                                    //$csi = round(($cart->simple_discount) / ($cart->origin_quantity),2);
                                    $csi = $cart->simple_discount;
                                    $cstoSatang = [];
                                    $cstoSatang[$cart->id] = [
                                        'item_id'           => $cart->id,
                                        'price'             => $csi * $cart->quantity,
                                        'vat_rate'          => $cart->vatpercent,
                                        'quantity'          => 1
                                    ];
//                                    dump($cstoSatang);
                                    $csi_price = $this->getSatangRounding($cstoSatang);
//                                    dump('SIMPLE');
//                                    dump($csi_price[0]->total);
                                    $pay_amount -= $csi_price[0]->total;
                                }

                                // minus complex discount
                                if (isset($cart->complex_discount)) {
                                    //if ($cart->origin_quantity != '') {
                                        //$cdi = round(($cart->complex_discount) / ($cart->origin_quantity),2);
                                    //} else {
                                        //$cdi = round(($cart->complex_discount) / ($cart->quantity),2);
                                        //$cdi = $cart->complex_discount;
                                    //}
                                    $cdi = $cart->complex_discount;
                                    $cdtoSatang = [];
                                    $cdtoSatang[$cart->id] = [
                                        'item_id'           => $cart->id,
                                        'price'             => $cdi * $cart->quantity,
                                        'vat_rate'          => $cart->vatpercent,
                                        'quantity'          => 1
                                    ];
                                    $cdi_price = $this->getSatangRounding($cdtoSatang);
//                                    dump('COMPLEX');
//                                    dump($cdi_price[0]->total);
                                    $pay_amount -= $cdi_price[0]->total;
                                }
                            }
                        }
                    }
                }
//                dump($pay_amount);
                $productDataSet = $this->_turnArrayProductToDataSet($result->shoppingCart);
            } else {

//                $request->session()->flash('messages', [
//                    'type' => 'error',
//                    'text' => 'invalid order number!'
//                ]);
//                return redirect('epos\order')->withErrors(['invalid order numberxxxx']);
                return redirect('epos/order')->with('msg', 'invalid order number');
            }
        }

        return view('epos.order.index', [
            'order_number' => $orderNumber,
            'search_result' => $result,
            'product_dataset' => $productDataSet,
            'pay_amount' => $pay_amount
        ]);
    }

    private function _turnArrayProductToDataSet($arr)
    {
        $result = [];
        foreach ($arr as $key => $product) {
            $result[$key][] = $product->id;
            $result[$key][] = $product->name;
            $result[$key][] = number_format($product->price,2);
            $result[$key][] = $product->quantity;
            $result[$key][] = $product->status;; // status
        }

        return $result;
    }

//    private function _getRecords($responseData) {
//
//        if ( !isset($responseData->data)
//            || !isset($responseData->data->records)
//            || count($responseData->data->records) < 1 ) {
//
//            return false;
//        }
//
//        return $responseData->data->records[0];
//    }
}