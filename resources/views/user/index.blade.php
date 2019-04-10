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

@section('title', 'User')

@section('breadcrumb')
<li class="active">User</li>
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
                                <label>Username, Name, Mobile, E-Mail</label>
                                {{ Form::text('full_text', null, [
                                    'id'          => 'full_text',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Username, Name, Mobile, E-Mail'
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
                <table class="table table-striped table-hover datatable-dom-position" id="contents-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="55">
                                <input type="checkbox" class="check-all">
                            </th>
                            <th width="5">No.</th>
                            <th width="5">Username</th>
                            <th width="5">Name</th>
                            <th width="5">Mobile Number</th>
                            <th width="5">Email</th>
                            <th width="1">Store</th>
                            <th width="5">Employee ID</th>
                            <th width="5">Registration Date</th>
                            <th width="5">Roles</th>
                            <th width="50">Edit</th>
                            <th width="50">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="10" class="text-center">Loading ...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer_script')
<script type="text/javascript">

    var url = '/user';
    var tableId = $('#contents-table');

    var oTable  = tableId.on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        fixedColumns: {},
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        fixedColumns: {
            leftColumns: 3,
        },
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
                        }
                    );
                } else {
                    swal('Error!', 'Error connection', 'error');
                }
            }
        },
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false ,className: 'text-center' },
            { data: 'number', name: 'number' , orderable: false ,className: 'text-center'},
            { data: 'username', name: 'username'},
            { data: 'name', name: 'name' },
            { data: 'mobile', name: 'mobile'},
            { data: 'email', name: 'email'},
            { data: 'makro_store_name' , name: 'makro_store_name', orderable: false },
            { data: 'id', name: 'id'},
            { data: 'regis_date', name: 'regis_date'},
            { data: 'authorize', name: 'authorize', orderable: false ,className: 'text-center'},
            { data: 'edit', name: 'edit' , orderable: false ,className: 'text-center'},
            { data: 'delete', name: 'delete' , orderable: false ,className: 'text-center'}
        ]
    });

    $('div.datatable-header').append(`
        @include('common._delete_button')&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('user/create/')])
    `);

</script>

@include('common._datatable')
@endsection

