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

@section('title', 'Coupon Report')

@section('breadcrumb')
<li class="active">Coupon Report</li>
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
                            <label>Ref. Code</label>
                            {{ Form::text('ref_code', null, [
                                'id'          => 'ref_code',
                                'class'       => 'form-control',
                                'placeholder' => 'Ref. Code'
                            ]) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Store</label>
                            @if(count($stores)!=1)
                                {{ Form::select('store_id', $stores, null, [
                                    'id'          => 'store_id',
                                    'class'       => 'form-control select2',
                                    'placeholder' => 'Select ...'
                                ]) }}
                            @else
                                <input type="hidden" id="store_id" name="store_id" value="{{ $current_store }}">
                                {{ Form::select('store_id', $stores, null, [
                                    'id'          => 'store_id',
                                    'class'       => 'form-control select2',
                                    'disabled'    => true
                                ]) }}
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label>Coupon Used Date</label>
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
                <table class="table table-border-gray table-striped datatable-dom-position" id="coupon-report-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="60" style="width:40px;">Store No.</th>
                            <th width="80" style="width:60px;">Coupon Code</th>
                            <th width="80" style="width:80px;">Ref. Code</th>
                            <th>Coupon Name (TH)</th>
                            <th>Coupon Name (EN)</th>
                            <th>Division</th>
                            <th>จำนวนใบที่ใช้</th>
                            <th>จำนวนเงิน</th>            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center">
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
    var url = 'coupon';
    var tableId = $('#coupon-report-table');
    var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: message, type: 'error' });
    }).DataTable({
        scrollY: true,
        scrollX:  true,
        scrollCollapse: true,
        scroller:       true,
        fixedColumns: {
            leftColumns: 3,
            heightMatch: 'none'
        },
        lengthMenu: [ 10, 50, 100, 500, 1000 ],
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
            d.coupon_code = $('#select-coupon-code').val();
            d.ref_code    = $('#ref_code').val();
            d.store_id    = $('#store_id').val();
            d.start_date  = $('#start_date').val();
            d.end_date    = $('#end_date').val();
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
            { data: 'store_id', name: 'store_id'},
            { data: 'coupon_code', name: 'coupon_code' },
            { data: 'ref_code', name: 'ref_code' },
            { data: 'coupon_name_th', name: 'coupon_name_th' },
            { data: 'coupon_name_en', name: 'coupon_name_en' },
            { data: 'division', name: 'division'},
            { data: 'usage_count', name: 'usage_count', orderable: false,className:'text-right'},
            { data: 'total_discount', name: 'total_discount' ,orderable: false, className:'text-right'},

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

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content') + '/report/' + url + '/report?' + $.param(data));
    });

    $(".select-dropdown").select2({
        minimumResultsForSearch: -1,
        placeholder: 'SELECT TYPE'
    });

    $('#select-coupon-code').select2({
        placeholder: 'Select Coupon Code ...',
        allowClear: true
    });

    $('#store_id').select2({
        placeholder: 'Select Store List ...',
        allowClear: true
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