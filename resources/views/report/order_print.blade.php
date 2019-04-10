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

@section('title', ($first_print) ? 'Print Report' : 'Reprint Report')

@section('breadcrumb')
<li class="active">{{ ($first_print) ? 'Print' : 'Reprint'}} Report</li>
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
            @if($first_print === true)
                <input type="hidden" name="running_number" value="1" />
            @endif
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Store</label>
                    @if(count($stores)!=1)
                        <?php $defaultStores = !empty($stores) ? $stores[key($stores)] : null; ?>
                        {{ Form::select('store_id', $stores, $defaultStores, [
                            'id'          => 'store_id',
                            'class'       => 'form-control store_id',
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
                    <div class="col-md-3">
                        <label>{{ ($first_print) ? 'Print' : 'Reprint'}} Date (From)</label>
                        {{ Form::text('reprint_date_start', date('d/m/Y'), [
                            'id'          => 'reprint_date_start',
                            'class'       => 'form-control',
                            'placeholder' => 'Reprint Date From'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>{{ ($first_print) ? 'Print' : 'Reprint'}} Date (To)</label>
                        {{ Form::text('reprint_date_end', date('d/m/Y'), [
                            'id'          => 'reprint_date_end',
                            'class'       => 'form-control',
                            'placeholder' => 'Reprint Date To'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Invoice Number</label>
                        {{ Form::text('invoice_no', null, [
                            'id'          => 'invoice_no',
                            'class'       => 'form-control',
                            'placeholder' => 'Invoice Number'
                        ]) }}
                    </div>
                    <div class="col-md-6">
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
                            <th>{{ ($first_print) ? 'Printed' : 'Reprinted'}} Date</th>
                            @if(!$first_print)
                            <th>Reprint</th>
                            @endif
                            <th>Issue Date</th>
                            <!-- <th>Settlement Date</th> -->
                            <th>Invoice No.</th>
                            <th>Name/Company</th>
                            <th>Tax Id</th>
                            <th>Branch No.</th>
                            <th>Invoice exvat amount</th>
                            <th>Tax</th>
                            <th>Invoice invat amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="21" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
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
            leftColumns: {{ ($first_print) ? '1' : '2'}},
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
            url: $("meta[name='root-url']").attr('content') + '/report/order_print/data',
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
            { data: 'reprinted_date', name: 'reprinted_date', width: '80px', searchable: false, className: 'text-center' },
            @if(!$first_print)
            { data: 'reprint', name: 'running_number', width: '200px' },
            @endif
            // { data: 'issue_date', name: 'issue_date' },
            { data: 'settlement_date', name: 'settlement_date' },
            { data: 'invoice_no', name: 'invoice_no' },
            { data: 'name_company', name: 'shop_name' },
            { data: 'tax_id', name: 'tax_id' },
            { data: 'branch_no', name: 'branch_id' },
            { data: 'invoice_exvat_amount', name:'amount_exc_vat', className:'text-right' },
            { data: 'tax', name: 'selling_vat', className:'text-right' },
            { data: 'invoice_invat_amount', name: 'amount_inc_vat', className:'text-right' }
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
        
        window.location.replace($("meta[name='root-url']").attr('content') + '/report/order_print/print?' + $.param(search));
    });
</script>

    @include('report._footer_script')

    @include('common._datetime_range_script', [
        'refer_start'   => '#reprint_date_start',
        'refer_end'     => '#reprint_date_end',
        'format_start'  => 'd/m/Y',
        'format_end'    => 'd/m/Y',
        'timepicker'    => false,
        'editable'      => true,
        'timefixed'     => false
    ])
@endsection