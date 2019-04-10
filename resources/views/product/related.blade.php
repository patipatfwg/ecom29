<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Product Related<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
		<div class="heading-elements">
			<ul class="icons-list">
				<li>  
					<a data-toggle="modal" data-target="#productModal"><i class="icon-plus-circle2 position-right"></i><span class="legitRipple-ripple" style="left: 60%; top: 55.5556%; transform: translate3d(-50%, -49%, 0px); transition-duration: 0.2s, 0.5s; width: 211.643%;"></span></a>  
				</li>
				<li><a data-action="collapse"></a></li>
			</ul>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12">
				<table class="table table-border-teal table-striped table-hover datatable-dom-position" 
					id="related-table" data-page-length="10" width="100%">
					<thead>
						<tr>
							<th class="bg-teal-400" width="20">No.</th>
							<th class="bg-teal-400">Product No</th>
							<th class="bg-teal-400">Online SKU</th>
							<th class="bg-teal-400">Product Name</th>
							<th class="bg-teal-400" width="80">Manage</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="data in productRelatedData">
							<td >@{{ $index+1 }}</td>
							<td >@{{ data.item }}</td>
							<td >@{{ data.online }}</td>
							<td >@{{ data.name }}</td>
							<td><a ng-click="deleteProductCategory($index);"><i class="icon-trash text-danger"></a></td>
						</tr>
						<tr ng-show="productRelatedData.length==0">
							<td colspan="5" class="text-center">No data.</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- form modal Add Product Related-->
<div class="modal fade" id="productModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Related Product</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" id="addAttribute" class="btn btn-default" data-dismiss="modal" ng-click="">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /form modal Add Product Related -->