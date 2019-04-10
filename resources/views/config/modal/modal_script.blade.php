 <script type="text/javascript">

"use strict";
    
    $.fn.dataTable.ext.errMode = 'none';

    var set_check = [];
    var type_ = ['product', 'Category'];
    var id = "{{ $install['id']}}";
    //set table id
    var tableId = $('#products-table');
    var first_access = 0;
    //set dataTable
    var oTable = tableId.on('error.dt', function (e, settings, techNote, message) {
        swal('Error!', 'Error connection', 'error');
    }).DataTable({
        scrollY: true,
        scrollX: '300px',
        fixedColumns: {
            leftColumns: 4,
            rightColumns: 1,
            //heightMatch: 'none'
        },
        processing: false,
        serverSide: true,
        searching: false,
        retrieve: true,
        autoWidth: false,
        destroy: true,
        order: [[5, "desc"]],
        cache: true,
        dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/config/payment_method/' + id + '/productdata',
            type: 'POST',
            data: function (d) {
                d.name = $('#input_product_name').val();
                d.tapSearch = $('input[name=type_search_modal]:checked').val();
                d.category = {
                    product:  $('input[group-name=product]').val(),
                    business: $('input[group-name=business]').val()
                };

                if(first_access == 0){
                    d.makro_store_id = 'not search';
                }

                // d.product = $('input[group-name=product]').val();
                // d.business = $('input[group-name=business]').val();
                
            },
            error: function (xhr, error, thrown) {
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
                    swal('Error!', 'Error connection', 'error');
                    tableId.find('tbody').find('td').html('No Data, please try again later');
            
                }
            }
        },
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['data'];
            });
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: "50px",
                    render: function (data, type, row) {
                    return '<input class="ids check" type="checkbox" name="product_ids[]" value="' + row['id'] + '" class="check">';
                }
            },
            {
                data: 'item_id',
                name: 'item_id',
                className: 'text-center',
                //width: "200px",
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'published_status',
                name: 'published_status',
                className: 'text-center',
                //width: "200px",
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="icon-eye text-teal"></i>';
                    }
                    else {
                        return '<i class="icon-eye-blocked text-grey"></i>';
                    }
                }
            },
            {
                data: 'approve_status',
                name: 'approve_status',
                className: 'text-center',
                //width: "200px"
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                className: 'text-center',
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'name_th',
                name: 'name_th',
                className: 'text-center',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'name_en',
                name: 'name_en',
                className: 'text-center',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'supplier_id',
                name: 'supplier_id',
                className: 'text-center'
            },
            {
                data: 'supplier_name',
                name: 'supplier_name',
                className: 'text-center',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'buyer_id',
                name: 'buyer_id',
                className: 'text-center'
            },
            {
                data: 'buyer_name',
                name: 'buyer_name',
                className: 'text-center',
                render: function (data, type, row) {
                    return unescape(data);
                }
            },
            {
                data: 'have_image',
                name: 'have_image',
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="glyphicon glyphicon-ok text-success"></i>';
                    }
                    else {
                        return '<i class="glyphicon glyphicon-remove text-danger"></i>';
                    }
                }
            },
            {
                data: 'have_detail',
                name: 'have_detail',
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="glyphicon glyphicon-ok text-success"></i>';
                    }
                    else {
                        return '<i class="glyphicon glyphicon-remove text-danger"></i>';
                    }
                }
            },
            {
                data: 'normal_price',
                name: 'normal_price',
                className: 'text-center',
                render: function (data, type, row) {
                    return data;
                }
            },
            {
                data: 'have_categories',
                name: 'have_categories',
                className: 'text-center',
                render: function (data, type, row) {
                    if (data == 'Y') {
                        return '<i class="glyphicon glyphicon-ok text-success"></i>';
                    }
                    else {
                        return '<i class="glyphicon glyphicon-remove text-danger"></i>';
                    }
                }
            }
        
        ],
        drawCallback : function(settings) {
            $(".priority-select").select2({
                data: [
                    { id: 1, text: '1'},
                    { id: 2, text: '2'},
                    { id: 3, text: '3'},
                    { id: 4, text: '4'},
                    { id: 5, text: '5'}      
                ],
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

            $('.priority-select').each(function() {
                var priority = $(this).attr('priority');
                if(priority > 5){
                    priority = 5;
                }
                $(this).val(priority).trigger('change');
            });
        }
    });
    first_access = 1;

    $('#search-form-modal').on('submit', function (e) {
        e.preventDefault();
        oTable.draw();
                // console.log($('#input_product_name').val());
                // console.log( $('input[name=type_search_modal]:checked').val());
                
                // console.log( $('input[group-name=product]').val() );
                // console.log( $('input[group-name=business]').val() );


        // console.log(type_[$("input[name=type_search_modal]:checked", ).val()]);
    });
    
 	$('.createBTN').on('click', function(event) {
        event.preventDefault();
        $('#myModal').modal();
    });

    $("input[name=type_search_modal]").on('change', function(){
    	var type = $("input[name=type_search_modal]:checked", ).val();

    	if(type == 0){
    		$('#modalType1').fadeToggle('fast', function(){
	        	$('#modalType0').fadeToggle('fast');
	    	});
    	}else{
            $('#input_product_name').val('');
    		$('#modalType0').fadeToggle('fast', function(){
		        $('#modalType1').fadeToggle('fast');
		    });
    	}
    });
    
    $('.check-all').on('click', function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    })


    $('#saveBTN').on('click', function(e){
       e.preventDefault();
          var radioType = type_[$('input[name=type_search_modal]:checked').val()];

           var values = $('input:checkbox:checked.ids').map(function () {
                           return this.value;
            }).get(); // ["18", "55", "10"]
            var array_Clear = cleanArray(values);
            
            var url = '/config/input_item';
            
                $.ajax({
                  type: "POST",
                  url: $("meta[name='root-url']").attr('content') + url,
                  data:{ item : array_Clear, id : id , productType: radioType},
                  cache: false,
                  success: function(message){
                       var return_message = "";
                       var error_length = message.data.errors.length;
                    
                         for (var i = 0; i < error_length ; i++) {
                            return_message += message.data.errors[i]['input']['content_id'] +' is '+ message.data.errors[i]['message']+'<br>';
                         }
                    
                                swal({
                                        title: 'Success',
                                        text: return_message,
                                        html:true,
                                        type: 'success'
                                 },function() {
                                        location.reload();
                                 });
                     }
                  
                });
                       
                    
    });

    function cleanArray(actual) {
      var newArray = new Array();
      for (var i = 0; i < actual.length; i++) {
        if (actual[i] && actual[i] != 'undefined') {
          newArray.push(actual[i]);
        }
      }
      return newArray;
    }

   
 </script>
