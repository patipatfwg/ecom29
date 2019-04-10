@if($invoice['invoice_type'] == 'credit-note')
<tr>
    <td colspan="3" class="border-top head-title border-bottom">จำนวนเงินที่ต้องชำระ (ตัวอักษร)</td>
    <td colspan="3" class="border-top border-right border-bottom text-value">{{ isset($invoice['summary-credit-note']['total-text'])? $invoice['summary-credit-note']['total-text'] : '' }}</td>
    <td colspan="2" class="border-top border-right head-title border-bottom">&nbsp;</td>
    <td class="border-top text-right border-bottom">&nbsp;</td>
</tr>
@else
<tr>
    <td colspan="3" class="border-top head-title border-bottom">จำนวนเงินที่ต้องชำระ (ตัวอักษร)</td>
    <td colspan="3" class="border-top border-right border-bottom text-value">{{ isset($invoice['summary']['total-text'])? $invoice['summary']['total-text'] : '' }}</td>
    <td colspan="2" class="border-top border-right head-title border-bottom">ราคาสินค้ารวมภาษีมูลค่าเพิ่ม/ TOTAL</td>
    <td class="border-top text-right border-bottom">{{ isset($invoice['summary']['total'])? $invoice['summary']['total'] : '' }}</td>
</tr>
@endif