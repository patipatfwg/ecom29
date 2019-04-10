<div class="invoice-header">
    <div class="col-xs-5 nopadding">
        <div class="contract-us text-bold">
            <div>{{ config('invoice.company_name') }}</div>
            <div>{{ config('invoice.head_office') . ' ' . config('invoice.tel') . ' ' . config('invoice.fax')}}</div>
            <div>{{ config('invoice.makro_tax_id') }}</div>
        </div>
    </div>
    <div class="col-xs-2 nopadding text-center">
        <div class="makro-logo black text-bold">
            <img class="" width=120 height=68 src="{{config('invoice.logo_path')}}">
            @if($invoice['invoice_type'] == 'deposit' && $invoice['format_type'] == 'short')
                {{--<table width="100%" style="font-family: Garuda; font-size: 2.8mm;" class="text-center black text-bold">
                    <tr>
                        <td  width="100%" style="white-space: nowrap; font-size: 2.8mm;">
                            {{ config('invoice.deposit_short_name.th') }}
                        </td>
                    </tr>
                    <tr>
                        <td  width="100%" style="white-space: nowrap; font-size: 2.8mm;">
                            {{ config('invoice.deposit_short_name.en') }}
                        </td>
                    </tr>
                </table>--}}
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
        @if(isset($invoice['template_type']) && $invoice['template_type'] == 'reprint')
        <div class="col-xs-10 text-right nopadding">
            <!-- Reprint Template -->
            <div class="sign-panel black nowrap">
                <div>
                    ใบแทนออกให้ครั้งที่ {{ $invoice['reprint']['count'] }} {{ $invoice['reprint']['date'] }} สาเหตุ ต้นฉบับสูญหาย
                </div>
                <div class="sign">
                    ลงชื่อ........................................
                </div>
            </div>
        </div>
        @elseif(isset($invoice['template_type']) && $invoice['template_type'] == 'replace')
        <div class="col-xs-12 text-right nopadding">
            <!-- Replace Template -->
            <div class="sign-panel black nowrap">
                เป็นการยกเลิกและออกใบกำกับภาษีฉบับใหม่ แทนฉบับเดิมเลขที่ {{ $invoice['replace']['old_invoice_number'] }}
            </div>
        </div>
        @endif
    </div>
</div>