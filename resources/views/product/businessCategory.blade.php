<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Business Category<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
		@if($productIntermediateData['approve_status']!='ready to approve')
		<div class="heading-elements">
			<ul class="icons-list">
				<li>  
					<a data-toggle="modal" data-target="#businessCatModal"><i class="icon-plus-circle2 position-right"></i><span class="legitRipple-ripple" style="left: 60%; top: 55.5556%; transform: translate3d(-50%, -49%, 0px); transition-duration: 0.2s, 0.5s; width: 211.643%;"></span></a>  
				</li>
				<li><a data-action="collapse"></a></li>
			</ul>
		</div>
		@endif
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12">
				<input type="hidden" name="old_business_category" value="{{ $old_business_category }}">
				<input ng-repeat="data in businessCategoryData" type="hidden" name="businessCategory_id[]" value="@{{ data.id }}">
				<table class="table table-border-teal table-striped table-hover datatable-dom-position" 
					id="categories-table" data-page-length="10" width="100%">
					<thead>
						<tr>
							<th class="bg-teal-400" width="20">No.</th>
							<th class="bg-teal-400">Category ID</th>
							<th class="bg-teal-400">Category Name</th>
							@if($productIntermediateData['approve_status']!='ready to approve')
							<th class="bg-teal-400" width="80">Remove</th>
							@endif
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="data in businessCategoryData">
							<td>@{{ $index+1 }}</td>
							<td>@{{ data.id }}</td>
							<td>@{{ data.name.th }}</td>
							@if($productIntermediateData['approve_status']!='ready to approve')
							<td><a ng-click="deleteBusinessCategory($index);"><i class="icon-trash text-danger"></a></td>
							@endif
						</tr>
						<tr ng-show="businessCategoryData.length==0" >
							<td class=" text-center" colspan="4">
								No data.                
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="businessCatModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Business Category</h4>
			</div>
			<div class="modal-body">
				<div class="col-lg-12">
					<div class="form-group">
						@include('common._dropdown',[
							'data' => $businessCategoryList, 
							'defaultText' => 'select business category', 
							'group' => 'business', 
							'language' => 'th',
							'disableSelectAll' => true
						])
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="addBusinessCategory" class="btn btn-default" data-dismiss="modal" ng-click="addBusinessCategory()">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- End Modal -->