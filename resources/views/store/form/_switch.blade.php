
<div class="col-lg-12">
    <div class="row">
        <div class="form-group col-lg-12">
            <div class="col-lg-2 control-label">
                <label for="{{ $text_name }}">
                    <span class="text-danger">*</span> {{ $text_name }}
                </label>
            </div>
            <div class="col-lg-6">
            <input 
                type="checkbox"
                name="{{ $text_name }}"
                class="switch {{ isset($leftLabel)? 'col-lg-8' : '' }}"
                data-on-text="{{ isset($onText)? $onText : 'active' }}" 
                data-off-text="{{ isset($offText)? $offText : 'inactive' }}"
                data-on-color="success"
                data-off-color="danger"
                data-size="mini" 
                {{ (isset($value) && $value == 'active' || $value == 'Y' )? 'checked' : '' }}
                >
            </div>
        </div>
    </div>
</div>