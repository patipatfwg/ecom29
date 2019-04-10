<?php
$scripts = [
    'datatables',
    'datatablesFixedColumns',
    'select2',
    'sweetalert',
    'datetimepicker',
    'bootstrap-select',
    'ckeditor',
    'to-markdown',
    'showdown'
];
?>



@extends('layouts.main')

@section('title', 'Content')

@section('breadcrumb')
<li class="active">Content</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
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
                            <div class="col-md-6">
                                <label>Content Name, Slug</label>
                                {{ Form::text('full_text', null, [
                                    'id'          => 'full_text',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Content Name, Slug'
                                ]) }}
                            </div>
                            <div class="col-md-6">
                                <label>Start Date (Before)</label>
                                    {{ Form::text('start_date', null, [
                                        'id'          => 'start_date',
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
                {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped datatable-dom-position" id="contents-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th width="5">
						<input type="checkbox" class="check-all">
					</th>
					<th width="5">No.</th>
                    <th>Slug</th>
                    <th>Content Name (TH)</th>
					<th>Content Name (EN)</th>	
					<th width="15">Create Date</th>
					<th width="10">Priority</th>
					<th width="5">Published</th>
					<th width="5">Edit</th>
					<th width="5">Delete</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="10" class="text-center">Loading ...</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('footer_script')
<script type="text/javascript">
    $('.select-dropdown').select2({
        minimumResultsForSearch: -1
    });

    var url = '/content';
    var tableId = $('#contents-table');
    var oTable  = tableId.on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        fixedColumns:   {},
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, false ]],
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
                d.full_text  = $('#full_text').val();
                d.date = $('#start_date').val();
                d.category_id = $(".select-dropdown option:selected").val() != "undefine"? $(".select-dropdown option:selected").val() : null;
            },
            error: function(xhr, error, thrown) {
                console.log(xhr);
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
                data['order'][index]['column'] = data['columns'][items.column]['data'];
            });
        },
        columns: [
            { 
                data: 'id',
                name: 'checkbox',
                orderable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<input class="ids check" type="checkbox" name="content_ids[]" value="' + data + '" class="check">';
                }
            },
            { data: 'number', name: 'number' , orderable: false },
            { data: 'slug', name: 'slug' },
            { data: 'name_th', name: 'name_th' },
            { data: 'name_en', name: 'name_en' },

            { 
                data: 'created_at',
                name: 'created_at'
            },
            { 
                data: 'priority',
                name: 'priority',
                render: function(data, type, row){
                    return '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" name="priority['+row.id+']" value="' + data + '"><input type="hidden" class="priority_number" name="priority_old['+row.id+']" priority_old="'+data+'" value="'+data+'">';
                }
            },
            { 
                data: 'status',
                name: 'status',
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
                data: 'id',
                name: 'edit',
                orderable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="/content/' + data + '/edit" class="btn btn-xs" data-placement="top" data-original-title="Edit" title="Edit" ><i class="icon-pencil"></i></a>';
                }
            },
            { 
                data: 'removable',
                name: 'delete',
                orderable: false,
                className: 'text-center',
                render: function(data, type, row){
                    if(data == true){
                        return '<a onclick="deleteItems(\'' + row.id + '\')"><i class="icon-trash text-danger"></a>';
                    }
                    else{
                        return '<i class="icon-trash text-grey-300">';
                    }
                }
            }
        ]
    });

    $('div.datatable-header').append(`
        @include('common._status_dropdown')&nbsp;
        @include('common._delete_button')&nbsp;
        @include('common._priority_button')&nbsp;
        @include('common._print_button',['custom' => 1])&nbsp;
        @include('common._create_button', ['url' => URL::to('content/create')])
    `);

    $('.custom-print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        data.full_text  = $('#full_text').val();
        data.date = $('#start_date').val();
        data.category_id = $(".select-dropdown option:selected").val() != "undefine"? $(".select-dropdown option:selected").val() : null;
        window.location.replace($("meta[name='root-url']").attr('content') + url + '/report?' + $.param(data));
    });

</script>
@include('common._priority_script')
@include('common._datetime_script', [
    'refer' => '#start_date',
    'format' => 'd/m/Y'
])
@include('common._datatable')

@endsection
