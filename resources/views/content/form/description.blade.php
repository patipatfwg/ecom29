<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading bg-teal-400">
            <h6 class="panel-title"><span class="text-danger">*</span> {{ trans_append_language('Detail',$lang) }}</h6>
        </div>
        
        <textarea
            id="full_text_{{ $lang }}"
            name="description_{{ $lang }}"
            class=""           
            >{{ isset($contentDetail) ? $contentDetail["description"][$lang] : '' }}</textarea>
    
        <div class="clearfix"></div>
        
    </div>
</div>