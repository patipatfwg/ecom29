<?php
$scripts = [
    'nestable',
    'sweetalert',
    'select2',
    'datatables',
];
?>

@extends('layouts.main')

@section('title', 'Brand')

@section('breadcrumb')
    <li><a href="/brand">Brand</a></li>
    @foreach($breadcrumb as $value)
    <li class="active"><a href="/brand/{{ $value['id'] }}">{{ $value['name'] }}</a></li>
    @endforeach

@endsection

@section('header_script')@endsection

@section('content')

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
            <a href="/brand/create/{{ $brand_id }}">
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
var appurl = '/brand';
$(function(){
    $.fn.dataTable.ext.errMode = 'none';
    var oTable = $('#members-table').on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, false ]],
        bAutoWidth: '100%',
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            type:'POST',
            url: appurl + "/getAjaxBrand",
            data: function (d) {
                d.brand_id = '{{ $brand_id }}';
                d.full_text  = $('#full_text').val();
            }
        },
        columns: [
            { data: 'number', name: 'number', orderable: false, searchable: false, className: 'text-center' },
            { data: 'content',   name: 'content' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
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


</script>
@endsection