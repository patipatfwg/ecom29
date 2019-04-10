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

@section('title', 'Banner')

@section('breadcrumb')
<li class="active">Banner</li>
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
        <div class="col-lg-12"> 
        {!! Form::open(['autocomplete' => 'off', 'class'=> 'form-horizontal','id'=> 'search-form']) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-lg-12">
                        <label>Banner Name</label>
                        {{ Form::text('search-text-input', null, [
                            'id'          => 'search-text-input',
                            'class'       => 'form-control',
                            'placeholder' => 'Banner Name'
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
<!-- End: New campaign panel -->

<!-- Start: Campaign list panel -->

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-striped table-hover datatable-dom-position" id="campaign-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th class="" width="50"><input type="checkbox" class="check-all"></th>
					<th class="">Image</th>
					<th class="">Banner Name</th>
					<th class="">Hyperlink</th>
					<th class="">Position</th>
					<th class="">Create Date</th>
					<th class="">Update Date</th>
					<th class="">Edit</th>
					<th class="">Delete</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<!-- End: Campaign list panel -->

@endsection

@section('footer_script')

<script type="text/javascript">

    // Datetime
    $.datetimepicker.setLocale('th');
    $('#launch-date-input').datetimepicker({
        format: 'Y-m-d H:i:s'
    });

    var url = '/banner';
    
    var oTable = $('#campaign-table').on('error.dt',function(e, settings, techNote, message){
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
                //launch-date-input
                d.search_text_input = $('#search-text-input').val();
                d.launch_date_input = $('#launch-date-input').val();
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
                    $('#campaign-table').find('tbody').find('td').html('No Data, please try again later');
                }
            }
        },
        columns: [
            {
                data: 'id',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<input class="ids check" type="checkbox" name="user_group_ids[]" value="'+data+'">';
                }
            },
            {
                data: 'imageUrl',
                name: 'imageUrl',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<img src="'+data+'?process=resize&resize_width=120&resize_height=80" target="_blank" style="width: 120px; height: 80px; border: 2px solid #5a5b5b;" alt="">';
                }
            },
            { data: 'name', name: 'name'},
            {
                data: 'redirectUrl',
                name: 'redirectUrl',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return data;
                }
            },
            { data: 'position', name: 'position'},
            { data: 'createAt', name: 'createAt'},
            { data: 'updateAt', name: 'updateAt'},
            {
                data: 'edit',
                name: 'edit',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="'+data+'"><i class="icon-pencil"></i></a>';
                }
            }, 
            {
                data: 'delete',
                name: 'delete',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return '<a onclick="deleteItems(\'' + row.id + '\')"><i class="icon-trash text-danger"></a>';
                }
            }
        ]
    });

    // Data table header
    $('div.datatable-header').append(`
        @include('common._delete_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('banner/create/')])
    `);
    if (!!window.performance && window.performance.navigation.type === 2) {
        //window.location.reload();
        $('.check-all').attr('checked', false);
    }
</script>

@include('common._datatable')
@endsection
