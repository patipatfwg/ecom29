<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Order Summary</h6>
    </div>
    <div class="panel-body panel-order" style="height: 420px">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_order_number', 'Order Number')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('order_number', ': '.$order_number)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_order_date', 'Order Date')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('order_date', ': '.$search_result->orderDate)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_order_status', 'Order Status')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('order_status', ': '.$search_result->orderStatus)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_customer_name', 'Customer Name')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('customer_name', ': '.$search_result->customerName)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_customer_mobile', 'Customer Mobile')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('customer_mobile', ': '.$search_result->customerMobile)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_makro_member', 'Makro Member')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('makro_member', ': '.$search_result->makro_member_card)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_customer_email', 'Customer Email')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('customer_email', ': '.$search_result->customerEmail)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_pickup_store', 'Pickup Store')) !!}
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    {!! Html::decode(Form::label('pickup_store', ': '. $search_result->pickupStore)) !!}
                </div>
            </div>
        </div>
    </div>
</div>