<?php
$scripts = [
    'datatables',
    'datatablesFixedColumns',
    'select2',
    'sweetalert',
    'datetimepicker',
    'bootstrap-select'
];
?>

@extends('layouts.main')

@section('title', 'Product')

@section('breadcrumb')
<li class="active">Product</li>
@endsection

@section('header_script')
    {{ Html::style('assets/css/dropdown.custom.css') }}
    <style>
        .action-icon {
            font-size: 2.25em;
        }
    </style>
@endsection
    
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

                @include('product.search')

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

<!-- Export Form -->
<div id="hidden_form_export" style="display:none;"></div>

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped table-hover datatable-dom-position" id="products-table" data-page-length="10" width="300%">
			<thead>
				<tr>
					<th>
						<input type="checkbox" class="check-all">
					</th>
					<th>Item&nbsp;ID</th>
					<th>Published</th>
					<th>Approval Status</th>
                    <th>RMS Status</th>
					<th width="200">Last Update</th>
					<th width="20">Product Name (TH)</th>
					<th width="20">Product Name (EN)</th>
					<th width="10">Supplier ID</th>
					<th width="20">Supplier Name</th>
					<th width="10">Buyer ID</th>
					<th width="20">Buyer Name</th>
					<th>Image</th>
					<th>Detail</th>
					<th>Normal Price</th>
					<th>Category</th>
					<th width="10">Priority</th>
					<th width="10">Edit</th>
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
        scrollY: true,
        scrollX: '300px',
        fixedColumns: {
            leftColumns: 4,
            rightColumns: 1,
            //heightMatch: 'none'
        },
        processing: false,
        serverSide: true,
        searching: false,
        retrieve: true,
        autoWidth: false,
        destroy: true,
        order: [[5, "desc"]],
        cache: true,
        dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/product/data',
            type: 'POST',
            data: function (d) {

                d.name = $('#input_product_name').val();
                d.supplier = $('#input_supplier').val();
                d.buyer = $('#input_buyer').val();
                d.item_id = $('#input_item_id').val();
                d.updated_after = $('#input_updated_date').val();
                d.approve_status = $('#select-approval').val();
                d.published_status = $('#select-publish').val();
                d.action_status = $('#select-action-status').val();
                d.category = { business: $('.dropdown-input[group-name="business"]').val(),product: $('.dropdown-input[group-name="product"]').val()};
                d.have_image = $('#select-image-status').val();
                d.makro_store_id = $('#store_id').val();
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
                        window.location.replace("./");
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
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: "50px",
                    render: function (data, type, row) {
                    return '<input class="ids check" type="checkbox" name="product_ids[]" value="' + row['item_id'] + '" class="check">';
                }
            },
            {
                data: 'item_id',
                name: 'item_id',
                //width: "200px",
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'published_status',
                name: 'published_status',
                className: 'text-center',
                //width: "200px",
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="icon-eye text-teal"></i>';
                    }
                    else {
                        return '<i class="icon-eye-blocked text-grey"></i>';
                    }
                }
            },
            {
                data: 'approve_status',
                name: 'approve_status',
                className: 'text-center',
                //width: "200px"
            },
            {
                data: 'action_status',
                name: 'action_status',
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'RMS Updated') { 
                        return '<i class="glyphicon glyphicon-one-fine-dot action-warning action-icon"></i>';
                    } else if (data == 'RMS New') {
                        return '<i class="glyphicon glyphicon-one-fine-dot text-success action-icon"></i>';
                    } else {
                        return '<i class="glyphicon glyphicon-one-fine-dot action-icon text-gray"></i>';
                    }
                }
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'name_th',
                name: 'name_th',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'name_en',
                name: 'name_en',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'supplier_id',
                name: 'supplier_id'
            },
            {
                data: 'supplier_name',
                name: 'supplier_name',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'buyer_id',
                name: 'buyer_id'
            },
            {
                data: 'buyer_name',
                name: 'buyer_name',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'have_image',
                name: 'have_image',
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="glyphicon glyphicon-ok text-success"></i>';
                    }
                    else {
                        return '<i class="glyphicon glyphicon-remove text-danger"></i>';
                    }
                }
            },
            {
                data: 'have_detail',
                name: 'have_detail',
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="glyphicon glyphicon-ok text-success"></i>';
                    }
                    else {
                        return '<i class="glyphicon glyphicon-remove text-danger"></i>';
                    }
                }
            },
            {
                data: 'normal_price',
                name: 'normal_price',
                className: 'text-right',
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'have_categories',
                name: 'have_categories',
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="glyphicon glyphicon-ok text-success"></i>';
                    }
                    else {
                        return '<i class="glyphicon glyphicon-remove text-danger"></i>';
                    }
                }
            },
            {
                data: 'priority',
                name: 'priority',
                className: 'text-center',
                render: function(data, type, row){
                    return '<select id="product-'+row['id']+'-priority" class="priority-select" priority="'+data+'"></select>';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    return '<a href="product/' + data + '/edit"><i class="icon-pencil"></i></a>';
                }
            }
        ],
        drawCallback : function(settings) {
            $(".priority-select").select2({
                data: [
                    { id: 1, text: '1'},
                    { id: 2, text: '2'},
                    { id: 3, text: '3'},
                    { id: 4, text: '4'},
                    { id: 5, text: '5'}      
                ],
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

            $('.priority-select').each(function() {
                var priority = $(this).attr('priority');
                if(priority > 5){
                    priority = 5;
                }
                $(this).val(priority).trigger('change');
            });
        }
    });

    $('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('#store_id').select2({
        placeholder: 'Select Store List ...',
        allowClear: true
    });

    $('div.datatable-header').append('\
        <div class="btn-group">\
            <button type="button" class="btn btn-width-200 bg-brown-400 btn-raised dropdown-toggle" data-toggle="dropdown">ACTION<span class="caret"></span></button>\
            <ul class="dropdown-menu dropdown-menu-left">\
                <li><a class="status-checkbox" status="approved" href="#"><i class="glyphicon glyphicon-ok"></i>Approve</a></li>\
                <li><a class="status-checkbox" status="ready" href="#"><i class="glyphicon glyphicon-hourglass"></i>Ready to approve</a></li>\
                <li class="divider"></li>\
                <li><a class="status-checkbox" status="active" href="#"><i class="icon-eye"></i> Publish</a></li>\
                <li><a class="status-checkbox" status="inactive" href="#"><i class="icon-eye-blocked"></i> Unpublish</a></li>\
            </ul>\
        </div>&nbsp;\
        <div class="btn-group">\
            <button type="button"class="btn btn-priority btn-width-100 btn-primary btn-raised legitRipple">\
                <i class="glyphicon glyphicon-floppy-disk"></i> Save\
            </button>\
        </div>&nbsp;\
        <div class="btn-group">\
            <a href="#" target="_blank" class="print-report btn btn-width-100 bg-violet-300 btn-raised legitRipple">\
            <i class="icon-file-download"></i> EXPORT</a>\
        </div>\
    ');

    $('body').on('click', '.check-all', function () {
        $('table.DTFC_Cloned input:checkbox').not(this).prop('checked', this.checked);
    });

    $('body').on('click', '.check', function () {
        $('table.DTFC_Cloned .check-all').prop('checked', false);
    });

    // Status: Approved, Ready, Editing, Active, Inactive
    $('body').on('click', '.status-checkbox', function (event) {
        event.preventDefault();

        // Not Select Item
        if(isChecked() == false){
            return;
        }

        callAjaxCustom(
            'POST', 
            $("meta[name='root-url']").attr('content') + '/product/status',
            $('table.DTFC_Cloned .ids:checked').serialize() + '&status=' + $(this).attr('status'),
            'formData',
            function(data){
                onAjaxMultipleItem(data.data.updated, data.data.errors, null, 'item_id');
                $('table.DTFC_Cloned .check-all').prop('checked', false);
                oTable.draw('page');
            }
        );

    });

    // $('body').on('click', '.btn-sync', function (event) {
    //     event.preventDefault();
    //     callAjax('POST', $("meta[name='root-url']").attr('content') + '/product/sync', null);
    // });

    $('body').on('click', '.btn-priority', function (event) {
        event.preventDefault();

        var params = getUpdatePriorityInput();

        if(params == null){
            return;
        }

        callAjaxCustom(
            'POST', 
            $("meta[name='root-url']").attr('content') + '/product/priority',
            JSON.stringify(params),
            'json',
            function(data){
                var data = data.data;
                onAjaxMultipleItem(data.updated, data.errors, null, 'item_id');
                $('table.DTFC_Cloned .check-all').prop('checked', false);
                oTable.draw('page');
            }
        );
    });

    $('body').on('click', '.btn-delete', function (event) {
        event.preventDefault();
        deleteItems(getCheckedId());
    });

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        var export_url = $("meta[name='root-url']").attr('content') + "/product/export";
        // window.location = ($("meta[name='root-url']").attr('content') + "/product/export?" + $.param(data));

        data.name = $('#input_product_name').val();
        data.supplier = $('#input_supplier').val();
        data.buyer = $('#input_buyer').val();
        data.item_id = $('#input_item_id').val();
        data.updated_after = $('#input_updated_date').val();
        data.approve_status = $('#select-approval').val();
        data.published_status = $('#select-publish').val();
        data.action_status = $('#select-action-status').val();
        data.category = { business: $('.dropdown-input[group-name="business"]').val(),product: $('.dropdown-input[group-name="product"]').val()};

        postExportProductData(export_url, data);
    });

    $('.select-dropdown').select2({
        minimumResultsForSearch: -1
    });

    function deleteItems(ids){
        swal({
            title: "{{ trans('validation.delete.alert.title') }}",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "{{ trans('validation.delete.alert.btn_cancel') }}",
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "{{ trans('validation.delete.alert.btn_ok') }}",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function(isConfirm){
            if (isConfirm) {
                callAjax('DELETE', $("meta[name='root-url']").attr('content') + '/product/' + ids, null, null, null, function(){
                    oTable.draw();
                });
            }
        });
    }

    function getUpdatePriorityInput()
    {   
        var data = oTable.rows().data();
        var params = [];
        var count = 0;
        for(var i=0; i<data.length; i++){
            var priority_new = $('#product-'+data[i].id+'-priority').select2('data')[0].text;
            var priority_old = $('#product-'+data[i].id+'-priority').attr('priority');

            if(priority_new != priority_old){
                params[count] = {
                    product_id: data[i].item_id,
                    priority: priority_new
                };            
                count++;
            }
        }
        
        if (count <= 0) {
            return null;
        }

        return params;
    }

    function postExportProductData (url, data) {

        // Remove old form
        $("#hidden_form_export").children().remove();
        
        var theForm;

        // Start by creating a <form>
        theForm = document.createElement('form');
        theForm.action = url;
        theForm.method = 'post';
        theForm.enctype = 'multipart/form-data';

        // Next create the <input>s in the form and give them names and values
        var _token = document.createElement('input');
        _token.type = 'hidden';
        _token.name = '_token';
        _token.value = "{{ csrf_token() }}";
        theForm.appendChild(_token);
                    
        $.each( data, function( key, value ) {
            
            if(key == 'draw' || key == 'columns' || key == 'search'){
                return;
            }

            if(key == 'category'){
                var business_category = document.createElement('input');
                business_category.type = 'hidden';
                business_category.name = 'category[business]';
                business_category.value = value.business;
                theForm.appendChild(business_category);

                var product_category = document.createElement('input');
                product_category.type = 'hidden';
                product_category.name = 'category[product]';
                product_category.value = value.product;
                theForm.appendChild(product_category);
            }
            else if(key == 'order'){
                var order_column = document.createElement('input');
                order_column.type = 'hidden';
                order_column.name = 'order[0][column]';
                order_column.value = value[0].column;
                theForm.appendChild(order_column);

                var order_dir = document.createElement('input');
                order_dir.type = 'hidden';
                order_dir.name = 'order[0][dir]';
                order_dir.value = value[0].dir;
                theForm.appendChild(order_dir);
            }
            else{
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                theForm.appendChild(input);
            }
        });

        // ...and it to the DOM...
        document.getElementById('hidden_form_export').appendChild(theForm);
        // ...and submit it
        theForm.submit();
    }

</script>

    @include('common._datetime_script', [
        'refer' => '#input_updated_date',
        'format' => 'd/m/Y'
    ])
    @include('common._dropdown_script')
    @include('common._call_ajax')

@endsection