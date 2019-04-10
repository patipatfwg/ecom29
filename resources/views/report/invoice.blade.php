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

@section('title', 'Invoice Report')

@section('breadcrumb')
<li class="active">Invoice Report</li>
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
                    <div  class="col-md-6">
                        <div class="col-md-6">
                            <label>Payment Date (From)</label>
                            <?php
                                $date      = date('Y-m-d 00:00:01');
                            ?>
                            {{ Form::text('payment_date_from', date("d/m/Y 00:00"), [
                                'id'          => 'payment_date_from',
                                'class'       => 'form-control',
                                'placeholder' => 'Payment Date From'
                            ]) }}
                        </div>
                        <div class="col-md-6">
                            <label>Payment Date (To)</label>
                            {{ Form::text('payment_date_to', date("d/m/Y 23:59"), [
                                'id'          => 'payment_date_to',
                                'class'       => 'form-control',
                                'placeholder' => 'Payment Date To'
                            ]) }}
                        </div>
                    </div>
                    <div  class="col-md-6">
                          <label>Invoice Type</label>
                          {{ Form::select('store_id', ['optional'], null, [
                                'id'          => 'invoice_type',
                                'class'       => 'form-control select2',
                                'placeholder' => 'All'
                            ]) }}
                    </div>
                    
                </div>
            </div>
            <div class="row form-group">
               
                    <div class="col-md-6">
                        <label>Payment Channel</label>
                        <div class="input-group">
                            <div class="multi-select-full">
                                {{ Form::select('payment_type', $configs, $default_config_select, [
                                    'class'    => 'multiselect-toggle-selection hide',
                                    'multiple' => 'multiple',
                                    'placeholder' => 'All'
                                ]) }}
                            </div>
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info multiselect-toggle-selection-button">Select All</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Store</label>
                        @if(count($stores)!=1)
                            {{ Form::select('store_id', $stores, null, [
                                'id'          => 'store_id',
                                'class'       => 'form-control select2',
                                'placeholder' => 'All'
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
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped datatable-dom-position" id="invoice-table" data-page-length="10" width="160%">
			<thead>
				<tr>
					<th width="80">No.</th>
					<th width="80">Invoice No.</th>
					<th>Issued Date</th>
					<th>Invoice Type</th>
					<th>Amount</th>
					<th>Order Number</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="6" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('footer_script')
<script type="text/javascript">
    "use strict";

    $.fn.dataTable.ext.errMode = 'none';

    //set tabel
    var $table = $('#invoice-table');
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
        order: [[ 1, false ]],
        cache: true,
        pageLength: 10,
        lengthMenu: [ 10, 50, 100, 500, 1000 ],
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/report/invoice/data',
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
            { data: 'no', name: 'no', width: '80px', orderable: false, searchable: false, className: 'text-center' },
            { data: 'invoiceNo', name: 'invoiceNo', width: '80px' },
            { data: 'isuedDate', name: 'isuedDate' },
            { data: 'invoiceType', name: 'invoiceType' },
            { data: 'amount', name: 'amount', className:'text-right' },
            { data: 'orderNumber', name: 'orderNumber' }
           
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
        window.location.replace($("meta[name='root-url']").attr('content') + '/report/invoice/print?' + $.param(search));
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
        'default_start' =>  '00:00:01',
        'timepicker'    => true,
        'editable'      => true
    ])
    
@endsection