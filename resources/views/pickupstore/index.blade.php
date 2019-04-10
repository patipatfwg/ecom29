<?php
    $scripts = [
        'nestable',
        'sweetalert',
        'select2',
        'datatables',
    ];
?>

@extends('layouts.main')

@section('title', 'Pickup Store')

@section('breadcrumb')
<li class="active">Pickup Store</li>
@endsection

@section('header_script')
@endsection

@section('content')

<!-- Start: Search panel -->
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
        {!! Form::open(['autocomplete' => 'off', 'class'=> 'form-horizontal','id'=> 'search-form']) !!}
        <div class="col-lg-12">
            <div class="row">
                <div class="form-group">
                    <div class="col-lg-4">
                        <label>Store</label>
                        @include('common._select', [
                            'id'           => 'store',
                            'name'         => 'store',
                            'data'         => $stores
                        ])
                    </div>
                    <div class="col-lg-3">
                        <label>Status</label>
                        @include('common._select', [
                            'id'           => 'pickup',
                            'name'         => 'pickup',
                            'data'         => $status
                        ])
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                {{ Form::button('<i class="icon-search4"></i> Search', [
                    'type'  => 'submit',
                    'id'    => 'search-data',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                ]) }}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- End: New campaign panel -->

<!-- Start: Campaign list panel -->

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table" id="pickupstore-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th class="" width="50">No.</th>
					<th class="" width="150">Store ID</th>
					<th class="">Name (EN)</th>
                    <th class="">Name (TH)</th>
					<th class="" width="150">Status</th>
					<th class="" width="150">Price Store</th>
					<th class="" width="150">Price Professional Store</th>
				</tr>
			</thead>
            <tbody>
                <tr>
                    <td colspan="7" class="text-center">
                        <i class="icon-spinner2 spinner"></i> Loading ...
                    </td>
                </tr>
            </tbody>
		</table>
	</div>
</div>

@endsection

@section('footer_script')

<script type="text/javascript">

var url         = '/pickupstore'
var status_edit = false
var status_load = true

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

var oTable = $('#pickupstore-table').on('error.dt',function(e, settings, techNote, message){
    swal('Error!', 'Error connection', 'error');
}).DataTable({
    scrollY: true,
    scrollX: '300px',
    processing: true,
    serverSide: true,
    searching: false,
    retrieve : true,
    destroy : true,
    order: [[ 2, "asc" ]],
    cache: true,
    dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
    language: {
        lengthMenu: '<span>Show :</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
    },
    ajax: {
        url: url + '/data',
        type: 'GET',
        beforeSend: function() {
            status_load = true
        },
        data: function (d) {
            d.makro_store_id  = $('#store').val();
            d.pickup          = $('#pickup').val();
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
                $('#pickupstore-table').find('tbody').find('td').html('No Data, please try again later');
            }
        }
    },
    fnServerParams: function(data) {
        data['order'].forEach(function(items, index) {
            data['order'][index]['column'] = data['columns'][items.column]['data'];
        });
    },
    createdRow: function( row, data, dataIndex ) {
        $(row).attr('id', data.id);
    },
    columns: [
        { data: 'number', name: 'number',orderable: false},
        { data: 'makro_store_id', name: 'makro_store_id'},
        { data: 'name_en', name: 'name_en' },
        { data: 'name_th', name: 'name_th' },
        {
            data: 'pickup',
            name: 'pickup',
            orderable: true,
            searchable: false,
            className: 'text-center',
            render: function(data, type, row) {
                return data;
            }
        },
        {
            data: 'price_store',
            name: 'price_store',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function(data, type, row) {
                return data;
            }
        },
        {
            data: 'price_store_professional',
            name: 'price_store_professional',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function(data, type, row){
                return data;
            }
        }
    ],
    drawCallback : function(settings) {
        resetAll();

    }
});

$("document").ready(function() {
    $('#store').select2();

    $('#pickup').select2({
        minimumResultsForSearch: -1
    });
    $('#search-data').click(function(e){
        e.preventDefault();

        if (checkDataToChange()) {
            swal({
                title: "{{ $message['cancel_data_change'] }}",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel",
                confirmButtonColor: '#DD6B55',
                confirmButtonText: "Confirm",
                closeOnConfirm: true,
                showLoaderOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    status_edit = false
                    hideEdit();
                    oTable.draw();
                }
            })
        } else {
            status_edit = false
            hideEdit();
            oTable.draw();
        }

    });

    // Data table header
    $('div.datatable-header').append(`
        <div class="btn-group">
            <button id="btn-edit" type="button" onclick="doEdit($(this));" class="btn btn-width-100 btn-default btn-raised legitRipple">
                Bulk edit
            </button>&nbsp;
        </div>
        <div class="btn-group">
            <button id="btn-save" type="button" onclick="doSave($(this));"class="hidden btn btn-width-100 btn-default btn-raised legitRipple">
                Save changes
            </button>&nbsp;
        </div>
        <div class="btn-group">
            <button id="btn-cancel" type="button" onclick="doCancel($(this));" class="hidden btn btn-width-100 btn-default btn-raised legitRipple">
                Cancel changes
            </button>&nbsp;
        </div>
    `);

});

function doEdit(element)
{
    $('#btn-save, #btn-cancel').removeClass('hidden');
    element.addClass('hidden');
    showEdit();
}

function doSave(element)
{
    if(validateData()){
        var dataChanged = getDataToChange();
        if(dataChanged !== false){
            $.ajax({
                type: 'PUT',
                url: url + '/saveDataEdit',
                data: { datas : dataChanged },
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        swal({
                            title: "Save",
                            text: data.message,
                            type: "success",
                            confirmButtonText: "OK"
                        })

                        hideEdit();
                        oTable.draw(1);
                    } else {
                        swal('Error!', data.message, 'error');
                    }
                },
                error: function(data) {
                    if(typeof data.responseJSON != 'undefined' && typeof data.responseJSON.permission != 'undefined')
                        swal('Save Fail.', data.responseJSON.permission, 'warning');
                }
            });
        } else {
            hideEdit();
        }
    } else {
        swal('Save Fail.', "{{ $message['update_pickup_not_price'] }}", 'error');
    }
}

function doCancel(element)
{
    if (checkDataToChange()) {
        swal({
            title: "{{ $message['cancel_data_change'] }}",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancel",
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "Confirm",
            closeOnConfirm: true,
            showLoaderOnConfirm: true
        },
        function(isConfirm){
            if (isConfirm) {
                status_edit = false
                resetAll();
                oTable.draw();
            }
        })
    } else {
        resetAll();
    }
}

function showEdit()
{
    $('span.pickup, span.price_store, span.price_store_professional').addClass('hidden');
    $('select.pickup, select.price_store, select.price_store_professional').removeClass('hidden');

    $('select.pickup').select2({
        minimumResultsForSearch: -1
    });
    $('select.price_store').select2();
    $('select.price_store_professional').select2();
}

function hideEdit()
{
    $('#btn-save, #btn-cancel').addClass('hidden');
    $('#btn-edit').removeClass('hidden');

    $('span.pickup, span.price_store, span.price_store_professional').removeClass('hidden');
    $('select.pickup, select.price_store, select.price_store_professional').addClass('hidden');

    if ($('select.pickup').data('select2')) $('select.pickup').select2('destroy');
    if ($('select.price_store').data('select2')) $('select.price_store').select2('destroy');
    if ($('select.price_store_professional').data('select2')) $('select.price_store_professional').select2('destroy');
}

function checkDataToChange()
{
    var status_change = false

    $.each($('table tbody select'), function( index, value ) {
        if ($(this).val() != $(this).parent().find('span[field="' + $(this).attr('field') + '"]').text()) {
            status_change = true;
            return false;
        }
    });

    return status_change
}

function validateData()
{
    var status_pickup = true;
    $.each($('table tbody tr[id]'), function( index, value ) {
        var pickup_val = $(this).find('select[field="pickup"]').val();
        var price_store_val = $(this).find('select[field="price_store"]').val();
        var price_store_professional_val = $(this).find('select[field="price_store_professional"]').val();

        if (pickup_val == 'Y' && (price_store_val == '' || price_store_professional_val == '')) {
            status_pickup = false;
            return false;
        }
    });

    return status_pickup;
}

function getDataToChange()
{
    var data_change = {};
    $.each($('table tbody tr[id]'), function( index, value ) {
        var id = $(this).attr('id');

        var pickup_val = $(this).find('select[field="pickup"]').val();
        var price_store_val = $(this).find('select[field="price_store"]').val();
        var price_store_professional_val = $(this).find('select[field="price_store_professional"]').val();

        var pickup_original = $(this).find('span[field="pickup"]').text();
        var price_store_original = $(this).find('span[field="price_store"]').text();
        var price_store_professional_original = $(this).find('span[field="price_store_professional"]').text();

        if(pickup_val != pickup_original){
            if(typeof data_change[id] == 'undefined'){
                data_change[id] = {pickup: pickup_val};
            } else {
                data_change[id].pickup = pickup_val;
            }
        }
        if(price_store_val != price_store_original){
            if(typeof data_change[id] == 'undefined'){
                data_change[id] = {price_store: price_store_val};
            } else {
                data_change[id].price_store = price_store_val;
            }
        }
        if(price_store_professional_val != price_store_professional_original){
            if(typeof data_change[id] == 'undefined'){
                data_change[id] = {price_store_professional: price_store_professional_val};
            } else {
                data_change[id].price_store_professional = price_store_professional_val;
            }
        }
    });
    
    return (Object.size(data_change) > 0) ? data_change : false;
}

function resetAll()
{
    hideEdit();
    $("select.pickup, select.price_store, select.price_store_professional").each(function(){
        var field = $(this).attr('field');
        var originalVal = $(this).parent().find("span." + field).text();
        $(this).val(originalVal);
    });
}
</script>
@endsection
