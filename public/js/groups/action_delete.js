"use strict";

function deleteData(id) {
    $('#deleteModal').modal('show');
    $('#deleteModal').find("#okay").click(function() {

        if (id != 0) {

            $.ajax({
                type: 'DELETE',
                url: $("meta[name='root-url']").attr("content") + "/groups/delete",
                data: { _token: $("meta[name='csrf-token']").attr("content"), 'id': id },
                async: false,
                dataType: "json",
                success: function(result) {
                    $('.modal-footer').html('<button type="button" data-dismiss="modal" class="btn green" id="okay">OK</button>');

                    if (result.status == 290) {
                        $('.modal-body').html('<span style="color:#ff0000;">' + result.message + '</span>');
                    } else {
                        $('.modal-body').html("Delete Complete!");
                        $('#sample_2').find('#list-' + id).remove();
                        $('#deleteModal').modal('hide');
                        location.reload();
                    }
                },
                error: function() {

                }
            });
        }
    });

    $('#deleteModal').on('hidden.bs.modal', function() {
        $('.modal-body').html('Would you like to delete?');
        $('.modal-footer').html('<button data-dismiss="modal" class="btn btn-default" type="button">Close</button><button class="btn btn-primary" type="button" id="okay">OK</button>');
    });
}