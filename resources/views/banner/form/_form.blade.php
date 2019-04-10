<form id="form-submit" class="form-horizontal" autocomplete="off"  enctype="multipart/form-data">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="col-lg-8">
                            <label class="control-label col-lg-3 text-left">
                                <span class="text-danger">*</span> Banner Name :
                            </label>
                            <div class="col-lg-9">
                                <input class="form-control content-name" name="banner_name" value="{{ isset($bannerId)? $bannerData['name'] : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-8">
                            <label class="control-label col-lg-3 text-left">
                                <span class="text-danger">*</span> Hyperlink :
                            </label>
                            <div class="col-lg-9">
                                <input class="form-control content-name" name="redirect_url" value="{{ isset($bannerId)? $bannerData['redirect_url'] : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-8">
                            <label class="control-label col-lg-3 text-left">
                                <span class="text-danger">*</span> Position :
                            </label>
                            <div class="col-lg-9">
                                <select class='form-control' name='position' {{ isset($bannerData['position'])? 'readonly' : '' }}>
                                    <option 
                                        value='NO_POSITION'
                                        {{ (isset($bannerData['position'])&&($bannerData['position']=='NO_POSITION'))? 'selected' : '' }}
                                        {{ (isset($bannerData['position'])&&($bannerData['position']!='NO_POSITION'))? 'disabled' : '' }}
                                        > NO POSITION 
                                    </option>
                                    <option 
                                        value='A1' 
                                        {{ (isset($bannerData['position'])&&($bannerData['position']=='A1'))? 'selected' : '' }}
                                        {{ (isset($bannerData['position'])&&($bannerData['position']!='A1'))? 'disabled' : '' }}
                                        > A1 
                                    </option>
                                    <option 
                                        value='A2' 
                                        {{ (isset($bannerData['position'])&&($bannerData['position']=='A2'))? 'selected' : '' }}
                                        {{ (isset($bannerData['position'])&&($bannerData['position']!='A2'))? 'disabled' : '' }}
                                        > A2 
                                    </option>
                                    <option 
                                        value='A3' 
                                        {{ (isset($bannerData['position'])&&($bannerData['position']=='A3'))? 'selected' : '' }}
                                        {{ (isset($bannerData['position'])&&($bannerData['position']!='A3'))? 'disabled' : '' }}
                                        > A3 
                                    </option>
                                    <option 
                                        value='A4' 
                                        {{ (isset($bannerData['position'])&&($bannerData['position']=='A4'))? 'selected' : '' }}
                                        {{ (isset($bannerData['position'])&&($bannerData['position']!='A4'))? 'disabled' : '' }}
                                        > A4 
                                    </option>
                                    <option 
                                        value='A5' 
                                        {{ (isset($bannerData['position'])&&($bannerData['position']=='A5'))? 'selected' : '' }}
                                        {{ (isset($bannerData['position'])&&($bannerData['position']!='A5'))? 'disabled' : '' }}
                                        > A5 
                                    </option>
                                    <option 
                                        value='A6' 
                                        {{ (isset($bannerData['position'])&&($bannerData['position']=='A6'))? 'selected' : '' }}
                                        {{ (isset($bannerData['position'])&&($bannerData['position']!='A6'))? 'disabled' : '' }}
                                        > A6 
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-offset-3 col-lg-9">
                                <a target='_blank' href='/banner/position/position-banner'>Show available positions</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-8">
                            <label class="control-label col-lg-3 text-left">
                                <span class="text-danger">*</span> Target :
                            </label>
                            <div class="col-lg-9">
                                <?php echo Form::select('target', [
                                    ''          => 'SELECT TARGET',
                                    '_blank'    => 'Open in new window' ,
                                    '_self'     => 'Replace the current page content'
                                ], isset($bannerData['target']) ? $bannerData['target'] : '', [
                                    'class' => 'form-control'
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
                @include('common._slug', [
                    'slug' => isset($bannerData['slug']) ? $bannerData['slug'] : '',
                    'readOnly' => isset($bannerData['slug']) ? true : false
                    ]
                )
            </div>

            <div class="row">
                <div class="col-lg-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {{ Html::ul($errors->all()) }}
                        </div>
                    @endif
                </div>

                @include('banner.form.banner')

                <div class="col-lg-12">
                    <div class="pull-right">
                        <div class="form-group">
                            {{ Form::button('<i class="icon-checkmark"></i> Save', [ 'type' => 'submit', 'class' => 'btn bg-primary-800 btn-raised btn-submit' ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>