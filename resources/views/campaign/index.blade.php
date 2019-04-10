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

@section('title', 'Campaign')

@section('breadcrumb')
<li class="active">Campaign</li>
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
                    <div class="col-lg-8">
                        <label>Campaign Code, Campaign Name</label>
                            {{ Form::text('search-text-input', null, [
                                            'id'          => 'search-text-input',
                                            'class'       => 'form-control',
                                            'placeholder' => 'Campaign Code, Campaign Name'
                            ]) }}

                    </div>
                    <div class="col-lg-4">
                        <label>Start Before</label>
                            {{ Form::text('launch-date-input', null, [
                                'id'          => 'launch-date-input',
                                'class'       => 'form-control',
                                'placeholder' => 'DD/MM/YYYY'
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
<!-- End: New campaign panel -->

<!-- Start: Campaign list panel -->

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped table-hover datatable-dom-position" id="campaign-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th class="" width="50"><input type="checkbox" class="check-all"></th>
					<th class="" width="50">No.</th>
					<th class="" width="150">Campaign Code</th>
					<th class="">Campaign Name (TH)</th>
					<th class="">Campaign Name (EN)</th>
					<th class="" width="150">Created Date</th>
					<th class="" width="150">Start Date</th>
					<th class="" width="150">End Date</th>
					<th class="" width="50">Published</th>
					<th class="" width="50">Edit</th>
					<th class="" width="50">Product List</th>
					<th class="" width="50">Delete</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<!-- End: Campaign list panel -->

@endsection

@section('footer_script')

<script type="text/javascript">

    var url = '/campaign';

    var oTable = $('#campaign-table').on('error.dt',function(e, settings, techNote, message){
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
            { data: 'number', name: 'number',orderable: false},
            { data: 'campaignCode', name: 'campaignCode' },
            { data: 'name_th', name: 'name_th' },
            { data: 'name_en', name: 'name_en' },
            { data: 'createdAt', name: 'createdAt'},
            { data: 'startDate', name: 'startDate'},
            { data: 'endDate', name: 'startDate'},
            {
                data: 'status',
                name: 'status',
                orderable: true,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    if(data == 'active'){
                        return '<i class="icon-eye text-teal"></i>';
                    } else if(data == null) {
                        return 'NULL';
                    } else {
                        return '<i class="icon-eye-blocked text-grey-300"></i>';
                    }
                }
            },
            {
                data: 'edit',
                name: 'edit',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    if((row.currentDateTimestamp <= row.endDateTimestamp && row.currentDateTimestamp >= row.startDateTimestamp) && row.status == 'active'){
                        return '<i class="icon-pencil"></i>';
                    } else {
                        return '<a href="'+data+'"><i class="icon-pencil"></i></a>';
                    }
                }
            },
            {
                data: 'id',
                name: 'mapping',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="campaign/'+data+'"><i class="icon-link"></i></a>';
                }
            },
            {
                data: 'id',
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
        @include('common._status_dropdown')&nbsp;
        @include('common._delete_button')&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('campaign/create/')])
    `);

     $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content') + "/campaign/export?" + $.param(data));
    });
</script>

@include('common._datetime_script', [
    'refer' => '#launch-date-input',
    'format' => 'd/m/Y'
])
@include('common._datatable')
@endsection
