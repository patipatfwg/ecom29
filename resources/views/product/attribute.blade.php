<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Attribute<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
		@if($productIntermediateData['approve_status']!='ready to approve')
			<div class="heading-elements">
				<ul class="icons-list">
					<li>  
						<a data-toggle="modal" data-target="#attributeModal"><i class="icon-plus-circle2 position-right"></i><span class="legitRipple-ripple" style="left: 60%; top: 55.5556%; transform: translate3d(-50%, -49%, 0px); transition-duration: 0.2s, 0.5s; width: 211.643%;"></span></a>  
					</li>
					<li><a data-action="collapse"></a></li>
				</ul>
			</div>
		@endif
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12">
				<table class="table table-border-teal table-striped table-hover datatable-dom-position" 
					id="categories-table" data-page-length="10" width="100%">
					<thead>
						<tr>
							<th class="bg-teal-400" width="20">No.</th>
							<th class="bg-teal-400">Attribute ID</th>
							<th class="bg-teal-400">Attribute Name</th>
                            <th class="bg-teal-400">Attribute Value</th>
							@if($productIntermediateData['approve_status']!='ready to approve')
								<th class="bg-teal-400" width="80">Remove</th>
							@endif
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="data in attributeData">
							<td>@{{ $index+1 }}</td>
							<td>@{{ data.id }}</td>
							<td>@{{ data.name }}</td>
                            <td>@{{ data.attribute_value_name }}</td>
							@if($productIntermediateData['approve_status']!='ready to approve')
							<td>
								<a ng-click="deleteAttribute($index)"><i class="icon-trash text-danger"></a>				
							</td>
							@endif
						</tr>
						<tr ng-show="attributeData.length==0" >
							<td class=" text-center" colspan="5">
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
<div class="modal fade" id="attributeModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Attribute</h4>
			</div>
			<div class="modal-body">
				<div class="col-lg-12" id="attributeForm">
					@foreach($attributeList as $attribute)
					<div class="form-group">
						<label data-toggle="collapse" data-target="#collapse-{{ $attribute['id'] }}">
							<input type="checkbox" name="attribute_id[]" id="attribute_id_{{ $attribute['id'] }}" value="{{ $attribute['id'] }}"
							@foreach($attributeData as $productAttribute)
								@if($productAttribute['attribute_id']==$attribute['id'])
									checked
								@endif
							@endforeach
							/> {{ $attribute['name']['th'] }}
						</label>
						 <div id="collapse-{{ $attribute['id'] }}" class="panel-collapse collapse in">
							<div class="form-group">
								@if(isset($attribute['subAttribute']))
									@foreach($attribute['subAttribute'] as $subAttribute)
										<input type="radio" name="attribute_value[{{ $attribute['id'] }}]" value="{{ $subAttribute['sub_attribute_id'] }}"
										@foreach($attributeData as $productAttribute)
											@if($productAttribute['attribute_value_id']==$subAttribute['sub_attribute_id'])
												checked
											@endif
										@endforeach>{{ $subAttribute['name']['th'] }} 
									@endforeach
								@endif
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="addAttribute" class="btn btn-default" data-dismiss="modal" ng-click="addAttribute()">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- End Modal -->