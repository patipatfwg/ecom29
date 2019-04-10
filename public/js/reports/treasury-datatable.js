"use strict";

$.fn.dataTable.ext.errMode = 'none';

//set tabel
var $table = $('#treasury-table');
var $thead = $table.find('thead');
var $tbody = $table.find('tbody');

//set dataTabel
var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
    new PNotify({ text: 'Error connection', type: 'error' });
}).DataTable({
    scrollY: true,
    scrollX: '300px',
    fixedColumns:   {
        leftColumns: 2,
        heightMatch: 'none'
    },
    processing: false,
    serverSide: true,
    searching: false,
    retrieve : true,
    destroy : true,
    order: [[ 0, false ]],
    cache: true,
    pageLength: 20,
    lengthMenu: [ 20, 100, 500, 1000 ],
    dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
    language: {
        lengthMenu: '<span>Show :</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
    },
    ajax: {
        url: $("meta[name='root-url']").attr('content') + '/report/treasury/data',
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
    fnServerParams: function(data) {
        data['order'].forEach(function(items, index) {
            data['order'][index]['column'] = data['columns'][items.column]['name'];
        });
    },
    columns: [
        { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
        { data: 'store_id', name: 'store_id', width: '200px' },
        { data: 'store_name_th', name: 'store_name.th' },
        { data: 'number_of_transaction', name: 'number_of_transaction' },
        { data: 'total_amount_vat_items', name: 'total_amount_vat_items' },
        { data: 'total_amount_exc_vat_items', name: 'total_amount_exc_vat_items' },
        { data: 'discount', name: 'discount' },
        { data: 'total_amount', name: 'total_amount' },
        { data: 'total_amount_exc_vat', name: 'total_amount_exc_vat' },
        { data: 'payment_fee', name: 'payment_fee' },
        { data: 'vat_payment_fee', name: 'vat_payment_fee' },
        { data: 'net_amount', name: 'net_amount' },
        { data: 'w_h_tax', name: 'w_h_tax' },
        { data: 'payment_type', name: 'payment_type' }
    ]
});

$('div.datatable-header').append(`
<div class="btn-group">
    <a href="#" target="_blank" class="print-report btn btn-width-100 bg-violet-300 btn-raised legitRipple"><i class="icon-printer2"></i> Excel</a>
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
    window.location.replace($("meta[name='root-url']").attr('content') + '/report/treasury/print?' + $.param(search));
});

$('#search-form').on('submit', function(e) {
    if($('#payment_date_from').val()==''||$('#payment_date_to').val()=='') {
        swal({
            title: "Warning",
            text: "Payment Date must select date from and date to",
            type: "warning",
            confirmButtonText: "OK"
            },
            function (){}
  	    );
    } else {
        oTable.draw();
        
    }
    e.preventDefault();
});

$('.dataTables_length select').select2({
    width: 'auto',
    minimumResultsForSearch: Infinity,
});

$('#store_id').select2({
    placeholder: 'Select Store List ...',
    allowClear: true
});

$('#payment_type').select2({
    placeholder: 'Select Payment Channel ...',
    allowClear: true
});

$.datetimepicker.setLocale('th');