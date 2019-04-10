<div class="panel">
    <div class="panel-heading">
        <h5 class="panel-title">Offline Data from RMS</h5>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            <div class="col-lg-6">
                @include('product.productRmsForm',[
                    'formName' => 'Online',
                    'product'  => 'productOnline',
                    'readonly' => true,
                    'last_update_datetimestamp' => isset($productOnlineData['last_update_datetimestamp'])? $productOnlineData['last_update_datetimestamp'] : null,
                ])
            </div>
            <div class="col-lg-6">
                @include('product.productRmsForm',[
                    'formName' => 'Staging',
                    'product'  => 'productIntermediate',
                    'readonly' => ($editAble)? false : true,
                    'last_update_datetimestamp' => isset($productIntermediateData['last_update_datetimestamp'])? $productIntermediateData['last_update_datetimestamp']: null,
                ])
            </div>
            <div class="col-lg-12">
                <a data-toggle="modal" data-target="#storePriceModal" class="btn bg-primary-800 col-lg-3 btn-raised pull-right">STORE AVAILABILITY & PRICES</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->

<div class="modal fade" id="storePriceModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">STORE AVAILABILITY & PRICES</h4>
			</div>
			<div class="modal-body">
				<div class="col-lg-12">
					<table class="table table-border-teal table-striped table-hover datatable-dom-position" 
                        id="categories-table" data-page-length="10" width="100%" style="margin-bottom:10px;">
                        <thead>
                            <tr>
                                <th class="bg-teal-400">Store ID</th>
                                <th class="bg-teal-400">Store Name (TH)</th>
                                <th class="bg-teal-400">Availability</th>
                                <th class="bg-teal-400">Store Price</th>
                                <th class="bg-teal-400">Store Price (VAT incl.)</th>
                                <th class="bg-teal-400">Promotion Price</th>
                                <th class="bg-teal-400">Promotion Price (VAT incl.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="data in storePriceData.data">
                                <td>@{{ data.makro_store_id }}</td>
                                <td>@{{ data.name.th }}</td>
                                <td><span class="glyphicon" ng-class="{ 'glyphicon-remove': !data.store_price , 'glyphicon-ok': data.store_price>0 }"></td>
                                <td>@{{ data.store_price || '' }}</td>
                                <td>@{{ data.store_price_vat_rate || '' }}</td>
                                <td>@{{ data.promotion_price || '' }}</td>
                                <td>@{{ data.promotion_price_vat_rate || '' }}</td>
                            </tr>
                            <tr ng-show="storePriceData.data.length==0" >
                                <td class=" text-center" colspan="7">
                                    No data.                
                                </td>
                            </tr>
                        </tbody>
                    </table>
				</div>
			</div>
			<div class="modal-footer">
                <ul class="pagination">
                    <li ng-repeat="i in pageList">
				        <a ng-click="selectPage($index)"> @{{$index+1}} </a>
                    </li>
                </ul>
			</div>
		</div>
	</div>
</div>

<!-- End Modal -->
