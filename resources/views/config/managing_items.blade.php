<?php
    $scripts = [
        'nestable',
        'sweetalert',
        'select2',
        'datatables',
        'datetimepicker'
    ];
?>

@extends('layouts.main')

@section('title', 'Payment Option Item List')

@section('breadcrumb')
<li><a href="/config/payment_method">Installment Option</a></li>
<li class="active">Managing Items</li>
@endsection

@section('header_script')
{{ Html::style('assets/css/dropdown.custom.css') }}
@endsection

@section('content')

@include('config.modal.modal')
<!-- Start: Search panel -->
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
      <div class="col-lg-12"> 
      {!! Form::open(['autocomplete' => 'off', 'class'=> 'form-horizontal','id'=> 'search-form']) !!}
          <div class="row">
              <div class="form-group">
                  <div class="col-lg-12">
                      <label>Item Name or Code</label>
                      {{ Form::text('search-text-input', null, [
                          'id'          => 'search-text-input',
                          'class'       => 'form-control',
                          'placeholder' => 'Item Name or Code'
                      ]) }}                 
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
<!-- End: Search panel -->

<!-- Start: Enable type panel -->
<div class="panel">
    <div class="panel-body">
      <h6 class="panel-title">Enable this payment option on</h6>
      <div class="radio">
        <label><input type="radio" name="enableType" value="product in list">Products in this list</label>
      </div>
      <div class="radio">
        <label><input type="radio" name="enableType" value="except product">All products EXCEPT products in this list</label>
      </div>
    </div>
</div>
<!-- End: Enable when panel -->

<!-- Start: Item list panel -->

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped table-hover datatable-dom-position" id="item-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th class="" width="50"><input type="checkbox" class="check-all"></th>
					<th class="" width="50">Item Type</th>
					<th class="" width="150">Item Code</th>
					<th class="">Item Name (TH)</th>
					<th class="">Item Name (EN)</th>
					<th class="" width="50">Delete</th>
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

<!-- End: Campaign list panel -->

@endsection

@section('footer_script')

<script type="text/javascript">

    var url = '/config/payment_method/{{ $install["id"] }}';

    var oTable1 = $('#item-table').on('error.dt',function(e, settings, techNote, message){
      console.log( 'An error has been reported by DataTables: ', message );
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '100%',
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
            data: function (d) {
                d.search_text_input = $('#search-text-input').val();
                
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
                    $('#item-table').find('tbody').find('td').html('No Data, please try again later');
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
                data: 'content_id',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<input class="ids check" type="checkbox" name="item_ids[]" value="'+data+'">';
                }
            },
            { data: 'itemType', name: 'itemType' },
            { data: 'item_id', name: 'item_id' },
            { data: 'name_th', name: 'name_th' },
            { data: 'name_en', name: 'name_en' },
            {
                data: 'content_id',
                name: 'delete',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a onclick="deleteItems(\'' + data + '\')"><i class="icon-trash text-danger"></a>';
                }
            }
        ]
    });

    // Data table header
    $('div.datatable-header').append(`
        @include('common._delete_button')&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('config/payment_method/'.$install["id"].'/product/add')])
    `);

    $('input[name="enableType"][value="{{ $install["enable_type"] }}"]').attr('checked', true);
    // Change enableType
    $('input[name="enableType"]').on('change', function(event) {
        type = $('input[name="enableType"]:checked').val();
        $.ajax({
            type: 'PUT',
            url: '/config/payment_method/{{ $install["id"] }}/enableType',
            dataType: 'json',
            data: {
                enableType: type,
            },
            success: function(data) {
                if (data.status || data.success) {
                    // onDeleted(data);
                    onAjaxSuccess(data);
                    oTable1.draw('page');
                } 
                else {
                    onAjaxFail(data);
                }
            },
            error: onAjaxError,
            complete: function() {
            }
        });
    })
    $('#search-form').on('submit', function(e) {
        oTable1.draw();
        e.preventDefault();
    });
    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable1.ajax.params();
        // console.log(url);
        window.location.replace($("meta[name='root-url']").attr('content') + url + '/report?' + $.param(data));
    });

    $('body').on('click', '.datatable-button', function(event) {
        event.preventDefault();

        $('.check-all').prop('checked', false);
        var action = $(this).attr('button-action');
        
        if(action == 'delete'){
            ids = $('.ids:checked')
                .serializeArray()
                .map(function(elem) {
                    return elem.value;
                }).join()
            deleteItems(ids);
        }
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
                $.ajax({
                    type: 'DELETE',
                    url: url + '/' + ids,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status || data.success) {
                            // onDeleted(data);
                            onAjaxSuccess(data);
                            oTable1.draw('page');
                        } 
                        else {
                            onAjaxFail(data);
                        }
                    },
                    error: onAjaxError,
                    complete: function() {
                    }
                });
            }
        });
    }
</script>

@include('config.modal.modal_script')
@include('common._dropdown_script_with_reset')
@include('common._call_ajax')
@endsection
