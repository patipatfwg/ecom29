
<!-- include refund / return -->
@if($invoice['invoice_type'] == 'credit-note')
<tr>
    <td colspan="2" class="border-right">
        <div class="head-title">หมายเหตุ/ Remark</div>
    </td>
    <td colspan="4" class="border-right color-blue text-condition">เงื่อนไข/ Condition</td>
    <td colspan="2" class="border-right border-bottom head-title">จำนวนเงินรวม/ AMOUNT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary-credit-note']['diff-order-amount'])? $invoice['summary-credit-note']['diff-order-amount'] : '' }}</td>
</tr>
<tr>
    <td colspan="2" rowspan="5" class="border-right border-bottom text-vertical-top"><div class="text-value">{{ isset($invoice['remark'])? $invoice['remark'] : '' }}</div></td>
    <td colspan="4" class="border-right color-blue text-condition">1.เอกสารนี้จะสมบูรณ์เมื่อมีตราประทับบริษัทและลายเซ็นเจ้าหน้าที่ผู้ได้รับมอบอำนาจ</td>
    <td colspan="2" class="border-right border-bottom head-title">ภาษีมูลค่าเพิ่ม/ VAT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary-credit-note']['amount'])? $invoice['summary-credit-note']['vat'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">และบริษัทได้รับชำระหนี้หรือเรียกเก็บเงินครบถ้วนแล้ว</td>
    <td colspan="2" class="border-right border-bottom head-title">จำนวนเงินก่อนภาษีมูลค่าเพิ่ม/ SUBTOTAL</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary-credit-note']['vat'])? $invoice['summary-credit-note']['sub-total'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">2.โปรดเก็บเอกสารไว้เป็นหลักฐาน ในการติดต่อกับทางบริษัท</td>
    <td colspan="2" class="border-right border-bottom head-title">มูลค่าลดหนี้/ CREDIT AMOUNT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary-credit-note']['credit-amount'])? $invoice['summary-credit-note']['credit-amount'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">3.บริษัทจะรับคืนสินค้าภายใน 7 วัน ยกเว้นของสดรับคืนภายในวันที่รับสินค้า</td>
    <td colspan="2" class="border-right border-bottom head-title">&nbsp;</td>
    <td class="text-right border-bottom text-value">&nbsp;</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition border-bottom">และสินค้าที่รับคืนต้องอยู่ในสภาพเดิม</td>
    <td colspan="2" class="border-right border-bottom head-title border-bottom">&nbsp;</td>
    <td class="text-right border-bottom border-bottom text-value">&nbsp;</td>
</tr>
<!-- deposit -->
@elseif($invoice['invoice_type'] == 'deposit')
<tr>
    <td colspan="2" class="border-right">
        <div class="head-title">หมายเหตุ/ Remark</div>
    </td>
    <td colspan="4" class="border-right color-blue text-condition">เงื่อนไข/ Condition</td>
    <td colspan="2" class="border-right border-bottom head-title">หักส่วนลด/ DISCOUNT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['discount'])? $invoice['summary']['discount'] : '' }}</td>
</tr>
<tr>
    <td colspan="2" rowspan="5" class="border-right border-bottom text-vertical-top"><div class="text-value">{{ isset($invoice['remark'])? $invoice['remark'] : '' }}</div></td>
    <td colspan="4" class="border-right color-blue text-condition">1.ใบเสร็จรับเงินนี้จะสมบูรณ์เมื่อมีตราประทับบริษัทและลายเซ็นเจ้าหน้าที่ผู้ได้รับมอบอำนาจ</td>
    <td colspan="2" class="border-right border-bottom head-title">จำนวนเงินรวมสุทธิ/ AMOUNT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['amount'])? $invoice['summary']['amount'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">และบริษัทได้รับชำระหนี้หรือเรียกเก็บเงินครบถ้วนแล้ว</td>
    <td colspan="2" class="border-right border-bottom head-title">หักเงินมัดจำ/ DEPOSIT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['deposit'])? $invoice['summary']['deposit'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">2.บริษัทขอสงวนสิทธิ์ในกรณีที่เป็นบัตรเครดิตใบเสร็จจะสมบูรณ์เมื่อได้เรียกเก็บเงินจากธนาคารเรียบร้อยแล้ว</td>
    <td colspan="2" class="border-right border-bottom head-title">จำนวนเงินก่อนภาษีมูลค่าเพิ่ม/ SUBTOTAL</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['sub-total'])? $invoice['summary']['sub-total'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">3.โปรดเก็บเอกสารไว้เป็นหลักฐานในการติดต่อกับทางบริษัท</td>
    <td colspan="2" class="border-right border-bottom head-title">ภาษีมูลค่าเพิ่ม/ VAT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['vat'])? $invoice['summary']['vat'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition border-bottom">4.บริษัทจะรับคืนสินค้าภายใน 7 วัน ยกเว้นของสดรับคืนภายในวันที่ซื้อ และสินค้าที่รับคืนต้องอยู่ในสภาพเดิม</td>
    <td colspan="2" class="border-right border-bottom head-title border-bottom">จำนวนเงินที่ต้องชำระ/ NET AMOUNT</td>
    <td class="text-right border-bottom border-bottom text-value">{{ isset($invoice['summary']['net-amount'])? $invoice['summary']['net-amount'] : '' }}</td>
</tr>
<!-- normal -->
@else
<tr>
    <td colspan="2" class="border-right">
        <div class="head-title">หมายเหตุ/ Remark</div>
    </td>
    <td colspan="4" class="border-right color-blue text-condition">เงื่อนไข/ Condition</td>
    <td colspan="2" class="border-right border-bottom head-title">หักส่วนลด/ DISCOUNT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['discount'])? $invoice['summary']['discount'] : '' }}</td>
</tr>
<tr>
    <td colspan="2" rowspan="5" class="border-right border-bottom text-vertical-top"><div class="text-value">{{ isset($invoice['remark'])? $invoice['remark'] : '' }}</div></td>
    <td colspan="4" class="border-right color-blue text-condition">1.โปรดเก็บเอกสารไว้เป็นหลักฐาน ในการติดต่อกับทางบริษัท</td>
    <td colspan="2" class="border-right border-bottom head-title">จำนวนเงินรวมสุทธิ/ AMOUNT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['amount'])? $invoice['summary']['amount'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">2.บริษัทจะรับคืนสินค้าภายใน 7 วัน ยกเว้นของสดรับคืนภายในวันที่รับสินค้า</td>
    <td colspan="2" class="border-right border-bottom head-title">หักเงินมัดจำ/ DEPOSIT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['deposit'])? $invoice['summary']['deposit'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">และสินค้าที่รับคืนต้องอยู่ในสภาพเดิม</td>
    <td colspan="2" class="border-right border-bottom head-title">จำนวนเงินก่อนภาษีมูลค่าเพิ่ม/ SUBTOTAL</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['sub-total'])? $invoice['summary']['sub-total'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition">&nbsp;</td>
    <td colspan="2" class="border-right border-bottom head-title">ภาษีมูลค่าเพิ่ม/ VAT</td>
    <td class="text-right border-bottom text-value">{{ isset($invoice['summary']['vat'])? $invoice['summary']['vat'] : '' }}</td>
</tr>
<tr>
    <td colspan="4" class="border-right color-blue text-condition border-bottom"></td>
    <td colspan="2" class="border-right border-bottom head-title border-bottom">จำนวนเงินที่ต้องชำระ/ NET AMOUNT</td>
    <td class="text-right border-bottom border-bottom text-value">{{ isset($invoice['summary']['net-amount'])? $invoice['summary']['net-amount'] : '' }}</td>
</tr>
@endif