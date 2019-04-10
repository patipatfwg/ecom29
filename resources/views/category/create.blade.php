<?php
$scripts = [
    'nestable',
    'sweetalert',
    'multi',
    'inputupload'
];
?>

@extends('layouts.main')

@section('title', 'Product Category Detail')

@section('breadcrumb')
    <li><a href="/category">Product Category</a></li>
    @if( ! empty($breadcrumb))
        @foreach($breadcrumb as $value)
        <li class="active"><a href="/category/{{ $value['id'] }}">{{ $value['name'] }}</a></li>
        @endforeach
    @endif
    <li class="active">Create</li>
@endsection

@section('header_script')@endsection

@section('content')

    <form id="form-submit" action="/category/create" method="post" class="form-horizontal" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="level" value="{{ $level }}">

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
                            @include('common._switch', [ 'status' => 'inactive' ])
                            <!-- End Switch -->
                        </div>
                        <br />
                        <div class="tabbable">
                            <ul class="nav nav-tabs bg-teal-400 nav-justified">
                                <?php $i = 1; ?>
                                @foreach($language as $lang)
                                    <li class="{{ $i == 1 ? 'active' : '' }}">
                                        <a href="#highlighted-justified-tab{{ $i }}"
                                           data-toggle="tab">{{ trans('form.title.'.$lang) }}</a>
                                    </li>
                                    <?php $i++; ?>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                <?php $i = 1; ?>
                                @foreach($language as $lang)
                                    <div class="tab-pane {{ $i == 1 ? 'active' : '' }}"
                                         id="highlighted-justified-tab{{ $i }}">
                                        <div class="table-responsive">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="input[{{ $lang }}][name_{{ $lang }}]">
                                                        {{ trans_append_language('Product Category Name',$lang) }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control"
                                                           name="input[{{ $lang }}][name_{{ $lang }}]">
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                @endforeach
                            </div>
                        </div>

                        <br />
                        @include('common._slug', [ 'slug' => isset($category['slug']) ? $category['slug'] : ''])
                        <!--<div class=" row">
                            <div class="col-lg-12">
                                <h5 class="attribute"><i
                                            class="icon-flip-vertical4 position-left"></i> Attribute
                                </h5 class="attribute">
                                <select multiple="multiple" name="attribute[]" id="attribute">
                                    @foreach ($attribute as $kData => $vData)
                                        <option value="{{$vData['id']}}">
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
                                'subject' => isset($category['seo_subject'])? $category['seo_subject'] : '' ,
                                'explanation' => isset($category['seo_explanation'])? $category['seo_explanation'] : '' 
                            ])
                        <!-- End Seo -->

                        <br/>

                        <div class="panel">
                        <div class="panel-heading bg-teal-400">
                            <h6 class="panel-title font-bold">Show Level B</h6>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                @include('common._switch', [ 
                                    'status' => isset($category['is_show_level_b']) ? $category['is_show_level_b'] : "Y",
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
                                <a target='_blank' href='/category/position/position-category{{ $level }}'>Instruction/guideline for upload images</a>
                            </div>
                        </div>
                        
                        @include('category.category_image._level' . $level)

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
                    url: $("meta[name='root-url']").attr('content') + '/category/create',
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.status || data.success) {
                            onAjaxSuccess(data, function() {
                                window.location = data.pathUrl;
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

        $(".switch").bootstrapSwitch();
        validateData.init();
    </script>

    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-create') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-update') !!}
@endsection