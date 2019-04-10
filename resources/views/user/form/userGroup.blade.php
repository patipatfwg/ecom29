

<div class="col-lg-12">
	<div class="panel">
		<div class="panel-heading bg-teal-400">
			<h6 class="panel-title">Authorize</h6>
		</div>
		<div class="panel-body">
			<div class="margin-10 margin-left-20">
				<div class="row">
					<div class="col-lg-12">
						<div id="groupRow" class="bootstrap-tagsinput" >			
                            <input type="hidden" id="userGroupTextBox" name="userGroup" value="{{ isset($id)? $arrId : '' }}">
							 @if(isset($id)&&!empty($userData['authorize']))
							 	@foreach($userData['authorize'] as $role)
								 	<span class="tag label label-info">{{ $role['name'] }}<span data-role="remove" onclick="remove(this,'{{ $role['id'] }}')"></span></span>		 
							 	@endforeach
							 @endif
							<button id="myModalP" type="button" class="tag label label-info" style="padding-right:15px;" data-toggle="modal" data-target="#myModal">+</button>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					
				</div>
			</div>
		</div>
        <!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">User Group</h4>
					</div>
					<div class="modal-body">
						<div class="col-lg-12">
							<div class="form-group">
								<label for="userGroupSelect"></label>
								<select id="userGroupSelect" class="selectpicker form-border-select">
                                    <option class="category-option" value="">
										-- Choose User Group --
									</option>
									@foreach($userGroupData as $key => $value)
										<option class="category-option" value="{{ $key }}">
											{{ $value }}
										</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="saveNewGroup" class="btn btn-default" data-dismiss="modal">Save</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
        <!-- End Modal -->
	</div>
</div>

