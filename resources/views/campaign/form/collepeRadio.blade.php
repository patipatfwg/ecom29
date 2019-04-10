<input type="hidden" name="campaign_type" value="online" >
{{--
<div class="col-lg-12">           
    <div class="col-lg-12 margin-bottom-20">
        <div class="col-lg-3">
            <input type="radio" name="campaign_type" value="offline" 
            @if(isset($campaignData))
                @if($campaignData['campaign_type']=='offline')
                    checked
                @endif
            @endif > Mapping Promotion 
        </div>
        <div class="col-lg-3">
            <input type="radio" name="campaign_type" value="online" 
            @if(isset($campaignData))
                @if($campaignData['campaign_type']=='online')
                    checked
                @endif
            @endif > Mapping Product
        </div>
    </div>
    <div class="col-lg-12" id="offlineTab">
        <div class="col-lg-12 margin-bottom-20">
            <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> Makro Promotion ID :
                    </label>
                    <div class="col-lg-3">
                        <select id="searchPromotion" name="promotion_id" class="selectpicker form-border-select">
                            <option class="category-option " value="-1">
                                -- Choose Makro Promotion ID --
                            </option>
                            @foreach($promotionData as $promotion)
                            <option class="category-option " value="{{ $promotion['id'] }}"
                                @if(isset($campaignData))
                                    @if($campaignData['promotion_id'] == $promotion['id'])
                                        selected
                                    @endif
                                @endif >
                                {{ $promotion['promotion_code'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>          
        </div>
        <div class="col-lg-12">
            <label class="control-label col-lg-3 text-left">
                Promotion period (Makro offline):
            </label>
            <div class="col-lg-5">
                <div class="input-group input-daterange">
                    <input id="promo_start_date" type="text" class="form-control" value="" readonly>
                    <div class="input-group-addon">
                        to
                    </div>
                    <input id="promo_end_date" type="text" class="form-control" value="" readonly>
                        <span class="input-group-addon">
                            <i class="icon-calendar2"></i>
                        </span>
                </div>
            </div>
        </div>

    </div>
        
    <div class="col-lg-12" id="onlineTab">
        @if(isset($campaignId))
            <bottom id="mapping-btn" class="btn bg-primary-800 btn-raised "> Mapping Products </bottom>
        @else
            Please Save This Campaign Before Mapping Products
        @endif
    </div>
</div>
--}}