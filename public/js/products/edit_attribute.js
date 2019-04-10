
var appUrlAttribute = '/attribute';
var AttributeTable;

$(function(){

    $("#btn_attrsave").click(function () {

        $("#modal_form_vertical").modal("hide");

        var productId = $("#productId").val();
        var attrId    = $("#mainAttribute").val();
        var attrName  = $("#mainAttribute option:selected").text();

        $.post(appUrlAttribute + "/postAjaxProductAttribute", 
            {"product_id": productId, "attribute_id": attrId, "attribute": attrName, "attribute_value_id":0, "attribute_value":"", "status":"active"}, 
            function(returnedData){

                var statusCode = returnedData.status.code;
                
                if (statusCode == 200) {
                    var statusMsg = returnedData.status.message;
                    swal('Insert!', statusMsg, 'success');

                    AttributeTable.draw();

                } else {
                    var errorMsg  = returnedData.errors.message;
                    swal('Insert!', errorMsg, 'warning');
                }

        }).fail(function(){
              swal('Connect', 'Connection Error', 'warning');
        });

      
    });

    $.ajax({
        type:'GET',
        url: appUrlAttribute + "/getAjaxAttributeMain",
        context: ''
    }).done(function(data) {
        $("#choose_attr").html(data);
    });
    
    $.fn.dataTable.ext.errMode = 'none';
    AttributeTable = $('#attribute-table').on('error.dt', function(e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, false ]],
        bAutoWidth: '100%',
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            type:'GET',
            url: appUrlAttribute + "/getAjaxProductAttribute",
            data: { product_id: 1 }
        },
        columns: [
            { data: 'attr_id', name: 'attr_id', orderable: false, searchable: false, className: 'text-center' },
            { data: 'attribute',   name: 'attribute' },
            { data: 'attribute_value',   name: 'attribute_value', orderable: false },
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
                        deleteCategory(id);
                    }
                });
                
            });
        }
    });
    
    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
 
});

function deleteCategory(_id) {
    $.ajax({
        type: 'DELETE',
        url: appurl + "/" + _id,
        dataType: 'json',
        success:function(data){
            console.log(data);
            if (data.success) {
                swal('Deleted!', data.messages, 'success');
                oTable.draw();
            } else {
                swal('Deleted!', data.messages, 'warning');
            }
        },
        error: function(){
            swal('Deleted!', 'Error connection', 'error');
        }
    });
}
