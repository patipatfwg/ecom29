<div class="line border-bottom">
    <div class="payment-type fl full-height border-right pd-l-1">ประเภทการชำระเงิน / Payment type</div>
    <div class="payment-chanel fl full-height border-right black">
        {{-- <div class="col-xs-4 nopadding full-height nopadding border-right text-center">Cash</div> --}}
        <div class="col-xs-8 nopadding full-height nopadding border-right text-center">
            {{ isset($invoice['information']['payment_type']) ? $invoice['information']['payment_type'] : '' }}
        </div>
        <div class="col-xs-4 nopadding full-height nopadding text-center">Coupon</div>
    </div>
    <div class="payment-unused fl full-height border-right">
        <div class="col-xs-6 nopadding full-height border-right"></div>
        <div class="col-xs-6 nopadding full-height"></div>
    </div>
    <div class="payment-total-amount fl full-height text-center black">ยอดเงินรวม</div>
</div>

<div class="line border-bottom">
    <div class="payment-type fl full-height border-right pd-l-1">จำนวนเงิน / Amount</div>
    <div class="payment-chanel fl full-height border-right black font-read">
        <div class="col-xs-8 nopadding full-height border-right text-right pd-r-1">
            {{ isset($invoice['payment']['amount'])? $invoice['payment']['amount'] : '' }}
        </div>
        <div class="col-xs-4 nopadding full-height text-right">
            {{ isset($invoice['payment']['coupon'])? $invoice['payment']['coupon'] : '' }}
        </div>
    </div>
    <div class="payment-unused fl full-height border-right">
        <div class="col-xs-6 nopadding full-height border-right"></div>
        <div class="col-xs-6 nopadding full-height"></div>
    </div>
    <div class="payment-total-amount fl full-height text-right black font-read pd-r-1">
        {{ isset($invoice['payment']['total'])? $invoice['payment']['total'] : '' }}
    </div>
</div>