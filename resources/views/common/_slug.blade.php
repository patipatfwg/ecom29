<div class="col-lg-12">
    <div class="panel">

        <!-- Header panel -->
        <div class="panel-heading bg-teal-400">
            <h6 class="panel-title"><span class="text-danger">* </span>Slug</h6>
        </div>
        <!-- End Header panel -->

        <!-- Tags panel -->
        <div class="panel-body">
            <div class="margin-10 margin-left-20">
                <div class="row">
                    <div class="col-lg-8">
                        <input type="text" name="slug" value="{{ isset($slug) ? $slug : '' }}" class="form-control" placeholder="Type some slug..."
                        @if(isset($readOnly)&&($readOnly))
                            readonly
                        @endif
                        >
                    </div>
                </div>
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

