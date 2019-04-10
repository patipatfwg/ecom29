<div class="col-lg-12 panel-body">
    <div class="form-group col-lg-12">

        <div class="col-lg-2 control-label">
            <label for="name_{{ $lang }}">
                <span class="text-danger">*</span> {{ trans_append_language('Name',$lang) }} :
            </label>
        </div>
        
        <div class="col-lg-7">
            <input 
                type="text" 
                class="form-control content-name" 
                name="name_{{ $lang }}"
                maxlength="80"
                placeholder="No longer than 80 characters"
                value='{{ isset($groupHilightData["name"][$lang])? $groupHilightData["name"][$lang] : ''}}'>
        </div>
    </div>
</div>