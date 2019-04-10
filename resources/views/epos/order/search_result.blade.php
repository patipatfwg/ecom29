<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Order {{-- #0000000670 --}}</h6>

    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                @include('epos.order.order_summary')
            </div>
            <div class="col-lg-6">
                @include('epos.order.payment_summary')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                @include('epos.order.shipping_address')
            </div>
            <div class="col-lg-6">
                @include('epos.order.billing_address')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading bg-gray">
                        <h6 class="panel-title">Shopping Cart</h6>
                    </div>
                    <table class="table table-border-gray table-striped datatable-dom-position" id="order-detail-table" data-page-length="10" width="100%">
                        <thead>
                        <tr>
                            <th width="80">NO.</th>
                            <th>Item Name</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th width="80">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--<tr>--}}
                            {{--<td width="80">1</td>--}}
                            {{--<td>[@ItemID]</td>--}}
                            {{--<td>[@UnitCost]</td>--}}
                            {{--<td>[@OrderedQty]</td>--}}
                            {{--<td width="80">[@Status]</td>--}}
                        {{--</tr>--}}
                        <tr><td colspan="5" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@section('footer_script')
    @parent
    <script>
        <?php
        $js_array = json_encode($product_dataset);
        echo "var dataSet = ". $js_array . ";\n";
        ?>
        $(document).ready(function() {
            $('#order-detail-table').DataTable( {
                data: dataSet,
                "scrollX": true,
                "bPaginate": false, //hide pagination
                "bFilter": false, //hide Search bar
                "bInfo": false, // hide showing entries
                fixedColumns:   {
                    //leftColumns: 1,
                    rightColumns: 1
                }
//                columns: [
//                    { title: "Id" },
//                    { title: "Name" },
//                    { title: "Price" },
//                    { title: "Qty" },
//                    { title: "Status" }
//                ]
            } );
        } );
    </script>
@endsection