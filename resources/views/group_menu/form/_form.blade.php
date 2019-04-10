<form id="form-submit" class="form-horizontal" autocomplete="off">

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <input type="hidden" name="content_type" value=null>
                        <input type="hidden" name="group_id" value="{{isset($group_id)? $group_id : ''}}">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-lg-8">

                                </div>
                                <!-- Switch -->
                                @include('common._switch', [ 'status' => isset($groupmenuData['status']) ? $groupmenuData['status'] : 'inactive' ])
                                <!-- End Switch -->
                            </div>
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
                                    @include('group_menu.form.title')
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
                @include('common._slug', [
                    'slug' => isset($groupmenuData['slug']) ? $groupmenuData['slug'] : '',
                    'readOnly' => !$editAble
                ])
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-right">
                            @include('common._submit_button')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>