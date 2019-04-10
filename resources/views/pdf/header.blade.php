<table style="width:100%">
    <tbody>
        <tr>
            <td width="38%">&nbsp;</td>
            <td width="24%">&nbsp;</td>
            <td width="38%">
                <table style="width:100%">
                    <tr>
                        <td width="73%">&nbsp;</td>
                        <td width="27%" class="border-all text-center head-title" style="padding: 4px;line-height: 16px;">
                        @if($invoice['copy_for'] == 'customer')
                            <div>ต้นฉบับลูกค้า</div>
                            <div>For customer</div>
                        @else
                            <div>สำเนาบริษัท</div>
                            <div>For company</div>
                        @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="38.5%" class="head-title text-company">
                <div>{{ config('invoice.company_name') }}</div>
                <div>{{ config('invoice.head_office') . ' ' . config('invoice.tel') . ' ' . config('invoice.fax')}}</div>
                <div>{{ config('invoice.makro_tax_id') }}</div>
            </td>
            <td width="23%" class="text-center">
                <img src="{{ public_path( config('invoice.logo_path') ) }}" >
            </td>
            <td width="38.5%" class="text-reprint">
            @if(isset($invoice['template_type']) && $invoice['template_type'] == 'reprint')
                <div>
                    ใบแทนออกให้ครั้งที่ {{ $invoice['reprint']['count'] }} {{ $invoice['reprint']['date'] }} สาเหตุ ต้นฉบับสูญหาย
                </div>
                <br><br>
                <div>
                    ลงชื่อ........................................
                </div>
            @elseif(isset($invoice['template_type']) && $invoice['template_type'] == 'replace')
                เป็นการยกเลิกและออกใบกำกับภาษีฉบับใหม่ แทนฉบับเดิมเลขที่ {{ $invoice['replace']['old_invoice_number'] }}
            @endif
            </td>
        </tr>
        <tr>
            <td width="38%">&nbsp;</td>
            <td width="24%" class="text-center head-document">
                @if($invoice['invoice_type'] == 'deposit' && $invoice['format_type'] == 'short')
                        <div>{{ config('invoice.deposit_short_name.th') }}</div>
                        <div>{{ config('invoice.deposit_short_name.en') }}</div>
                @elseif($invoice['invoice_type'] == 'deposit' && $invoice['format_type'] == 'long')
                        <div>{{ config('invoice.deposit_long_name.th') }}</div>
                        <div>{{ config('invoice.deposit_long_name.en') }}</div>
                @elseif($invoice['invoice_type'] == 'normal')
                        <div>{{ config('invoice.normal_name.th') }}</div>
                        <div>{{ config('invoice.normal_name.en') }}</div>
                @elseif($invoice['invoice_type'] == 'credit-note')
                        <div>{{ config('invoice.credit_note_name.th') }}</div>
                        <div>{{ config('invoice.credit_note_name.en') }}</div>
                @endif
            </td>
            <td width="38%">&nbsp;</td>
        </tr>
    </tbody>
</table>