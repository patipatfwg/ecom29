<?php
$scripts = [
    'datatables',
    'datatablesFixedColumns',
    'select2',
    'sweetalert',
    'datetimepicker',
    'uniform',
    'bootstrap_multiselect'
];
?>

@extends('layouts.main')

@section('title', 'Order Report')

@section('breadcrumb')
<li class="active">Order Report</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
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
                    <div class="col-md-3">
                        <label>Order Number</label>
                        {{ Form::text('order_no', null, [
                            'id'          => 'order_no',
                            'class'       => 'form-control',
                            'placeholder' => 'Order Number'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Customer First Name</label>
                        {{ Form::text('customer_firstname', null, [
                            'id'          => 'customer_firstname',
                            'class'       => 'form-control',
                            'placeholder' => 'Customer First Name'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Customer Last Name</label>
                        {{ Form::text('customer_lastname', null, [
                            'id'          => 'customer_lastname',
                            'class'       => 'form-control',
                            'placeholder' => 'Customer Last Name'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Customer Mobile</label>
                        {{ Form::text('customer_phone', null, [
                            'id'          => 'customer_phone',
                            'class'       => 'form-control',
                            'placeholder' => 'Customer Mobile'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        <label>Customer Email</label>
                        {{ Form::text('customer_email', null, [
                            'id'          => 'customer_email',
                            'class'       => 'form-control',
                            'placeholder' => 'Customer Email'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Customer Type</label>
                        {{ Form::text('customer_type', null, [
                            'id'          => 'customer_type',
                            'class'       => 'form-control',
                            'placeholder' => 'Customer Type'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Payment Date (From)</label>
                        {{ Form::text('payment_date_from', null, [
                            'id'          => 'payment_date_from',
                            'class'       => 'form-control',
                            'placeholder' => 'Payment Date From'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Payment Date (To)</label>
                        {{ Form::text('payment_date_to', null, [
                            'id'          => 'payment_date_to',
                            'class'       => 'form-control',
                            'placeholder' => 'Payment Date To'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        <label>
                            <span class="text-danger">*</span> Create Date (From)
                        </label>
                        <?php
                        $createDateFrom = convertDateTime(date('Y-m-d 00:00', strtotime('-1 day')), 'Y-m-d H:i', 'd/m/Y H:i');
                        ?>
                        {{ Form::text('create_date_from', $createDateFrom, [
                            'id'          => 'create_date_from',
                            'class'       => 'form-control',
                            'placeholder' => 'Create Date From',
                            'required'    => true
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>
                            <span class="text-danger">*</span> Create Date (To)
                        </label>
                        <?php
                        $createDateTo = convertDateTime(date('Y-m-d 23:59', strtotime('-1 day')), 'Y-m-d H:i', 'd/m/Y H:i');
                        ?>
                        {{ Form::text('create_date_to', $createDateTo, [
                            'id'          => 'create_date_to',
                            'class'       => 'form-control',
                            'placeholder' => 'Create Date To',
                            'required'    => true
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        @include('common._select',[
                            'data' => [
                                'pending'  => 'Pending',
                                'created' => 'Created',
                                'cancelled' => 'Cancelled',
                                'expired' => 'Expired',
                                'failed'    => 'Failed',
                                'failed_oms' => 'Failed Oms'
                            ],
                            'hasPlaceholder' => true,
                            'name' => 'status',
                            'id' => 'select-status',
                        ])
                    </div>
                    <div class="col-md-3">
                        <label>Store</label>
                        @if(count($stores)!=1)
                            {{ Form::select('store_id', $stores, null, [
                                'id'          => 'store_id',
                                'class'       => 'form-control select2',
                                'placeholder' => 'Select Stores...'
                            ]) }}
                        @else
                        <input type="hidden" name="store_id" value="{{ $current_store }}">
                        {{ Form::select('store_id', $stores, null, [
                            'id'          => 'store_id',
                            'class'       => 'form-control select2',
                            'disabled'    => true
                        ]) }}
                    @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Payment Channel</label>
                        <div class="input-group">
                            <div class="multi-select-full">
                                {{ Form::select('payment_type', $configs, $default_config_select, [
                                    'class'    => 'multiselect-toggle-selection hide',
                                    'multiple' => 'multiple'
                                ]) }}
                            </div>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info multiselect-toggle-selection-button">Select All</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <table class="table table-border-gray table-striped datatable-dom-position" id="order-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="80">No.</th>
                            <th width="200">Order&nbsp;Number</th>
                            <th>Create Date</th>
                            <th>Customer Name</th>
                            <th>Customer Mobile</th>
                            <th>Customer Email</th>
                            <th>Customer Type</th>
                            <th>Order Amount (vat items)</th>
                            <th>Order Amount (vat free items)</th>
                            <th>Discount</th>
                            <th>Order Amount</th>
                            <th>Order Amount Exc Vat</th>
                            <th>Payment Fee</th>
                            <th>VAT of Payment Fee</th>
                            <th>Net Amount</th>
                            <th>W.H. TAX</th>
                            <th>Payment Channel</th>
                            <th>Payment ID</th>
                            <th>Payment Date</th>
                            <th>Store No.</th>
                            <th>Store Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="22" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
    <script type="text/javascript">
    "use strict";

    $.fn.dataTable.ext.errMode = 'none';

    //set tabel
    var $table = $('#order-table');
    var $thead = $table.find('thead');
    var $tbody = $table.find('tbody');

    //set dataTabel
    var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({ text: 'Error connection', type: 'error' });
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        fixedColumns:   {
            leftColumns: 2,
            heightMatch: 'none'
        },
        processing: false,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, false ]],
        cache: true,
        pageLength: 10,
        lengthMenu: [ 10, 50, 100, 500, 1000 ],
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/report/order_status/data',
            type: 'POST',
            data: function (d) {
                d.search = $('#search-form').serializeArray();
            },
            error: function(xhr, error, thrown) {
                if(xhr.responseJSON.expired) {
                    swal({
                    title: "Error!",
                    text: 'Session Expired',
                    type: "error",
                    confirmButtonText: "OK"
                    },
                    function(){
                        location.reload();
                    });
                } else {
                    new PNotify({text: 'Error connection', type: 'error' });
                    $tbody.children().remove();
                    $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
                }
            }
        },
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['name'];
            });
        },
        columns: [
            { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
            { data: 'order_no', name: 'order_no', width: '200px' },
            { data: 'created_at', name: 'created_at' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'customer_phone', name: 'customer_phone' },
            { data: 'customer_email', name: 'customer_email' },
            { data: 'customer_type', name: 'customer_type' },
            { data: 'amount_vat_items', name: 'amount_vat_items',className:'text-right'},
            { data: 'amount_vat_free_items', name: 'amount_vat_free_items',className:'text-right'},
            { data: 'discount', name: 'discount', className:'text-right' },
            { data: 'total_amount', name: 'total_amount', className:'text-right' },
            { data: 'total_amount_exc_vat', name: 'total_amount_exc_vat', className:'text-right' },
            { data: 'payment_fee', name: 'payment_fee', className:'text-right' },
            { data: 'payment_fee_vat', name: 'payment_fee_vat', className:'text-right' },
            { data: 'net_amount', name: 'net_amount', className:'text-right' },
            { data: 'w_h_tax', name: 'w_h_tax', className:'text-right' },
            { data: 'payment_type', name: 'payment_type' },
            { data: 'payment_id', name: 'payment_id' },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'store_id', name: 'store_id' },
            { data: 'store_name_th', name: 'store_name.th' },
            { data: 'status', name: 'status' }
        ]
    });

    $('div.datatable-header').append(`@include('common._print_button')`);

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data   = oTable.ajax.params();
        var search = {
            start: data.start,
            length: data.length,
            search: data.search,
            order: data.order,
            report: 'print'
        };
        window.location.replace($("meta[name='root-url']").attr('content') + '/report/order_status/print?' + $.param(search));
    });
    // Initialize
    $('.multiselect-toggle-selection').multiselect();
    $(".styled, .multiselect-container input[type='checkbox']").uniform({ radioClass: 'choice'});
</script>

    @include('report._footer_script')

    @include('common._datetime_range_script', [
        'refer_start'   =>  '#payment_date_from',
        'refer_end'     =>  '#payment_date_to',
        'format_start'  =>  'd/m/Y H:i',
        'format_end'    =>  'd/m/Y H:i',
        'default_start' =>  '17:00',
        'timepicker'    => true,
        'editable'      => true
    ])

    @include('common._datetime_range_script', [
        'refer_start'   =>  '#create_date_from',
        'refer_end'     =>  '#create_date_to',
        'format_start'  =>  'd/m/Y H:i',
        'format_end'    =>  'd/m/Y H:i',
        'default_start' =>  '00:00',
        'default_end'   =>  '23:59',
        'timepicker'    => true,
        'editable'      => true
    ])

@endsection