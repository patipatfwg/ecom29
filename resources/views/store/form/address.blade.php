<div class="col-lg-12">
    <div class="row">
        <div class="form-group col-lg-12">
            <div class="col-lg-2 control-label">
                <label for="{{ 'address_line_1['.$language.']' }}">
                    <span class="text-danger">*</span> {{ trans_append_language('Address Line 1',$lang) }}
                </label>
            </div>
            <div class="col-lg-7">
                <input
                    type="text"
                    class="form-control content-name"
                    name="{{ 'address_line_1['.$language.']' }}"
                    maxlength="80"
                    placeholder="No longer than 80 characters"
                    value='{{ isset($address_line_1)? $address_line_1 : ''}}'>
            </div>
        </div>
    </div>
</div>