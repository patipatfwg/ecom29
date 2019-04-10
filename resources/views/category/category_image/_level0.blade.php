<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (A)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_A']['image']) && ! empty($category['image_detail']['position_A']['image']) )
                        <input type="hidden" name="temp_position_A" id="temp_position_A" value="{{ $category['image_detail']['position_A']['image'] }}">
                        <img id="temp_position_A_display" src="{{ $category['image_detail']['position_A']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/855x380.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_A" id="image_position_A" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_A']['image']) && ! empty($category['image_detail']['position_A']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_A"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
            <span>Size 855 x 380 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_A" value="{{ $category['image_detail']['position_A']['url'] or '' }}">
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (B)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_B']['image']) && ! empty($category['image_detail']['position_B']['image']) )
                        <input type="hidden" name="temp_position_B" id="temp_position_B" value="{{ $category['image_detail']['position_B']['image'] }}">
                        <img id="temp_position_B_display" class="position-a-display" src="{{ $category['image_detail']['position_B']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/285x380.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_B" id="image_position_B" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_B']['image']) && ! empty($category['image_detail']['position_B']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_B"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
            <span>Size 285 x 380 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_B" value="{{ $category['image_detail']['position_B']['url'] or '' }}">
        </div>
    </div>
</div>