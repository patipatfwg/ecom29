<script type="text/javascript">
    var url = '/coupon';
    var tableId = $('#history-table');
    var id = '{{$id}}';
    var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: message, type: 'error' });
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, false ]],
        cache: true,
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url + '/' + id + '/usage/data',
            type: 'GET',
            data: function(d) {
            d.full_text = $('#full_text').val();
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.status = $('#status').val();
            },
            error: function(xhr, error, thrown) {
                if(xhr.responseJSON.expired) {
                    swal({
                    title: "Error!",
                    text: 'Session Expired',
                    type: "error",
                    confirmButtonText: "OK"
                    },
                    function(){
                        location.reload();
                    });
                } else {
                    swal('Error!', 'Error connection', 'error');
                    tableId.find('tbody').find('td').html('No Data, please try again later');
                }
            }
        },
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['data'];
            });
        },
        columns: [
            { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
            { data: 'used_date', name: 'used_date'},
            { data: 'order_no', name: 'order_no' },
            { data: 'order_amount', name: 'order_amount' },
            { data: 'makro_member_card', name: 'makro_member_card' },
            { data: 'first_name', name: 'customer_firstname' },
            { data: 'last_name', name: 'customer_lastname' },
            { data: 'customer_type', name: 'customer_type' },
            { data: 'mobile_number', name: 'mobile_number' },
            { data: 'email', name: 'email' },
            { data: 'status', name: 'status' }
        ]
    });

    $('div.datatable-header').append(`
        @include('common._print_button')&nbsp;
    `);

    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('#select-type').on('select2:select', function (evt) {
        var status = $("#select-type option:selected").val();
        $("#status").val(status);
    });

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content') + url + '/' + id + '/report?' + $.param(data));
    });

    $(".select-dropdown").select2({
        minimumResultsForSearch: -1,
        placeholder: 'SELECT TYPE'
    });
</script>