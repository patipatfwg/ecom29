<table style="width:100%;">
@if($invoice['invoice_type'] == 'credit-note')
    <tr>
        <td width="25%">
            <span class="head-title">อ้างถึง ใบกำกับภาษีเลขที่</span>
            <span class="text-value">{{ isset($invoice['reference']['deposit_invoice_number'])? $invoice['reference']['deposit_invoice_number'] : '' }}</span>
        </td>
        <td width="25%" colspan="3">&nbsp;</td>
        <td width="25%">
            <span class="head-title">ลงวันที่</span>
            <span class="text-value">{{ isset($invoice['reference']['deposit_invoice_date'])? $invoice['reference']['deposit_invoice_date'] : '' }}</span>
        </td>
        <td width="25%" class="head-title text-right">(หน่วย:บาท)</td>
    </tr>
@elseif($invoice['invoice_type'] == 'normal' || $invoice['invoice_type'] == 'deposit')
    <tr>
        <td width="25%">&nbsp;</td>
        <td width="25%" colspan="3">&nbsp;</td>
        <td width="25%">&nbsp;</td>
        <td width="25%" class="head-title text-right">(หน่วย:บาท)</td>
    </tr>
@else
    <tr>
        <td width="25%">อ้างถึง ใบแจ้งหนี้เลขที่</td>
        <td width="25%" colspan="3">&nbsp;</td>
        <td width="25%">&nbsp;</td>
        <td width="25%" class="head-title text-right">(หน่วย:บาท)</td>
    </tr>
@endif
</table>