var url = '/delivery_area';
var $table = $('#delivery-area-table');
var $thead = $table.find('thead');
var $tbody = $table.find('tbody');
var leaveText = $('#cancel_data_change').val();
var validateText = $('#update_validate_delivery_area').val();

var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
    new PNotify({text: 'Error connection', type: 'error' });
}).DataTable({
    scrollY: true,
    scrollX: true,
    processing: true,
    serverSide: true,
    searching: false,
    retrieve : true,
    destroy : true,
    order: [[ 2, "asc" ]],
    cache: true,
    dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
    language: {
        lengthMenu: '<span>Show :</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
    },
    ajax: {
        url: $("meta[name='root-url']").attr('content') + '/delivery_area/data',
        type: 'POST',
        data: function (d) {
            d.search = $('#search-form').serializeArray();
        },
        error: function(xhr, error, thrown) {
            if(xhr.responseJSON.expired) {
                swal({
                title: "Error!",
                text: 'Session Expired',
                type: "error",
                confirmButtonText: "OK"
                },
                function(){
                    location.reload();
                });
            } else {
                new PNotify({text: 'Error connection', type: 'error' });
                $tbody.children().remove();
                $tbody.append('<tr><td class="text-center" colspan="' + $thead.find('tr th').length + '">No Data, please try again later</td></tr>');
            }
        }
    },
    preDrawCallback: function( settings ) {
        if($("#search-form #postcode-error").text() != ''){
            $("#search-form #postcode").focus();
            return false;
        }
    },
    drawCallback: function( settings ){
        doCancel();
    } ,
    fnServerParams: function(data) {
        data['order'].forEach(function(items, index) {
            data['order'][index]['column'] = data['columns'][items.column]['name'];
        });
    },
    columns: [
        { data: 'postcode', name: 'postcode'},
        { data: 'province', name: 'province' },
        { data: 'district', name: 'district' },
        { data: 'subdistrict', name: 'subdistrict' },
        { data: 'status', name: 'status' },
        { data: 'makro_inventory_store', name: 'makro_inventory_store' },
        { data: 'price_store', name: 'price_store' },
        { data: 'price_store_professional', name: 'price_store_professional' },
    ]
});

$('#btn-search').click(function(){
    if(isEdit()){
        doWarning(oTable);
    } else {
        oTable.draw();
    }
})

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

$('#select-province').select2({
    placeholder: 'All',
    allowClear: true
});

$('#select-district, #select-sub-district').select2({
    placeholder: 'Please select province',
    allowClear: true
});

$('#status').select2({
    minimumResultsForSearch: -1
});

$('div#delivery-area-table_length span.select2').attr('style', 'width: 74px !important; display: table-caption');

$('div.datatable-header').append(`
    <div class="btn-group">
        <button id="btn-edit" type="button" class="btn btn-width-100 btn-default btn-raised legitRipple">
            Bulk edit
        </button>&nbsp;
    </div>
    <div class="btn-group">
        <button id="btn-save" type="button" class="btn btn-width-100 btn-default btn-raised legitRipple hide">
            Save changes
        </button>&nbsp;
    </div>
    <div class="btn-group">
        <button id="btn-cancel" type="button" class="btn btn-width-100 btn-default btn-raised legitRipple hide">
            Cancel changes
        </button>&nbsp;
    </div>
`);

$("document").ready(function(){

    Object.size = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    $("#btn-edit").click(function(){
        $(".edit_select").not(".status").select2({
            minimumResultsForSearch: 0
        });

        $(".edit_select.status").select2({
            minimumResultsForSearch: -1
        });

        $("#btn-edit, .edit_text").addClass('hide');
        $("#btn-save, #btn-cancel, .edit_select").removeClass('hide');
    });

    $("#btn-save").click(function(){
        if(validateData()){
            var updateData = getSaveData();
            if(Object.size(updateData)){
                $.ajax({
                    type: 'PUT',
                    url: url + '/saveData',
                    data: { data :  updateData},
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            swal({
                                title: "Save",
                                text: "Save Success.",
                                type: "success",
                                confirmButtonText: "OK"
                            })
                            hideBtn();
                            oTable.draw();
                        } else {
                            swal('Error!', 'Save Fail.', 'error');
                        }
                    },
                    error: function(data) {
                        if(typeof data.responseJSON != 'undefined' && typeof data.responseJSON.permission != 'undefined')
                            swal('Save Fail.', data.responseJSON.permission, 'warning');
                    }
                });
            } else {
                hideBtn();
            }
        }
    });

    $("#btn-cancel").click(function(){
        $(".dataTable td").css('height', 'auto');
        if(isEdit()){
            doWarning();
        } else {
            doCancel();
        }
    });

    $("#select-province").change(function(){
        var province_id =  $(this).val();
        if(province_id != ''){
            $.get('delivery_area/district/' + province_id).done(function(response){
                var data = JSON.parse(response);
                var dataSize = Object.size(data);
                if(dataSize > 0){
                    $("#select-district").html(`<option value="">All</option>`);
                    for(district_name in data){
                        $("#select-district").append(`<option value="` + data[district_name] + `">` + district_name + `</option>`);
                    }
                } else {
                    setProvinceOpt();
                    setDistrictOpt();
                }
            });

            $.get('delivery_area/sub_district_all/' + province_id).done(function(response){
                var data = JSON.parse(response);
                var dataSize = Object.size(data);
                
                if(dataSize > 0){
                    $("#select-sub-district").html(`<option value="">All</option>`);
                    for(sub_district_name in data){
                        $("#select-sub-district").append(`<option value="` + data[sub_district_name] + `">` + sub_district_name + `</option>`);
                    }
                } else {
                    setDistrictOpt();
                }
            });
        } else {
            $("#select-district").html(selectProvince);
            setDistrictOpt();
        }

    });

    $("#select-district").change(function(){
        var district_id = $(this).val();
        if(district_id != ''){
            $.get('delivery_area/sub_district/' + district_id).done(function(response){
                var data = JSON.parse(response);
                var dataSize = Object.size(data);
                
                if(dataSize > 0){
                    $("#select-sub-district").html(`<option value="">All</option>`);
                    for(sub_district_name in data){
                        $("#select-sub-district").append(`<option value="` + data[sub_district_name] + `">` + sub_district_name + `</option>`);
                    }
                } else {
                    setDistrictOpt();
                }

            });
        } else {
            setDistrictOpt();
        }
    });

    function setProvinceOpt()
    {
        $("#select-district").html(`<option value="">Please select province</option>`);
    }

    function setDistrictOpt()
    {
        $("#select-sub-district").html(`<option value="">Please select district</option>`);
    }

    function validateData()
    {
        var validateStatus = true;
        $(".edit_select").each(function(){
            var selectVal = $(this).val();
            var status = $(this).parent().parent().find(".edit_select.status").val();

            if(selectVal == '' && status == 'Y'){
                swal('Save Fail.', validateText, 'error');
                validateStatus = false;
                return validateStatus;
            }
        });
        return validateStatus;
    }

    function getSaveData()
    {
        var data = {};

        $(".edit_select").each(function(){
            var selectVal = $(this).val();
            var originalVal = $(this).parent().find(".edit_text").text();
            var status = $(this).parent().parent().find(".edit_select.status").val();
            
            if(selectVal != '' && selectVal != originalVal){
                data[$(this).attr('id')] = selectVal;
            }
        });
        return data;
    }
});

function doWarning(oTable = false)
{
    swal({
        title: leaveText,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes"
    },
    function(isConfirm){
        if (isConfirm) {
            doCancel();
            if(oTable !== false){
                oTable.draw();
            }
        }
    });
}

function hideBtn()
{
    $("#btn-edit, .edit_text").removeClass('hide');
    $("#btn-save, #btn-cancel, .edit_select, #delivery-area-table .select2").addClass('hide');
}

function doCancel()
{
    hideBtn();
    $(".edit_select").each(function(){
        var originalVal = $(this).parent().find(".edit_text").text();
        $(this).val(originalVal);
    });
}

function isEdit()
{
    var is_edit = false;
    $(".edit_select").each(function(){
        var selectVal = $(this).val();
        var originalVal = $(this).parent().find(".edit_text").text();
        if(selectVal != originalVal){
            is_edit = true;
            return false;
        }
    });
    return is_edit;
}