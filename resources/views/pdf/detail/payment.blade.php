<tr>
    <td class="text-left border-right head-title border-bottom" colspan="3">ประเภทการชำระเงิน / Payment Type</td>
    <td class="text-center border-right border-bottom text-value">{{ isset($invoice['information']['payment_type']) ? $invoice['information']['payment_type'] : '' }}</td>
    <td class="text-center border-right border-bottom text-value" colspan="2">Coupon</td>
    <td class="border-right border-bottom">&nbsp;</td>
    <td class="border-right border-bottom">&nbsp;</td>
    <td class="text-center border-bottom text-value">ยอดเงินรวม</td>
</tr>
<tr>
    <td colspan="3" class="text-left border-right head-title border-bottom">จำนวนเงิน / Amount</td>
    <td class="text-right border-right border-bottom text-value">{{ isset($invoice['payment']['amount'])? $invoice['payment']['amount'] : '' }}</td>
    <td colspan="2" class="text-right border-right border-bottom text-value">{{ isset($invoice['payment']['coupon'])? $invoice['payment']['coupon'] : '' }}</td>
    <td class="border-right border-bottom text-value">&nbsp;</td>
    <td class="border-right border-bottom text-value">&nbsp;</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['payment']['total'])? $invoice['payment']['total'] : '' }}</td>
</tr>