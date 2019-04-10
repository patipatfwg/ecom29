<div class="invoice-header">
    </div>
    <table cellpadding="5px" autosize="1" border="1" width="100%" style="overflow: wrap; border-collapse: collapse;">
        <tr>
            <td class="col-5">
                <div style="padding-top: 12mm; line-height: 1.4; font-size: 10.874px; font-weight: bold;">
                    <div>{{ config('invoice.company_name') }}</div>
                    <div>{{ config('invoice.head_office') . ' ' . config('invoice.tel') . ' ' . config('invoice.fax')}}</div>
                    <div>{{ config('invoice.makro_tax_id') }}</div>
                </div>
            </td>
            <td class="col-2 nopadding" style="font-size: 12.874px; text-align: center;">
                <div class="makro-logo black text-bold">
                    <img width="125" height="68" src="{{public_path(config('invoice.logo_path'))}}">
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
            </td>
            <td class="col-5 nopadding text-center" style="vertical-align: top;">
                <div class="copy-box" style="float: right;">
                    <div class="copy-box-middle">
                        <div class="copy-box-inner">
                            @if($invoice['copy_for'] == 'customer')
                            <div>
                                ต้นฉบับลูกค้า<br><br>
                                For customer
                            </div>
                            @else
                            <div>สำเนาบริษัท</div>
                            <div>For company</div>
                            @endif
                        </div>   
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div class="col-5">
        <div style="padding-top: 12mm; line-height: 1.4; font-size: 10.874px; font-weight: bold;">
            <div>{{ config('invoice.company_name') }}</div>
            <div>{{ config('invoice.head_office') . ' ' . config('invoice.tel') . ' ' . config('invoice.fax')}}</div>
            <div>{{ config('invoice.makro_tax_id') }}</div>
        </div>
    </div>
    <div class="col-2 nopadding" style="font-size: 12.874px; text-align: center;">
        <div class="makro-logo black text-bold">
            <img width="125" height="68" src="{{public_path(config('invoice.logo_path'))}}">
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
    <div class="col-5 nopadding text-center" style="vertical-align: top;">
        <div class="copy-box" style="float: right;">
            <div class="copy-box-middle">
                <div class="copy-box-inner">
                    @if($invoice['copy_for'] == 'customer')
                    <div>
                        ต้นฉบับลูกค้า<br><br>
                        For customer
                    </div>
                    @else
                    <div>สำเนาบริษัท</div>
                    <div>For company</div>
                    @endif
                </div>   
            </div>
        </div>
    </div>
    <div class="col-xs-5 nopadding">
        
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