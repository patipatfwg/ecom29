<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">{{ $formName }}</h5>
        <div class="heading-elements">Last published: {{ convertDateTime($last_update_status, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</div>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            <div class="form-group" 
                @if($productName=='productIntermediate')
                    ng-class="{'has-warning': compare('last_flag')}"
                @endif>
                <label class="control-label col-lg-4 text-left text-bold">RMS Synchronization Status:</label>
                <div class="col-lg-8">
                    <label class="control-label col-lg-12 text-left"  ng-bind="{{$productName}}.last_flag"></label>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group" 
                @if($productName=='productIntermediate')
                    ng-class="{'has-warning': compare('rms_status')}"
                @endif>
                <label class="control-label col-lg-4 text-left text-bold">RMS Availability Status:</label>
                <div class="col-lg-8">
                    <label class="control-label col-lg-12 text-left"  ng-bind="{{$productName}}.rms_status"></label>
                </div>
            </div>
        </div>
        <div class="form-group">
            @include('common._switch', [
                'status' => ($product['published_status']=='Y') ? 'active' : 'inactive',
                'statusName' => 'Publication Status',
                'readonly' => $readonly,
                'leftLabel' => true,
            ])
        </div>
        <div class="col-lg-12">
            <div class="form-group" 
                @if($productName=='productIntermediate')
                    ng-class="{'has-warning': compare('approve_status')}"
                @endif>
                <label class="control-label col-lg-4 text-left text-bold">Approval Status:</label>
                <div class="col-lg-8">
                    <label class="control-label col-lg-12 text-left" ng-bind="{{$productName}}.approve_status"></label>
                </div>
            </div>
        </div>
        <div class="form-group" 
            @if($productName=='productIntermediate')
                ng-class="{'has-warning': compare('published','start_date') || compare('published','end_date')}"
            @endif>
            @include('product.datetime',[
				'product' => $productName,
				'readOnly' => $readonly
			])
        </div>
    </div>
</div>
