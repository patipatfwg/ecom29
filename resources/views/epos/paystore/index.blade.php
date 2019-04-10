<?php
$scripts = ['sweetalert'];
?>

@extends('layouts.epos.main')

@section('title', 'Update Pay@Store Payment')

@section('breadcrumb')
    <li>Order Search</li>
    <li class="active">Update Pay@Store Payment</li>
@endsection

@section('header_script')@endsection

@section('content')

    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Pay@Store Payment</h6>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-lg-12">
                {!! Form::open([
                    'autocomplete' => 'off',
                    'class' => 'form-horizontal',
                    'id' => 'form-submit',
                    'method' => 'put'
                ]) !!}
                <input type="hidden" name="pay_amount" id="payAmount" value="{{$pay_amount}}" />
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {{--For test: <code>0000000670</code>--}}
                            {!! Html::decode(Form::label('order_number', '<span class="text-danger">*</span> Sale Order Number : ')) !!}
                        </div>
                        <div class="col-md-9">
                            <div class="input-group">
                                {{ Form::text('order_number', null, [
                                    'id'          => 'order_number',
                                    'class'       => 'form-control',
                                    'placeholder' => ''
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">      
                            {!! Html::decode(Form::label('deposit_invoice', '<span class="text-danger">*</span> Deposit Tax Invoice Number : ')) !!}
                        </div>
                        <div class="col-md-9">
                            <div class="input-group">
                                {{ Form::text('deposit_invoice', null, [
                                    'id'          => 'deposit_invoice',
                                    'class'       => 'form-control',
                                    'placeholder' => ''
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('amount', '<span class="text-danger">*</span> Amount : ')) !!}
                        </div>
                        <div class="col-md-9">
                            <div class="input-group">
                                {{ Form::text('amount', null, [
                                    'id'          => 'amount',
                                    'class'       => 'form-control',
                                    'placeholder' => ''
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('sub_payment_type', '<span class="text-danger">*</span> Payment Type : ')) !!}
                        </div>
                        <div class="col-md-9 ">
                            <div class="form-group">
                                @foreach ($sub_payment_types as $key => $value)
                                <span class="col-md-4">
                                    <input type="radio" name="sub_payment_type" value="{{$key}}" class="styled"> {{$value}} 
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-offset-6 col-md-6">
                        {{ Form::button('<i class="icon-cart-remove"></i> Submit', array(
                            'id' => 'buttonSubmit',
                            'class' => 'pull-right btn bg-primary-800 btn-raised legitRipple legitRipple'
                        )) }}
                        <a id="btnReset" class="pull-right btn">Reset</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('footer_script')
    <script>
        var formSubmit = $('#form-submit');
        $('#btnReset').click(function(e){
            e.preventDefault();
            formSubmit[0].reset();
            return false;
        });
        $(document).ready(function() {
            $("#deposit_invoice").keypress(function(event){
                var ew = event.which;
                //console.log('ew=' + ew)
                if(ew == 0) //
                    return true;
                if(ew == 8) // Backspace
                    return true;
                if(48 <= ew && ew <= 57) // 0-9
                    return true;

                return false;
            });
            $("#amount").keypress(function(event){
                var ew = event.which;
                //console.log('ew=' + ew)
                if(ew == 0) //
                    return true;
                if(ew == 8) // Backspace
                    return true;
                if(ew == 46) // dot
                    return true;
                if(48 <= ew && ew <= 57) // 0-9
                    return true;

                return false;
            });
        });
        formSubmit.on('click', '#buttonSubmit', function (e) {
            e.preventDefault();
            if ($('#form-submit').valid()) {
                swal({
                        title: "Are you sure?",
                        text: "you want to confirm payment success for order {{ $order_number }} \n(this action cannot be undone)",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "Cancel",
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: "Confirm",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            var url = '/epos/order/paystore';
                            $.ajax({
                                type: 'PUT',
                                url: url,
                                data: formSubmit.serialize(),
                                success: function(data) {
                                    if (data.status || data.success) {
                                        swal({ title: "Update succeed.", type: 'success' });
                                        formSubmit[0].reset();
                                        //window.location = url
                                        window.location = '{!! '/epos/order?order_number='.$order_number !!}'
                                    } else {
                                        swal({ title: "Update fail", text: data.messages, type: 'error' });
                                    }
                                }
                            });
                        }
                    });
            }
            return false;
        });
    </script>
    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\PayStoreRequest', '#form-submit') !!}

    <script>
        <?php
//        if($status['status']){
//            if($status['msg']['success']){
//                echo "swal('Update','".$status['msg']['messages']."', 'success');";
//            }else{
//                echo "swal('Update','".$status['msg']['messages']."', 'error');";
//            }
//        }
            if (Session::has('msg')) {
                echo 'swal("Error!", "' . Session::get('msg') . '", "error");';
            }
        ?>
    </script>
@endsection