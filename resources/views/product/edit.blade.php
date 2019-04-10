<?php
$scripts = [
	'angular',
    'datatables',
    'nestable',
    'sweetalert',
    'multi',
    'select2',
	'inputupload',
	'dropzone',
    'ckeditor',
    'sortable',
    'datetimepicker',
    'to-markdown',
    'showdown',
    'uniform',
    'iCheck'
];
?>

@extends('layouts.main')
@section('title', 'Product Detail')
@section('breadcrumb')
<li><a href="{{ url('/product') }}">Product</a></li>
<li class="active">{{$item_id}}</li>
@endsection
@section('header_script')
	{{ Html::style('assets/css/dropdown.custom.css') }}
@endsection
@section('content')

<div ng-app="productApp">
    <div ng-controller="productController">
        <form id="form-submit" class="form-horizontal" autocomplete="off" method="PUT">
            <input type="hidden" name="productId" id="productId" value="{{ $product_id }}">
            <input type="hidden" name="language" id="language" value="{{app()->getLocale()}}">
            <input type="hidden" name="item_id" value="{{ $productIntermediateData['item_id'] }}">
            <input type="hidden" name="location" value="{{ $productIntermediateData['location'] }}">
            <input type="hidden" name="published_status" value="{{ $productIntermediateData['published_status'] }}">
            <input type="hidden" name="product_intermediate_data_old" value="{{ $productIntermediateData_old }}">
            @foreach($language as $lang)
            <input type="hidden" id="description_old_{{$lang}}" name="description_old[{{$lang}}]">
            @endforeach

            @include('product.compareRms')

            @include('product.compareOnline')

            @include('product.compareStatus')

            @include('product.comparePricing')

            @include('product.compareSize')

			<div id="brand">
                @include('product.productBrand')
            </div>
            <div id="categories">
                @include('product.businessCategory')
            </div>
            <div id="categories">
                @include('product.productCategory')
            </div>
			<div id="attribute">
                @include('product.attribute')
            </div>

            <div id="attribute">
                @include('common._images_upload')
            </div>

            <input type="hidden" id="setStatus" name="setStatus" value="">

            <div class="pull-right">
                <div class="col-lg-12">
                    <div class="form-group">

                        {{ Form::button('<i class="icon-checkmark"></i> Save', [
                        'name' => 'editing',
                        'class' => 'btn bg-primary-800 btn-raised btn-save'
                        ]) }}

                        {{ ($productIntermediateData['approve_status']!='approved')? Form::button('<i class="icon-checkmark"></i> Ready to Approve', [
                        'name' => 'ready',
                        'class' => 'btn bg-primary-800 btn-raised btn-ready',
                        'style' => 'margin-left:20px;'
                        ]) : '' }}

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('footer_script')
    {!! Html::script('assets/js/plugins/forms/tags/tagsinput.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/tags/tokenfield.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    {!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}

    @include('product._footer_script')
    @include('product._script_autocomplete')
    @include('common._dropdown_script')
    @include('common._seo_script',[
        'id' => 'productIntermediate'
    ])
    @include('common._datetime_range_script',[
        'format_start' => 'd/m/Y 00:00:00',
        'format_end'   => 'd/m/Y 23:59:59',
        'refer_start'  => '#start_date-online',
        'refer_end'    => '#end_date-online',
        'timefixed'    => false
    ])
    @include('common._datetime_range_script',[
        'format_start' => 'd/m/Y 00:00:00',
        'format_end'   => 'd/m/Y 23:59:59',
        'refer_start'  => '#start_date-intermediate',
        'refer_end'    => '#end_date-intermediate',
        'timefixed'    => false
    ])

    <script type="text/javascript">
    $(".switch").bootstrapSwitch();
    </script>
@endsection