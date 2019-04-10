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
    'inputupload'
];
?>

@extends('layouts.main')

@section('title','Campaign Detail')

@section('breadcrumb')
    <li><a href="/campaign">Campaign</a></li>
    <li class="active">{{ isset($campaignData['id'])? $campaignData['slug'] : 'Create' }}</li>
@endsection

@section('header_script')
@endsection

@section('content')
    @include('campaign.form._form')
@endsection

@section('footer_script')

    @include('common._datetime_range_script', [
        'format_start'  => 'd/m/Y 00:00:00',
        'format_end'    => 'd/m/Y 23:59:59',
        'refer_start'   => '#start_date',
        'refer_end'     => '#end_date',
        'start_minDate' => date('d/m/Y 00:00:00', strtotime('+1 day')),
        'editable'      => true,
        'timefixed'     => false
    ])

    {!! Html::script('assets/js/plugins/forms/tags/tagsinput.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/tags/tokenfield.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    {!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}

    @include('common._slug_script', [
        'slug_input_name' => 'name_en'
    ])
    @include('common._seo_script')
    @include('campaign._footer_script')

    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-create') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-update') !!}

@endsection
