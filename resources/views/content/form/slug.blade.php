
<div class="col-lg-12">
    <div class="col-lg-1">     
    </div>
    <div class="col-lg-8">
        <div id="slug_collapse" class="collapse"> 
            <label class="control-label text-teal">
                Your slug =   
                <span class="slug-display"></span>
            </label>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="col-lg-1 control-label">
        <label for="slug">
            <span class="text-danger">*</span> {{ trans('Slug : ') }}
        </label>
    </div>
    <div class="col-lg-8">
        <input 
            type="text" 
            class="form-control content-name" 
            name="slug"
            maxlength="80"
            placeholder="Please paste your unslug string"
            value='{{ isset($contentDetail)? $contentDetail["slug"] : ''}}'>
    </div>
</div>
