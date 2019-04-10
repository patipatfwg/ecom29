<div class="col-lg-12">
    <div class="row">
        <div class="form-group col-lg-12">
            <div class="col-lg-2 control-label">
                <label for="{{ $text_name }}">
                    <span class="text-danger">*</span> {{ $text_name }}
                </label>
            </div>
            <div class="col-lg-7">
                <input
                    type="text"
                    class="form-control content-name"
                    OnKeyPress="return chkNumber(this)"
                    name="{{ $name }}"
                    maxlength="80"
                    placeholder="{{ $text_name }}"
                    value='{{ isset($value)? $value : ''}}'>
            </div>
        </div>
    </div>
</div>