@if($invoice['invoice_type'] == 'credit-note')
<tr>
    <td colspan="3" class="border-right head-title text-center text-sign-signature">ผู้รับคืนสินค้า / Receiver</td>
    <td class="border-right head-title text-center text-sign-signature">ผู้ส่งสินค้า / Sender</td>
    <td colspan="2" class="border-right head-title text-center text-sign-signature">ผู้ตรวจสอบ / Checked By</td>
    <td colspan="3" class="border-right head-title text-center text-sign-signature">ผู้ได้รับมอบอำนาจ / Authorized Signature</td>
</tr>
<tr>
    <td colspan="3" class="border-right head-title text-signature">วันที่/ DATE</td>
    <td class="border-right head-title text-signature">วันที่/ DATE</td>
    <td colspan="2" class="border-right head-title text-signature">วันที่/ DATE</td>
    <td colspan="3" class="border-right head-title text-signature">วันที่/ DATE</td>
</tr>

@elseif($invoice['invoice_type'] == 'deposit')
<tr>
    <td colspan="9" class="text-center head-title text-signature">ในนามบริษัท สยามแม็คโคร จำกัด (มหาชน)</td>
</tr>
<tr>
    <td colspan="4" class="text-right text-signature">
        <div class="text-right head-title">ผู้ได้รับมอบอำนาจ/Authorized Signature</div>
    </td>
    <td colspan="5" class="text-signature">
        <div class="text-left border-bottom img-signature">
            <img src="{{ public_path( config('invoice.signature_path') ) }}">
        </div>
    </td>
</tr>

<!-- normal -->
@else
<tr>
    <td colspan="3" class="border-right head-title text-center text-sign-signature">ผู้ส่งสินค้า / Sender</td>
    <td class="border-right head-title text-center text-sign-signature">ผู้รับสินค้า / Receiver</td>
    <td colspan="2" class="border-right head-title text-center text-sign-signature">ผู้ตรวจสอบ / Checked By</td>
    <td colspan="3" class="border-right head-title text-center text-sign-signature">ผู้ได้รับมอบอำนาจ / Authorized Signature</td>
</tr>
<tr>
    <td colspan="3" class="border-right head-title text-signature">วันที่/ DATE</td>
    <td class="border-right head-title text-signature">วันที่/ DATE</td>
    <td colspan="2" class="border-right head-title text-signature">วันที่/ DATE</td>
    <td colspan="3" class="border-right head-title text-signature">วันที่/ DATE</td>
</tr>
@endif