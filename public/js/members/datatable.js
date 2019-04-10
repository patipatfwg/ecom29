"use strict";

$.fn.dataTable.ext.errMode = 'none';

//set tabel
var $table = $('#members-table');
var $thead = $table.find('thead');
var $tbody = $table.find('tbody');

//set dataTabel
var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
    new PNotify({text: 'Error connection', type: 'error' });
}).DataTable({
    scrollY: true,
    scrollX: '300px',
    fixedColumns:   {
        leftColumns: 2,
        rightColumns: 1
    },
    lengthMenu: [ 10, 50, 100, 500, 1000 ],
    processing: false,
    serverSide: true,
    searching: false,
    retrieve : true,
    destroy : true,
    order: [[ 1, "asc" ]],
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
    fnServerParams: function(data) {
        data['order'].forEach(function(items, index) {
            data['order'][index]['column'] = data['columns'][items.column]['name'];
        });
    },
    columns: [
        { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
        { data: 'username', name: 'username' },
        { data: 'email', name: 'email' },
        { data: 'created_at', name: 'created_at' },
        { data: 'first_name', name: 'first_name' },
        { data: 'last_name', name: 'last_name' },
        { data: 'phone', name: 'phone' },
        { data: 'makro_member_card', name: 'makro_member_card' },
        { data: 'shop_name', name: 'business.shop_name' },
        { data: 'shop_type', name: 'business.shop_type' },
        { data: 'tax_id', name: 'tax_id' },
        { data: 'makro_register_date', name: 'makro_register_date' },
        { data: 'makro_register_store_id', name: 'makro_register_store_id' , orderable: false, searchable: false, className: 'text-center' },
        { data: 'last_login_date', name: 'last_login_date' },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ]
});

$('div.datatable-header').append(`
<div class="btn-group">
    <a href="#" target="_blank" class="print-report btn btn-priority bg-violet-300 btn-width-100 bg-primary btn-raised legitRipple"><i class="icon-printer2"></i> Excel</a>
</div>`);

$('.print-report').on('click', function(event) {
    event.preventDefault();
    var data   = oTable.ajax.params();
    var search = {
        start: data.start,
        length: data.length,
        search: data.search,
        order: data.order
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

$.datetimepicker.setLocale('th');
$('#start_date').datetimepicker({
    format: 'd-m-Y H:i:s',
    onShow: function(ct) {
        var checkDate = false;
        if ($('#end_date').val() != '') {
            checkDate = convertDate($('#end_date').val());
        }
        this.setOptions({
            maxDate: checkDate
        })
    }
});

$('#end_date').datetimepicker({
    format: 'd-m-Y H:i:s',
    onShow: function(ct) {
        var checkDate = false;
        if ($('#start_date').val() != '') {
            checkDate = convertDate($('#start_date').val());
        }
        this.setOptions({
            minDate: checkDate
        })
    }
});

$('#start_last_login_date').datetimepicker({
    format: 'd-m-Y H:i:s',
    onShow: function(ct) {
        var checkDate = false;
        if ($('#end_last_login_date').val() != '') {
            checkDate = convertDate($('#end_last_login_date').val());
        }
        this.setOptions({
            maxDate: checkDate
        })
    }
});

$('#end_last_login_date').datetimepicker({
    format: 'd-m-Y H:i:s',
    onShow: function(ct) {
        var checkDate = false;
        if ($('#start_last_login_date').val() != '') {
            checkDate = convertDate($('#start_last_login_date').val());
        }
        this.setOptions({
            minDate: checkDate
        })
    }
});


function convertDate(date) {
    var splitTime = date.split(' ');
    var splitDate = splitTime[0].split('-');
    return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0] + ' ' + splitTime[1];
}