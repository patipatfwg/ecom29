<?php
$scripts = [
    'nestable',
    'sweetalert',
    'multi',
    'inputupload'
];

?>

@extends('layouts.main')

@section('title', 'Business Category Detail')

@section('breadcrumb')
    <li><a href="/category_business">Business Category</a></li>
    @if( ! empty($breadcrumb))
        @foreach($breadcrumb as $value)
        <li class="active"><a href="/category_business/{{ $value['id'] }}">{{ $value['name'] }}</a></li>
        @endforeach
    @endif
    <li class="active">{{ $category_id }}</li>
@endsection

@section('header_script')@endsection

@section('content')
<form id="form-submit" method="post" action="/category_business/{{ $category_id }}" enctype="multipart/form-data" class="form-horizontal" autocomplete="off">
    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
    <input type="hidden" name="category_id" value="{{ $category_id }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="priority" value="{{ $category['priority'] }}">
    <input type="hidden" name="level" value="{{ $category['level'] }}">

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <!-- @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {{ Html::ul($errors->all()) }}
                        </div>
                    @endif -->
                    <div class="row">
                        <div class="col-lg-8"></div>
                        <!-- Switch -->
                        @include('common._switch', [ 'status' => isset($category['status']) ? $category['status'] : null ])
                        <!-- End Switch -->
                    </div>
                    <br />

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
                                                    {{ trans_append_language('Business Category Name',$value) }}<span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="input[{{ $value }}][name_{{ $value }}]" value="{{ $category['name'][$value] }}">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            @endforeach
                        </div>
                    </div>

                    @include('common._slug', [ 'slug' => isset($category['slug']) ? $category['slug'] : ''])

                    <!--<div class="row">
                        <div class="col-lg-12">
                            <h5 class="attribute"><i class="icon-flip-vertical4 position-left"></i> Attribute</h5 class="attribute">
                            <select multiple="multiple" name="attribute[]" id="attribute">
                                @foreach ($attribute as $kData => $vData)
                                    <option {{ (isset($vData['selected']) && $vData['selected'] === true) ? 'selected' : '' }} value="{{$vData['id']}}">
                                        {{ $vData['name']['th'] }}

                                        @if (count($vData['sub_attribute_name']) > 0)
                                            [{{ implode(', ', $vData['sub_attribute_name']) }}]
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="clearfix"></div>
                        </div>
                    </div>-->
                    <br />
                    <!-- Seo -->
                        @include('common._seo' , [
                            'subject'     => isset($category['seo_subject'])? $category['seo_subject'] : 'Subject',
                            'explanation' => isset($category['seo_explanation'])? $category['seo_explanation'] : 'Explanation',
                            'slug'        => isset($category['slug']) ? $category['slug'] : ''
                        ])
                    <!-- End Seo -->
                    <br />
                    <div class="panel">
                        <div class="panel-heading bg-teal-400">
                            <h6 class="panel-title font-bold">Show Level B</h6>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                @include('common._switch', [ 
                                    'status' => $category['is_show_level_b'],
                                    'statusName' => 'Show level B',
                                    'inputName' => 'is_show_level_b',
                                    'onText' => 'Yes',
                                    'offText' => 'No',
                                    'leftLabel' => true
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <a target="_blank" href="/category_business/position/position-category{{ $category['level'] }}">Instruction/guideline for upload images</a>
                        </div>
                    </div>

                    @include('category.category_image._level' . $category['level'])

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
@endsection

@section('footer_script')
    <script type="text/javascript" src="/assets/js/plugins/forms/tags/tagsinput.min.js"></script>
    <script type="text/javascript" src="/assets/js/plugins/forms/tags/tokenfield.min.js"></script>
    <script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="/assets/js/core/libraries/jquery_ui/full.min.js"></script>

    @include('common._seo_script')
    @include('common._call_ajax')
    @include('category.category_image._image_script')
    @include('common._slug_script', ['slug_input_name' =>'input[en][name_en]'])

    <script type="text/javascript">
    $('#attribute').multi();

    var validateData = {
        init: function () {
            var _self = this;
            $('#form-submit').on('click', '.btn-submit', function (e) {
                e.preventDefault();
                _self.validate();
            });
        },
        validate: function () {

            var _self = this;
            var name_th = $("input[name='input[th][name_th]']").val();
            var name_en = $("input[name='input[en][name_en]']").val();

            var formData = new FormData($('#form-submit')[0]);
            $.ajax({
                type: 'POST',
                url: $("meta[name='root-url']").attr('content') + '/category_business/{{ $category_id }}',
                data: formData,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status || data.success) {
                        onAjaxSuccess(data, function() {
                            window.location = '/category_business/{{ $category_id }}/edit';
                        });
                    } else {
                        onAjaxFail(data);
                    }
                },
                error: function(data) {
                    var dataImage = '';
                    $.each(data.responseJSON, function(kData, vData) {
                        dataImage += vData + '\n';
                    });
                    swal('{{ trans('validation.create.fail') }}', dataImage, 'warning');
                }
            });
        }
    }

    $('.switch').bootstrapSwitch();
    validateData.init();
    </script>

    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-create') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-update') !!}

@endsection