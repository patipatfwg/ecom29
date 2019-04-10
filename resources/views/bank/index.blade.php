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

@section('title', 'Bank')

@section('breadcrumb')
<li class="active">Bank</li>
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
                            <label>Bank Name</label>
                            {{ Form::text('full_text', null, [
                                'id'          => 'full_text',
                                'class'       => 'form-control',
                                'placeholder' => 'Bank Name'
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
                <table class="table table-border-gray table-striped datatable-dom-position" id="bank-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="20"><input type="checkbox" class="check-all" value=""></th>
                            <th width="100">Bank Name (TH)</th>
                            <th width="100">Bank Name (EN)</th>
                            <th width="20">Logo</th>
                            <th width="20">Fee</th>
                            <th width="20">Status</th>               
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
    var url = '/bank';
    var tableId = $('#bank-table');
    var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: message, type: 'error' });
    }).DataTable({
        scrollY: true,
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
            d.full_text = $('#full_text').val();
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
                    return '<input class="ids check" type="checkbox" name="bank_ids[]" value="'+data+'">';
                }
            },
            { data: 'bank_name_th', name: 'bank_name_th'},
            { data: 'bank_name_en', name: 'bank_name_en'},
            { 
                data: 'logo', 
                name: 'logo', 
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<img src="'+data+'?process=resize&resize_width=120&resize_height=80" target="_blank" style="width: 120px; height: 80px; border: 2px solid #5a5b5b;" alt="">';
                }
            },
            { data: 'fee', name: 'fee', orderable: false },
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
            }
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

</script>
@include('common._datatable')


@endsection