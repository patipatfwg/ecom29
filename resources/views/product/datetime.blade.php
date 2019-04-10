<!-- Date time picker panel -->
<div class="col-lg-12">
    <label class="control-label col-lg-3 text-left">
        <span class="text-danger"></span>Published Date
    </label>
    <div class="col-lg-9">
        <div class="input-group input-daterange">
            <input 
                id="start_date{{ ($readOnly)? '-online' : '-intermediate' }}" 
                type="text" 
                class="form-control" 
                name="start_date{{ ($readOnly)? '-online' : '' }}" 
                ng-model="{{$productName}}.published.started_date" 
                ng-readonly={{$readOnly}}
                >
            <div class="input-group-addon">
                to
            </div>
            <input 
                id="end_date{{ ($readOnly)? '-online' : '-intermediate' }}" 
                type="text" 
                class="form-control" 
                name="end_date{{ ($readOnly)? '-online' : '' }}" 
                ng-model="{{$productName}}.published.end_date" 
                ng-readonly={{$readOnly}}
                >
        </div>
    </div>
</div>
<!-- End Date time picker panel -->