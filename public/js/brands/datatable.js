"use strict";

$.fn.dataTable.ext.errMode = 'none';

//set tabel
var $table = $('#brands-table');
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
        url: $("meta[name='root-url']").attr('content') + '/brand/getAjaxBrand',
        type: 'POST',
        data: function (d) {
            d.parent_id = null;
            d.full_text  = $('#full_text').val();
        },
        error: function(xhr, error, thrown) {
            new PNotify({text: 'Error connection', type: 'error' });
            $tbody.children().remove();
            $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
        }
    },
    columns: [
        { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false, className: 'text-center' },
        { data: 'content', name: 'content' },
        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
    ],
    drawCallback : function(settings) {
        $('[data-delete]').on('click',function(){
            event.preventDefault();
            var id = $(this).attr('data-delete');
            swal({
                title: 'Are you sure?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            },
            function(isConfirm){
                if (isConfirm) {
                    deleteBrand(id);
                }
            });
        });
    }
});

$('div.datatable-header').append(`
<div class="btn-group">
    <a href="${$("meta[name='root-url']").attr('content')}/brand/create" class="btn bg-teal-400 btn-raised legitRipple"><i class="icon-plus-circle2 position-left"></i> ADD DATA</a>
</div>`);

$('#search-form').on('submit', function(e) {
    oTable.draw();
    e.preventDefault();
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

function deleteBrand(_id) {
    $.ajax({
        type: 'DELETE',
        url: $("meta[name='root-url']").attr('content') + '/brand/' + _id,
        dataType: 'json',
        success:function(data){
            if (data.success) {
                swal('Deleted!', data.messages, 'success');
                oTable.draw('page');
            } else {
                swal('Deleted!', data.messages, 'warning');
            }
        },
        error: function(){
            swal('Deleted!', 'Error connection', 'error');
        }
    });
}