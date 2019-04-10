@if($invoice['invoice_type'] == 'credit-note')
<div class="line border-bottom">
    <div class="amount-text full-height fl border-right invoice-grey">
        <div class="col-xs-3 nopadding nowrap">จำนวนเงินที่ต้องชำระ</div>
        <div class="col-xs-9 nopadding text-center black font-read">{{ isset($invoice['summary-credit-note']['total-text'])? $invoice['summary-credit-note']['total-text'] : '' }}</div>
    </div>
    <div class="total-label full-height fl border-right invoice-grey pd-l-1">มูลค่าเดิม/ ORIGINAL AMOUNT</div>
    <div class="total-amount full-height fl text-right black black font-read pd-r-1">
         {{ isset($invoice['summary-credit-note']['original-order-amount'])? $invoice['summary-credit-note']['original-order-amount'] : '' }}
    </div>
</div>
@else
<div class="line border-bottom">
    <div class="amount-text full-height fl border-right invoice-grey">
        <div class="col-xs-3 nopadding nowrap">จำนวนเงินที่ต้องชำระ</div>
        <div class="col-xs-9 nopadding text-center black font-read">{{ isset($invoice['summary']['total-text'])? $invoice['summary']['total-text'] : '' }}</div>
    </div>
    <div class="total-label full-height fl border-right invoice-grey pd-l-1">ราคาสินค้ารวมภาษีมูลค่าเพิ่ม/ TOTAL</div>
    <div class="total-amount full-height fl text-right black font-read pd-r-1">{{ isset($invoice['summary']['total'])? $invoice['summary']['total'] : '' }}</div>
</div>
@endif