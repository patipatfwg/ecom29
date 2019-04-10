<form id="form-submit" class="form-horizontal" autocomplete="off">

    <!-- Input hidden field -->
    @if(isset($contentId))
    <input type="hidden" name="id" value="{{ $contentId }}">
    <input type="hidden" id="selected-category-id" name="category_id" value="{{ !empty($contentCategory['category_id'])? $contentCategory['category_id'] : 'undefine' }}">
    <input type="hidden" name="priority" value="{{ $contentDetail['priority'] }}"> 
    @else
    <input type="hidden" id="selected-category-id" name="category_id" value="undefine">
    @endif

    @if(!empty($contentCategory['id']))
    <input type="hidden" name="content_category_id" value="{{ $contentCategory['id'] }}">
    @endif
    <!-- End Input hidden field -->

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="col-lg-8"></div>
                            <!-- Switch -->
                            @include('common._switch', [ 'status' => isset($contentDetail) ? $contentDetail['status'] : 'inactive'])
                            <!-- End Switch -->
                    </div>
                </div>
                <!-- Tab container -->
                <div class="col-lg-12">
                
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        {{ Html::ul($errors->all()) }}
                    </div>
                    @endif

                    <!-- Tab panel -->
                    <div class="tabbable">

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
                                    @include('content.form.name')
                                    <!-- End Name panel -->

                                    <!-- Description panel -->
                                    @include('content.form.description')
                                    <!-- End Description panel-->

                                    <!-- Tag panel -->
                                    @include('content.form.tag')
                                    <!-- End Tag panel -->

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

                <!-- Slug -->
                <div class="col-lg-12">
                    @include('common._slug',['slug' => isset($contentDetail['slug'])? $contentDetail['slug'] : ''])
                </div>
                <!-- End Slug -->

                <div class="col-lg-12">
                    <div class="form-group">
                        <!-- Date time picker -->
                        @include('content.form.datetime')
                        <!-- End Date time picker -->

                    </div>
                </div>
                <!-- End Category drop-down -->

                <!-- Seo panel -->
                <div class="col-lg-12">
                @include('common._seo', [
                    'subject'     => isset($contentDetail['seo_subject']) ? $contentDetail['seo_subject'] : '',
                    'explanation' => isset($contentDetail['seo_explanation']) ? $contentDetail['seo_explanation'] : '',
                    'slug'        => isset($contentDetail['slug']) ? $contentDetail['slug'] : ''
                ])
                </div>
                <!-- End Seo panel -->

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