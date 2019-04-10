<?php
$scripts = [
    'select2',
    'datetimepicker',
    'sweetalert',
    'to-markdown',
];
?>

@extends('layouts.main')

@section('title', 'Coupon')

@section('breadcrumb')
    <li><a href="/coupon">Coupon</a></li>
    <li class="active">{{ ($coupon['coupon_code'])? $coupon['coupon_code'] : 'Create' }}</li>
@endsection

@section('header_script')@endsection

@section('content')
    @include('coupon.form._form')
@endsection

@section('footer_script')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@include('common._call_ajax')

@include('common._datetime_range_script', [
        'format_start'  => 'd/m/Y H:i',
        'format_end'    => 'd/m/Y H:i',
        'refer_start'   => '#started_date',
        'refer_end'     => '#end_date',
        'timepicker'    => true
    ])
@include('coupon._footer_script')
@include('common._priority_script')
@include('common._dropdown_right_script')
@endsection