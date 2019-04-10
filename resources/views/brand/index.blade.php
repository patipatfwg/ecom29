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

@section('title', 'Brand')

@section('breadcrumb')
<li class="active">Brand</li>
@endsection

@section('header_script')
<style>
    
    .input-width-priority-80 {
        background: #fff;
        width: 60px !important;
        height: 30px;
        padding: 0px !important;
    }
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 6px 8px;
        line-height: 1.5384616;
        vertical-align: middle;
        border-top: 1px solid #ddd;
    }

</style>
@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
     {!! Form::open(['autocomplete' => 'off', 'class'=> 'form-horizontal','id'=> 'search-form']) !!}
        <div class="col-lg-12">
            <div class="row">
                <div class="form-group">
                    <div class="col-lg-6">
                        <label>Brand Name</label>
                        {{ Form::text('full_text', null, [
                            'id'          => 'full_text',
                            'class'       => 'form-control',
                            'placeholder' => 'Brand Name'
                        ]) }}
                    </div>
                    <div class="col-lg-6">   
                        <label>Brand ID</label>
                        {{ Form::text('brand_id', null, [
                            'id'          => 'brand_id',
                            'class'       => 'form-control',
                            'placeholder' => 'Brand ID'
                        ]) }}         
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
        </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped datatable-dom-position" id="brands-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th><input type="checkbox" class="check-all" autocomplete="off"></th>
					<th>No.</th>
					<th>Brand ID</th>
					<th>Brand Name (TH)</th>
					<th>Brand Name (EN)</th>
					<th>Priority</th>
                    <th>Published</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="9" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('footer_script')

<script type="text/javascript">
    var url = '/brand';

    var $table = $('#brands-table');
    var $thead = $table.find('thead');
    var $tbody = $table.find('tbody');

    //set dataTable
    var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: 'Error connection', type: 'error' });
    }).removeAttr('width').DataTable({
        autoWidth: false,
        scrollY: true,
        scrollX: '300px',
        processing: false,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 2, "desc" ]],
        cache: true,
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url + '/getAjaxBrand',
            type: 'POST',
            data: function (d) {
                d.parent_id = null;
                d.full_text  = $('#full_text').val();
                d.brand_id = $('#brand_id').val();
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
        columns: [
            {
                data: 'id',
                name: 'checkbox',
                width: '2%',
                orderable: false, 
                searchable: false,
                render: function(data, type, row){
                    return '<input class="ids check" type="checkbox" name="brand_ids[]" value="'+data+'">';
                }
            },
            { 
                data: 'number', 
                name: 'number',
                width: '2%',
                orderable: false,
                searchable: false
            },
            { data: 'id', name: 'id', width: '5%' },
            { data: 'name_th', name: 'name_th' },
            { data: 'name_en', name: 'name_en' },
            {
                data: 'priority',
                name: 'priority',
                width: '8%',
                searchable: false,
                render: function(data, type, row){
                    return '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center" type="text" id="brand-'+row['id']+'-priority" name="priority['+row.id+']" priority="'+data+'" value="'+data+'">';
                }
            },{ 
                data: 'status',
                name: 'status',
                width: '5%',
                orderable: false,
                className: 'text-center',
                render: function(data, type, row){
                    if(data == 'active'){
                        return '<i class="icon-eye text-teal"></i>';
                    }
                    else{
                        return '<i class="icon-eye-blocked text-grey-300"></i>';
                    }
                }
            },
            { 
                data: 'edit',
                name: 'edit',
                width: '5%',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="'+data+'"><i class="icon-pencil"></i></a>';
                }
            },
            { 
                data: 'id',
                name: 'delete',
                width: '5%',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="#" data-delete="'+data+'"><i class="icon-trash text-danger"></i></a>';
                }
            }
        ],
        drawCallback : function(settings) {

            $('[data-delete]').on('click',function(event){
                event.preventDefault();
                var id = $(this).attr('data-delete');
                var url = 'brand/check_del/'+id;
                     $.ajax({
                        method: 'GET',
                        url: url,
                        success: function(data){
                            if(data['data_list'][0].total > 0) {
                                swal({
                                  title: "This brand is in use",
                                  type: "warning",
                                  confirmButtonText: "ok",
                                  closeOnConfirm: true
                                });
                            }else{
                                swal({
                                    title: "{{ trans('validation.delete.alert.title') }}",
                                    type: "warning",
                                    showCancelButton: true,
                                    cancelButtonText: "{{ trans('validation.delete.alert.btn_cancel') }}",
                                    confirmButtonColor: '#DD6B55',
                                    confirmButtonText: "{{ trans('validation.delete.alert.btn_ok') }}",
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: true,
                                },
                                function(isConfirm){
                                    if (isConfirm) {
                                        deleteBrand(id);
                                    }
                                });
                            }
                        },
                        complete: function (data) {
                            $('.check-all').prop('checked', false);
                        }
                    });
            });
        }
    });

    $('div.datatable-header').append(`
        @include('common._status_dropdown')&nbsp;
        @include('common._delete_button')&nbsp;
        @include('common._priority_button')&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('brand/create')])
    `);

    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('body').on('click', '.check-all', function() {
        $('table input:checkbox').not(this).prop('checked', this.checked);
    });

    $('body').on('click', '.check', function() {
        $('table .check-all').prop('checked', false);
    });

    $('body').on('click', '.status-checkbox', function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: url + '/status',
            data: $('.ids:checked').serialize() + '&status=' + $(this).attr('status-data'),
            success: function(data){
                swal("{{ trans('validation.create.title') }}", "", 'success');
                oTable.draw();
            },
            complete: function (data) {
                $('.check-all').prop('checked', false);
            }
        });
    });

    $('body').on('click', '.datatable-button', function(event) {
        event.preventDefault();
        $('.check-all').prop('checked', false);
        var action = $(this).attr('button-action');
        if(action == 'show'){
            callAjax('PUT', url+"/status/"+getCheckedId(),{status:'active'}, function(){
                oTable.draw();
            });
        }
        else if(action == 'hide'){
            callAjax('PUT', url+"/status/"+getCheckedId(),{status:'inactive'}, function(){
                oTable.draw();
            });
        }
    });

    $('body').on('click', '.btn-delete', function(event) {
        event.preventDefault();
                var id = getCheckedId();
                var url = 'brand/check_del/'+id;

        var action = $(this).attr('button-action');
        if(action == 'delete'){
                    $.ajax({
                        method: 'GET',
                        url: url,
                        success: function(data){
                             if(data.del_list.length > 0){
                                if(data.error > 0){
                                    swal({
                                      title: "This brand is in use",
                                      text: data.text,
                                      type: "warning",
                                      confirmButtonText: "ok",
                                      closeOnConfirm: false
                                    },
                                    function(){
                                        deleteItems(data.del_list);
                                    });
                                }else{
                                    swal({
                                      title: "You can delete",
                                      text: data.text,
                                      type: "warning",
                                      confirmButtonText: "ok",
                                      closeOnConfirm: false
                                    },
                                    function(){
                                        deleteItems(data.del_list);
                                    });
                                }
                             }else{
                                     swal({
                                      title: "This brand is in use.",
                                      text: data.text,
                                      type: "warning",
                                      confirmButtonText: "ok",
                                      closeOnConfirm: true
                                    });
                             }

                        },
                        complete: function (data) {

                        }
                    });
            
        }
        // Unset Checkbox
        // $('.check-all').prop('checked', false);
        // $('table input:checkbox').not(this).prop('checked', false);
    });

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content') + "/brand/export?" + $.param(data));
    });

    $('body').on('click', '.btn-priority', function(event) {
        var data = oTable.rows().data();
        var params = [];
        var count = 0;
        for(var i=0; i<data.length; i++){

            var priority_new = $('#brand-'+data[i].id+'-priority').val();
            var priority_old = $('#brand-'+data[i].id+'-priority').attr('priority');

            if(priority_new != priority_old){
                params[count] = {
                    category_id: data[i].id,
                    seo_subject: data[i].seo_subject,
                    seo_explanation: data[i].seo_explanation,
                    priority: priority_new,
                    slug: data[i].slug,
                    status: data[i].status
                }
                count++;
            }
        }
        
        if (params.length <= 0) {
            return;
        }

        // call ajax
        $.ajax({
            type: 'PUT',
            url: url + '/priority',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(params),
            dataType: 'json',
            success: function(data) {
                if (data.status || data.success) {
                    onAjaxSuccess(data);
                    oTable.draw('page');
                } 
                else {
                    onAjaxFail(data);
                }
            },
            error: onAjaxError,
            complete: function() {

            }
        });
        
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
                            onAjaxSuccess(data);
                            oTable.draw('page');
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

    function deleteBrand(_id) {
        $.ajax({
            type: 'DELETE',
            url: $("meta[name='root-url']").attr('content') + '/brand/' + _id,
            dataType: 'json',
            success:function(data){
                if (data.success) {
                    swal('Deleted!', null, 'success');
                    oTable.draw('page');
                } else {
                    swal('Deleted!', null, 'warning');
                }
            },
            error: function(){
                swal('Deleted!', 'Error connection', 'error');
            }
        });
    }
</script>
@include('common._priority_script')
@include('common._call_ajax')

<script type="text/javascript">
<?php
if($status['status']){
    if($status['msg']['success']){
        echo "swal('Edit!','".$status['msg']['messages']."', 'success');";
    }else{
        echo "swal('Edit!','".$status['msg']['messages']."', 'error');";
    }
}
?>
</script>

@endsection