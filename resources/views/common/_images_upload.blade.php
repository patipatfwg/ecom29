<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Picture <small>
        @if(!isset($hidden))
            <span class="text-danger">*</span>&nbsp;Only .jpg or .png allowed and 800x800 resolution</small>
        @endif
        </h5>
        
    </div>

    <div class="panel-body">
        <div class="panel-body" ng-controller="imageController" id="imageController">
            <div class="row">
                <div class="col-lg-12">
                @if(!isset($hidden))
                    <div id="dropzone" class="dropzone"></div>
                @endif
                </div>
            </div>
            <div class="row">
                <!-- 
                <div class="col-lg-8">
                    <table class="table table-border-teal table-striped table-hover datatable-dom-position" data-page-length="10" width="100%">
                        <thead>
                            <tr>
                                <th class="bg-teal-400" width="20">No.</th>
                                <th class="bg-teal-400"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="data in tmpImage" width="46.4px">
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td class=" text-center" colspan="4" ng-show="tmpImage.length==0">
                                    No data.                
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                -->

                <div class="col-lg-12">
                    <table class="table table-border-teal table-striped table-hover datatable-dom-position" 
                        id="images-table" data-page-length="10" width="100%">
                        <thead>
                            <tr>
                                <!-- <th class="bg-teal-400" width="10">No.</th> -->
                                <th class="bg-teal-400" width="80">Image</th>
                                @if(!isset($hidden))
                                <th class="bg-teal-400" width="10"><i class="icon-trash"></i></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="images-table-body">
                            <tr ng-repeat="data in tmpImage track by $index">
                                <!-- <th>@{{ $index+1 }}</th> -->
                                <th><img ng-src="@{{ data.url }}?process=resize&resize_width=40&resize_height=40" height="40px"><input type="hidden" name="images[]" value="@{{ data.url }}"></th>
                                @if(!isset($hidden))
                                <th><a ng-click="deleteImage($index)"><i class="icon-trash text-danger"></i></a></th>
                                @endif
                            </tr>
                            <tr>
                                <td class=" text-center" colspan="4" ng-show="tmpImage.length==0">
                                    No data.                
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
