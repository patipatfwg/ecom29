<div class="col-lg-12">
    <div class="row">
        <div class="form-group col-lg-12">
            <div class="col-lg-2 control-label">
                <label for="{{ 'name['.$language.']' }}">
                    <span class="text-danger">*</span> Store {{ trans_append_language('Name',$lang) }}
                </label>
            </div>
            <div class="col-lg-7">
                <input
                    type="text"
                    class="form-control content-name"
                    name="{{ 'store_name['.$language.']' }}"
                    maxlength="150"
                    placeholder="No longer than 150 characters"
                    value='{{ isset($name)? $name : ''}}'>
            </div>
        </div>
    </div>
</div>