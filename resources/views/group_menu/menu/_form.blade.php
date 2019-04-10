<form id="form-submit" class="form-horizontal" autocomplete="off">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <input type="hidden" name="group_id" value={{$id}}>
                        <input type="hidden" name="hilight_id" value="{{isset($hilight_id) ? $hilight_id : ''}}">
                        <input type="hidden" name="priority" value="{{isset($groupHilightData['priority']) ? $groupHilightData['priority'] : 99}}">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-lg-8">

                                </div>
                                <!-- Switch -->
                                @include('common._switch', [ 'status' => isset($groupHilightData['status']) ? $groupHilightData['status'] : 'inactive' ])
                                <!-- End Switch -->
                            </div>
                        </div>
                    </div>
                    <div class="form-group">

                        <!-- Tab container -->
                        <div class="col-lg-12">

                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                {{ Html::ul($errors->all()) }}
                            </div>
                            @endif

                            <!-- Tab panel -->
                            <div class="tabbable panel">

                                <!-- Tab menu -->
                                <ul class="nav nav-tabs bg-teal-400 nav-justified">
                                    @foreach($language as $lang)
                                    <li class="{{ $lang == $language[0] ? 'active' : '' }}">
                                        <a href="#tab-panel-{{ $lang }}" data-toggle="tab">{{ trans('form.title.'.$lang) }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                                <!-- End Tab menu -->

                                <!-- Tab content -->
                                <div class="tab-content">
                                    @foreach($language as $lang)
                                    <div class="tab-pane fade {{ $lang == $language[0] ? 'in active' : '' }}" id="tab-panel-{{ $lang }}">
                                        <div class="table-responsive">

                                            <!-- Name panel -->
                                            @include('group_menu.form.name')
                                            <!-- End Name panel -->

                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <!-- End Tab content -->

                            </div>
                            <!-- End Tab panel -->

                        </div>
                        <!-- End Tab container -->

                    </div>
                </div>
                <div class="col-lg-12" id="Type">
                    <div class="col-lg-12 margin-bottom-20">
                        <div class="form-group">
                            <label class="control-label col-lg-3 text-left">
                                <span class="text-danger">*</span> Type :
                            </label>
                            <div class="col-lg-4">
                                @include('common._select', [
                                    'data' => [
                                        'link_external'     => 'External Link',
                                        'link_internal'     => 'Internal Link',
                                        'banner'            => 'Banner',
                                        'campaign'          => 'Campaign',
                                        'business_category' => 'Business Category',
                                        'product_category'  => 'Product Category',
                                        'content'           => 'Content'
                                    ],
                                    'hasPlaceholder' => true, 
                                    'name' => 'type',
                                    'id' => 'select-type', 
                                    'value' => isset($groupHilightData["type"]) ? $keyType[$groupHilightData["type"]] : null
                                ])
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Switch -->
                                    
                @include('group_menu.menu.collapseRadio', ['selectedType' => isset($groupHilightData["type"]) ? $groupHilightData["type"] : null])

                <!-- End Switch -->
                <div class="col-lg-12" id="Target">
                    <div class="col-lg-12 margin-bottom-20">
                        <div class="form-group">
                            <label class="control-label col-lg-3 text-left">
                                <span class="text-danger">*</span> Target :
                            </label>
                            <div class="col-lg-4">
                                @include('common._select', [
                                    'data' => [
                                        '_blank'    => 'Open in new window' ,
                                        '_self'     => 'Replace the current page content'
                                    ], 
                                    'hasPlaceholder' => true,
                                    'id' => 'select-target', 
                                    'name' => 'target', 
                                    'value' => isset($groupHilightData['target']) ? $keyTarget[$groupHilightData['target']] : ''
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form footer panel -->
                <div class="col-lg-12">
                    <div class="pull-right">
                        <div class="form-group">
                            {{ Form::button('<i class="icon-checkmark"></i> Save', [ 'type' => 'submit', 'class' => 'btn bg-primary-800 btn-raised btn-submit' ]) }}
                        </div>
                    </div>
                </div>
                <!-- End Form footer panel -->
            </div>
        </div>
    </div>
</form>