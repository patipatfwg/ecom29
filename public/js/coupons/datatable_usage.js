"use strict";

$.fn.dataTable.ext.errMode = 'none';

//set tabel
var $table = $('#coupons-table');
var $thead = $table.find('thead');
var $tbody = $table.find('tbody');

var code = 'MAKRO107'; // fix for test

//set dataTabel
var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
    new PNotify({text: message, type: 'error' });
}).DataTable({
    scrollY: true,
    scrollX: '300px',
    fixedColumns:   {
        leftColumns: 2,
        rightColumns: 1
    },
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
        url: $("meta[name='root-url']").attr('content') + '/coupon/'+code+'/usage/data',
        type: 'POST',
        data: function (d) {
            d.search = $('#search-form').serializeArray();
        },
        error: function(xhr, error, thrown) {
            new PNotify({text: error, type: 'error' });
            $tbody.children().remove();
            $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
        }
    },
    columns: [
        { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
        { data: 'used_date', name: 'used_date' },
        { data: 'order_number', name: 'order_number'  },
        { data: 'order_amount', name: 'order_amount', className: 'text-right' },
        { data: 'makro_id', name: 'makro_id' },
        { data: 'customer_name', name: 'customer_name' },
        { data: 'customer_type', name: 'customer_type' },
        { data: 'mobile_number', name: 'mobile_number' },
        { data: 'email', name: 'email' },
        { data: 'status', name: 'status' }
    ]
});


$('body').on('click', '.btn-delete-coupon', function() {
    event.preventDefault();
    var self = $(this);
    var url = self.attr('href');
    var id = self.data('id');

    if (confirm('Confirm to remove?')) {
        event.preventDefault();
        alert('in progress...');
        // $.ajax({
        //     type: 'DELETE',
        //     url: url,
        //     data: {'coupon_ids':[id]},
        //     success: function(data){
        //         console.log('data->', data);
        //         oTable.draw();
        //     },
        //     complete: function (data) {
        //
        //     }
        // });
    }
});

$('.btn-delete-coupons').on('click', function() {
    event.preventDefault();
    $('.check-all').prop('checked', false); // reset

    var url = $("meta[name='root-url']").attr('content') + '/coupon/usage/delete';
    //var ids = getCheckedId();

    if (confirm('Confirm to remove?')) {
        event.preventDefault();

        alert('in progress...');

        // $.ajax({
        //     type: 'DELETE',
        //     url: url,
        //     data: $('.ids').serialize(),
        //     success: function(data){
        //         console.log('data->', data);
        //         oTable.draw();
        //     },
        //     complete: function (data) {
        //
        //     }
        // });
    }
});

$('.print-report').on('click', function(event) {
    event.preventDefault();
    var data   = oTable.ajax.params();
    var search = {
        start: data.start,
        length: data.length,
        search: data.search,
        order: data.order,
        report: 'print'
    };
    window.location.replace($("meta[name='root-url']").attr('content') + '/coupon/report?' + $.param(search));
});

    $(".select-dropdown").select2({
        minimumResultsForSearch: -1
    });

$('body').on('click', '.check-all', function() {
    $('table input:checkbox').not(this).prop('checked', this.checked);
});

$('body').on('click', '.check', function() {
    $('table .check-all').prop('checked', false);
});

$('#search-form').on('submit', function(e) {
    oTable.draw();
    e.preventDefault();
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

function convertDate(date) {
    var splitTime = date.split(' ');
    var splitDate = splitTime[0].split('-');
    return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0] + ' ' + splitTime[1];
}

function getCheckedId() {
    var ids = $('.ids:checked').serializeArray();
    return ids.map(function (elem) {
        return elem.value;
    }).join();
}