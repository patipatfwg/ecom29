<form id="form-submit" class="form-horizontal" autocomplete="off" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    @if(isset($brand_id))
    <input type="hidden" name="brand_id" value="{{ $brand_id }}">
    <input type="hidden" name="priority" value="{{ $brand['priority'] }}">
    <input type="hidden" name="_method" value="PUT"/>
    @endif

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {{ Html::ul($errors->all()) }}
                        </div>
                    @endif

                    <!-- Start: Status toggle -->
                    <div class="row">
                        <div class="col-lg-12 margin-bottom-20">
                            <div class="col-lg-8"></div>
                            <!-- Switch -->
                            @include('common._switch', [ 'status' => isset($brand['status']) ? $brand["status"] : 'inactive'])
                            <!-- End Switch -->
                        </div>
                    </div>
                    <!-- End: Status toggle -->

                    <!-- Start: Tab panel -->
                    <div class="tabbable">

                        <!-- Start: Tab menu -->
                        <ul class="nav nav-tabs bg-teal-400 nav-justified">
                            @foreach($language as $lang)
                            <li class="{{ $lang == $language[0] ? 'active' : '' }}">
                                <a href="#tab-panel-{{ $lang }}" data-toggle="tab">{{ trans('form.title.'.$lang) }}</a>
                            </li>
                            @endforeach
                        </ul>
                        <!-- End: Tab menu -->

                        <!-- Tab content -->
                        <div class="tab-content">
                            @foreach($language as $lang)
                            <div class="tab-pane fade {{ $lang == $language[0] ? 'in active' : '' }}" id="tab-panel-{{ $lang }}">
                                <div class="table-responsive">

                                    <!-- Name panel -->
                                    @include('brand.form.name',[
                                        'language' => $lang,
                                        'name' => isset($brand['name'][$lang])? $brand['name'][$lang] : ''
                                    ])
                                    <!-- End Name panel -->
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- End Tab content -->

                    </div>
                    <!-- End: Tab panel -->
            
                    <div class="col-lg-12">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8">
                            <a target='_blank' href='/brand/position/position-brand'>Instruction/guideline for upload images</a>
                        </div>
                    </div>
                    
                    <!-- Start: Banner Brand Panel -->
                    @include('brand.form._image',[
                        'label' => 'Banner (Small)',
                        'input_name' => 'images[thumb]',
                        'type' => 'thumb',
                        'input_file_name' => 'banner_small_image',
                        'image' => isset($brand['images']['thumb']['image'])? $brand['images']['thumb']['image'] : null,
                        'url' => !empty($brand['images']['thumb']['url'])? $brand['images']['thumb']['url'] : '',
                        'width' => 178,
                        'height' => 70
                    ])
                    <!-- End: Banner Brand Panel -->

                    <!-- Start: Brand Page A Panel -->
                    @include('brand.form._image',[
                        'label' => 'Banner Brand Page (A)',
                        'input_name' => 'images[A]',
                        'type' => 'A',
                        'input_file_name' => 'banner_pageA_image',
                        'image' => isset($brand['images']['A']['image'])? $brand['images']['A']['image'] : null,
                        'url' => !empty($brand['images']['A']['url'])? $brand['images']['A']['url'] : '',
                        'width' => 1140,
                        'height' => 380
                    ])
                    <!-- End: Brand Page A Panel -->

                    <!-- Start: Brand Page B Panel -->
                    @include('brand.form._image',[
                        'label' => 'Banner Brand Page (B)',
                        'input_name' => 'images[B]',
                        'type' => 'B',
                        'input_file_name' => 'banner_pageB_image',
                        'image' => isset($brand['images']['B']['image'])? $brand['images']['B']['image'] : null,
                        'url' => !empty($brand['images']['B']['url'])? $brand['images']['B']['url'] : '',
                        'width' => 285,
                        'height' => 380
                    ])
                    <!-- End: Brand Page B Panel -->
                    <!-- Slug -->
                    @include('common._slug', ['slug' => isset($brand['slug']) ? $brand['slug'] : ''])
                    <!-- End Slug -->
                    <!-- Seo panel -->
                    @include('common._seo' , [
                        'subject'     => isset($brand['seo_subject']) ? $brand['seo_subject'] : '' ,
                        'explanation' => isset($brand['seo_explanation']) ? $brand['seo_explanation'] : '',
                        'slug'        => isset($brand['slug']) ? $brand['slug'] : ''
                    ])
                    <!-- End Seo panel -->

                    <!-- Start: Save button -->
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
                    <!-- End: Save button -->

                </div>
            </div>
        </div>
    </div>
</form>