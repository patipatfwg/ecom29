"use strict";

$.fn.dataTableExt.sErrMode = 'throw';

var delay = (function() {
    var timer = 0;
    return function(callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$("#modeulsTable").DataTable({

    // Delay search by keyup
    initComplete: function() {

        var api = this.api();

        $("#modeulsTable_filter input")
            .off(".DT")
            .on("keyup.DT", function(e) {
                $value = $(this).val();
                delay(function() {
                    // api.search($value).draw();
                }, 2000);
            });

    },
    serverSide: true,
    processing: true,
    searching: true,
    iDisplayLength: 10,
    deferRender: true,
    bFilter: false,
    bAutoWidth: false,
    responsive: true,
    cache: true,
    ajax: {
        url: $("meta[name='root-url']").attr('content') + '/modules/data',
        type: "POST",
        data: {
            "_token": $("meta[name='csrf-token']").attr('content')
        },
        async: true,
        beforeSend: function() {
            console.log("ajax beforeSend");
            $("#modeulsTable_filter input").prop("readonly", true);
            $("#modeulsTable_filter input").prop("disabled", true);
        },
        complete: function(data) {
            console.log("ajax complete");
            $("#modeulsTable_filter input").prop("readonly", false);
            $("#modeulsTable_filter input").prop("disabled", false);
            $("#modeulsTable_processing").css('display', 'none');
        },
        error: function(xhr, error, thrown) {
            console.log("ajax error");
            if (error === "parsererror") {
                alert("No Data, please try again later");
            }
        }
    },
    columns: [
        { data: 'row_no', name: 'row_no', orderable: true, searchable: false, sClass: 'center' },
        { data: 'name', name: 'name', orderable: true, searchable: true, sClass: 'left' },
        { data: 'description', name: 'desc', orderable: true, searchable: false, sClass: 'left' },
        { data: 'menu_id', name: 'desc', orderable: true, searchable: false, sClass: 'left' },
        { data: 'create', name: 'create', orderable: true, searchable: false, sClass: 'center' },
        { data: 'read', name: 'read', orderable: true, searchable: false, sClass: 'center' },
        { data: 'update', name: 'update', orderable: true, searchable: false, sClass: 'center' },
        { data: 'delete', name: 'delete', orderable: true, searchable: false, sClass: 'center' },
        { data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'center' }
    ]
});

// Modify select dropdown show display of datatable.
$('#modeulsTable_wrapper .dataTables_length select').select2();