<form id="form-submit" class="form-horizontal" autocomplete="off">

    <!-- Input hidden field -->
    @if(isset($userGroupId))
    <input type="hidden" name="id" value="{{ $userGroupId }}">
    @endif
    <!-- End Input hidden field -->

    <div class="panel">
        <div class="panel-body">
            <div class="row">

                <!-- Status switch -->
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="col-lg-8"></div>
     
                        @include('common._switch', [ 'status' => isset($status) ? $status : 'inactive' ])

                    </div>
                </div>
                <!-- End Status switch -->

                <!-- Admin Group Name -->
                <div class="col-lg-12 margin-bottom-10">
                    <div class="form-group">
                        <label class="control-label col-lg-2" for="name">
                            <span class="text-danger">*</span> {{ trans('Role Name') }}
                        </label>
                        <div class='col-lg-10'>
                            <input 
                                type="text" 
                                class="form-control content-name" 
                                name="name"
                                maxlength="80"
                                placeholder="No longer than 80 characters"
                                value="{{ isset($name) ? $name : '' }}">
                        </div>
                    </div>
                </div>
                <!-- End Admin Group Name -->

                <!-- Authorize panel -->
                <div class="col-lg-12">

                    <div class="panel">
                        <div class="panel-heading bg-teal-400">
                            <h6 class="panel-title">Permissions</h6>
                        </div>
                    </div>

                    <div class="panel-body">
                        @foreach($menus as $menu_key => $menu)
                        <div class="role-panel margin-bottom-10">
                            <div class="row">
                                <div class="col-lg-12">
                                    <span>{{ isset($menu['name']) ? $menu['name'] : '' }}&nbsp;&nbsp;(<a class="checkAll" data-group="{{ $menu_key }}">check all</a>/<a class="uncheckAll" data-group="{{ $menu_key }}">uncheck all</a>)</span>
                                </div>
                            </div>

                            @foreach($menu['sub_menu'] as $sub_menu_key => $sub_menu)
                                 @include('user_group.form._permission', [ 'menu_key' => $menu_key, 'sub_menu_key' => $sub_menu_key, 'sub_menu' => $sub_menu ])
                            @endforeach

                        </div>
                        @endforeach
                    </div>

                </div>
                <!-- End Authorize panel -->

                <!-- Save button -->
                <div class="col-lg-12">
                    <div class="pull-right">
                        <div class="form-group">
                            {{ Form::button('<i class="icon-checkmark"></i> Save', [ 'type' => 'submit', 'class' => 'btn bg-primary-800 btn-raised btn-submit' ]) }}
                        </div>
                    </div>
                </div>
                <!-- End Save button -->

            </div>
        </div>
    </div>
</form>