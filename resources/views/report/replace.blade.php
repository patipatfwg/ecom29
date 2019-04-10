<?php
$scripts = [
    'datatables',
    'datatablesFixedColumns',
    'select2',
    'datetimepicker',
    'uniform',
    'bootstrap_multiselect'
];
?>

@extends('layouts.main')

@section('title', 'Replace Report')

@section('breadcrumb')
<li class="active">Replace Report</li>
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
                'class' => 'form-horizontal',
                'id'    => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>
                            <span class="text-danger">*</span> Makro store
                        </label>
                        <?php $defaultStores = !empty($stores) ? $stores[key($stores)] : null; ?>
                        {{ Form::select('store_id', $stores, $defaultStores, [
                            'id'          => 'store_id',
                            'class'       => 'form-control store_id',
                            'placeholder' => 'Select Stores...'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>
                            <span class="text-danger">*</span> New issued date (From)
                        </label>
                        <?php
                        // $date      = date('Y-m-d');
                        // $newdate   = strtotime('-1 day', strtotime($date));
                        // $date_from = date('Y-m-d', $newdate);
                        // $date_from = convertDateTime($date_from, 'Y-m-d', 'd/m/Y');
                        ?>
                        {{ Form::text('new_issued_date_from', date('d/m/Y'), [
                            'id'          => 'new_issued_date_from',
                            'class'       => 'form-control',
                            'placeholder' => 'New issued date From'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>
                            <span class="text-danger">*</span> New issued date (To)
                        </label>
                        {{ Form::text('new_issued_date_to', date('d/m/Y'), [
                            'id'          => 'new_issued_date_to',
                            'class'       => 'form-control',
                            'placeholder' => 'New issued date To'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Replacement type</label>
                        @include('common._select',[
                            'data' => [
                                'long_to_long' => 'Wrong Spelling',
                                'short_to_long' => 'Short to Long'
                            ],
                            'default' => 0,
                            'defaultValue' => 'All',
                            'name' => 'replace_type',
                            'id'   => 'replace_type'
                        ])
                    </div>
                    <div class="col-md-6 replace_checkbok">
                        <label class="checkbox-inline">
                            <input name="interday" type="checkbox" class="styled" value="1">
                            interday (ข้ามวัน)
                        </label>
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
                <table class="table table-border-gray table-striped datatable-dom-position" id="replace-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <!-- <th width="80">No.</th> -->
                            <th width="200">New Issued Date</th>
                            <th width="200">Old Issued Date</th>
                            <th>Old Invoice No.</th>
                            <th>Old Name/Company</th>
                            <th>Old Tax ID</th>
                            <th>Old Branch No.</th>
                            <th>New Invoice No.</th>
                            <th>New Name/Company</th>
                            <th>New Tax ID</th>
                            <th>New Branch No.</th>
                            <th>Invoice Exvat Amount</th>
                            <th>Tax</th>
                            <th>Invoice Invat Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="14" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
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
    var $table = $('#replace-table');
    var $thead = $table.find('thead');
    var $tbody = $table.find('tbody');

    //set dataTabel
    var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({ text: 'Error connection', type: 'error' });
    }).DataTable({
        deferRender: true,
        scrollY: true,
        scrollX: true,
        scrollCollapse: true,
        scroller: true,
        fixedColumns:   {
            leftColumns: 2,
            heightMatch: 'none'
        },
        processing: false,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [],
        cache: true,
        pageLength: 10,
        lengthMenu: [ 10, 50, 100, 500, 1000 ],
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/report/replace/data',
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
        drawCallback: function(settings) {
            $('div.DTFC_LeftBodyLiner').addClass('DTFC_LeftBodyLinerWidth');
        },
        columns: [
            // { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
            { data: 'new_issued_date', name: 'new_info.issue_date', width: '200px'},
            { data: 'old_issued_date', name: 'old_info.issue_date', width: '200px'},
            { data: 'old_invoice', name: 'old_invoice' },
            { data: 'old_company_name', name: 'old_info.company_name' },
            { data: 'old_tax_id', name: 'old_info.tax_id' },
            { data: 'old_branch_id', name: 'old_info.branch_id' },
            { data: 'new_invoice', name: 'new_invoice' },
            { data: 'new_company_name', name: 'new_company_name' },
            { data: 'new_tax_id', name: 'new_info.tax_id' },
            { data: 'new_branch_id', name: 'new_info.branch_id' },
            { data: 'invoice_exvat_amount', name: 'amount_exc_vat',className:'text-right' },
            { data: 'tax', name: 'selling_vat',className:'text-right' },
            { data: 'invoice_invat_amount', name: 'amount_inc_vat',className:'text-right' }
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
        window.location.replace($("meta[name='root-url']").attr('content') + '/report/replace/print?' + $.param(search));
    });

    $('select').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
</script>

@include('report._footer_script')

@include('common._datetime_range_script', [
    'refer_start'   => '#new_issued_date_from',
    'refer_end'     => '#new_issued_date_to',
    'format_start'  => 'd/m/Y',
    'format_end'    => 'd/m/Y',
    'timepicker'    => false,
    'editable'      => true,
    'timefixed'     => false
])

@endsection