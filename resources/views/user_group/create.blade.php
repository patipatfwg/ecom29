<?php
    $scripts = [
        'sweetalert'
    ];
?>

@extends('layouts.main')

@section('title','Role Detail')

@section('breadcrumb')
    <li><a href="/user_group">User Group</a></li>
    <li class="active">Create</li>
@endsection

@section('header_script')
    {{ Html::style('assets/css/icons/fontawesome/styles.min.css') }}
    {{ Html::style('assets/css/awesome-bootstrap-checkboxes.css') }}
@endsection
    
@section('content')
    @include('user_group.form._form')
@endsection

@section('footer_script')
    @include('user_group._footer_script')
@endsection