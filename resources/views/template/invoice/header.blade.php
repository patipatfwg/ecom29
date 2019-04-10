<div class="invoice-header">
    <div class="col-xs-5 nopadding" >
        <div class="contract-us text-bold">
            <div>{{ config('invoice.company_name') }}</div>
            <div>{{ config('invoice.head_office') . ' ' . config('invoice.tel') . ' ' . config('invoice.fax')}}</div>
            <div>{{ config('invoice.makro_tax_id') }}</div>
        </div>
    </div>
    <div class="col-xs-2 nopadding text-center">
        <div class="makro-logo black text-bold">
            <img class="full-height full-width" width=125 height=68 src="{{asset(config('invoice.logo_path'))}}">
            @if($invoice['invoice_type'] == 'deposit' && $invoice['format_type'] == 'short')
                <div class="invoice-name text-center">
                    <div>{{ config('invoice.deposit_short_name.th') }}</div>
                    <div>{{ config('invoice.deposit_short_name.en') }}</div>
                </div>
            @elseif($invoice['invoice_type'] == 'deposit' && $invoice['format_type'] == 'long')
                <div class="invoice-name text-center">
                    <div>{{ config('invoice.deposit_long_name.th') }}</div>
                    <div>{{ config('invoice.deposit_long_name.en') }}</div>
                </div>
            @elseif($invoice['invoice_type'] == 'normal')
                <div class="invoice-name text-center">
                    <div>{{ config('invoice.normal_name.th') }}</div>
                    <div>{{ config('invoice.normal_name.en') }}</div>
                </div>
            @elseif($invoice['invoice_type'] == 'credit-note')
                <div class="invoice-name text-center">
                    <div>{{ config('invoice.credit_note_name.th') }}</div>
                    <div>{{ config('invoice.credit_note_name.en') }}</div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-xs-5 nopadding">
        <div class="copy-box border-all fr text-center">
            <div class="copy-box-middle">
                <div class="copy-box-inner">
                    @if($invoice['copy_for'] == 'customer')
                    <div>ต้นฉบับลูกค้า</div>
                    <div>For customer</div>
                    @else
                    <div>สำเนาบริษัท</div>
                    <div>For company</div>
                    @endif
                </div>   
            </div>
        </div>
        <div class="col-xs-10 text-right nopadding">

            @if(isset($invoice['template_type']) && $invoice['template_type'] == 'reprint')
            <!-- Reprint Template -->
            <div class="sign-panel black nowrap">
                <div>
                    ใบแทนออกให้ครั้งที่ {{ $invoice['reprint']['count'] }} {{ $invoice['reprint']['date'] }} สาเหตุ ต้นฉบับสูญหาย
                </div>
                <div class="sign">
                    ลงชื่อ........................................
                </div>
            </div>
            @elseif(isset($invoice['template_type']) && $invoice['template_type'] == 'replace')
            <!-- Replace Template -->
            <div class="sign-panel black nowrap">
                เป็นการยกเลิกและออกใบกำกับภาษีฉบับใหม่ แทนฉบับเดิมเลขที่ {{ $invoice['replace']['old_invoice_number'] }}
            </div>
            @endif
        </div>
        
    </div>
</div>