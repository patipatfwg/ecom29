<script type="text/javascript">
    var url = '/campaign/{{ $campaign_id }}/product/add';
    var tableId = $('#product-mapping-table');

    var oTable  = tableId.on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 1, false ]],
        cache: true,
        dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url+'/data',
            type: 'GET',
            data: function (d) {
                d.category = { business: $('.dropdown-input[group-name="business"]').val(),product: $('.dropdown-input[group-name="product"]').val()};
                d.name = $('#full_text').val();
                d.item_id = $('#item_id_text').val();
                d.online = 1;
            },
            error: function(xhr, error, thrown) {
                swal('Error!', 'Error connection', 'error');
                tableId.find('tbody').find('td').html('No Data, please try again later');
            }
        },
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['data'];
            });
        },
        columns: [
            { data: 'id', name: 'checkbox', orderable: false, className: 'text-center',
                render: function(data, type, row){
                    return '<input class="ids click-all check" type="checkbox" name="campaign_product_ids[]" value="'+data+'">';
                }
            },
            { data: 'number', name: 'number', orderable: false, className: 'text-center'},
            { data: 'item_id', name: 'item_id'},
            { data: 'name_th', name: 'name_th'},
            { data: 'name_en', name: 'name_en'},
            { data: 'normal_price', name: 'normal_price', className: 'text-right'},
            { data: 'approve_status', name: 'approve_status', className: 'text-center'},
            { 
                data: 'published_status', 
                name: 'published_status', 
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="icon-eye text-teal"></i>';
                    }
                    else {
                        return '<i class="icon-eye-blocked text-grey"></i>';
                    }
                }
            }
        ]
    });
</script>

@include('common._datatable')