<?php
$scripts = [
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

@section('title', 'Store Detail')

@section('breadcrumb')
<li><a href="{{ url('/store') }}">Store</a></li>
<li class="active">{{$store['makro_store_id']}}</li>
@endsection

@section('header_script')@endsection

@section('content')

 @include('store.form._form')
 
 
@endsection

@section('footer_script')
{!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
@include('common._validate_form_script')
@include('common._call_ajax')
@include('common._dropdown_right_script')
@include('store.form._footer_script')
@endsection