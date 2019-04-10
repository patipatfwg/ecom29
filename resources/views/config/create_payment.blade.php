 <?php
    $scripts = [
        'angular',
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
        'to-markdown',
        'showdown'
    ];
    ?>

@extends('layouts.main')

@section('title', 'Payment Option')

@section('breadcrumb')

<li><a href="{{URL::to('/config/payment_method')}}">Payment Option</a></li>
<li class="active">Installment Option</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Installment Option</h6>
    </div>
    @include('config.form.modal')   
    @include('config.form._form')
                
</div>
 
@endsection

@section('footer_script')
    
    
    @include('common._datetime_range_script', [
        'format_start' => 'd/m/Y 00:00:00',
        'format_end' => 'd/m/Y 23:59:59',
        'refer_start' => '#start_date',
        'refer_end' => '#end_date'
    ])
    @include('common._dropdown_right_script')
    @include('config.form.create_footer_script')
    @include('common._priority_script')

@endsection
