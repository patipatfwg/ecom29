"use strict";

$.fn.dataTable.ext.errMode = 'none';

//set tabel
var $table = $('#order-detail-table');
var $thead = $table.find('thead');
var $tbody = $table.find('tbody');

//set dataTabel
var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
    new PNotify({text: 'Error connection', type: 'error' });
}).DataTable({
    scrollY: true,
    scrollX: '300px',
    fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
    },
    processing: false,
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
        url: $("meta[name='root-url']").attr('content') + '/member/data',
        type: 'POST',
        data: function (d) {
            d.search = $('#search-form').serializeArray();
        },
        error: function(xhr, error, thrown) {
            new PNotify({text: 'Error connection', type: 'error' });
            $tbody.children().remove();
            $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
        }
    },
    columns: [
        { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
        { data: 'email', name: 'email' },
        { data: 'date', name: 'date' },
        { data: 'first_name', name: 'first_name' },
        { data: 'last_name', name: 'last_name' },
        // { data: 'mobile', name: 'mobile' },
        // { data: 'makro_member_card', name: 'makro_member_card' },
        // { data: 'shop_name', name: 'shop_name' },
        // { data: 'tax_id', name: 'tax_id' },
        // { data: 'makro_register_store_id', name: 'makro_register_store_id' },
        // { data: 'last_login_date', name: 'last_login_date' },
        // { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ]
});

$('div.datatable-header').append(`
<div class="btn-group">
    <a href="#" target="_blank" class="print-report btn btn-priority btn-width-100 bg-primary btn-raised legitRipple"><i class="icon-printer2"></i> Excel</a>
</div>`);

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
    window.location.replace($("meta[name='root-url']").attr('content') + '/member/report?' + $.param(search));
});

$('#search-form').on('submit', function(e) {
    oTable.draw();
    e.preventDefault();
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

