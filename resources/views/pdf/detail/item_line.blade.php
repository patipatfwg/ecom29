@if(isset($data['type']) && $data['type'] == 'item-line-header')
    <tr>
        <td height="15" class="item_detail border-right" colspan="2">{{ isset($data['text'])? $data['text'] : ''}}</td>
        <td height="15" class="item_detail border-right" colspan="2">&nbsp;&nbsp;&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail">&nbsp;</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'item-line')
    <tr>
        <td height="15" class="item_detail border-right text-center">{{ isset($data['no'])? $data['no'] : ''}}</td>
        <td height="15" class="item_detail border-right text-center">{{ isset($data['item_id'])? $data['item_id'] : ''}}</td>
        <td height="15" class="item_detail border-right" colspan="2">{{ isset($data['description'])? $data['description'] : ''}}</td>
        <td height="15" class="item_detail border-right text-center">{{ isset($data['quantity'])? $data['quantity'] : ''}}</td>
        <td height="15" class="item_detail border-right text-center">{{ isset($data['unit'])? $data['unit'] : ''}}</td>
        <td height="15" class="item_detail border-right text-right" >{{ isset($data['unit_price'])? $data['unit_price'] : ''}}</td>
        <td height="15" class="item_detail border-right text-center">{{ isset($data['vat_code'])? $data['vat_code'] : ''}}</td>
        <td height="15" class="item_detail text-right">{{ isset($data['total_amount'])? $data['total_amount'] : ''}}</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'discount-header')
    <tr>
        <td height="15" class="item_detail border-right" colspan="2">เงื่อนไขส่วนลด</td>
        <td height="15" class="item_detail border-right" colspan="2">&nbsp;&nbsp;&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right text-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-right">&nbsp;</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'discount-line')
    <tr>
        <td height="15" class="item_detail border-right text-center">{{ isset($data['no'])? $data['no'] : ''}}</td>
        <td height="15" class="item_detail border-right" colspan="3">{{ isset($data['discount_description'])? $data['discount_description'] : ''}}</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['discount_amount'])? $data['discount_amount'] : ''}}</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-right">&nbsp;</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'discount-summary')
    <tr>
        <td height="15" class="item_detail border-right text-center">&nbsp;</td>
        <td height="15" class="item_detail border-right">รวมส่วนลด</td>
        <td height="15" class="item_detail border-right" colspan="2">&nbsp;&nbsp;&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['discount_total_amount'])? $data['discount_total_amount'] : ''}}</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-right">&nbsp;</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'item-groups-header')
    <tr>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right text-center color-blue">จำนวนชิ้น</td>
        <td height="15" class="item_detail text-center color-blue" width="15%">รหัส ภ.พ.</td>
        <td height="15" class="item_detail border-right text-center color-blue">ราคาสินค้า</td>
        <td height="15" class="item_detail border-right text-center color-blue">ภาษี</td>
        <td height="15" class="item_detail border-right text-center color-blue">รวม</td>
        <td height="15" class="item_detail border-right"></td>
        <td height="15" class="item_detail border-right"></td>
        <td height="15" class="item_detail text-right">&nbsp;</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'item-groups-line')
    <tr>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right text-center">{{ isset($data['quantity'])? $data['quantity'] : ''}}</td>
        <td height="15" class="item_detail text-center">{{ isset($data['vat_code'])? $data['vat_code'] : ''}}</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['unit_price'])? $data['unit_price'] : ''}}</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['vat_amount'])? $data['vat_amount'] : ''}}</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['total_amount'])? $data['total_amount'] : ''}}</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-right">&nbsp;</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'item-groups-summary')
    <tr>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-center">รวม</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['unit_price'])? $data['unit_price'] : ''}}</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['vat_amount'])? $data['vat_amount'] : ''}}</td>
        <td height="15" class="item_detail border-right text-right">{{ isset($data['total_amount'])? $data['total_amount'] : ''}}</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-right">&nbsp;</td>
    </tr>
@elseif(isset($data['type']) && $data['type'] == 'next-page')
    <tr>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-right">{{ isset($data['text_next_page'])? $data['text_next_page'] : ''}}</td>
    </tr>
@else

    <tr>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right" colspan="2">&nbsp;&nbsp;&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail border-right text-right">&nbsp;</td>
        <td height="15" class="item_detail border-right">&nbsp;</td>
        <td height="15" class="item_detail text-right">&nbsp;</td>
    </tr><!-- Line Empty -->
@endif