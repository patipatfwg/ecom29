<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-12 collapse {{ $selectedType == 'product_category'? 'in' : '' }}" id="product_category_tab">
            <div class="col-lg-12 margin-bottom-20">
                <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> Makro Product Category :
                    </label>
                    <div class="col-lg-4">
                        @include('common._dropdown',['data' => $productCategoryList, 'disableSelectAll' => true,'nullDefault' => true,'defaultText' => 'select product category', 'group' => 'product', 'language' => 'th', 'name' => 'value', 'value' => isset($groupHilightData["value"]) && isset($groupHilightData["type"]) && $groupHilightData["type"] == 'product_category'? $groupHilightData["value"] : null])
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 collapse {{ $selectedType == 'content'? 'in' : '' }}" id="content_tab">
            <div class="col-lg-12 margin-bottom-20">
                <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> Makro Content :
                    </label>
                    <div class="col-lg-4">
                        @include('common._dropdown',['data' => $contentList,'disableSelectAll' => true,'nullDefault' => true, 'defaultText' => 'select content', 'group' => 'content', 'language' => 'th', 'name' => 'value', 'value' => isset($groupHilightData["value"]) && isset($groupHilightData["type"]) && $groupHilightData["type"] == 'content'? $groupHilightData["value"] : null])
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 collapse {{ $selectedType == 'business_category'? 'in' : '' }}" id="business_category_tab">
            <div class="col-lg-12 margin-bottom-20">
                <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> Makro Business Category :
                    </label>
                    <div class="col-lg-4">
                        @include('common._dropdown',['data' => $businessCategoryList,'nullDefault' => true, 'disableSelectAll' => true,'defaultText' => 'select business category', 'group' => 'business', 'language' => 'th', 'name' => 'value', 'value' => isset($groupHilightData["value"]) && isset($groupHilightData["type"]) && $groupHilightData["type"] == 'business_category'? $groupHilightData["value"] : null])
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 collapse {{ $selectedType == 'campaign'? 'in' : '' }}" id="campaign_tab">
            <div class="col-lg-12 margin-bottom-20">
                <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> Makro Campaign :
                    </label>
                    <div class="col-lg-4">
                        @include('common._dropdown',[
                            'data'        => $campaignList,
                            'defaultText' => 'select campaign',
                            'group'       => 'campaign',
                            'language'    => 'th',
                            'disableSelectAll' => true,
                            'nullDefault' => true,
                            'name'        => 'value',
                            'value'       => isset($groupHilightData["value"]) && isset($groupHilightData["type"]) && $groupHilightData["type"] == 'campaign'? $groupHilightData["value"] : null]
                        )
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 collapse {{ ($selectedType == 'banner')? 'in' : '' }}" id="banner_tab">
            <div class="col-lg-12 margin-bottom-20">
                <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> Makro Banner :
                    </label>
                    <div class="col-lg-4">
                        <input type="hidden" id="banners" name="value" value='{{ isset($groupHilightData["value"]) && $groupHilightData["type"] == "banner" ? $groupHilightData["value"] : ''}}'>
                        @include('common._select', [ 
                            'name' => 'slug_banner',
                            'id' => 'select-banner'
                        ])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 collapse {{ $selectedType == 'link_external'? 'in' : '' }}" id="link_external_tab">
            <div class="col-lg-12 margin-bottom-20">
                <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> External Link :
                    </label>
                    <div class="col-lg-4">
                        <input name="value" class="form-control" value='{{ isset($groupHilightData["value"]) && $groupHilightData["type"] == "link_external" ? $groupHilightData["value"] : ''}}'>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 collapse {{ $selectedType == 'link_internal'? 'in' : '' }}" id="link_internal_tab">
            <div class="col-lg-12 margin-bottom-20">
                <div class="form-group">
                    <label class="control-label col-lg-3 text-left">
                        <span class="text-danger">*</span> Internal Link :
                    </label>
                    <div class="col-lg-4">
                        <input name="value" class="form-control" value='{{ isset($groupHilightData["value"]) && $groupHilightData["type"] == "link_internal"? $groupHilightData["value"] : ''}}'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>