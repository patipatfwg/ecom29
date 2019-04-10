<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Shipping Address</h6>
    </div>
    <div class="panel-body panel-shipping-address" style="min-height: 140px">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_shipping_address_name',
                    $search_result->shoppingAddress->firstName.' '.
                    $search_result->shoppingAddress->middleName.' '.
                    $search_result->shoppingAddress->lastName.' '.
                    $search_result->shoppingAddress->phone))
                     !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    {!! Html::decode(Form::label('lb_shipping_address',
                    $search_result->shoppingAddress->addressLine1.' '.
                    $search_result->shoppingAddress->addressLine2.' '.
                    $search_result->shoppingAddress->addressLine3.' '.
                    $search_result->shoppingAddress->addressLine4.' '.
                    $search_result->shoppingAddress->addressLine5.' '.
                    $search_result->shoppingAddress->addressLine6.' '.
                    $search_result->shoppingAddress->city.' '.
                    $search_result->shoppingAddress->state.' '.
                    $search_result->shoppingAddress->country.' '.
                    $search_result->shoppingAddress->zipCode)) !!}
                </div>
            </div>
        </div>
    </div>
</div>