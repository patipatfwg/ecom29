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

@section('title', 'Content Category')

@section('breadcrumb')
    @if($status == 0)
      <li class="active">Content Category</li>
      <?php $category_id = null; ?>
    @elseif($status == 1)
      <li><a href="/content_category">Content Category</a></li>
      @foreach($breadcrumb as $value)
        <li class="active"><a href="/content_category/{{ $value['id'] }}">{{ $value['name'] }}</a></li>
      @endforeach
      <?php $category_id = $category_id; ?>
    @endif
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                {!! Form::open([
                    'autocomplete' => 'off',
                    'class'        => 'form-horizontal',
                    'id'           => 'search-form'
                ]) !!}
                <div class="form-group">
                    {{ Form::text('name', null, [
                                    'id'          => 'name',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Category Name'
                                ]) }}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    {{ Form::text('category_id', null, [
                                    'id'          => 'category_id',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Category ID'
                                ]) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                {{ Form::button('<i class="icon-search4"></i> Search', [
                            'type'  => 'submit',
                            'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                ]) }}
            </div>
        </div>

        <?php
            if (empty($parent_id)) {
                $p = 'NULL';
            } else {
                $p = $parent_id;
            }
        ?>
        <input type="hidden" id="parent_id" name="parent_id" value={{ $p }}>
        <input type="hidden" name="type" value="content">
        {!! Form::close() !!}
    </div>
</div>

<div class="panel">
    <div class="panel-body table-responsive">
        <table class="table table-border-gray table-striped datatable-dom-position" id="category-product-table" data-page-length="10" width="100%">
            <thead>
                <tr>
                    <th width="50"><input type="checkbox" class="check-all"></th>
                    <th width="50">No.</th>
                    <th>Category ID</th>
                    <th>Content Category Name(TH)</th>
                    <th>Content Category Name(EN)</th>
                    <th width="50">Level</th>
                    <th width="50">Publish</th>
                    <th width="80">Priority</th>
                    <th width="50"><i class="icon-pencil"></i></th>
                    <th width="50"><i class="icon-trash "></i></th>
                    <th width="50"><i class="icon-tree6" ></i></th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="12" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
            </tbody>
        </table>
    </div>
</div>


@endsection

@section('footer_script')
<script type="text/javascript">
    var url    = '/content_category';
    var oTable = $('#category-product-table').on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: 'Error connection', type: 'error' });
    }).DataTable({
        processing: false,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 2, 'desc' ]],
        bAutoWidth: '100%',
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/content_category/data',
            type: 'POST',
            data: function (d) {
                d.search = $('#search-form').serializeArray();
            },
            error: function(xhr, error, thrown) {
                new PNotify({text: 'Error connection', type: 'error' });
                $tbody.children().remove();
                $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
            }
        },
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'number', name: 'number', orderable: false, searchable: false, className: 'text-center' },
            { data: 'category_id', name: 'category_id', className: 'text-center'},
            { data: 'category_name_th', name: 'category_name_th', className: 'text-center'},
            { data: 'category_name_en', name: 'category_name_en', className: 'text-center'},
            { data: 'level', name: 'level', orderable: false, searchable: false, className: 'text-center'},
            { data: 'status', name: 'status', orderable: false, searchable: false, className: 'text-center'},
            { data: 'priority', name: 'priority', searchable: false, className: 'text-center'},
            { data: 'edit', name: 'edit', orderable: false, searchable: false, className: 'text-center' },
            { data: 'delete', name: 'delete', orderable: false, searchable: false, className: 'text-center' },
            { data: 'child', name: 'child', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    $('div.datatable-header').append(`
        @include('common._status_dropdown')&nbsp;
        @include('common._delete_button')&nbsp;
        @include('common._priority_button')&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('content_category/create/'.$category_id)])
    `);
</script>
@include('common._priority_script')
@include('common._datatable')
@endsection
