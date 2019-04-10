<?php
   $scripts = [
       'select2',
       'sweetalert',
       'bootstrap-select',
   ];
   ?>
@extends('layouts.main')
@section('title', 'User Detail')
@section('breadcrumb')
<li class="active"><a href="/user">User</a></li>
<li class="active">{{ isset($userData['id'])? ''.$userData['username'] : 'Create' }}</li>
@endsection
@section('header_script')@endsection
@section('content')
    @include('user.form._form')
@endsection
@section('footer_script')
{!! Html::script('assets/js/plugins/forms/tags/tagsinput.min.js') !!}
{!! Html::script('assets/js/plugins/forms/tags/tokenfield.min.js') !!}
{!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
{!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
{!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}

@include('user.form.footer_script')
@endsection