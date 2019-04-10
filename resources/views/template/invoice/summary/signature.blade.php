@if($invoice['invoice_type'] == 'credit-note')
<div class="signature">
    <div class="sign-sender fl full-height border-right">
        <div class="height-80 text-center">ผู้รับคืนสินค้า / Receiver</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-receiver fl full-height border-right">
        <div class="height-80 text-center">ผู้ส่งสินค้า / Sender</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-checker fl full-height border-right">
        <div class="height-80 text-center">ผู้ตรวจสอบ / Checked By</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-authorizer fl full-height">
        <div class="height-80 text-center">ผู้ได้รับมอบอำนาจ / Authorized Signature</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
</div> 
@elseif($invoice['invoice_type'] == 'deposit')
<div class="signature">
    <!-- <div class="original-sign-sender fl full-height border-right">
        <div class="height-80 text-center">ผู้ส่งสินค้า / Sender</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="original-sign-receiver fl full-height border-right">
        <div class="height-80 text-center">ผู้รับสินค้า / Receiver</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div> 
    <div class="sign-collector fl full-height border-right">
        <div class="height-80 text-center">ผู้รับเงิน / Collector</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-checker fl full-height border-right">
        <div class="height-80 text-center">ผู้ตรวจสอบ / Checked By</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-authorizer fl full-height">
        <div class="height-80 text-center">ผู้ได้รับมอบอำนาจ / Authorized Signature</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div> -->
    <div class="invoice-info-top">
        <div class="text-center text-footer">
            ในนามบริษัท สยามแม็คโคร จำกัด (มหาชน)
        </div>
    </div>
    <div class="invoice-info-top">
        <div class="col-xs-6 text-footer text-right nopadding full-height">ผู้ได้รับมอบอำนาจ/Authorized Signature</div>
        <div class="col-xs-3">
            <div class="border-bottom nopadding" style="">
                <img class="signature-img" src="{{asset(config('invoice.signature_path'))}}">
            </div>
        </div>
        <div class="col-xs-3"></div>
    </div>
</div>
@else <!-- normal -->
<div class="signature">
    <div class="sign-sender fl full-height border-right">
        <div class="height-80 text-center">ผู้ส่งสินค้า / Sender</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-receiver fl full-height border-right">
        <div class="height-80 text-center">ผู้รับสินค้า / Receiver</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-checker fl full-height border-right">
        <div class="height-80 text-center">ผู้ตรวจสอบ / Checked By</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
    <div class="sign-authorizer fl full-height">
        <div class="height-80 text-center">ผู้ได้รับมอบอำนาจ / Authorized Signature</div>
        <div class="height-20 pd-l-1">วันที่/ DATE</div>
    </div>
</div>
@endif