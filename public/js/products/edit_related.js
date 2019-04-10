"use strict";

var productId = $('#productId').val();
var language = $('#language').val(); 

var oTableRelated = $('#related-table').DataTable({ 
    processing: true,
    serverSide: true,
    searching: false,
    retrieve : true,
    ordering: false,
    cache: true,
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    
    ajax: {
        url: $("meta[name='root-url']").attr('content') + '/product/'+ productId +'/relate',
        type: 'GET',
        data: function (data) {
            data.language  = $('#language').val();
        },
        error: function(xhr, error, thrown) {
            swal('Error!', 'Product related error connection', 'error');
            oTableRelated.find('tbody').find('td').html('No Data, please try again later');
        }
    }, 
    columns: [ 
        { data: 'no', name: 'no' },
        { data: 'product_code', name: 'product_code' },
        { data: 'online_sku', name: 'online_sku' },
        { data: 'product_name', name: 'product_name' },
        { data: 'action', name: 'action' }  
    ]
});

function reloadTableRelated(){
    oTableRelated.draw();
}

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

function relatedProductDel(relatedId,productId){
    var url = $("meta[name='root-url']").attr('content')+'/product/'+productId+'/relate/'+relatedId+'/delete';
    $.getJSON(url, function( data ) {
        new PNotify({text: 'success', type: 'success' });
        reloadTableRelated();
    });
}

$(document).ready(function(){
    $('#btnProductSearch').click( function(event){
        var name = $('#txtProductSearch').val();
        event.preventDefault();                
        if(name.length > 2){     
            searchProduct(name);
        } else {
            $('#error-type').html("<div style='color: red;'>Must type at least 3 letters.</div>");
        }
    });
});

var oTableAddRelated;
function searchProduct(name){
    oTableAddRelated = $('#add-related-table').DataTable({ 
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        ordering: false,
        cache: true,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        
        ajax: {
            url: $("meta[name='root-url']").attr('content')+'/product/'+productId+'/relate/search',
            type: 'GET',
            data: function (data) {
                data.name  = name;
            },  
            error: function(xhr, error, thrown) {
                swal('Error!', 'Error connection', 'error');
                oTableAddRelated.find('tbody').find('td').html('No Data, please try again later');
            }
        }, 
        columns: [ 
            { data: 'chk', name: 'chk' },
            { data: 'product_code', name: 'product_code' },
            { data: 'product_name', name: 'product_name' }, 
        ]
    });
}
 
function reloadTableAddRelated(){
    oTableAddRelated.clear().draw();
}
function hideModalProductRelated(){
    $('#modal_form_vertical2').modal('hide');
}

$( "#btnProductRelatedSave" ).click( function (event) {
    event.preventDefault();// using this page stop being refreshing

    var matches = [];
    $(".chkProductId:checked").each(function() {
        matches.push(this.value);
    });

    if(matches.length>0){
        var postData = { "related_ids" : matches };
        $.ajax({
            type: "POST",
            url: $("meta[name='root-url']").attr('content')+'/product/'+productId+'/relate/create',
            data: postData,
            dataType: 'JSON',
            success: function(data) {
                new PNotify({text: 'success', type: 'success' });
                reloadTableRelated();
                searchProduct(null);
                reloadTableAddRelated();
                hideModalProductRelated();
            }
        });
    } else {
        $('#error-type').html("<div style='color: red;'>Please select at least 1 product.</div>");
    }
});

$("#btnProductRelatedClose").click( function () {
    reloadTableAddRelated();
});

