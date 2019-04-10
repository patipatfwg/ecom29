<?php $scripts = ['datatables', 'datatablesFixedColumns', 'sweetalert', 'select2', 'bootstrap-select']; ?>

@extends('layouts.epos.main')

@section('title', 'Invoice Search')

@section('breadcrumb')
    <li class="active">Invoice Search</li>
@endsection

@section('header_script')
    {{ Html::style('assets/css/dropdown.custom.css') }}
@endsection

@section('content')

    @include('epos.invoice.form_search')

    @include('epos.invoice.search_result')

@endsection

@section('footer_script')

    {{--{{ Html::script('js/orders/datatable.js') }}--}}
@endsection