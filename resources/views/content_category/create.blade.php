<?php
$scripts = [
    'nestable',
    'sweetalert',
    'multi'
];
?>

@extends('layouts.main')

@section('title', 'Content Category')

@section('breadcrumb')
    <li><a href="/content_category">Content Category</a></li>
    <li class="active">Add Content Category</li>
@endsection

@section('header_script')@endsection

@section('content')
@include('content_category._form')
@endsection

@section('footer_script')
    <script type="text/javascript" src="/assets/js/plugins/forms/tags/tagsinput.min.js"></script>
    <script type="text/javascript" src="/assets/js/plugins/forms/tags/tokenfield.min.js"></script>
    <script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="/assets/js/core/libraries/jquery_ui/full.min.js"></script>

    @include('common._seo_script')
    @include('common._validate_form_script')
    <script type="text/javascript">
        var form = $('#form-submit');
        var url = '/content_category';
        var httpMethod = 'POST';
        var successCallback = function () {
            window.location = '/content_category/{{ $parent_id }}';
        }
        $(".switch").bootstrapSwitch();
        validateAndSubmit(form, url, httpMethod, successCallback);
    </script>

    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\ContentCategoryRequest', '#form-submit') !!}
@endsection
