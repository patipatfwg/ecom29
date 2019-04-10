{{--<style>.DTFC_LeftBodyLiner { overflow-x: hidden; }</style>--}}
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Refund List</h6>
    </div>
    <div class="panel-body table-responsive">
        {{--<div class="row">--}}
            {{--<div class="col-lg-12">--}}
                <table class="table table-border-gray table-striped datatable-dom-position" id="invoices-table" data-page-length="10" width="160%">
                    <thead>
                    <tr>
                        <th width="80">No.</th>
                        <th width="200">Credit Note Number</th>
                        <th>Created Date</th>
                        <th>Store</th>
                        <th>Payment Type</th>
                        <th>Refund Amount</th>
                        <th>Refund Reason</th>
                        <th>Modified Date</th>
                        <th>Status</th>
                        <th width="50">Manage</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<tr>--}}
                        {{--<td width="80">1</td>--}}
                        {{--<td width="200">@InvoiceNo</td>--}}
                        {{--<td>@Createts</td>--}}
                        {{--<td>@TotalAmount</td>--}}
                        {{--<td>@PaymentType</td>--}}
                        {{--<td>???</td>--}}
                        {{--<td>@Modifyts</td>--}}
                        {{--<td>???</td>--}}
                        {{--<td width="50">Manage</td>--}}
                    {{--</tr>--}}
                    {{--<tr><td colspan="9" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>--}}
                    </tbody>
                </table>
            {{--</div>--}}
        {{--</div>--}}
    </div>
</div>
@section('footer_script')
    @parent
    <script>
        <?php
            $js_array = json_encode($refund_dataset);
            $permission_epos = json_encode($permission);
            echo "var permission = ". $permission_epos . ";\n";
            echo "var dataSet = ". $js_array . ";\n";
            ?>
            $.fn.dataTable.ext.errMode = 'none';
        $(document).ready(function() {
            var t = $('#invoices-table').DataTable( {
                data: dataSet,
                scrollX:        true,
//                scrollY:        "5000px",
//                scrollY: true,
                scrollCollapse: true,
                scroller:       true,
                retrieve:       true,
                destroy:        true,
                bPaginate:      true, //hide pagination
                bFilter:        false, //hide Search bar
                bInfo:          true, // hide showing entries
                fixedColumns:   {
                    leftColumns: 1,
                    rightColumns: 1,
                },
                autoWidth: false,
                iDisplayLengt: "All",
                dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
                language: {
                    lengthMenu: '<span>Show :</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                }

            }).on('error.dt', function(e, settings, techNote, message) {
                new PNotify({text: 'Error connection', type: 'error' });
            });

            t.on('order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();
            // Disaple Export Button
            // if (dataSet != '' && permission.refund_order_export == true) {
            //    $('div.datatable-header').append('<a href="#" target="_blank" class="print-report btn btn-width-100 bg-violet-300 btn-raised legitRipple" style="margin-left: 10px;"><i class="icon-file-download"></i> EXPORT</a>');
            // }

            $('.print-report').on('click', function(event) {
               event.preventDefault();
               var startDate = getParameterByName('start_date');
               var endDate = getParameterByName('end_date');
               var status = getParameterByName('status');
               window.location.replace($("meta[name='root-url']").attr('content') + '/epos/refund/export?start_date=' + startDate + '&end_date=' + endDate + '&status=' + status);
            });

        } );

        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        function update(invoiceNo) {
            var ddlStatus = '<select id="updateStatus" class="form-control" style="width: 50%; margin: 0 auto;" data-width="100%" name="status"><option value="2">In Progress</option><option value="3">Settled</option></select>';
            if ('{{$status}}' == 'InProgress') {
                ddlStatus = '<select id="updateStatus" class="form-control" style="width: 50%; margin: 0 auto;" data-width="100%" name="status"><option value="3">Settled</option></select>';
            }

            swal({
                    title: "Are you sure?",
                    text: "you want to update refund status for invoice <br/><br/>"+invoiceNo+" to <br/><br/>"+ddlStatus+" <br/>(this action cannot be undone)",
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
                        //console.log('updateStatus-->', $('#updateStatus').val());
                        var url = '/epos/refund/'+invoiceNo+'/update';
                        var status = $('#updateStatus').val();
                        $.ajax({
                            type: 'PUT',
                            url: url,
                            data: {invoiceNo: invoiceNo, status: status},
                            success: function(data) {
                                if (data.status || data.success) {
                                    swal({ title: "Update succeed.", type: 'success' },function(){
                                        location.reload();
                                    });

                                    //formSubmit[0].reset();
                                    //window.location = url
                                } else {
                                    console.log(data)
                                    swal({ title: "Update fail", text: data.messages, type: 'error' });
                                }
                            }
                        });
                    }
                });
        }

    </script>
@endsection