"use strict";

$.fn.dataTable.ext.errMode = 'none';

//set tabel
var url = '/coupon';
var $table = $('#coupons-table');
var $thead = $table.find('thead');
var $tbody = $table.find('tbody');

//set dataTabel
var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
    new PNotify({text: message, type: 'error' });
}).DataTable({
    scrollY: true,
    scrollX: '300px',
    lengthMenu:[10,50,100,500,1000],
    processing: false,
    serverSide: true,
    searching: false,
    retrieve : true,
    destroy : true,
    order: [[ 0, false ]],
    cache: false,
    dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
    language: {
        lengthMenu: '<span>Show :</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
    },
    ajax: {
        url: url + '/data',
        type: 'GET',
        data: function (d) {
            d.coupon_code = $('#coupon_code').val();
            d.coupon_name = $('#coupon_name').val();
            d.coupon_type = $('#status').val();
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
                new PNotify({text: error, type: 'error' });
                $tbody.children().remove();
                $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
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
            name: 'checkbox',
            orderable: false,
            className: 'text-center',
            render: function(data, type, row){
                 if( row.usage_count > 0){
                     return '-';
                }else{
                     return '<input type="checkbox" class="ids check" name="coupon_ids[]" value="'+data+'">';
                }
                
            } 
        },
        
        { data: 'number', name: 'number',  orderable: false, width: '80px', searchable: false},
        { data: 'coupon_code', name: 'coupon_code' },
        { data: 'coupon_name_th', name: 'coupon_name_th' },
        { data: 'coupon_name_en', name: 'coupon_name_en' },
        { data: 'coupon_type', name: 'coupon_type' },
        { data: 'amount', name: 'amount', className: 'text-right'},
        { data: 'created_at', name: 'created_at' },
        { data: 'started_date', name: 'started_date' },
        { data: 'end_date', name: 'end_date' },
        { data: 'expired_at', name: 'expired_at' },
        { 
            data: 'usage_count',
            name: 'usage_count',
            orderable: true,
            className: 'text-center',
            render: function(data, type, row){
                
                    return '<label class="w-45">'+ data +'</label><a href="coupon/'+ row.id +'/usage"><i class="icon-sort ic-blue" id="'+ row.id +'"></i></a>';
            }
        },
        { 
            data: 'status',
            name: 'status',
            orderable: false,
            className: 'text-center',
            render: function(data, type, row){
                if(data == 'active'){
                    return '<i class="icon-eye text-teal"></i>';
                }
                else{
                    return '<i class="icon-eye-blocked text-grey-300"></i>';
                }
            }
        },
        {   
            data: 'id',
            name: 'edit',
            orderable: false,
            className: 'text-center',
            render: function(data, type, row){
                if( row.expired_at == '') {
                    return '<a href="/coupon/' + data + '/edit" class="btn btn-xs" data-placement="top" data-original-title="Edit" title="Edit" ><i class="icon-pencil"></i></a>';
                }else{
                    return '<i class="icon-pencil"></i>';
                }
                
            }
        },
        { 
            data: 'removable',
            name: 'delete',
            orderable: false,
            className: 'text-center',
            render: function(data, type, row){
                    if( row.usage_count > 0){
                         return '<i class="icon-trash" title="This coupon have coupon usage.">';
                    }else{
                         return '<a onclick="deleteItems(\'' + row.id + '\')"><i class="icon-trash text-danger"></a>';
                    }
                   
            }
        }
    ]
});

$('#select-type').on('select2:select', function (evt) {
        var status = $("#select-type option:selected").val();
        $("#status").val(status);
});


