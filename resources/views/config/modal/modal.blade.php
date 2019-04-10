<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SEARCH FOR ITEM</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                   
                    <div class="panel">
                        <div class="panel-heading bg-gray">
                            <h6 class="panel-title">Search</h6>
                        </div>
                        <div class="panel-body">
                            {!! Form::open([
                                'autocomplete' => 'off',
                                'class'        => 'form-horizontal',
                                'id'           => 'search-form-modal'
                            ]) !!}
                        <div class="row col-lg-12" style="z-index: 9999">
                            <div class="col-lg-12">
                                <div class="col-lg-6 col-md-6 col-xs-6">
                                    <label>
                                        <input type="radio" name="type_search_modal" value="0" checked> Products
                                    </label>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-xs-6">
                                    <label>
                                        <input type="radio" name="type_search_modal" value="1"> Category
                                    </label>
                                </div>
                            </div>
                        </div>
                            
                        <div class="row modalType" id="modalType0">
                              <div class="form-group">
                                  <div class="col-lg-12">
                                      <!--/////////////////-->
                                      <div class="row col-lg-12">
                                        <div class="col-lg-12">
                                         <label>Item Name or Code</label>
                                            {{ Form::text('input_product_name', null, [
                                                'id'          => 'input_product_name',
                                                'class'       => 'form-control',
                                                'placeholder' => 'Product Name'
                                            ]) }}
                                        </div>
                                    </div>
                                     <!--/////////////////-->
                                  </div>
                              </div>
                        </div>

                        <div class="row modalType" id="modalType1" style="display:none">
                              <div class="form-group">
                                  <div class="col-lg-12">
                                      <!--/////////////////-->
                                      <div class="row col-lg-12">
                                        <div class="col-lg-12">
                                           
                                            <div class="row col-lg-12">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                       {{--  <div class="col-md-6">
                                                            {{ Form::text('full_text', null, [
                                                                'id'          => 'full_text',
                                                                'class'       => 'form-control',
                                                                'placeholder' => 'Product Name ro Code'
                                                            ]) }}
                                                        </div> --}}
                                                       
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-6">
                                                            @include('common._dropdown',[
                                                                'data' => $productCategoryList,
                                                                'defaultText' => 'select product category',
                                                                'group' => 'product',
                                                                'language' => 'th',
                                                                'id' => 'inp_product'
                                                               ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('common._dropdown',[
                                                                'data' => $businessCategoryList, 
                                                                'defaultText' => 'select business category', 
                                                                'group' => 'business', 
                                                                'language' => 'th',
                                                                'id' => 'inp_business'
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            
                                        </div>
                                    </div>
                                     <!--/////////////////-->
                                  </div>
                              </div>
                        </div>

                        <div class="row col-lg-12">
                             <div class="form-group">
                                <div class="col-lg-12">
                                      {{ Form::button('<i class="icon-search4"></i> Search', array(
                                          'type'  => 'submit',
                                          'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                                      )) }}
                                </div> 
                            </div>
                        </div>
                          
                         {!! Form::close() !!} 
                        </div>
                    </div>
                </div>

                 <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="btn-group">
                                <button type="button" id=saveBTN class="btn btn-submit btn-width-100 btn-primary btn-raised legitRipple"><i class="icon-checkmark4"></i> Save</button>
                            
                        </div>
                           <table class="table table-border-gray table-striped datatable-dom-position" id="products-table" data-page-length="10" width="160%">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="check-all">
                                        </th>
                                        <th>Item&nbsp;ID</th>
                                        <th>Published</th>
                                        <th>Approval Status</th>
                                        <th width="200">Last Update</th>
                                        <th width="20">Product Name (TH)</th>
                                        <th width="20">Product Name (EN)</th>
                                        <th width="10">Supplier ID</th>
                                        <th width="20">Supplier Name</th>
                                        <th width="10">Buyer ID</th>
                                        <th width="20">Buyer Name</th>
                                        <th>Image</th>
                                        <th>Detail</th>
                                        <th>Normal Price</th>
                                        <th>Category</th>          
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            {{-- <i class="icon-spinner2 spinner"></i> --}} 
                                            No Data ...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                {{-- <ul class="pagination">
                   
                </ul> --}}
            </div>
        </div>
    </div>
</div>
