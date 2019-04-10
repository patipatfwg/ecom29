<div class="row">
    <div class="col-lg-6">

        <!-- Start: Product Name, Update Date -->
        <div class="form-group">
            
            <div class="col-lg-12">
                <label>Product Name</label>
                {{ Form::text('input_product_name', null, [
                    'id'          => 'input_product_name',
                    'class'       => 'form-control',
                    'placeholder' => 'Product Name'
                ]) }}
            </div>

            
            
            <div class="clearfix"></div>

        </div>
        <!-- End: Product Name, Update Date -->

        <!-- Start: Supplier, Buyer -->
        <div class="form-group">
            <div class="col-lg-6">
                <label>Supplier ID or Name</label>
                {{ Form::text('input_supplier', null, [
                    'id'          => 'input_supplier',
                    'class'       => 'form-control',
                    'placeholder' => 'Supplier ID, Supplier Name'
                ]) }}
            </div>
            <div class="col-lg-6">
                <label>Buyer ID or Name</label>
                {{ Form::text('input_buyer', null, [
                    'id'          => 'input_buyer',
                    'class'       => 'form-control',
                    'placeholder' => 'Buyer ID, Buyer Name'
                ]) }}
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- End: Supplier, Buyer -->

        
        <div class="form-group" style="padding-top: 5px;">
            <!-- Start: Item ID, Approve Status, Published Status -->
            <div class="col-lg-6">
                <label>Item ID</label>
                 {{ Form::text('input_item_id', null, [
                    'id'          => 'input_item_id',
                    'class'       => 'form-control',
                    'placeholder' => 'Item ID'
                ]) }}
            </div>
            <!-- End: Item ID, Approve Status, Published Status -->

            <div class="col-lg-6">
                <label>Update Date</label>
                    {{ Form::text('input_updated_date', null, [
                        'id'          => 'input_updated_date',
                        'class'       => 'form-control',
                        'placeholder' => 'DD/MM/YYYY'
                    ]) }}
            </div>
        </div>
        

    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <!-- Start: Product Category Dropdown --> 
            <div class="col-md-12">
                <label>Product Category</label>
                @include('common._dropdown', ['data' => $product_categories, 'defaultText' => 'Select Product Category', 'group' => 'product', 'language' => 'th'])
            </div>
            <!-- End: Product Category Dropdown --> 
        </div>
        <div class="form-group">
            <!-- Start: Business Category Dropdown --> 
            <div class="col-md-12">
                <label>Business Category</label>
                @include('common._dropdown', ['data' => $business_categories, 'defaultText' => 'Select Business Category', 'group' => 'business', 'language' => 'th'])
            </div>
            <!-- End: Business Category Dropdown -->
        </div>
        <div class="form-group">
            <div class="col-lg-6" style="margin-top: 2px;">
                <label>Approval Status</label>
                @include('common._select',['data' => $approve_status, 'default' => 'any', 'defaultValue' => 'Any', 'id' => 'select-approval'])
            </div>

            <div class="col-lg-6" style="margin-top: 2px;">
                <label>Publication Status</label>
                @include('common._select',['data' => $publish_status, 'default' => 'any', 'defaultValue' => 'Any', 'id' => 'select-publish'])
            </div>

            
        </div>
    </div>

    <div class="col-lg-12">
        <div class="form-group">
            <div class="col-lg-6">
                <label>Makro Store</label>
                {{ Form::select('store_id', $stores, null, [
                    'id'          => 'store_id',
                    'class'       => 'form-control select2',
                    'placeholder' => 'Select Stores...'
                ]) }}
            </div>
            <div class="col-lg-3">
                <label>Image</label>
                @include('common._select',[
                    'data' => [
                        'Y' => 'Yes',
                        'N' => 'No'
                    ],
                    'default' => 'any',
                    'defaultValue' => 'Any',
                    'id' => 'select-image-status',
                ])
            </div>
            <div class="col-lg-3">
                <label>RMS Status</label>
                @include('common._select',[
                    'data' => [
                        'RMS New' => 'RMS New',
                        'RMS Updated' => 'RMS Updated',
                        'RMS No Update' => 'RMS No Update'
                    ],
                    'default' => 'any',
                    'defaultValue' => 'Any',
                    'id' => 'select-action-status',
                ])
            </div>
        </div>
    </div>

</div>