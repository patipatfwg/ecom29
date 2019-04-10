<?php $scripts = [
    'datatables',
    'sweetalert'
]; ?>

@extends('layouts.epos.main')

@section('title', 'Order Search')

@section('breadcrumb')
<li class="active">Order Search</li>
@endsection

@section('header_script')@endsection

@section('content')

    @include('epos.order.form_search')

    @if ($order_number !== '')
        @if (empty($search_result))
            {{--<span>Data not found</span>--}}
        @else
            @include('epos.order.search_result')
        @endif
    @endif

@endsection