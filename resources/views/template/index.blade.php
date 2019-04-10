<?php $scripts = [
    'datatables', 
    'datatablesFixedColumns', 
    'sweetalert', 
    'select2', 
    'bootstrap-select']; 
?>

@extends('layouts.main')

@section('title', 'Template Invoice')

@section('breadcrumb')
    <li class="active">Template Invoice</li>
@endsection

@section('header_script')
    {{ Html::style('assets/css/invoice/main.css') }}
@endsection

@section('content')
    <page size="A4" id="print-this"></page>
@endsection

@section('footer_script')

@endsection