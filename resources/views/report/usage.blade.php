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

@section('title', 'Usage Report')

@section('breadcrumb')
<li class="active">Usage Report</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
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
                        <div class="col-md-6">
                            <label>Coupon Code</label>
                                @include('common._select', [
                                'data' => $coupon,
                                'name' => 'coupon_code','id' => 'select-coupon-code','hasPlaceholder' => true]
                                )
                        </div>
                        <div class="col-md-6">
                            <label>Order Number</label>
                            {{ Form::text('full_text', null, [
                                'id'          => 'full_text',
                                'class'       => 'form-control',
                                'placeholder' => 'Order Number'
                            ]) }}
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Status</label>
                                @include('common._select', [
                                'data' => [
                                    'all'      => 'All',
                                    'hold'     => 'Hold',
                                    'used'     => 'Used',
                                    'expired'  => 'Expired',
                                    'canceled'  => 'Canceled'
                                ],
                                'name' => 'status','id' => 'select-type']
                                )
                            <input type="hidden" id="status" name="value">
                        </div>
                        <div class="col-md-6">
                        <label>Used Date</label>
                            <div class="input-group input-daterange">
                                {{ Form::text('start_date', null, [
                                    'id'          => 'start_date',
                                    'class'       => 'form-control',
                                    'placeholder' => 'DD/MM/YYYY HH:MM:SS'
                                ]) }}
                                <div class="input-group-addon">
                                    to
                                </div>
                                {{ Form::text('end_date', null, [
                                    'id'          => 'end_date',
                                    'class'       => 'form-control',
                                    'placeholder' => 'DD/MM/YYYY HH:MM:SS'
                                ]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                 
                        {{ Form::button('<i class="icon-search4"></i> Search', [
                            'type'  => 'submit',
                            'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                        ]) }}
                    
                </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="history-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Coupon Code</th>
                            <th>Used Date</th>
                            <th>Order Number</th>
                            <th>Order Amount</th>
                            <th>Makro ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Customer Type</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Status</th>               
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="12" class="text-center">
                                <i class="icon-spinner2 spinner"></i> Loading ...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
<script type="text/javascript">
    var url = 'usage';
    var tableId = $('#history-table');
    var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: message, type: 'error' });
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, false ]],
        cache: true,
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url + '/data',
            type: 'GET',
            data: function(d) {
            d.full_text = $('#full_text').val();
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.status = $('#status').val();
            d.coupon_code = $('#select-coupon-code').val();
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
                    swal('Error!', 'Error connection', 'error');
                    tableId.find('tbody').find('td').html('No Data, please try again later');
                }
            }
        },
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['data'];
            });
        },
        columns: [
            { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
            { data: 'coupon_code', name: 'coupon_code'},
            { data: 'used_date', name: 'used_date'},
            { data: 'order_no', name: 'order_no' },
            { data: 'order_amount', name: 'order_amount',className:'text-right'},
            { data: 'makro_member_card', name: 'makro_member_card' },
            { data: 'first_name', name: 'customer_firstname' },
            { data: 'last_name', name: 'customer_lastname' },
            { data: 'customer_type', name: 'customer_type' },
            { data: 'mobile_number', name: 'mobile_number' },
            { data: 'email', name: 'email' },
            { data: 'status', name: 'status' }
        ]
    });

    $('div.datatable-header').append(`
        @include('common._print_button')&nbsp;
    `);

    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('#select-type').on('select2:select', function (evt) {
        var status = $("#select-type option:selected").val();
        $("#status").val(status);
    });

    $('#select-coupon-code').on('select2:select', function (evt) {
        var coupon_code = $("#select-coupon-code option:selected").val();
        $("#coupon_code").val(coupon_code);
    });

    $('#select-coupon-code').select2({
        placeholder: 'Select Coupon Code',
        allowClear: true
    });

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content')+ '/report/' + url + '/report?' + $.param(data));
    });

    $("#select-type").select2({
        minimumResultsForSearch: -1,

    });
</script>

@include('common._datetime_range_script', [
        'format_start'  => 'd/m/Y H:i:00',
        'format_end'    => 'd/m/Y H:i:00',
        'refer_start'   => '#start_date',
        'refer_end'     => '#end_date',
        'timepicker'    => true,
        'editable'      => true
    ])

@endsection