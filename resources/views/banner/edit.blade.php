<?php
    $scripts = [
        'nestable',
        'sweetalert',
        'datetimepicker',
        'bootstrap-select',
        'ckeditor',
        'to-markdown',
        'showdown',
        'multi',
        'select2',
        'inputupload'
    ];
?>

@extends('layouts.main')

@section('title','Banner Detail')

@section('breadcrumb')
    <li><a href="/banner">Banner</a></li>
    <li class="active">{{ isset($bannerId)? ''.$bannerData['slug'] : 'Create' }}</li>
@endsection

@section('header_script')
@endsection
    
@section('content')
    @include('banner.form._form')
@endsection

@section('footer_script')

    {!! Html::script('js/contents/datetime_picker.js') !!}

    {!! Html::script('assets/js/plugins/forms/tags/tagsinput.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/tags/tokenfield.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    {!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}
    
    @if(!isset($bannerData['slug']))
        @include('common._slug_script')
    @endif
    @include('banner._footer_script')

@endsection
