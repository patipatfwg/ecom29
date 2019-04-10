<script type="text/javascript">

    $('.select-dropdown').select2({
        minimumResultsForSearch: -1
    });
    
    var url = '/campaign/{{ $campaign_id }}/product';
    var tableId = $('#product-mapping-table');

    var oTable  = tableId.on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        fixedColumns: {},
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 6, 'asc' ]],
        cache: true,
        dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url,
            type: 'GET',
            data: function (d) {
                d.category = { business: $('.dropdown-input[group-name="business"]').val(),product: $('.dropdown-input[group-name="product"]').val()};
                d.name = $('#full_text').val();
                d.item_id = $('#item_id_text').val();
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
                    if((row.currentDateTimestamp <= row.endDateTimestamp && row.currentDateTimestamp >= row.startDateTimestamp) && row.campaignStatus == 'active'){
                        return '-';
                    }
                    else{
                        return '<input class="ids check" type="checkbox" name="campaign_product_ids[]" value="'+data+'">';
                    }
                }
            },
            { data: 'number', name: 'number', orderable: false, className: 'text-center'},
            { data: 'item_id', name: 'item_id'},
            { data: 'name_th', name: 'name_th'},
            { data: 'name_en', name: 'name_en'},
            { data: 'normal_price', name: 'normal_price', className: 'text-right'},
            {
                data: 'priority', 
                name: 'priority',
                orderable: true,
                searchable: false,
                render: function(data, type, row){
                    if((row.currentDateTimestamp <= row.endDateTimestamp && row.currentDateTimestamp >= row.startDateTimestamp) && row.campaignStatus == 'active'){
                        return '<input readonly type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" type="text" id="product-'+row['id']+'-priority" name="priority['+row.Product_id+']" priority="'+data+'" value="'+data+'"><input type="hidden" class="priority_number" name="priority_old['+row.Product_id+']"priority_old="'+data+'" value="'+data+'">';
                    }
                    else{
                        return '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" type="text" id="product-'+row['id']+'-priority" name="priority['+row.Product_id+']" priority="'+data+'" value="'+data+'"><input type="hidden" class="priority_number" name="priority_old['+row.Product_id+']"priority_old="'+data+'" value="'+data+'">';
                    }
                }
            },
            { data: 'approve_status', name: 'approve_status', orderable: true, className: 'text-center'},
            { data: 'delete', name: 'delete', orderable: false, className: 'text-center',
                render: function(data, type, row){
                    if((row.currentDateTimestamp <= row.endDateTimestamp && row.currentDateTimestamp >= row.startDateTimestamp) && row.campaignStatus == 'active'){
                        return '<i class="icon-trash">';
                    }
                    else{
                        return '<a onclick="deleteItems(\''+data+'\')"><i class="icon-trash text-danger"></a>';
                    }
                }
            }
        ]
    });

    // Data table header
    $('div.datatable-header').append(`
        @include('common._delete_button')&nbsp;
        @include('common._priority_button')
    `);
</script>
@include('common._priority_script')

<script type="text/javascript">
    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('body').on('click', '.check-all', function() {
        $('table input:checkbox').not(this).prop('checked', this.checked);
    });

    $('body').on('click', '.check', function() {
        $('table .check-all').prop('checked', false);
    });

    $('body').on('click', '.status-checkbox', function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: url + '/status',
            data: $('.ids:checked').serialize() + '&status=' + $(this).attr('status-data'),
            success: function(data){
                oTable.draw();
            },
            complete: function (data) {
                $('.check-all').prop('checked', false);
            }
        });
    });

    $('body').on('click', '.btn-priority', function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: url + '/priority',
            data: $('.priority_number').serialize(),
            success: function(data){
                // onAjaxMultipleItem(data.success,data.errors);
                onAjaxSuccess(data);
                oTable.draw();
            },
            complete: function (data) {

            }
        });
    });

    $('body').on('click', '.datatable-button', function(event) {
        event.preventDefault();
        $('.check-all').prop('checked', false);
        var action = $(this).attr('button-action');
        if(action == 'show'){
            callAjax('PUT', url+"/status/"+getCheckedId(),{status:'active'}, function(){
                oTable.draw();
            });
        }
        else if(action == 'hide'){
            callAjax('PUT', url+"/status/"+getCheckedId(),{status:'inactive'}, function(){
                oTable.draw();
            });
        }
        else if(action == 'delete'){
            deleteItems(getCheckedId());
        }
    });

    function deleteItems(ids){
        swal({
            title: "{{ trans('validation.delete.alert.title') }}",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "{{ trans('validation.delete.alert.btn_cancel') }}",
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "{{ trans('validation.delete.alert.btn_ok') }}",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function(isConfirm){
            if (isConfirm) {
                callAjax('DELETE' , url+"/"+ ids, null, null, null, function(){
                    oTable.draw();
                });
            }
        });
    }

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content') + url + '/report?' + $.param(data));
    });
</script>

@include('common._call_ajax')