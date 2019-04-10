<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Payment Status</h6>
    </div>
    <div class="panel-body panel-order" style="min-height: 360px">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_payment_type', 'Payment Type')) !!}
                </div>
            </div>
            {{--{{ dump($search_result) }}--}}
            {{--{{ dd(isset($search_result->orderPayment)) }}--}}
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('payment_type', ': '.$search_result->orderPayment->type)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_payment_ref', 'Payment Ref.')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('payment_ref', ': '.$search_result->orderPayment->ref)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_payment_status', 'Payment Status')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('payment_status', ': '.$search_result->orderPayment->status)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_total_amount', 'Total Amount')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
{{--                    {!! Html::decode(Form::label('total_amount', ': '.number_format($search_result->orderPayment->totalAmount,2).' '.$search_result->orderPayment->currency)) !!}--}}
                    {!! Html::decode(Form::label('total_amount', ': '.number_format($pay_amount,2).' '.$search_result->orderPayment->currency)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_payment_expired', 'Payment Expired')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('payment_expired', ': '.$search_result->orderPayment->expired)) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer" style="height: 60px">
        <div class="col-lg-12">
            <div class="text-right">
{{--                <a href="{{'/epos/order/paystore?order_number='.$order_number.'&amount='.$pay_amount }}" class="btn bg-primary-800 btn-raised btn-submit"><i class="icon-checkmark"></i> Update Payment</a>--}}
                @if ($search_result->orderStatus == 'created')
                    @if (isset($search_result->orderPayment->type) && ($search_result->orderPayment->type == 'PayAtStore'))
                        @if (isset($search_result->orderPayment->status) && ($search_result->orderPayment->status == 'PENDING'))
                            <a href="{{'/epos/order/paystore?order_number='.$order_number.'&amount='.$pay_amount }}" class="btn bg-primary-800 btn-raised btn-submit"><i class="icon-checkmark"></i> Update Payment</a>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>