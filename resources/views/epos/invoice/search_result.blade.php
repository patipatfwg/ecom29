<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="invoices-table" data-page-length="10" width="160%">
                    <thead>
                    <tr>
                        <th width="80">No.</th>
                        <th width="100">Manage</th>
                        <th width="100">Order No.</th>
                        <th width="100">Invoice No.</th>
                        <th>Created Date</th>
                        <th>Invoice Type</th>
                        <th>Amount</th>
                        <th>Print Counter</th>
                        <th>Original Invoice No.</th>
                        <th>Issued Date</th>
                        <th>Settlement Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<tr>--}}
                        {{--<td width="80">1</td>--}}
                        {{--<td width="100">Manage</td>--}}
                        {{--<th width="100">@OrderNo</th>--}}
                        {{--<td width="100">@InvoiceNo</td>--}}
                        {{--<td>@Createts</td>--}}
                        {{--<td>@InvoiceType</td>--}}
                        {{--<td>@TotalAmount</td>--}}
                        {{--<td>???</td>--}}
                        {{--<td>@MasterInvoiceNo</td>--}}
                        {{--<td>@DateInvoiced</td>--}}
                        
                    {{--</tr>--}}
                    {{--<tr><td colspan="9" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>--}}
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
        $js_array = json_encode($invoice_dataset);
        echo "var dataSet = ". $js_array . ";\n";
        ?>
        $.fn.dataTable.ext.errMode = 'none';
        $(document).ready(function() {
            $('#invoices-table').DataTable( {
                data: dataSet,
                "scrollX": true,
                "bPaginate": false, //hide pagination
                "bFilter": false, //hide Search bar
                "bInfo": false, // hide showing entries
                fixedColumns:   {
                    leftColumns: 1,
                    rightColumns: 1
                }
//                columns: [
//                    { title: "Invoice No." },
//                    { title: "Created Date" },
//                    { title: "Invoice Type" },
//                    { title: "Amount" },
//                    { title: "Print Counter" },
//                    { title: "Original Invoice No." },
//                    { title: "Issued Date" },
//                    { title: "Manage" }
//                ]
            } );
        } );

        function update(invoiceNo, omsInvoiceNo, invoiceType, paymentType, status) { 
            swal(
                {
                    title: "Are you sure?",
                    text: 'You want to confirm to issue credit note<br/><br/>' + omsInvoiceNo + ' to <span class="font-bold">Settled</span><br/><br/>(this action cannot be undone)',
                    type: "warning",
                    html: true,
                    showCancelButton: true,
                    cancelButtonText: "Cancel",
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: "Confirm",
                    closeOnConfirm: true,
                    showLoaderOnConfirm: true
                },
                function(isConfirm){
                    if (isConfirm) {
                        var url = '/epos/refund/'+invoiceNo+'/update';
                        $.ajax({
                            type: 'PUT',
                            url: url,
                            data: {
                                invoiceNo: invoiceNo, 
                                status: status,
                                paymentType: paymentType,
                                invoiceType: invoiceType
                            },

                            success: function(data) {

                                if (data.status || data.success) {
                                    swal({ title: "Update succeed.", type: 'success' },function(){
                                        location.reload();
                                    });
                                } else {
  
                                    swal({ title: "Update fail", text: data.messages, type: 'error' });
                                }
                            }
                        });
                    }
                }
            );
        }

    </script>
@endsection