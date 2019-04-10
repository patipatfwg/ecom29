<?php
$scripts = [
    'datatables',
    'nestable',
    'sweetalert',
    'multi',
    'select2',
    'inputupload',
    'dropzone',
    'ckeditor',
    'sortable',
    'datetimepicker',
    'showdown',
    'uniform',
    'iCheck'
];
?>

@extends('layouts.main')

@section('title', 'Store')

@section('breadcrumb')
<li class="active">Store</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body search_section">
        <div class="col-lg-12">
            {!! Form::open([
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Store Name</label>
                        {{ Form::text('store_name', null, [
                            'id'          => 'store_name',
                            'class'       => 'form-control',
                            'placeholder' => 'Store Name'
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <label>Store ID</label>
                        {{ Form::text('store_id', null, [
                            'id'          => 'store_id',
                            'class'       => 'form-control',
                            'placeholder' => 'Store ID'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4">
                        <label>Zone</label>
                        @include('common._select',[
                            'data' => $region,
                            'hasPlaceholder' => true,
                            'name' => 'region',
                            'id' => 'select-region',
                        ])
                        <input type="hidden" id="region" name="value">
                    </div>
                    <div class="col-md-4">
                        <label>Have Delivery</label>
                        @include('common._select',[
                            'data' => [
                                'all'  => 'Any',
                                'Y' => 'Yes',
                                'N' => 'No'
                            ],
                            'hasPlaceholder' => true,
                            'name' => 'delivery',
                            'id' => 'select-delivery',
                        ])
                        <input type="hidden" id="delivery" name="value">
                    </div>
                    <div class="col-md-4">
                        <label>Publication Status</label>
                        @include('common._select',[
                            'data' => [
                                'all'          => 'Any',
                                'active'    => 'Publish',
                                'inaction'  => 'Unpublish'
                            ],
                            'hasPlaceholder' => true,
                            'name' => 'status',
                            'id' => 'select-status',
                        ])
                        <input type="hidden" id="status" name="value">
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
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="store-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="20"><input type="checkbox" class="check-all" value=""></th>
                            <th width="200" style="min-width:200px;">Store No.</th>
                            <th>Store Name (TH)</th>
                            <th>Store Name (EN)</th>
                            <th>Zone (TH)</th>
                            <th>Zone (EN)</th>
                            <th>Phone Number</th>
                            <th>Have Delivery</th>
                            <th>Status</th>
                            <th width="50">Edit</th>             
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="12" class="text-center">
                                <i class="icon-spinner2 spinner"></i> Loading ...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
<script type="text/javascript">
    var url = '/store';
    var tableId = $('#store-table');
    var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: message, type: 'error' });
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
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url + '/data',
            type: 'GET',
            data: function(d) {
                d.store_name = $('#store_name').val();
                d.store_id = $('#store_id').val();
                d.delivery = $('#delivery').val();
                d.status = $('#status').val();
                d.region = $('#region').val();
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
                    return '<input class="ids check" type="checkbox" name="store_ids[]" value="'+data+'">';
                }
            },
            { data: 'makro_store_id', name: 'makro_store_id'},
            { data: 'name_th', name: 'name_th'},
            { data: 'name_en', name: 'name_en'},
            { data: 'region_th', name: 'region_th' , orderable: false},
            { data: 'region_en', name: 'region_en' , orderable: false},
            { data: 'contact_phone', name: 'contact_phone'},
            { data: 'have_delivery', name: 'have_delivery'},
            { 
                data: 'status',
                name: 'status', 
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    if (data == 'active') {
                    return '<i class="icon-eye text-teal"></i>';
                    }
                    else {
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
                render: function(data, type, row) {
                    return '<a href="' + data + '"><i class="icon-pencil"></i></a>';
                }
            },
            
        ]
    });

    $('div.datatable-header').append(`
        @include('common._show_hide_button')&nbsp;
        @include('common._print_button')&nbsp;
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
        window.location.replace($("meta[name='root-url']").attr('content') + url + '/report?' + $.param(data));
    });

    $("#select-region").select2({
        minimumResultsForSearch: -1,
        placeholder: 'Any'
    });

    $("#select-delivery").select2({
        minimumResultsForSearch: -1,
        placeholder: 'Any'
    });

    $("#select-status").select2({
        minimumResultsForSearch: -1,
        placeholder: 'Any'
    });

    $('#select-region').on('select2:select', function (evt) {
        var region = $("#select-region option:selected").val();
        $("#region").val(region);
    });

    $('#select-delivery').on('select2:select', function (evt) {
        var region = $("#select-delivery option:selected").val();
        $("#delivery").val(region);
    });

    $('#select-status').on('select2:select', function (evt) {
        var region = $("#select-status option:selected").val();
        $("#status").val(region);
    });
</script>
@include('common._datatable')

@endsection