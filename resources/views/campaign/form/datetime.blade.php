<!-- Date time picker panel -->
<div class="col-lg-8">
    <label class="control-label col-lg-3 text-left">
        <span class="text-danger">*</span>{{ trans(' Publish Date : ') }}
    </label>
    <div class="col-lg-9">
        <div class="input-group input-daterange">
            <input 
                id="start_date" 
                type="text" 
                class="form-control" 
                name="start_date" 
                value="{{ !empty($campaignData['start_date'])? convertDateTime($campaignData['start_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '' }}"
                >
            <div class="input-group-addon">
                to
            </div>
            <input 
                id="end_date" 
                type="text" 
                class="form-control" 
                name="end_date" 
                value="{{ !empty($campaignData['end_date'])? convertDateTime($campaignData['end_date'], 'Y-m-d H:i:s', 'd/m/Y H:i:s') : '' }}">
        </div>
    </div>
</div>
<!-- End Date time picker panel -->