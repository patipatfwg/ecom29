<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D1-1)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D1_1']['image']) && ! empty($category['image_detail']['position_D1_1']['image']) )
                        <input type="hidden" name="temp_position_D1_1" id="temp_position_D1_1" value="{{ $category['image_detail']['position_D1_1']['image'] }}">
                        <img id="temp_position_D1_1_display" src="{{ $category['image_detail']['position_D1_1']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/285x380.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D1_1" id="image_position_D1_1" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D1_1']['image']) && ! empty($category['image_detail']['position_D1_1']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D1_1"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
        </div>
        Size 285 x 380 pixels, .jpg, .jpeg, .png file format only,</span>
    </div>

    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D1_1" value="{{ $category['image_detail']['position_D1_1']['url'] or '' }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D1-2)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D1_2']['image']) && ! empty($category['image_detail']['position_D1_2']['image']) )
                        <input type="hidden" name="temp_position_D1_2" id="temp_position_D1_2" value="{{ $category['image_detail']['position_D1_2']['image'] }}">
                        <img id="temp_position_D1_2_display" src="{{ $category['image_detail']['position_D1_2']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/285x380.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D1_2" id="image_position_D1_2" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D1_2']['image']) && ! empty($category['image_detail']['position_D1_2']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D1_2"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
        </div>
        Size 285 x 380 pixels, .jpg, .jpeg, .png file format only,</span>
    </div>

    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D1_2" value="{{ $category['image_detail']['position_D1_2']['url'] or '' }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D1-3)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D1_3']['image']) && ! empty($category['image_detail']['position_D1_3']['image']) )
                        <input type="hidden" name="temp_position_D1_3" id="temp_position_D1_2" value="{{ $category['image_detail']['position_D1_3']['image'] }}">
                        <img id="temp_position_D1_3_display" src="{{ $category['image_detail']['position_D1_3']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/285x380.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D1_3" id="image_position_D1_3" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D1_3']['image']) && ! empty($category['image_detail']['position_D1_3']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D1_3"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
        </div>
        Size 285 x 380 pixels, .jpg, .jpeg, .png file format only,</span>
    </div>

    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D1_3" value="{{ $category['image_detail']['position_D1_3']['url'] or '' }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D2)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D2']['image']) && ! empty($category['image_detail']['position_D2']['image']) )
                        <input type="hidden" name="temp_position_D2" id="temp_position_D2" value="{{ $category['image_detail']['position_D2']['image'] }}">
                        <img id="temp_position_D2_display" src="{{ $category['image_detail']['position_D2']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/380x190.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D2" id="image_position_D2" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D2']['image']) && ! empty($category['image_detail']['position_D2']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D2"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
            <span>Size 380 x 190 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D2" value="{{ $category['image_detail']['position_D2']['url'] or '' }}">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D3)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D3']['image']) && ! empty($category['image_detail']['position_D3']['image']) )
                        <input type="hidden" name="temp_position_D3" id="temp_position_D3" value="{{ $category['image_detail']['position_D3']['image'] }}">
                        <img id="temp_position_D3_display" src="{{ $category['image_detail']['position_D3']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/190x190.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D3" id="image_position_D3" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D3']['image']) && ! empty($category['image_detail']['position_D3']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D3"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
            <span>Size 190 x 190 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D3" value="{{ $category['image_detail']['position_D3']['url'] or '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D4)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D4']['image']) && ! empty($category['image_detail']['position_D4']['image']) )
                        <input type="hidden" name="temp_position_D4" id="temp_position_D4" value="{{ $category['image_detail']['position_D4']['image'] }}">
                        <img id="temp_position_D4_display" src="{{ $category['image_detail']['position_D4']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/190x190.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D4" id="image_position_D4" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D4']['image']) && ! empty($category['image_detail']['position_D4']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D4"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
            <span>Size 190 x 190 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D4" value="{{ $category['image_detail']['position_D4']['url'] or '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D5)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D5']['image']) && ! empty($category['image_detail']['position_D5']['image']) )
                        <input type="hidden" name="temp_position_D5" id="temp_position_D5" value="{{ $category['image_detail']['position_D5']['image'] }}">
                        <img id="temp_position_D5_display" src="{{ $category['image_detail']['position_D5']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/190x190.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D5" id="image_position_D5" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D5']['image']) && ! empty($category['image_detail']['position_D5']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D5"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
            <span>Size 190 x 190 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D5" value="{{ $category['image_detail']['position_D5']['url'] or '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner main category (D6)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_D6']['image']) && ! empty($category['image_detail']['position_D6']['image']) )
                        <input type="hidden" name="temp_position_D6" id="temp_position_D6" value="{{ $category['image_detail']['position_D6']['image'] }}">
                        <img id="temp_position_D6_display" src="{{ $category['image_detail']['position_D6']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/190x190.png') }}" alt=""/>
                    @endif
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                <div>
                    <span class="btn btn-info btn-file">
                        <span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                        <input type="file" name="image_position_D6" id="image_position_D6" accept="image/*">
                    </span>
                    &nbsp;<a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload"><i class="fa fa-times"></i> Remove</a>
                    @if (isset($category['image_detail']['position_D6']['image']) && ! empty($category['image_detail']['position_D6']['image']) )
                    &nbsp;<a class="btn fileupload-new btn-danger remove-exists" reference="temp_position_D6"><i class="fa fa-times"></i> Remove</a>
                    @endif
                </div>
            </div>
            <span>Size 190 x 190 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_D6" value="{{ $category['image_detail']['position_D6']['url'] or '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Banner category (A)</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail">
                    @if (isset($category['image_detail']['position_A']['image']) && ! empty($category['image_detail']['position_A']['image']) )
                        <input type="hidden" name="temp_position_A" id="temp_position_A" value="{{ $category['image_detail']['position_A']['image'] }}">
                        <img id="temp_position_A_display" src="{{ $category['image_detail']['position_A']['image'] }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/1140x380.png') }}" alt=""/>
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
            <span>Size 1140 x 380 pixels, .jpg, .jpeg, .png file format only</span>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group">
            <label class="control-label">Link</label>
            <input type="text" class="form-control" name="url_position_A" value="{{ $category['image_detail']['position_A']['url'] or '' }}">
        </div>
    </div>
</div>