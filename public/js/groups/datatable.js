"use strict";

$.fn.dataTableExt.sErrMode = 'throw';

var delay = (function() {
    var timer = 0;
    return function(callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$("#groupsTable").DataTable({

    // Delay search by keyup
    initComplete: function() {

        var api = this.api();

        $("#groupsTable_filter input")
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
        url: $("meta[name='root-url']").attr('content') + '/groups/data',
        type: "POST",
        data: {
            "_token": $("meta[name='csrf-token']").attr('content')
        },
        async: true,
        beforeSend: function() {
            console.log("ajax beforeSend");
            $("#groupsTable_filter input").prop("readonly", true);
            $("#groupsTable_filter input").prop("disabled", true);
        },
        complete: function(data) {
            console.log("ajax complete");
            $("#groupsTable_filter input").prop("readonly", false);
            $("#groupsTable_filter input").prop("disabled", false);
            $("#groupsTable_processing").css('display', 'none');
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
        { data: 'action', name: 'action', orderable: false, searchable: false, sClass: 'center' }
    ]
});

// Modify select dropdown show display of datatable.
$('#groupsTable_wrapper .dataTables_length select').select2();