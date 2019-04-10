var App = function () {
    var oTable = function (obj , option = {}) {
		// Table setup
        // ------------------------------
        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                width: '100px',
                targets: [ 5 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
            drawCallback: function () {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
            },
            preDrawCallback: function() {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
            }
        });
        var arrTable = {
            processing: false,
            serverSide: true,
            retrieve : true,
            destroy : true,
            //stateSave : true,
            order: [[ 0, false ]],
            bAutoWidth: '100%',
            drawCallback : function(settings) {
             // console.log('drawCallback');
             // Switch($(".switch"));
            },
            preDrawCallback: function() {
                // console.log('preDrawCallback');

            },
            // select: {
            //     style: 'os',
            //     selector: 'td:first-child'
            // },
            
        };
        if($.isEmptyObject(option) == false){
            $.extend( arrTable, option);
        }
        console.log(arrTable);
        $(obj).DataTable(arrTable);
        


        // Buttons
        // $('.datatable-select-buttons').DataTable({
        //     dom: '<"dt-buttons-full"B><"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        //     buttons: [
        //         {extend: 'selected', className: 'btn btn-default'},
        //         {extend: 'selectedSingle', className: 'btn btn-default'},
        //         {extend: 'selectAll', className: 'btn bg-blue'},
        //         {extend: 'selectNone', className: 'btn bg-blue'},
        //         {extend: 'selectRows', className: 'btn bg-teal-400'},
        //         {extend: 'selectColumns', className: 'btn bg-teal-400'},
        //         {extend: 'selectCells', className: 'btn bg-teal-400'}
        //     ],
        //     select: true
        // });



        // External table additions
        // ------------------------------

        // Add placeholder to the datatable filter option
        // $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


        // Enable Select2 select for the length option
        // $('.dataTables_length select').select2({
        //     minimumResultsForSearch: Infinity,
        //     width: 'auto'
        // });
    };

    // var Switch = function (obj , option = {}) {
    //     $(obj).bootstrapSwitch();
    // };
    // var editor = function (obj , option = {}) {
    //     var arrEditor = {
    //         relative_urls: false,
    //         selector: $(obj).selector,
    //         width : "100%",
    //         height : "500",
    //         theme_advanced_default_foreground_color : '#FF00FF',
    //         theme_advanced_font_sizes : '10px, 12px, 13px, 14px, 16px, 18px, 20px',
    //         font_size_style_values : '12px, 13px, 14px, 16px, 18px, 20px',
    //         toolbar_items_size : 'small',
    //         setup : function(ed){
    //             ed.on('init', function() {
    //                 this.getDoc().body.style.fontSize = '12px';
    //             });
    //             ed.on('change', function () {
    //                 ed.save();
    //             });
    //         }
            
    //     };  
    //     if($.isEmptyObject(option) == false){
    //         $.extend( arrEditor, option);
    //     }
    //     tinymce.init(arrEditor);
    // };
    return {
        initTable: function (obj , option = {}) {
          oTable(obj , option);
        },
        // initSwitch: function (obj , option = {}) {
        //     Switch(obj , option);
        // },
        // initEditor: function (obj , option = {}) {
        //     editor(obj , option);
        // },

    };
}();