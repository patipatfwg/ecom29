<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Billing Address</h6>
    </div>
    <div class="panel-body panel-billing-address" style="min-height: 140px">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_billing_address_name',
                    $search_result->billingAddress->firstName.' '.
                    $search_result->billingAddress->middleName.' '.
                    $search_result->billingAddress->lastName.' '.
                    $search_result->billingAddress->phone))
                     !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_billing_address',
                    $search_result->billingAddress->addressLine1.' '.
                    $search_result->billingAddress->addressLine2.' '.
                    $search_result->billingAddress->addressLine3.' '.
                    $search_result->billingAddress->addressLine4.' '.
                    $search_result->billingAddress->addressLine5.' '.
                    $search_result->billingAddress->addressLine6.' '.
                    $search_result->billingAddress->city.' '.
                    $search_result->billingAddress->state.' '.
                    $search_result->billingAddress->country.' '.
                    $search_result->billingAddress->zipCode)) !!}
                </div>
            </div>
        </div>
    </div>
</div>