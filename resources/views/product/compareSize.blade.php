<div class="panel">
    <div class="panel-heading">
        <h5 class="panel-title">Weight and Size</h5>
    </div>
    
        <div class="panel-body" >
                <div class="col-lg-12">
                    <div class="col-lg-6">
                        @include('product.productSizeForm',[
                            'formName' => 'Online',
                            'product' => 'productOnline',
                            'readonly' => true,
                            'lwh_uom_id' => empty($productOnlineData['lwh_uom']['id'])? null : $productOnlineData['lwh_uom']['id'],
                            'weight_uom_id' => empty($productOnlineData['weight_uom']['id'])? null : $productOnlineData['weight_uom']['id'],
                            'weightUom' => $weightUom,
                            'lwhUom' => $lwhUom,
                            'language' => app()->getLocale(),
                            'updated_at' => $productOnlineData['updated_at'],
                        ])
                    </div>
                    <div class="col-lg-6">
                        @include('product.productSizeForm',[
                            'formName' => 'Staging',
                            'product' => 'productIntermediate',
                            'readonly' => false,
                            'lwh_uom_id' => $productIntermediateData['lwh_uom']['id'],
                            'weight_uom_id' => $productIntermediateData['weight_uom']['id'],
                            'weightUom' => $weightUom,
                            'lwhUom' => $lwhUom,
                            'language' => app()->getLocale(),
                            'updated_at' => $productIntermediateData['updated_at'],
                        ])
                    </div>
                </div>
        </div>
</div>