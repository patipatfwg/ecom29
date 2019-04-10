<div class="invoice-info border-all">

    <div class="invoice-info-top border-bottom">

        <div class="invoice-info-left-side fl nopadding full-height border-right pd-l-1">

            <!-- Start: Customer Information -->
            <div class="row nopadding invoice-info-line">
                <div class="col-xs-5 nopadding full-height">
                    ชื่อลูกค้า/ Customer Name
                </div>
                <div class="col-xs-7 nopadding full-height black font-read wrap-text">
                    {{ isset($invoice['information']['customer_name'])? $invoice['information']['customer_name'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-line">
                <div class="col-xs-5 nopadding full-height">
                    รหัสสมาชิกลูกค้า/ Customer No.
                </div>
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['customer_no'])? $invoice['information']['customer_no'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-address2-line">
                <div class="col-xs-5 nopadding full-height">
                    ที่อยู่/ Address
                </div>
                <div class="col-xs-7 nopadding full-height black font-read nowrap">
                    {{ isset($invoice['information']['customer_address1'])? $invoice['information']['customer_address1'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-address2-line">
                <div class="col-xs-12 nopadding full-height black font-read nowrap">
                {{ isset($invoice['information']['customer_address2'])? $invoice['information']['customer_address2'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-line">
                <div class="col-xs-5 nopadding">
                    เลขประจำตัวผู้เสียภาษี/ Tax ID
                </div>
                <div class="col-xs-7 nopadding">
                    <div class="col-xs-6 nopadding full-height black font-read">
                        {{ isset($invoice['information']['customer_tax_id'])? $invoice['information']['customer_tax_id'] : '' }}
                    </div>
                    <div class="col-xs-6 nopadding full-height">
                        <div class="col-xs-6 nopadding full-height">
                            สาขา
                        </div>
                        <div class="col-xs-6 nopadding full-height black font-read">
                            {{ isset($invoice['information']['customer_branch'])? $invoice['information']['customer_branch'] : '' }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: Customer Information -->
        </div>

        <div class="invoice-info-right-side fl nopadding full-height pd-l-1">

            <!-- Start: Makro Store Information -->
            <div class="row nopadding invoice-info-line">
                @if($invoice['invoice_type'] == 'normal')
                <div class="col-xs-5 nopadding full-height">
                    สาขาที่ออกเอกสาร/ Branch
                </div>
                @else
                <div class="col-xs-5 nopadding full-height">
                    สาขาที่ออกใบกำกับภาษี/ Branch
                </div>
                @endif
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['receipt_branch'])? $invoice['information']['receipt_branch'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-address3-line">
                <div class="col-xs-5 nopadding full-height">
                    ที่อยู่/ Address
                </div>
                <div class="col-xs-7 nopadding full-height black font-read">
                    <div class="address">
                        {{ isset($invoice['information']['receipt_address'])? $invoice['information']['receipt_address'] : '' }}
                    </div>
                </div>
            </div>

            <div class="row nopadding invoice-info-line">
                <div class="col-xs-5 nopadding full-height">
                    <!-- POSD ID -->
                </div>
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{-- isset($invoice['information']['receipt_post_id'])? $invoice['information']['receipt_post_id'] : '' --}}
                </div>
            </div>
            <!-- End: Makro Store Information -->

        </div>
    </div>

    <div class="invoice-info-bottom ">
        <div class="invoice-info-left-side fl nopadding full-height border-right pd-l-1">

            <!-- Start: Receive Information -->
            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    สถานที่ส่งสินค้า/ Shipping address
                </div>
                <div class="col-xs-7 nopadding full-height">
                    {{ isset($invoice['information']['shipping_address'])? $invoice['information']['shipping_address'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-address-line">
                <div class="col-xs-5 nopadding full-height">
                    ที่อยู่/ Address
                </div>
                <div class="col-xs-7 nopadding full-height">
                    {{ isset($invoice['information']['address'])? $invoice['information']['address'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    ชื่อผู้รับสินค้า/ Receiver
                </div>
                <div class="col-xs-7 nopadding full-height">
                    {{ isset($invoice['information']['receiver_name'])? $invoice['information']['receiver_name'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    อีเมล/ E-mail
                </div>
                <div class="col-xs-7 nopadding full-height">
                    {{ isset($invoice['information']['receiver_email'])? $invoice['information']['receiver_email'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    เบอร์ติดต่อ/ TEL No.
                </div>
                <div class="col-xs-7 nopadding full-height">
                    {{ isset($invoice['information']['receiver_telephone'])? $invoice['information']['receiver_telephone'] : '' }}
                </div>
            </div>
            <!-- End: Receive Information -->

        </div>
        <div class="invoice-info-right-side fl nopadding full-height pd-l-1">

            <!-- Start: Tax Information -->
            <div class="row nopadding invoice-info-bottom-line">
                @if($invoice['invoice_type'] == 'normal')
                <div class="col-xs-5 nopadding full-height">
                    เลขที่/ No.
                </div>
                @else
                <div class="col-xs-5 nopadding full-height">
                    เลขที่ใบกำกับภาษี/ Tax Invoice No.
                </div>
                @endif
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['tax_invoice_no'])? $invoice['information']['tax_invoice_no'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-line">
                @if($invoice['invoice_type'] == 'normal')
                <div class="col-xs-5 nopadding full-height">
                    วันที่/ Date
                </div>
                @else
                <div class="col-xs-5 nopadding full-height">
                    วันที่ใบกำกับภาษี/ Tax Invoice Date
                </div>
                @endif
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['tax_invoice_date'])? $invoice['information']['tax_invoice_date'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    วันที่สั่งซื้อ/ Order Date
                </div>
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['order_date'])? $invoice['information']['order_date'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    เลขที่สั่งซื้อ/ Order No.
                </div>
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['order_no'])? $invoice['information']['order_no'] : '' }}
                </div>
            </div>

            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    วิธีการชำระเงิน/ Payment type
                </div>
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['payment_type'])? $invoice['information']['payment_type'] : '' }}
                </div>
            </div> 
            @if($invoice['invoice_type'] == 'normal') 
                <!-- only normal invoice will display Deposit receipt -->
            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height">
                    เลขที่ใบรับมัดจำ/ Deposit receipt
                </div>
                <div class="col-xs-7 nopadding full-height black font-read">
                    {{ isset($invoice['information']['deposit_receipt'])? $invoice['information']['deposit_receipt']:'' }}                    
                </div>
            </div>
            @else
            <div class="row nopadding invoice-info-bottom-line">
                <div class="col-xs-5 nopadding full-height"></div>
                <div class="col-xs-7 nopadding full-height black font-read"></div>
            </div>
            @endif
            <!-- End: Receive Information -->
        </div>
    </div>
</div>