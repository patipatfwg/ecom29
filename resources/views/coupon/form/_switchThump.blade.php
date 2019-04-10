
<div class="col-lg-3 control-label">
    <label for="{{ $text_name }}">
        {{ $text_name }}
    </label>
</div>
<div class="col-lg-6">
    <input 
        type="checkbox"
        name="thumbnail_display"
        class="switch {{ isset($leftLabel)? 'col-lg-8' : '' }}"
        data-on-text="{{ isset($onText)? $onText : 'Yes' }}" 
        data-off-text="{{ isset($offText)? $offText : 'No' }}"
        data-on-color="success"
        data-off-color="danger"
        data-size="mini" 
        {{ ($coupon['thumbnail_display'] == 'Y')?'checked':'' }}
        >
</div>
