<?php
$scripts = [
  'nestable',
  'sweetalert',
  'select2',
  'datatables',
  'datetimepicker',
  'datatablesFixedColumns',
  'bootstrap-select',
  'ckeditor',
  'to-markdown',
  'showdown'
];
?>

@extends('layouts.main')

@section('title', 'User Group')

@section('breadcrumb')
<li class="active">User Group</li>
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
                            <div class="col-md-12">
                                <label>Role Name</label>
                                {{ Form::text('full_text', null, [
                                    'id'          => 'full_text',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Role Name'
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
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-hover datatable-dom-position" id="user_group-table" data-page-length="10" width="100%">
                    <thead>
                        <tr>
                            <th width="50"><input type="checkbox" class="check-all"></th>
                            <th width="50">No.</th>
                            <th>Role Name</th>
                            <th width="50">Permissions</th>
                            <th width="50">Number of Users</th>
                            <th width="50">Edit</th>
                            <th width="50">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="7" class="text-center">Loading ...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
<script type="text/javascript">

    var url = '/user_group';
    var tableId = $('#user_group-table');

    var oTable  = tableId.on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
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
            type: 'POST',
            data: function (d) {
                d.full_text = $('#full_text').val();
                console.log(d);
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
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<input class="ids check" type="checkbox" name="user_group_ids[]" value="'+data+'">';
                }
            },
            { data: 'number', name: 'number', orderable: false },
            { data: 'name', name: 'name' },
            { data: 'module', name: 'module', orderable: false},
            { data: 'amount', name: 'amount', orderable: false,className:'text-right' },
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
                render: function(data, type, row){
                    if(row.amount!=0) {
                        return '<i class="icon-trash">';
                    } else {
                        return '<a onclick="deleteItems(\''+data+'\')"><i class="icon-trash text-danger"></a>';
                    } 
                }
            }
        ]
    });

    $('div.datatable-header').append(`
        @include('common._delete_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('user_group/create/')])
    `);

</script>

@include('common._datatable')

@endsection
