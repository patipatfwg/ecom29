<script type="text/javascript">
$('#search-form').on('submit', function(e) {
    /*if($('#payment_date_from').val() == '' || $('#payment_date_to').val() == '') {
        swal({
            title: "Warning",
            text: "Payment Date must select date from and date to",
            type: "warning",
            confirmButtonText: "OK"
        });
    } else {
        oTable.draw();
    }*/

    if(
        ($('#create_date_from').val() != '' && $('#create_date_to').val() == '')
        || ($('#create_date_from').val() == '' && $('#create_date_to').val() != '')
    ) {

        swal({
            title: 'Warning',
            text: 'create date must select date from and date to',
            type: 'warning',
            confirmButtonText: 'OK'
        });

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

$('.store_id').select2({
    placeholder: 'Select Store List ...',
    allowClear: false
});

$('#select-status').select2({
    placeholder: 'Select Status List ...',
    allowClear: true
});

$('#payment_type').select2({
    placeholder: 'Select Payment Channel ...',
    allowClear: true
});

$('#select-status').on('select2:select', function (evt) {
    var region = $("#select-status option:selected").val();
    $("#status").val(region);
});
</script>