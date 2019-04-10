<?php
$scripts = [
    'select2',
    'sweetalert',
    'datatables'
];
?>

@extends('layouts.main')

@section('title', 'Attribute')

@section('breadcrumb')
<li class="active">Attribute</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
        <form method="POST" id="search-form" class="form-horizontal">
            <div class="col-lg-12">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Attribute Name</label>
                            <input type="text" id="q" name="q" class="form-control" placeholder="Attribute Name" value="{{ $textsearch }}" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                        <button type="submit" class="pull-right btn bg-teal-400 btn-raised legitRipple legitRipple">
                        <i class="icon-search4"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div> 
        </form>
    </div>
</div>

<div class="panel">
 <div class="panel-body table-responsive">
  <div class="panel-default">
   <table class="table table-border-gray table-striped table-hover datatable-dom-position" id="attribute-table" data-page-length="10" width="100%">
    <thead>
     <tr>
      <th class="" width="20">No.</th>
      <th class="">Attribute Name (TH)</th>
      <th class="">Attribute Name (EN)</th>
      <th class="">Attribute Values</th>
      <th class="">Last&nbsp;Update</th>
      {{-- 
      <th class="">Products</th>
      --}}
      <th class="">Delete</th>
      <th class="">Edit</th>
     </tr>
    </thead>
   </table>
  </div>
 </div>
</div>

@endsection

@section('footer_script')
<script type="text/javascript">
var appurl = '/attribute';
var oTable;
$(function(){

    $.fn.dataTable.ext.errMode = 'none';
    oTable = $('#attribute-table').on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        fixedColumns: {
            leftColumns: 3,
            rightColumns: 2,
            //heightMatch: 'none'
        },
        scrollY: true,
        scrollX: '300px',
        destroy : true,
        order: [[ 4, 'desc' ]],
        bAutoWidth: '100%',
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            type:'POST',
            url: appurl + "/getAjaxAttribute",
            data: function (d) {
                d.search  = $( '#search-form' ).serializeArray();
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
                }
            }
        },
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['data'];
            });
        },
        columns: [
            { data: 'attr_no', name: 'attr_no', orderable: false, searchable: false},
            { data: 'attr_name_th', name: 'attr_name_th'},
            { data: 'attr_name_en', name: 'attr_name_en'},
            { data: 'attr_sub', name: 'attr_sub', orderable: false },
            { data: 'update_at', name: 'update_at'},
            {{-- { data: 'have_products', name: 'have_products', orderable: false }, --}}
            { data: 'delete', name: 'delete', orderable: false, searchable: false, className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],

        drawCallback : function(settings) {
            $('[data-delete]').on('click',function(){
                event.preventDefault();
                var id = $(this).attr('data-delete');
                swal({
                    title: 'Are you sure?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, delete it!',
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                },
                function(isConfirm){
                    if (isConfirm) {
                        deleteCategory(id);
                    }
                });
            });

            
        }
    });

    $('div.datatable-header').append(`
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('/attribute/add')])
        
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
        window.location.replace($("meta[name='root-url']").attr('content') + "/attribute/export?" + $.param(data));
    });

});

</script>
<script type="text/javascript">
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
                callAjax('DELETE' , appurl+"/"+ ids, null, null, null, function(){
                    oTable.draw();
                });
            }
        });
    }
    function callAjax(type, url, data, successCallback = null, postSuccessCallback = null, completeCallback = null, postFailCallback = null) {
        $.ajax({
            type: type,
            url: url,
            data: data,
            dataType: 'json',
            success: function(data) {
                if (data.status || data.success) {
                    if(successCallback) {
                        successCallback();
                    }
                    onAjaxSuccess(data, postSuccessCallback);
                } else {
                    if (data.expired) {
                        swal("{{ trans('validation.delete.title') }}", "Session Expired", 'error');
                    }

                    onAjaxFail(data, postFailCallback);
                }
            },
            error: onAjaxError,
            complete: function() {
                if(completeCallback) {
                    completeCallback();
                }
            }
        });
    }
    function onAjaxError() {
        swal("{{ trans('validation.delete.title') }}", "{{ trans('validation.delete.fail') }}", 'error');
    }
    function onAjaxSuccess(data, callback = null) {
        swal({
            title: "{{ trans('validation.delete.title') }}",
            text: "{{ trans('validation.delete.success') }}",
            type: "success",
            confirmButtonText: "{{ trans('validation.btn_ok') }}"
            },
            callback
        );
    }

    function onAjaxFail(data, callback = null) {
        swal({
            title: "{{ trans('validation.delete.title') }}",
            text: "{{ trans('validation.delete.fail') }}",
            type: "warning",
            confirmButtonText: "{{ trans('validation.btn_ok') }}"
            },
            callback
        );
    }
</script>
@endsection
