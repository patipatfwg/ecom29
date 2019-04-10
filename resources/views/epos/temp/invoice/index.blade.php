<?php
$scripts = [
    'datatables',
    'datatablesFixedColumns',
    'select2',
    'sweetalert',
    'datetimepicker'
];
?>

@extends('layouts.main')

@section('title', 'Invoice Search')

@section('breadcrumb')
<li class="active">Invoice Search</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Invoice Search [IDM_Invoice_List]</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            {!! Form::open([
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4">
                        <div class="input-group">
                            {{ Form::text('order_number', null, [
                                'id'          => 'order_number',
                                'class'       => 'form-control',
                                'placeholder' => 'Sale Order Number [@OrderNo]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            {{ Form::text('return_order_number', null, [
                                'id'          => 'return_order_number',
                                'class'       => 'form-control',
                                'placeholder' => 'Return Order Number [@OrderNo]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        {{--@include('common._select', [ 'data' => array_column($categoryList,'full_name','id'), 'defaultValue' => '-- Choose category --'])--}}
                        <div class="input-group">
                            {{ Form::text('invoice_number', null, [
                                'id'          => 'invoice_number',
                                'class'       => 'form-control',
                                'placeholder' => 'Invoice Number [@OrderNo]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-12">
                        * Please enter search input as one of field: Sale Order Number, Return Order Number or Invoice Number
                    </div>
                </div>
            </div>
            {{--<div class="row">--}}
                {{----}}
            {{--<div>--}}
            <div class="clearfix"></div>
            <div class="row">
                {{ Form::button('<i class="icon-search4"></i> Search', array(
                    'type'  => 'submit',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                )) }}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="invoices-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="80">No.</th>
                            <th width="100">Invoice No.</th>
                            <th>Created Date</th>
                            <th>Invoice Type</th>
                            <th>Amount</th>
                            <th>Print Counter</th>
                            <th>Original Invoice No.</th>
                            <th>Issued Date</th>
                            <th width="50">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="80">1</td>
                            <td width="100">@InvoiceNo</td>
                            <td>@Createts</td>
                            <td>@InvoiceType</td>
                            <td>@TotalAmount</td>
                            <td>???</td>
                            <td>@MasterInvoiceNo</td>
                            <td>@DateInvoiced</td>
                            <td width="50">Manage</td>
                        </tr>
                        <tr><td colspan="9" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
{{--{{ Html::script('js/coupons/datatable.js') }}--}}

@endsection