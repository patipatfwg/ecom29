<?php
$scripts = [
    'nestable',
    'sweetalert',
    'select2',
    'datatables',
    'datetimepicker',
    'datatablesFixedColumns'
];
?>

@extends('layouts.main')

@section('title', 'Group Menu - Items')

@section('breadcrumb')
<li><a href="/group_menu">Group Menu</a></li>
<li class="active">{{$title}}</li>
@endsection

@section('header_script')
{{ Html::style('assets/css/dropdown.custom.css') }}
@endsection
@section('content')
    <div class="panel">
        <div class="panel-body">
            <lable><b>Group Menu Name:</b> {{$title}}</lable>
        </div>
    </div>
    
    <div class="panel">
        <div class="panel-body table-responsive">
            <table class="table table-border-gray table-striped datatable-dom-position" id="group_menu-table" data-page-length="10" width="100%">
                <thead>
                    <tr>
                        <th width="10"><input type="checkbox" class="check-all"></th>
                        <th width="20">No.</th>
                        <th width="20">Slug/Link</th>
                        <th>Menu Name (TH)</th>
                        <th>Menu Name (EN)</th>
                        <th width="10">Priority</th>
                        <th width="10">Published</th>
                        <th width="10">Edit</th>
                        <th width="10">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center">Loading ...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('footer_script')
    @include('common._priority_script')
    @include('common._datatable')
    @include('group_menu.form._form_script')
@endsection