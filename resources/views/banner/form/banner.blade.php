<div class="col-lg-12">
    <div class="panel">
        <div class="panel-heading bg-teal-400">
            <h6 class="panel-title">
                <span class="text-danger">*</span> Banner Image
            </h6>
        </div>
        <div class="panel-body">
            <div class="margin-10 margin-left-20">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <div class="col-lg-8">
                                <span id="file-error" class="help-block error-help-block text-danger"></span>
                                <div class="fileupload fileupload-new input-group" data-provides="fileupload">
                                    <div class="form-inline">
                                        <div class="fileupload fileupload-new input-group" data-provides="fileupload">
                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file text-primary">
                                                    <span class="fileupload-new"><i class="glyphicon glyphicon-camera"></i>&nbsp;&nbsp;Select file</span>
                                                    <span class="fileupload-exists">Change</span>
                                                    <input type="file" name="thumb" id="thumb" accept="image/*">
                                                </span>
                                            </span>
                                            <span class="input-group-btn input-group-btn-fileupload">
                                                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                                            </span>
                                        </div>
                                    </div>
                                    <p id="image-format" class="form-control-static text-grey">Only .jpg or .png allowed</p>
                                    <input id="thumb_tmp" type="hidden" name="thumb_tmp" value="{{ isset($bannerData['image_url'])? $bannerData['image_url'] : ''  }}"> 
                                    <input id="" type="hidden" name="thumb_old" value="{{ isset($bannerData['image_url'])? $bannerData['image_url'] : ''  }}">  
                                    <div class="fileupload-new thumbnail">
                                        @if(isset($bannerData))
                                            <img class="thumb_old" src="{{ $bannerData['image_url'] }}">
                                        @else
                                            <img src="{{ URL::asset('/assets/images/no-img.png') }}" alt=""/>
                                        @endif
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>