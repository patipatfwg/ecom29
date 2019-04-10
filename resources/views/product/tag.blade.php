<div class="col-lg-12">
    <div class="panel">

        <!-- Header panel -->
        <div class="panel-heading bg-teal-400">
            <h6 class="panel-title">{{ trans_append_language('Tags',$lang) }}</h6>
        </div>
        <!-- End Header panel -->

        <!-- Tags panel -->
        <div class="panel-body">
            <div class="margin-10 margin-left-20">
                <div class="row">
                    <div class="col-lg-12 tags-input-container form-border">
                        @if( isset($tags['id']) )
                        <input type="hidden" name="tag_id" value="{{ $tags['id'] }}"> 
                        @endif
                        <input id="tagsinput_{{$lang}}" type="text"
                        @if($readonly)
                            disabled
                        @else
                            name="tags[{{ $lang }}]"
                        @endif
                        data-role="tagsinput" placeholder="Type some tag...">
                    </div>
                </div>
                <!-- <div class="margin-top-12">
                    <label id="tags_show_{{$lang}}" class="control-label break-word">Ex :</label>
                </div> -->
            </div>

            <!-- Edit icon -->
            <!-- <div class="text-right">
                <i class="glyphicon glyphicon-pencil"></i>
            </div> -->
            <!-- End Edit icon -->

        </div>
        <!-- End Tags panel -->

    </div>
</div>