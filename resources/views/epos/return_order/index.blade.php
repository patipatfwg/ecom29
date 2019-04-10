<?php $scripts = [
    'datatables',
    'sweetalert'
]; ?>

@extends('layouts.epos.main')

@section('title', 'Return Order Search')

@section('breadcrumb')
    <li class="active">Return Order Search</li>
@endsection

@section('header_script')@endsection

@section('content')

    @include('epos.return_order.form_search')

    @include('epos.return_order.search_result')

@endsection