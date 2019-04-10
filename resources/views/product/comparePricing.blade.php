<div class="panel">
    <div class="panel-heading">
        <h5 class="panel-title">Pricing</h5>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            <div class="col-lg-6">
                @include('product.productPricingForm',[
                    'formName' => 'Online',
                    'product' => 'productOnline',
                    'readonly' => true,
                    'unit_type_id' => empty($productOnlineData['unit_type']['id'])? null : $productOnlineData['unit_type']['id'],
                    'unitType' => $unitType,
                    'language' => app()->getLocale(),
                    'updated_at' => $productOnlineData['updated_at'],
                ])
            </div>
            <div class="col-lg-6">
                @include('product.productPricingForm',[
                    'formName' => 'Staging',
                    'product' => 'productIntermediate',
                    'readonly' => ($editAble)? false : true,
                    'unit_type_id' => isset($productIntermediateData['unit_type']['id'])? $productIntermediateData['unit_type']['id'] : '',
                    'unitType' => $unitType,
                    'language' => app()->getLocale(),
                    'updated_at' => $productIntermediateData['updated_at'],
                ])
            </div>
        </div>
    </div>
</div>