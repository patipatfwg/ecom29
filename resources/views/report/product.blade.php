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

@section('title', 'Product Report')

@section('breadcrumb')
<li class="active">Product Report</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                {!! Form::open([
                    'autocomplete' => 'off',
                    'class'        => 'form-horizontal',
                    'id'           => 'search-form'
                ]) !!}

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Product Name</label>
                                {{ Form::text('item_name.th', null, [
                                    'id'          => 'item_name.th',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Product Name'
                                ]) }}
                            </div>
                            <div class="col-md-6">
                                <label>Item ID</label>
                                {{ Form::text('item_id', null, [
                                    'id'          => 'item_id',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Item ID'
                                ]) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Buyer Group</label>
                                {{ Form::text('buyer_name', null, [
                                    'id'          => 'buyer_name',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Buyer Group'
                                ]) }}
                            </div>
                            <div class="col-md-3">
                                <label>Payment Date (From)</label>
                            <?php
                                $date = date("Y-m-d 17:00");
                                $newdate = strtotime ( "-1 day" , strtotime ( $date ) ) ;
                                $date_from = date ( "Y-m-d H:i" , $newdate );
                                $date_from = convertDateTime($date_from, 'Y-m-d H:i', 'd/m/Y H:i');
                            ?>
                                {{ Form::text('payment_date_from', $date_from, [
                                    'id'          => 'payment_date_from',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Payment Date From'
                                ]) }}
                            </div>
                            <div class="col-md-3">
                                <label>Payment Date (To)</label>
                                {{ Form::text('payment_date_to', date("d/m/Y 17:00"), [
                                    'id'          => 'payment_date_to',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Payment Date To'
                                ]) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {{ Form::button('<i class="icon-search4"></i> Search', [
                            'type'  => 'submit',
                            'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                        ]) }}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped table-hover datatable-dom-position" id="products-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th width="5">No.</th>
					<th>Item&nbsp;ID</th>
					<th>Product Name</th>
					<th>Qty</th>
					<th>Amount</th>
					<th>Vat free items (Y/N)</th>
					<th>Buyer Group</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="14" class="text-center">Loading ...</td>
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

    //set table id
    var tableId = $('#products-table');

    //set dataTable
    var oTable = tableId.on('error.dt', function (e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        processing: false,
        serverSide: true,
        searching: false,
        retrieve: true,
        destroy: true,
        lengthMenu: [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]],
        order: [[3, "desc"]],
        cache: true,
        dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/report/product/data',
            type: 'POST',
            data: function (d) {
                d.search = $('#search-form').serializeArray();
            },
            error: function (xhr, error, thrown) {
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
                data['order'][index]['column'] = data['columns'][items.column]['name'];
            });
        },
        columns: [
            {
                data: 'number',
                name: 'number',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'item_id',
                name: 'item_id', 
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'item_name_th',
                name: 'item_name.th', 
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'quantity',
                name: 'quantity',
                className:'text-right',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'amount',
                name: 'amount',
                className:'text-right',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'vat_rate',
                name: 'vat_rate',
                orderable: false,
                className:'text-center',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'buyer_name',
                name: 'buyer_name',
                render: function (data, type, row) {
                    return unescape(data);
                }
            }
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
        window.location.replace($("meta[name='root-url']").attr('content') + "/report/product/print?" + $.param(search));
    });

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

    
    @include('common._call_ajax')

@endsection