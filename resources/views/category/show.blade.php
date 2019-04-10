<?php
$scripts = [
    'nestable',
    'sweetalert',
    'select2',
    'datatables',
];
?>

@extends('layouts.main')

@section('title', 'Category')

@section('breadcrumb')
    <li><a href="/category">Category</a></li>
    @foreach($breadcrumb as $value)
    <li class="active"><a href="/category/{{ $value['id'] }}">{{ $value['name'] }}</a></li>
    @endforeach

@endsection

@section('header_script')@endsection

@section('content')

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
            <a href="/category/create/{{ $category_id }}">
                <button class="btn btn-link"><i class="icon-plus2"></i> Add Category</button>
            </a>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel-default">
                    {!! Form::open([
                        'autocomplete' => 'off',
                        'class'        => 'form-horizontal',
                        'id'           => 'search-form'
                    ]) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-6">
                                    {!! Html::decode(Form::label('full_text', 'Full Text')) !!}
                                    {{ Form::text('full_text', null, [
                                        'class' => 'form-control'
                                    ]) }}
                                </div>
                                
                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    {{ Form::button('<i class="icon-search4"></i> Search', array(
                                        'type'  => 'submit',
                                        'class' => 'btn bg-teal-400 btn-raised legitRipple legitRipple'
                                    )) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <div class="clearfix"></div>
                    <br>
                    <table class="table table-border-teal table-striped table-hover datatable-dom-position" id="members-table" data-page-length="10" width="100%">
                        <thead>
                            <tr>
                                <th class="bg-teal-400" width="20">No.</th>
                                <th class="bg-teal-400">Name</th>
                                <th class="bg-teal-400">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('footer_script')
<script type="text/javascript">
var appurl = '/category';
var oTable;
$(function(){
    $.fn.dataTable.ext.errMode = 'none';
    oTable = $('#members-table').on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, 'asc' ]],
        bAutoWidth: '100%',
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            type:'POST',
            url: appurl + "/getAjaxCategory",
            data: function (d) {
                console.log(d);
                d.parent_id = '{{ $category_id }}';
                d.full_text  = $('#full_text').val();
            }
        },
        columns: [
            { data: 'number', name: 'number', orderable: false, searchable: false, className: 'text-center' },
            { data: 'content', name: 'content' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        drawCallback : function(settings) {
            $('[data-delete]').on('click',function(){
                event.preventDefault();
                var id = $(this).attr('data-delete');
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
                        deleteCategory(id);
                    }
                });
            });
        }
    });
    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
});

function deleteCategory(_id) {
    $.ajax({
        type: 'DELETE',
        url: appurl + "/" + _id,
        dataType: 'json',
        success:function(data){
            console.log(data);
            if (data.success) {
                swal("{{ trans('validation.delete.title') }}", data.messages, 'success');
                oTable.draw();
            } else {
                swal("{{ trans('validation.delete.title') }}", data.messages, 'warning');
            }
        },
        error: function(){
            swal("{{ trans('validation.delete.title') }}", "{{ trans('validation.error_connection') }}", 'error');
        }
    });
}
</script>
@endsection