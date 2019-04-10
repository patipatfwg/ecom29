"use strict";

var productId = $('#productId').val();
var language = $('#language').val(); 

var oTableCategory = $('#categories-table').DataTable({ 
    processing: true,
    serverSide: true,
    searching: false,
    retrieve : true,
    ordering: false,
    cache: true,
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    
    ajax: {
        url: $("meta[name='root-url']").attr('content') + '/product/'+ productId +'/category',
        type: 'GET',
        data: function (data) {
            data.language  = language;
        },
        error: function(xhr, error, thrown) {
            swal('Error!', 'category error connection', 'error');
            oTableCategory.find('tbody').find('td').html('No Data, please try again later');
        }
    }, 
    columns: [ 
        { data: 'no', name: 'no' },
        { data: 'category_id', name: 'category_id' },
        { data: 'online_sku', name: 'online_sku' },
        { data: 'category_name', name: 'category_name' },
        { data: 'action', name: 'action' }  
    ] 
    // ],
    // columnDefs : [ {
    //     "targets": 4,                   // what column ?
    //     "data": 'category_id',
    //     "render" : function ( data ) {
    //        // return '<a href="'+data+'">Download</a>';
    //     },
    //     "defaultContent": "<a class='btn btn-default'><i class='icon-bin'></i></a>"
    // }]

});

// select list
reloadSelectCategoryList();
hideCategoryList();

function hideCategoryList(){
    $('#category2').hide();
    $('#category3').hide();
    $('#secondCategory').empty();
    $('#thirdCategory').empty();
}

function reloadSelectCategoryList(){
 
    $.get($("meta[name='root-url']").attr('content') + '/product/category/list?language='+ language, function(json){
        $('#mainCategory').empty();
        $('#mainCategory').append($('<option>').text(" -- Select main category -- "));
        $.each(json, function(i, obj){
            $('#mainCategory').append($('<option>').text(obj.name_th).attr('value', obj.id).attr('parent_id', obj.parent_id));
        });
    }); 

}

$('#mainCategory').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var category_id = this.value;
    $('#category_id').val(category_id);
    $('#category2').hide();
    $.get($("meta[name='root-url']").attr('content') + '/product/category/' + category_id + '/parent?level=2&language='+ language , function(json){
        $('#secondCategory').empty();
        $('#secondCategory').append($('<option>').text(" -- Select sub category 2 -- "));
        if(json.length > 0){
            $('#category2').show();
        }
        $.each(json, function(i, obj){
            $('#secondCategory').append($('<option>').text(obj.name_th).attr('value', obj.id).attr('parent_id', obj.parent_id));
        });
    }); 
});

$('#secondCategory').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var category_id = this.value;
    $('#category_id').val(category_id);
    $('#category3').hide();
    $.get($("meta[name='root-url']").attr('content') + '/product/category/' + category_id + '/parent?level=2&language='+ language , function(json){
        $('#thirdCategory').empty();
        $('#thirdCategory').append($('<option>').text(" -- Select sub category 3 -- "));
        if(json.length > 0){
            $('#category3').show();
        }  
        $.each(json, function(i, obj){
            $('#thirdCategory').append($('<option>').text(obj.name_th).attr('value', obj.id).attr('parent_id', obj.parent_id));
        });
    }); 
});

$('#thirdCategory').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var category_id = this.value;
    $('#category_id').val(category_id);
});
// end select list

function reloadTableCategory(){
    oTableCategory.draw();
}

function categoryProductDel(categoryId,productId){

    var url = $("meta[name='root-url']").attr('content')+'/product/'+productId+'/category/'+categoryId+'/delete';
    $.getJSON(url, function( data ) {
        new PNotify({text: 'success', type: 'success' });
        alert('success.');
        hideCategoryList();
        reloadTableCategory();
    });

}

function hideModalCategory(){
    $('#modal_form_vertical').modal('hide');
}

$( "#btnCategorySave" ).click( function (event) {
    event.preventDefault();             // using this page stop being refreshing 
    var category_id = $("#category_id").val();
    var dataString = 'category_id='+ category_id;
    if(category_id){
        $.ajax({
            type: "POST",
            url: $("meta[name='root-url']").attr('content')+'/product/'+productId+'/category/create',
            data: dataString,
            dataType: 'JSON',
            success: function(data) {
                new PNotify({text: 'success', type: 'success' });
                hideCategoryList();
                reloadTableCategory();
                reloadSelectCategoryList();
                hideModalCategory();
            }
        });
    } else {
        $('#error').html("<div style='color: red;'>Please select category.</div>");
    }
});

$( "#btnCategoryClose" ).click( function () {
    hideCategoryList();
    hideModalCategory();

});
 