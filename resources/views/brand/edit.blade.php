<?php
$scripts = [
    'nestable',
    'sweetalert',
    'multi',
    'inputupload'
];

?>

@extends('layouts.main')

@section('title', 'Brand')

@section('breadcrumb')
    <li><a href="/brand">Brand</a></li>
    <li class="active">{{ $brand_id}}</li>
@endsection

@section('header_script')@endsection

@section('content')
    @include('brand.form._form')
@endsection

@section('footer_script')
    @include('brand._footer_script',['appUrl' => '/brand/'.$brand_id , 'redirectUrl' => '/brand/'.$brand_id.'/edit'])
    @include('common._slug_script',['slug_input_name' =>'name[en]'])
    {{-- @include('common.check_sizeImage',['form_name' =>'form-submit']) --}}
    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
@endsection
