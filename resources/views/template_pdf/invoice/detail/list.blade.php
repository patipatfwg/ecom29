<!-- Total Line 18 Line --> 
<div class="invoice-detail-list border-bottom">

    <div class="item-lines">

        <!-- item-line 17 line -->
        @for($i=0; $i < 17; $i++)

            {{-- 
            @include('template_pdf.invoice.detail.item_line_table')
             --}}
            
            @if(isset($invoice['item_lines'][$i]))
                @include('template_pdf.invoice.detail.item_line', ['data' => $invoice['item_lines'][$i]])
            @else
                @include('template_pdf.invoice.detail.item_line')
            @endif
            
        @endfor
        
        {{-- @include('template_pdf.invoice.detail.item_line_table') --}}

        
        @if($invoice['pagination']['current_page'] != $invoice['pagination']['total_page'])
            @include('template_pdf.invoice.detail.item_line', [
                'data' => [
                    'type' => 'item-line',
                    'total_amount' => 'มีต่อหน้า ' . (string)($invoice['pagination']['current_page'] + 1)
                ]
            ])
        @else
            @include('template_pdf.invoice.detail.item_line')
        @endif
        

    </div>

</div>