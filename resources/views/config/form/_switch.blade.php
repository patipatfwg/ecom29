<div class="col-lg-12">
    <div class="row">
        <div class="form-group col-lg-12">
            <div class="col-lg-2 control-label">
                <label for="{{ $text_name }}">
                    <span class="text-danger">*</span> {{ $text_name }}
                </label>
            </div>
            <div class="col-lg-7 pdt-1px">
                    <!-- Status switch checkbox -->               
                    <input 
                        type="checkbox"
                        name="status"
                        class="switch {{ isset($leftLabel)? 'col-lg-8' : '' }}"
                        data-on-text="{{ isset($onText)? $onText : 'Active' }}" 
                        data-off-text="{{ isset($offText)? $offText : 'Inactive' }}"
                        data-on-color="success"
                        data-off-color="danger"
                        data-size="mini" 
                        {{ (isset($value) && $value == 'active')? 'checked' : '' }}
                        > 
                    <!-- End Status switch checkbox -->
            </div>
        </div>         
    </div>
</div>