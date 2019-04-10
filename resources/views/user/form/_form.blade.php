<div class="panel">
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12">
				<form id="form-submit" class="" autocomplete="off">
					<div class="row">
						<div class="col-lg-12">
							@if(isset($id))
							<div class="col-lg-6">
								<div class="form-group">
									<label for="">
									<span class="text-danger"></span>  Employee ID
									</label>
									{{ Form::text('id', $userData['id'], [
									'id'          => 'id',
									'class'       => 'form-control',
									'placeholder' => 'Employee ID',
									'disabled'    => true
									]) }}
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="start_date">
									<span class="text-danger"></span> Registration Date
									</label>
									<div class="input-group">
										{{ Form::text('start_date', $userData['regis_date'], [
										'disabled' 	  =>  true ,
										'id'          => 'start_date',
										'class'       => 'form-control',
										'placeholder' => 'Register Date'
										]) }}
										<span class="input-group-addon"><i class="icon-calendar2"></i></span>
									</div>
								</div>
							</div>
							@endif
						</div>
						<div class="col-lg-12">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="username">
									<span class="text-danger">*</span> Username
									</label>
									<input name="username" value="{{ isset($id)? $userData['username'] : ''}}" id="username" class="form-control" placeholder="Username" {{ isset($id)? "disabled" : null}}>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="makro_store_id">
									<span class="text-danger">*</span> Stores
									</label>
										{{ Form::select('makro_store_id', 
											$stores,
											null, [
											'id'          => 'makro_store_id',
											'class'       => 'form-control select2'
										]) }}
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="password">
									<span class="text-danger">*</span> Password
									</label>
									{{ Form::password('password', array('class' => 'form-control','placeholder' => 'Password')) }}
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="password_confirmation">
									<span class="text-danger">*</span> Confirm Password
									</label>
									{{ Form::password('password_confirmation', array('class' => 'form-control','placeholder' => 'Confirm Password')) }}
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="name">
									<span class="text-danger"></span> First Name
									</label>
									{{ Form::text('name', isset($id)? $userData['name'] : '', [
									'id'          => 'name',
									'class'       => 'form-control',
									'placeholder' => 'First Name'
									]) }}
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="surname">
									<span class="text-danger"></span> Last Name
									</label>
									{{ Form::text('surname', isset($id)? $userData['surname'] : '', [
									'id'          => 'surname',
									'class'       => 'form-control',
									'placeholder' => 'Last Name'
									]) }}
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="mobile">
									<span class="text-danger"></span> Mobile Number
									</label>
									{{ Form::text('mobile', isset($id)? $userData['mobile'] : '', [
									'id'          => 'mobile',
									'class'       => 'form-control',
									'placeholder' => '08xxxxxxxx'
									]) }}
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="email">
									<span class="text-danger"></span> Email
									</label>
									{{ Form::email('email', isset($id)? $userData['email'] : '', [
									'id'          => 'email',
									'class'       => 'form-control',
									'placeholder' => 'sample@sample.com'
									]) }}
								</div>
							</div>
						</div>
					 
						@include('user.form.userGroup')
						<div class="col-lg-12">
							<div class="pull-right">
								<div class="form-group">
									{{ Form::button('<i class="icon-checkmark"></i> Save', [ 'type' => 'submit', 'class' => 'btn bg-primary-800 btn-raised btn-submit' ]) }}
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

