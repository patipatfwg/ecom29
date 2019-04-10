<script type="text/javascript">
    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('body').on('click', '.check-all', function() {
        $('table input:checkbox').not(this).prop('checked', this.checked);
    });

    $('body').on('click', '.check', function() {
        $('table .check-all').prop('checked', false);
    });

    $('body').on('click', '.status-checkbox', function(event) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: url + '/status',
            data: $('.ids:checked').serialize() + '&status=' + $(this).attr('status-data'),
            success: function(data){
                swal("{{ trans('validation.create.title') }}", "", 'success');
                oTable.draw();
            },
            complete: function (data) {
                $('.check-all').prop('checked', false);
            }
        });
    });

    $('body').on('click', '.btn-priority', function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: url + '/priority',
            data: $('.priority_number').serialize(),
            success: function(data){
                // swal("{{ trans('validation.create.title') }}", "", 'success');
                onAjaxSuccess(data);
                oTable.draw();                
            },
            complete: function (data) {

            }
        });
    });

    $('body').on('click', '.datatable-button', function(event) {
        event.preventDefault();

        $('.check-all').prop('checked', false);
        var action = $(this).attr('button-action');
        
        if(action == 'show'){
            callAjax('PUT', url+"/status/"+getCheckedId(),{status:'active'}, function(){
                oTable.draw();
            });
        }
        else if(action == 'hide'){
            callAjax('PUT', url+"/status/"+getCheckedId(),{status:'inactive'}, function(){
                oTable.draw();
            });
        }
        else if(action == 'delete'){
             deleteItems(getCheckedId());
        }
    });

    function deleteItems(ids){
        swal({
            title: "{{ trans('validation.delete.alert.title') }}",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "{{ trans('validation.delete.alert.btn_cancel') }}",
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "{{ trans('validation.delete.alert.btn_ok') }}",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function(isConfirm){
            if (isConfirm) {

                $.ajax({
                    type: 'DELETE',
                    url: url + '/' + ids,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status || data.success) {
                            // onDeleted(data);
                            onAjaxSuccess(data);
                            oTable.draw('page');
                        } 
                        else {
                            onAjaxFail(data);
                        }
                    },
                    error: onAjaxError,
                    complete: function() {

                    }
                });
            }
        });
    }

    function onDeleted(data){

        if (data.deleted != null && data.errors != null){

            var list = '';

            // Delete Success
            for (var item in data.deleted) {
                list += '<li class="list-group-item"><i class="glyphicon glyphicon-ok text-success"></i>[id: '+data.deleted[item]+']</li>';
            }
            
            // Delete Failed
            for (var item in data.errors) {
                list += '<li class="list-group-item"><i class="glyphicon glyphicon-remove text-danger"></i>[id: '+data.errors[item].id+'] '+data.errors[item].message+'</li>';
            }
            
            swal({
                title: "{{ trans('validation.delete.title') }}",
                text:  '<ul class="list-group no-border">'+list+'</ul>',
                html: true
            });
        }
        else{
            onAjaxSuccess(data);
        }
    }

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data = oTable.ajax.params();
        window.location.replace($("meta[name='root-url']").attr('content') + url + '/report?' + $.param(data));
    });
</script>

@include('common._call_ajax')