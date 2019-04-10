@if(isset($data['type']) && $data['type'] == 'groups-header')
<div class="line">
    <div class="detail-column-1 fl full-height border-right text-center"></div>
    <div class="detail-column-2 fl full-height border-right text-center">จำนวนสินค้า</div>
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
@elseif(isset($data['type']) && $data['type'] == 'groups-summary')
<div class="line">
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
<div class="line">
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
@endif