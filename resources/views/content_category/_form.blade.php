<form id="form-submit" class="form-horizontal" autocomplete="off">

    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
    <input type="hidden" name="category_id" value="{{ $category_id or ''}}">
    <input type="hidden" name="priority" value="{{ $category['priority'] or ''}}">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {{ Html::ul($errors->all()) }}
                        </div>
                    @endif

                    <div class="tabbable">
                        <ul class="nav nav-tabs bg-teal-400 nav-justified">
                            <?php $i = 1; ?>
                            @foreach($language as $value)
                                <li class="{{ $i == 1 ? 'active' : '' }}">
                                    <a href="#highlighted-justified-tab{{ $i }}" data-toggle="tab">{{ $value }}</a>
                                </li>
                                <?php $i++; ?>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            <?php $i = 1; ?>
                            @foreach($language as $value)
                                <div class="tab-pane {{ $i == 1 ? 'active' : '' }}" id="highlighted-justified-tab{{ $i }}">
                                    <!-- {{ $value }} -->
                                    <div class="table-responsive">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="input[{{ $value }}][name_{{ $value }}]">
                                                    <span class="text-danger">*</span> {{ trans_append_language('Category Name',$value) }}
                                                </label>
                                                <input type="text" class="form-control" name="input[{{ $value }}][name_{{ $value }}]" value="<?php echo isset($category['name']) ? $category['name'][$value] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="form-group">

                                <div class="col-lg-8">

                                </div>

                                <!-- Switch -->
                                @include('common._switch', [ 'status' => isset($category['status']) ? $category['status'] : 'inactive' ])
                                <!-- End Switch -->

                            </div>
                        </div>

                        <!-- Seo -->
                        @include('common._seo', [
                            'subject'     => isset($category['seo_subject'])? $category['seo_subject'] : '',
                            'explanation' => isset($category['seo_explanation'])? $category['seo_explanation'] : '',
                            'slug'        => isset($category['slug']) ? $category['slug'] : ''
                        ])
                        <!-- End Seo -->

                    <br/>

                    <div class="pull-right">
                        <div class="col-lg-12">
                            <div class="form-group">
                            {{ Form::button('<i class="icon-checkmark"></i> Save', [
                                'type'  => 'submit',
                                'class' => 'btn bg-primary-800 btn-raised btn-submit'
                            ]) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
