
<div class="col-lg-3 control-label">
    <label for="{{ $text_name }}">
        <span class="text-danger">*</span> {{ $text_name }}
    </label>
</div>
<div class="col-lg-6">
    <input 
        type="checkbox"
        name="status"
        class="switch {{ isset($leftLabel)? 'col-lg-8' : '' }}"
        data-on-text="{{ isset($onText)? $onText : 'active' }}" 
        data-off-text="{{ isset($offText)? $offText : 'inactive' }}"
        data-on-color="success"
        data-off-color="danger"
        data-size="mini" 
        {{ ($coupon['status'] == 'active')?'checked':'' }}
        >
</div>
