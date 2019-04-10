<div class="panel">
    <div class="panel-heading">
        <h5 class="panel-title">Status</h5>
    </div>
    <div class="panel-body" >
                <div class="col-lg-12">
                    <div class="col-lg-6">
                        @include('product.productStatusForm',[
                            'formName' => 'Online',
                            'productName' => 'productOnline',
                            'product' => $productOnlineData,
                            'readonly' => true,
                            'last_update_status' => $productOnlineData['last_update_status'],
                        ])
                    </div>
                    <div class="col-lg-6">
                        @include('product.productStatusForm',[
                            'formName' => 'Staging',
                            'productName' => 'productIntermediate',
                            'product' => $productIntermediateData,
                            'readonly' => ($editAble)? false : true,
                            'last_update_status' => $productIntermediateData['last_update_status'],
                        ])
                    </div>
                </div>
    </div>
</div>