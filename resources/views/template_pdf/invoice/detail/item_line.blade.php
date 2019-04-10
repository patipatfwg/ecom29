@if(!isset($data['type']))
<div class="line font-read">
    <div class="detail-column-1 fl full-height border-right text-center black"></div>
    <div class="detail-column-2 fl full-height border-right text-center black"></div>
    <div class="detail-column-3 fl full-height border-right text-left black"></div>
    <div class="detail-column-4 fl full-height border-right text-center black"></div>
    <div class="detail-column-5 fl full-height border-right text-center black"></div>
    <div class="detail-column-6 fl full-height border-right text-right black"></div>
    <div class="detail-column-7 fl full-height border-right text-center black"></div>
    <div class="detail-column-8 fl full-height text-right black"></div>
</div>
@else
    @if($data['type'] == 'discount-header')
    <div class="line font-read">
        <div class="detail-column-1-2 fl full-height border-right text-left nowrap black">
            เงื่อนไขส่วนลด
        </div>
        <!-- <div class="detail-column-2 fl full-height border-right text-center black"></div> -->
        <div class="detail-column-3 fl full-height border-right text-center black"></div>
        <div class="detail-column-4 fl full-height border-right text-center black"></div>
        <div class="detail-column-5 fl full-height border-right text-center black"></div>
        <div class="detail-column-6 fl full-height border-right text-right black"></div>
        <div class="detail-column-7 fl full-height border-right text-center black"></div>
        <div class="detail-column-8 fl full-height text-right black"></div>
    </div>
    @elseif($data['type'] == 'discount-summary')
    <div class="line font-read">
        <div class="detail-column-1 fl full-height border-right text-center black"></div>
        <div class="detail-column-2 fl full-height border-right text-left black">
            รวมส่วนลด
        </div>
        <div class="detail-column-3 fl full-height border-right text-center black"></div>
        <div class="detail-column-4 fl full-height border-right text-center black"></div>
        <div class="detail-column-5 fl full-height border-right text-center black"></div>
        <div class="detail-column-6 fl full-height border-right text-right black">
            {{ isset($data['discount_total_amount'])? $data['discount_total_amount'] : ''}}
        </div>
        <div class="detail-column-7 fl full-height border-right text-center black"></div>
        <div class="detail-column-8 fl full-height text-right black"></div>
    </div>
    @elseif($data['type'] == 'discount-line')
    <div class="line font-read">
        <div class="detail-column-1 fl full-height border-right text-center black">
            {{ isset($data['no'])? $data['no'] : ''}}
        </div>
        <div class="detail-column-2-3 fl full-height border-right text-left nowrap black">
            {{ isset($data['discount_description'])? $data['discount_description'] : '' }}
        </div>
        <!-- <div class="detail-column-3 fl full-height border-right text-center black"></div> -->
        <div class="detail-column-4 fl full-height border-right text-center black"></div>
        <div class="detail-column-5 fl full-height border-right text-center black"></div>
        <div class="detail-column-6 fl full-height border-right text-right black">
            {{ isset($data['discount_amount'])? $data['discount_amount'] : ''}}
        </div>
        <div class="detail-column-7 fl full-height border-right text-center black"></div>
        <div class="detail-column-8 fl full-height text-right black"></div>
    </div>
    @elseif($data['type'] == 'item-line-header')
    <div class="line font-read">
        <div class="detail-column-1-2 fl full-height border-right text-left nowrap black">
            {{ isset($data['text'])? $data['text'] : '' }}
        </div>
        <!-- <div class="detail-column-2 fl full-height border-right text-center black"></div> -->
        <div class="detail-column-3 fl full-height border-right text-center black"></div>
        <div class="detail-column-4 fl full-height border-right text-center black"></div>
        <div class="detail-column-5 fl full-height border-right text-center black"></div>
        <div class="detail-column-6 fl full-height border-right text-right black"></div>
        <div class="detail-column-7 fl full-height border-right text-center black"></div>
        <div class="detail-column-8 fl full-height text-right black"></div>
    </div>
    @elseif($data['type'] == 'item-line')
    <div class="line font-read">
        <div class="detail-column-1 fl full-height border-right text-center black">
            {{ isset($data['no'])? $data['no'] : ''}}
        </div>
        <div class="detail-column-2 fl full-height border-right text-center black">
            {{ isset($data['item_id'])? $data['item_id'] : ''}}
        </div>
        <div class="detail-column-3 fl full-height border-right text-left black">
            {{ isset($data['description'])? $data['description'] : ''}}
        </div>
        <div class="detail-column-4 fl full-height border-right text-center black">
            {{ isset($data['quantity'])? $data['quantity'] : ''}}
        </div>
        <div class="detail-column-5 fl full-height border-right text-center black">
            {{ isset($data['unit'])? $data['unit'] : ''}}
        </div>
        <div class="detail-column-6 fl full-height border-right text-right black">
            {{ isset($data['unit_price'])? $data['unit_price'] : ''}}
        </div>
        <div class="detail-column-7 fl full-height border-right text-center black">
            {{ isset($data['vat_code'])? $data['vat_code'] : ''}}
        </div>
        <div class="detail-column-8 fl full-height text-right black">
            {{ isset($data['total_amount'])? $data['total_amount'] : ''}}
        </div>
    </div>
    @elseif($data['type'] == 'item-groups-header')
    <div class="line font-read">
        <div class="detail-column-1 fl full-height border-right text-center"></div>
        <div class="detail-column-2 fl full-height border-right text-center">จำนวนชิ้น</div>
        <div class="detail-column-3 fl full-height border-right">
            <div class="half-width fl nopadding text-center">รหัส ภ.พ.</div>
            <div class="half-width fl nopadding text-center">ราคาสินค้า</div>
        </div>
        <div class="detail-column-4 fl full-height border-right text-center">ภาษี</div>
        <div class="detail-column-5 fl full-height border-right text-center">ราคา</div>
        <div class="detail-column-6 fl full-height border-right text-right"></div>
        <div class="detail-column-7 fl full-height border-right text-center"></div>
        <div class="detail-column-8 fl full-height text-right"></div>
    </div>
    @elseif($data['type'] == 'item-groups-line')
    <div class="line font-read">
        <div class="detail-column-1 fl full-height border-right text-center black"></div>
        <div class="detail-column-2 fl full-height border-right text-center black">
            {{ isset($data['quantity'])? $data['quantity'] : ''}}
        </div>
        <div class="detail-column-3 fl full-height border-right">
            <div class="half-width fl nopadding text-center black">
                {{ isset($data['vat_code'])? $data['vat_code'] : ''}}
            </div>
            <div class="half-width fl nopadding text-right black">
                {{ isset($data['unit_price'])? $data['unit_price'] : ''}}
            </div>
        </div>
        <div class="detail-column-4 fl full-height border-right text-right black">
            {{ isset($data['vat_amount'])? $data['vat_amount'] : ''}}
        </div>
        <div class="detail-column-5 fl full-height border-right text-right black">
            {{ isset($data['total_amount'])? $data['total_amount'] : ''}}
        </div>
        <div class="detail-column-6 fl full-height border-right text-right black"></div>
        <div class="detail-column-7 fl full-height border-right text-center black"></div>
        <div class="detail-column-8 fl full-height text-right black"></div>
    </div>
    @elseif($data['type'] == 'item-groups-summary')
    <div class="line font-read">
        <div class="detail-column-1 fl full-height border-right text-center black"></div>
        <div class="detail-column-2 fl full-height border-right text-center black"></div>
        <div class="detail-column-3 fl full-height border-right">
            <div class="half-width fl nopadding text-center black">รวม</div>
            <div class="half-width fl nopadding text-right black">
                {{ isset($data['unit_price'])? $data['unit_price'] : ''}}
            </div>
        </div>
        <div class="detail-column-4 fl full-height border-right text-right black">
            {{ isset($data['vat_amount'])? $data['vat_amount'] : ''}}
        </div>
        <div class="detail-column-5 fl full-height border-right text-right black">
            {{ isset($data['total_amount'])? $data['total_amount'] : ''}}
        </div>
        <div class="detail-column-6 fl full-height border-right text-right black"></div>
        <div class="detail-column-7 fl full-height border-right text-center black"></div>
        <div class="detail-column-8 fl full-height text-right black"></div>
    </div>
    @else
    <div class="line font-read">
        <div class="detail-column-1 fl full-height border-right text-center black"></div>
        <div class="detail-column-2 fl full-height border-right text-center black"></div>
        <div class="detail-column-3 fl full-height border-right text-left black"></div>
        <div class="detail-column-4 fl full-height border-right text-center black"></div>
        <div class="detail-column-5 fl full-height border-right text-center black"></div>
        <div class="detail-column-6 fl full-height border-right text-right black"></div>
        <div class="detail-column-7 fl full-height border-right text-center black"></div>
        <div class="detail-column-8 fl full-height text-right black"></div>
    </div>
    @endif
@endif