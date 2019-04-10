<?php
$scripts = [
    'datatables',
    'nestable',
    'sweetalert',
    'select2',
    'sortable',
    'datetimepicker',
    'showdown',
    'uniform',
    'iCheck'
];
?>

@extends('layouts.main')

@section('title', 'Member')

@section('breadcrumb')
<li>Cronjob</li>
<li class="active">Member</li>
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
                            <label>Member Number</label>
                            {{ Form::text('member_card_no', null, [
                                'id'          => 'member_card_no',
                                'class'       => 'form-control',
                                'placeholder' => '13-digit or 14-digit'
                            ]) }}
                        </div>
                        <div class="col-md-6">
                            <label>Service</label>
                            @include('common._select',[
                                'data' => $error_status,
                                'hasPlaceholder' => true,
                                'name' => 'error_status',
                                'id' => 'error_status',
                            ])
                            
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
                <table class="table table-border-gray table-striped datatable-dom-position" id="cronmember-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="20"><input type="checkbox" class="check-all" value=""></th>
                            <th width="100">Filename</th>
                            <th width="100">Member Number</th>
                            <th width="20">Ecommerce Customer ID</th>
                            <th width="20">Tag</th>
                            <th width="20">Service</th>
                            <th width="20">Error</th>
                            <th width="20">Count</th>
                            <th width="20">Created</th> 
                            <th width="20">Updated</th>
                            <th width="20">Reset</th>                
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
    var url = '/cron/member';
    var tableId = $('#cronmember-table');
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
                d.member_card_no = $('#member_card_no').val();
                d.error_status   = $('#error_status').val();
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
                } else if(xhr.responseJSON.member_card_no) {
                    swal('Error!', xhr.responseJSON.member_card_no[0], 'error', "OK" );
                } else {
                    swal('Error!', 'Error connection', 'error');
                    tableId.find('tbody').find('td').html('No Data, please try again later');
                }
                //console.log(xhr.responseJSON); 
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
                    return '<input class="ids check" type="checkbox" name="member_ids[]" value="'+data+'">';
                }
            },
            { data: 'file', name: 'file',  orderable: false},
            { data: 'member_card_no', name: 'member_card_no',  orderable: false},
            { 
               data: 'ecommerce_customer_id', name: 'ecommerce_customer_id',  orderable: false 
            },
            { data: 'tag', name: 'tag',  orderable: false},
            { data: 'service', name: 'service',  orderable: false},
            { data: 'message', name: 'message', orderable: false },
            { 
                data: 'count',
                name: 'count', 
                orderable: false,
                className: 'text-right'
            },
            { data: 'created_at', name: 'created_at',  orderable: false},
            { data: 'updated_at', name: 'updated_at',  orderable: false},
            { 
            data: 'reset',
            name: 'reset',
            orderable: false,
            className: 'text-center',
            render: function(data, type, row){
                return '<button type="button" id="reset-btn-id" onclick="resetItems(\'' + row.id + '\')" class="btn btn-width-100 btn-danger btn-raised legitRipple btn-delete datatable-button"><i class="glyphicon glyphicon-repeat"></i> Reset</button>';     
            }
        }
            
        ]
    });

    $('div.datatable-header').append(`
<div class="btn-group">
    <button type="button" id="reset-btn" class="btn btn-width-100 btn-danger btn-raised legitRipple btn-delete datatable-button">
        <i class="glyphicon glyphicon-repeat"></i> Reset
    </button>
</div>
    `);

    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $("#error_status").select2({
        minimumResultsForSearch: -1,
        placeholder: 'All'
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('body').on('click', '#reset-btn', function (event) {
        event.preventDefault();
        // Not Select Item
        if(isChecked() == false){
            return;
        }

        callAjaxCustom(
            'PUT', 
            $("meta[name='root-url']").attr('content') + '/cron/member/count',
            $('input.ids:checked').serializeArray(),
            'formData',
            function(data) {
                $("#search-form")[0].reset();
                //console.log(data);
                // onAjaxMultipleItem(data.data.updated, data.data.errors, null, 'item_id');
                $('input.check-all').prop('checked', false);
                oTable.draw('page');

            }
        );
        
    });

    function resetItems(ids){
        $.ajax({
            type: 'PUT',
            url: $("meta[name='root-url']").attr('content') + '/cron/member/count/' + ids ,
            dataType: 'json',
            success: function(data) {
                if (data.status || data.success) {
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
</script>
@include('common._datatable')


@endsection