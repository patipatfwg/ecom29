@if(isset($custom))
<div class="btn-group">
    <a href="#" target="_blank" class="custom-print-report btn btn-width-100 bg-violet-300 btn-raised legitRipple">
    <i class="icon-file-download"></i> EXPORT</a>
</div>
@else
<div class="btn-group">
    <a href="#" target="_blank" class="print-report btn btn-width-100 bg-violet-300 btn-raised legitRipple" id="btn_export">
    <i class="icon-file-download"></i> EXPORT</a>
</div>
@endif