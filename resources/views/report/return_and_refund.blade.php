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

@section('title', 'Return and Refund Report')

@section('breadcrumb')
<li class="active">Return and Refund Report</li>
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
                                <label>Store</label>
                                @if(count($stores)!=1)
                                    {{ Form::select('store_id', $stores, null, [
                                        'id'          => 'store_id',
                                        'class'       => 'form-control select2',
                                        'placeholder' => 'Select ...'
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
                                <label>Start Date (From)</label>
                            <?php
                                $date = date("Y-m-d 17:00");
                                $newdate = strtotime ( "-1 day" , strtotime ( $date ) ) ;
                                $date_from = date ( "Y-m-d H:i" , $newdate );
                                $date_from = convertDateTime($date_from, 'Y-m-d H:i', 'd/m/Y H:i');
                            ?>
                                {{ Form::text('refund_date_from', $date_from, [
                                    'id'          => 'refund_date_from',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Refund Date From'
                                ]) }}
                            </div>
                            <div class="col-md-3">
                                <label>End Date (To)</label>
                                {{ Form::text('refund_date_to', date("d/m/Y 17:00"), [
                                    'id'          => 'refund_date_to',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Refund Date To'
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
		<table class="table table-border-gray table-striped table-hover datatable-dom-position" id="return-and-refund-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th width="5">No.</th>
                    <th>Order No.</th>
					<th>Store No.</th>
					<th>Store Name</th>
                    <th>Item Id</th>
					<th>Date</th>
                    <th>Ref ID</th>
                    <th>Return/Refund Qty</th>
					<th>Amount</th>
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
    var tableId = $('#return-and-refund-table');

    //set dataTable
    var oTable = tableId.on('error.dt', function (e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
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
            url: $("meta[name='root-url']").attr('content') + '/report/return_and_refund/data',
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
                data: 'order_no',
                name: 'order_no', 
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'store_no',
                name: 'store_id', 
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'store_name_th',
                name: 'store_name_th', 
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'item_id',
                name: 'item_id', 
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'datetime',
                name: 'datetime',
                className: 'text-center',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'payment_id',
                name: 'payment_id', 
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
        window.location.replace($("meta[name='root-url']").attr('content') + "/report/return_and_refund/print?" + $.param(search));
    });

</script>

    @include('report._footer_script')

    @include('common._datetime_range_script', [
        'refer_start'   =>  '#refund_date_from',
        'refer_end'     =>  '#refund_date_to',
        'format_start'  =>  'd/m/Y H:i',
        'format_end'    =>  'd/m/Y H:i',
        'default_start' =>  '17:00',
        'timepicker'    => true,
        'editable'      => true
    ])

    
    @include('common._call_ajax')

@endsection