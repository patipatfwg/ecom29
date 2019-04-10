<table class="border-all text-value" style="width:100%;">
    <tbody>
        <tr>
            <td class="border-right text-center border-bottom head-title" width="5.5%">
                <div>ลำดับที่</div>
                <div>ITEM</div>
            </td>
            <td class="border-right text-center border-bottom head-title" width="9.5%">
                <div>รหัสสินค้า</div>
                <div>ARTICLE NO.</div>
            </td>
            <td class="border-right text-center border-bottom head-title" width="33.5%" colspan="2">
                <div>รายละเอียด</div>
                <div>DESCRIPTION</div>
            </td>
            <td class="border-right text-center border-bottom head-title" width="9.5%">
                <div>จำนวน/น้ำหนัก</div>
                <div>QUANTITY</div>
            </td>
            <td class="border-right text-center border-bottom head-title" width="8%">
                <div>หน่วยบรรจุ</div>
                <div>UNIT</div>
            </td>
            <td class="border-right text-center border-bottom head-title" width="12.5%">
                <div>ราคาต่อหน่วย</div>
                <div>UNIT PRICE</div>
            </td>
            <td class="border-right text-center border-bottom head-title" width="12.5%">
                <div>รหัส ภ.พ.</div>
                <div>VAT CODE</div>
            </td>
            <td class="text-center border-bottom head-title" width="9%">
                <div>จำนวนเงินรวม</div>
                <div>TOTAL</div>
            </td>
        </tr>
<!-- Item Line -->
    <!-- Total Line 16 Line -->
        <!-- item-line 14 line -->
        @for($i=0; $i < 14; $i++)

            @if(isset($invoice['item_lines'][$i]))
                @include('pdf.detail.item_line', ['data' => $invoice['item_lines'][$i]])
            @else
                @include('pdf.detail.item_line')
            @endif

        @endfor
        <!-- End item-line 14 line -->
        <!-- page-line 1 line -->
        @if($invoice['pagination']['current_page'] != $invoice['pagination']['total_page'])
            @include('pdf.detail.item_line', [
                'data' => [
                    'type' => 'next-page',
                    'text_next_page' => 'มีต่อหน้า ' . (string)($invoice['pagination']['current_page'] + 1)
                ]
            ])
        @else
            @include('pdf.detail.item_line', [
                'data' => [
                    'type' => 'next-page',
                    'text_next_page' => ''
                ]
            ])
        @endif
        <!-- End page-line 1 line -->
    <!-- End Total Line 16 Line -->
<!-- End Item Line -->

        @include('pdf.detail.total_amount_line')

        @include('pdf.detail.remark')

        @include('pdf.detail.payment')

        @include('pdf.detail.signature')
    </tbody>
</table>