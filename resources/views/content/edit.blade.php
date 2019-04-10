<?php
    $scripts = [
        'nestable',
        'sweetalert',
        'datetimepicker',
        'select2',
        'ckeditor',
        'to-markdown',
        'showdown'
    ];
?>

@extends('layouts.main')

@section('title','Content Detail')

@section('breadcrumb')
    <li><a href="/content">Content</a></li>
    <li class="active">{{ isset($contentId)? ''.$contentDetail['slug'] : 'Create' }}</li>
@endsection

@section('header_script')
@endsection
    
@section('content')
    @include('content.form._form')
@endsection

@section('footer_script')

    {!! Html::script('js/contents/datetime_picker.js') !!}

    {!! Html::script('assets/js/plugins/forms/tags/tagsinput.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/tags/tokenfield.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    {!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}

    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-create') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-update') !!}

    @include('common._seo_script')
    @include('content._footer_script')
    @include('common._datetime_range_script', [
        'format_start' => 'd/m/Y 00:00:00',
        'format_end' => 'd/m/Y 23:59:59',
        'refer_start' => '#start_date',
        'refer_end' => '#end_date',
        'editable'  => true
    ])
@endsection