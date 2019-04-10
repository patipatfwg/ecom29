@if($invoice['invoice_type'] == 'credit-note')
<div class="invoice-line-break">
    <div class="col-xs-6 nopadding text-left tb-dispaly full-height">
        <div class="va-middle">
            อ้างถึง ใบกำกับภาษีเลขที่ <span class="black font-read">{{ isset($invoice['reference']['deposit_invoice_number'])? $invoice['reference']['deposit_invoice_number'] : '' }}</span>
        </div>
    </div>
    <div class="col-xs-6 nopadding full-height">
        <div class="col-xs-10 nopadding text-left tb-dispaly full-height">
            <div class="va-middle">
                 ลงวันที่ <span class="black font-read">{{ isset($invoice['reference']['deposit_invoice_date'])? $invoice['reference']['deposit_invoice_date'] : '' }}</span>
            </div>
        </div>
        <div class="col-xs-2 nopadding text-center  tb-dispaly full-height">
            <div class="va-middle" >(หน่วย:บาท)</div>
        </div>
    </div>
</div>
@elseif($invoice['invoice_type'] == 'normal' || $invoice['invoice_type'] == 'deposit')
<div class="invoice-line-break">
    <div class="col-xs-6 nopadding text-left tb-dispaly full-height">
        <div class="va-middle">
            
        </div>
    </div>
    <div class="col-xs-6 nopadding full-height">
        <div class="col-xs-10 nopadding text-left tb-dispaly full-height">
            <div class="va-middle">
                 
            </div>
        </div>
        <div class="col-xs-2 nopadding text-center  tb-dispaly full-height">
            <div class="va-middle" >(หน่วย:บาท)</div>
        </div>
    </div>
</div>
@else
<div class="invoice-line-break">
    <div class="col-xs-6 nopadding text-left  tb-dispaly full-height">
        <div class="va-middle" >
             อ้างถึง ใบแจ้งหนี้เลขที่
        </div>
    </div>
    <div class="col-xs-6 nopadding  tb-dispaly full-height">
        <div class="col-xs-10 nopadding text-left va-middle">
            
        </div>
        <div class="col-xs-2 nopadding text-center tb-dispaly full-height">
             <div class="va-middle" >
                 (หน่วย:บาท)
            </div>
        </div>
    </div>
</div>
@endif