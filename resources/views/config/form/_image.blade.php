<div class="col-lg-12">
    <div class="form-group">

        <label class="control-label col-lg-2">
            <span class="text-danger">*</span>&nbsp;{{ $label }}
        </label>

        <div class="col-lg-8">

            <div class="fileupload fileupload-new input-group" data-provides="fileupload">

                <div class="form-inline">
                    <div class="fileupload fileupload-new input-group" data-provides="fileupload">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file text-primary">
                                <span class="fileupload-new"><i class="glyphicon glyphicon-camera"></i>&nbsp;&nbsp;Select file</span>
                                <span class="fileupload-exists">Change</span>
                                <input type="file" name="{{ $input_file_name }}" accept="image/*">
                            </span>
                        </span>
                        <span class="input-group-btn input-group-btn-fileupload">
                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </span>
                    </div>
                </div>

                <p class="form-control-static text-grey">Size {{ $width }}x{{ $height }} pixel, .jpg or .png file format only</p>
                
                <div class="fileupload-new thumbnail">
                    <input type="hidden" name="{{ $input_name.'[type]' }}" value="{{ $type }}">
                    <input type="hidden" name="{{ $input_name.'[input_file_name]' }}" value="{{ $input_file_name }}">
                    <input type="hidden" name="{{ $input_name.'[old]' }}" value="{{isset($image)? $image : ''}}">

                    @if (isset($image) && !empty( $image ) )
                        <img class="thumb_old" src="{{ $image }}">
                    @else
                        <img src="{{ URL::asset('/assets/images/no-img.png') }}" alt=""/>
                    @endif

                </div>
                <div class="fileupload-preview fileupload-exists thumbnail"></div>
            </div>
            

        </div>

    </div>
</div>

<div class="col-lg-12">
    <div class="form-group">

        <div class="col-lg-2 control-label text-right">
            <label for="{{ $input_name.'[url]' }}">
                {{ 'Link' }}
            </label>
        </div>
        
        <div class="col-lg-4">
            <input 
                type="text" 
                class="form-control" 
                name="{{ $input_name.'[url]' }}"
                maxlength="80"
                placeholder=""
                value='{{ isset($url)? $url : ''}}'>
        </div>
    </div>
</div>
