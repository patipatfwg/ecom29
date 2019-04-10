<script type="text/javascript">

    var url = '/group_menu';
    var tableId = $('#group_menu-table');
    var id = '{{$id}}';

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
        order: [[ 5, 'asc' ]],
        cache: true,
        dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url +'/'+ id +'/hilight',  
            type: 'GET',
            data: function (d) {
                console.log(d);
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
            {
                data: 'id',
                name: 'checkbok',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<input class="ids check" type="checkbox" name="user_group_ids[]" value="'+data+'">';
                }
            },
            { data: 'number', name: 'number', orderable: false },
            { data: 'value', name: 'value' },
            { data: 'hilight_name_th', name: 'hilight_name_th' },
            { data: 'hilight_name_en', name: 'hilight_name_en' },
            { 
                data: 'priority', 
                name: 'priority',
                className: 'text-center',
                render: function(data, type, row){
                    return '<input type="text" maxlength="2" onKeyPress="CheckNum()" class="form-control input-width-priority-80 text-center priority_number" name="priority['+row.id+']" value="' + data + '"><input type="hidden" class="priority_number" name="priority_old['+row.id+']" priority_old="'+data+'" value="'+data+'">';
                }
            },
            {
                data: 'status',
                name: 'status',
                className: 'text-center',
                render: function(data, type, row){
                    return data == 'active'? '<i class="icon-eye text-teal">' : '<i class="icon-eye-blocked text-grey-300">';
                }
            },
            {
                data: 'edit',
                name: 'edit',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a href="'+data+'"><i class="icon-pencil"></i></a>';
                }
            },
            {
                data: 'delete',
                name: 'delete',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<a onclick="deleteHilight(\''+data+'\')"><i class="icon-trash text-danger"></a>';
                }
            }
        ]
    });

    $('div.datatable-header').append(`
        @include('common._show_hide_button')&nbsp;
        @include('common._delete_button')&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._priority_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('group_menu/'.$id.'/menu/add?title='.$title)])
    `);


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
            url: url + '/menu/status',
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
            callAjax('PUT', url+"/menu/status/"+getCheckedId(),{status:'active'}, function(){
                oTable.draw();
            });
        }
        else if(action == 'hide'){
            callAjax('PUT', url+"/menu/status/"+getCheckedId(),{status:'inactive'}, function(){
                oTable.draw();
            });
        }
        else if(action == 'delete'){
            deleteHilight(getCheckedId());
        }
    });
        function deleteHilight(ids){
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
                callAjax('DELETE' , url+"/"+ ids+"?type=hilight" , null, null, null, function(){
                    oTable.draw();
                });
            }
        });
    }

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content') + url + '/' + id + '/' + '{{$title}}' + '/report?' + $.param(data));
    });
</script>

