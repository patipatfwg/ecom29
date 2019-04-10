<?php
$scripts = [
    'nestable',
    'sweetalert',
    'datetimepicker',
    'bootstrap-select',
    'select2'
];
?>

@extends('layouts.main')

@section('title','Group Menu - Items')

@section('breadcrumb')
    <li><a href="/group_menu">Group Menu</a></li>
    <li><a href="/group_menu/{{$id}}/content?title={{$title}}">{{$title}}</a></li>
    <li class="active">{{ isset($hilight_id)?  $groupHilightData['name']['en'] : 'Add Menu' }}</li>
@endsection

@section('header_script')
    {{ Html::style('assets/css/dropdown.custom.css') }}
@endsection

@section('content')
    @include('group_menu.menu._form')
@endsection

@section('footer_script')

    {!! Html::script('js/contents/datetime_picker.js') !!}

    {!! Html::script('assets/js/plugins/forms/tags/tagsinput.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/tags/tokenfield.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    {!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}

    @include('group_menu.menu._footer_script')

@endsection