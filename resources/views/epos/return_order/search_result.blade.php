<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Return Order Lists of Order {{ $order_number }}</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="order-return-table" data-page-length="10" width="100%">
                    <thead>
                    <tr>
                        <th width="200">Return Order Number</th>
                        <th>Return Date</th>
                        <th>Return Amount</th>
                        <th>Return Channel</th>
                        <th width="50">Manage</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<tr><td colspan="6" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('footer_script')
    @parent
    <script>
        <?php
        $js_array = json_encode($order_dataset);
        echo "var dataSet = ". $js_array . ";\n";
        ?>
        $(document).ready(function() {
            $('#order-return-table').DataTable( {
                data: dataSet,
                "scrollX": true,
                "bPaginate": false, //hide pagination
                "bFilter": false, //hide Search bar
                "bInfo": false // hide showing entries
//                columns: [
//                    { title: "Order number" },
//                    { title: "Return Date" },
//                    { title: "Return Amount" },
//                    { title: "Return Channel" },
//                    { title: "Manage" }
//                ]
            } );
        } );
    </script>
    {{--{{ Html::script('js/epos/return_datatable.js') }}--}}
@endsection