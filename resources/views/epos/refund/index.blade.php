<?php $scripts = [
    'datatables',
    'datatablesFixedColumns',
    'datatablesButtons',
    'datetimepicker',
    'sweetalert']; ?>

@extends('layouts.epos.main')

@section('title', 'Refund List Search')

@section('breadcrumb')
    <li class="active">Refund List Search</li>
@endsection

@section('header_script')@endsection

@section('content')

    @include('epos.refund.form_search')

    @include('epos.refund.search_result')

@endsection

@section('footer_script')
    @include('common._datetime_range_script', [
        'format_start' => 'd/m/Y',
        'format_end' => 'd/m/Y',
        'refer_start' => '#start_date',
        'refer_end' => '#end_date',
        'editable' => true
    ])
    {{--@include('common._datetime_range_script', [--}}
        {{--'format_start' => 'Y-m-d',--}}
        {{--'format_end' => 'Y-m-d',--}}
        {{--'refer_start' => '#start_last_login_date',--}}
        {{--'refer_end' => '#end_last_login_date'--}}
    {{--])--}}
@endsection