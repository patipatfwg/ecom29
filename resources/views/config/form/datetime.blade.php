<!-- Date time picker panel -->
<div class="col-lg-12">
    <div class="row">
           <label class="control-label col-lg-2 text-left">
            {{ trans(' Valid Period') }}
        </label>
        <div class="col-lg-7">
            <div class="input-group input-daterange">
                <input id="start_date" type="text" class="form-control" name="start_date" value="{{ isset($payment)? date('d/m/Y  H:i:s', strtotime($payment['started_date'])) : '' }}">
                <div class="input-group-addon">
                    to
                </div>
                <input id="end_date" type="text" class="form-control" name="end_date" value="{{ isset($payment)? date('d/m/Y H:i:s', strtotime($payment['end_date'])) : '' }}">
            </div>
        </div>
    </div>

</div>
<!-- End Date time picker panel -->