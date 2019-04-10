<?php
$scripts = [
    'nestable',
    'sweetalert',
    'select2',
    'datatables',
    'datetimepicker',
    'datatablesFixedColumns'
];
?>

@extends('layouts.main')

@section('title', 'Group Menu Detail')

@section('breadcrumb')
<li><a href="/group_menu">Group Menu</a></li>
<li class="active">{{ $groupmenuData['slug'] }}</li>
@endsection

@section('header_script')@endsection

@section('content')
    @include('group_menu.form._form')
@endsection

@section('footer_script')
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    
        @include('group_menu._footer_script')
    
   
    @include('common._validate_form_script')
    @if($editAble)
        @include('common._slug_script',['slug_input_name' => 'title_en'])
    @endif
@endsection