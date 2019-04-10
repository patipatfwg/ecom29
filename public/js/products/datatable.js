"use strict";

$.fn.dataTable.ext.errMode = 'none';

//set tabel id
var tableId = $('#products-table');

//set dataTabel
var oTable = tableId.on('error.dt', function (e, settings, techNote, message) {
    swal('Error!', 'Error connection', 'error');
}).DataTable({
    scrollY: true,
    scrollX: '300px',
    fixedColumns: {
        leftColumns: 3,
        rightColumns: 1
    },
    processing: false,
    serverSide: true,
    searching: false,
    retrieve: true,
    destroy: true,
    order: [[0, false]],
    cache: true,
    dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
    language: {
        lengthMenu: '<span>Show :</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
    },
    ajax: {
        url: $("meta[name='root-url']").attr('content') + '/product/data',
        type: 'POST',
        data: function (d) {
            d.full_text = $('#full_text').val();
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
        },
        error: function (xhr, error, thrown) {
            swal('Error!', 'Error connection', 'error');
            tableId.find('tbody').find('td').html('No Data, please try again later');
        }
    },
    columns: [
        {
            data: 'checkbox',
            name: 'checkbox',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
                return '<input class="ids check" type="checkbox" name="product_ids[]" value="' + data + '" class="check">';
            }
        },
        {
            data: 'number',
            name: 'number',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'item',
            name: 'item',
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'online',
            name: 'online',
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'name',
            name: 'name',
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'buyer',
            name: 'buyer',
            className: 'text-center'
        },
        {
            data: 'image',
            name: 'image',
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'detail',
            name: 'detail',
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'price',
            name: 'price',
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'category',
            name: 'category',
            className: 'text-center',
            render: function (data, type, row) {
                return data;
            }
        },
        {
            data: 'approve',
            name: 'approve',
            className: 'text-center',
        },
        {
            data: 'hide',
            name: 'hide',
            className: 'text-center',
            render: function (data, type, row) {
                if (data == 'active') {
                    return '<i class="icon-eye text-teal"></i>';
                }
                else {
                    return '<i class="icon-eye-blocked text-grey-300"></i>';
                }
            }
        },
        {
            data: 'priority',
            name: 'priority',
            className: 'text-center',
            render: function (data, type, row) {
                return '<input maxlength="3" class="form-control input-width-priority-80 text-center priority_number" type="text" name="priority[' + row.id + ']" value="' + data + '">';
            }
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: function (data, type, row) {
                return '<a href="product/' + data + '/edit"><i class="icon-pencil"></i></a>';
            }
        }
    ]
});

$('#search-form').on('submit', function (e) {
    oTable.draw();
    e.preventDefault();
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

$('div.datatable-header').append(`
<div class="btn-group">
    <button type="button" class="btn btn-width-100 bg-brown-400 btn-raised dropdown-toggle" data-toggle="dropdown">Status <span class="caret"></span></button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li><a class="status-checkbox" data-group="approve" data-id="3" href="#"><i class="icon-minus3"></i> Approve</a></li>
        <li><a class="status-checkbox" data-group="approve" data-id="2" href="#"><i class="icon-minus3"></i> Waiting action</a></li>
        <li><a class="status-checkbox" data-group="approve" data-id="1" href="#"><i class="icon-minus3"></i> New Sync</a></li>
        <li class="divider"></li>
        <li><a class="status-checkbox" data-group="show_hide_delete" data-id="1" href="#"><i class="icon-eye-blocked"></i> Hide</a></li>
        <li><a class="status-checkbox" data-group="show_hide_delete" data-id="2" href="#"><i class="icon-eye"></i> Show</a></li>
    </ul>
</div>
<div class="btn-group">
    <button type="button" data-group="show_hide_delete" data-id="3" class="btn btn-width-100 btn-danger btn-raised legitRipple status-checkbox"><i class="icon-bin"></i> Delete</button>
</div>
<div class="btn-group">
    <button type="button" class="btn btn-priority btn-width-100 btn-primary btn-raised legitRipple"><i class="icon-checkmark4"></i> Save</button>
</div>
<div class="btn-group">
    <button type="button" class="btn btn-sync btn-width-100 btn-success btn-raised legitRipple"><i class="icon-sync"></i> Sync</button>
</div>
`);

$.datetimepicker.setLocale('th');
$('#start_date').datetimepicker({
    format: 'd-m-Y H:i:s',
    onShow: function (ct) {
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
    onShow: function (ct) {
        var checkDate = false;
        if ($('#start_date').val() != '') {
            checkDate = convertDate($('#start_date').val());
        }
        this.setOptions({
            minDate: checkDate
        })
    }
});

$('body').on('click', '.check-all', function () {
    $('table.DTFC_Cloned input:checkbox').not(this).prop('checked', this.checked);
});

$('body').on('click', '.check', function () {
    $('table.DTFC_Cloned .check-all').prop('checked', false);
});

//status approve, buyer, show && hide && delete
$('body').on('click', '.status-checkbox', function (event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: $("meta[name='root-url']").attr('content') + '/product/status',
        data: $('.ids:checked').serialize() + '&group=' + $(this).attr('data-group') + '&id=' + $(this).attr('data-id'),
        success: function (data) {
            console.log(data);
            oTable.draw('page');
        },
        complete: function (data) {
            $('.check-all').prop('checked', false);
        }
    });
});

//priority
$('body').on('click', '.btn-priority', function (event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: $("meta[name='root-url']").attr('content') + '/product/priority',
        data: $('.priority_number').serialize(),
        success: function (data) {
            console.log(data);
            oTable.draw('page');
        },
        complete: function (data) {
            //$('.check-all').prop('checked', false);
        }
    });
});

//sync
$('body').on('click', '.btn-sync', function (event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: $("meta[name='root-url']").attr('content') + '/product/sync',
        success: function (data) {
            console.log(data);
        }
    });
});

function convertDate(date) {
    var splitTime = date.split(' ');
    var splitDate = splitTime[0].split('-');
    return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0] + ' ' + splitTime[1];
}