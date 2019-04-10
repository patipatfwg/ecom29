<!-- Status switch checkbox -->
<div class="col-lg-{{ isset($leftLabel)? '12' :'4 text-right' }}">
    <label class="control-label {{ isset($leftLabel)? 'col-lg-4 text-bold' : 'margin-right-10' }} ">{{ isset($statusName)? $statusName.": " : trans('ACTION: ') }}</label>
        <input 
            type="checkbox"
            name="{{ isset($inputName) ? $inputName : 'status' }}"
            class="switch {{ isset($leftLabel)? 'col-lg-8' : '' }}"
            data-on-text="{{ isset($onText)? $onText : 'Publish' }}" 
            data-off-text="{{ isset($offText)? $offText : 'Unpublish' }}"
            data-on-color="success"
            data-off-color="danger"
            data-size="mini" 
            {{ ($status == "N" || $status == "inactive") ? "" : "checked='checked'" }}
            {{ (isset($readonly)&&$readonly==true) ? 'disabled' : '' }}
            >
    </label>
</div>
<!-- End Status switch checkbox -->