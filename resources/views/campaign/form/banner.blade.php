<div class="col-lg-12">
    <div class="panel">
        <!-- Header panel -->
        <div class="panel-heading bg-teal-400">
            <h6 class="panel-title">Banner</h6>
        </div>
        <!-- End Header panel -->
        <!-- Tags panel -->
        <div class="panel-body">
            <div class="margin-10 margin-left-20">
                <div class="row">
                    <a target='_blank' href='/campaign/position/position-campaign'>Show available positions</a>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="control-label">
                                Banner Campaign Page (A)
                            </label>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail">
                                    <input id="" type="hidden" name="thumb_old" value="{{ isset($campaignData['bannerA'])? $campaignData['bannerA'] : ''  }}"> 
									@if(isset($campaignData['bannerA']) && !empty($campaignData['bannerA']))
                                    <img class="thumb_old" src="{{ $campaignData['bannerA'] }}" alt="" /> 
									@else
                                    <img class="thumb_old" src="{{ URL::asset('/assets/images/no-img.png') }}" alt="" /> 
									@endif
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                <div>
                                    <span class="btn btn-info btn-file">
									<span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                                    <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                    <input type="file" name="thumb" id="thumb" accept="image/*">
                                    </span>
                                    <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                        <i class="fa fa-times"></i> Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="control-label">
                                Banner Campaign Page (B)
                            </label>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail">
                                    <input id="" type="hidden" name="thumb_old2" value="{{ isset($campaignData['bannerB'])? $campaignData['bannerB'] : ''  }}"> 
									@if(isset($campaignData['bannerB']) && !empty($campaignData['bannerB']))
                                    <img class="thumb_old2" src="{{ $campaignData['bannerB'] }}" alt="" /> 
									@else
                                    <img class="thumb_old2" src="{{ URL::asset('/assets/images/no-img.png') }}" alt="" />
									@endif
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail"></div>
                                <div>
                                    <span class="btn btn-info btn-file">
									<span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span>
                                    <span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                    <input type="file" name="thumb2" id="thumb2" accept="image/*">
                                    </span>
                                    <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                        <i class="fa fa-times"></i> Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="margin-top-10">
            </div>
        </div>
        <!-- Edit icon -->
        <!-- <div class="text-right">
            <i class="glyphicon glyphicon-pencil"></i>
        </div> -->
        <!-- End Edit icon -->
    </div>
    <!-- End Tags panel -->
</div>
