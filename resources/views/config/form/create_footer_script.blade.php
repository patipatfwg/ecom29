<script type="text/javascript" src="/assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="/assets/js/core/libraries/jquery_ui/full.min.js"></script>

 @include('common._validate_form_script')
<script type="text/javascript">
   
    $(".switch").bootstrapSwitch();

    $('#bankBtn').on('click', function() {
    	$('#myModal').modal();
    });

var payment = '{{ isset($payment) ? $payment["id"] : "" }}';
var createConfig = {
    form: $('#form-submit'),
    url: '/config',
    httpMethod: 'POST',
    successCallback: function() {
        window.location = '/config/payment_method';
    }
}
var updateConfig = {
    form: $('#form-submit'),
    url: '/config/'+payment,
    httpMethod: 'PUT',
    successCallback: function() {
         location.reload();
    }
}

var config = (payment === '') ? createConfig : updateConfig;

validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);
</script>
   
{!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}

{{-- Data Table --}}

<script type="text/javascript">
    var url = '/bank';
    var tableId = $('#bank-table');
    var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: message, type: 'error' });
    }).DataTable({
        scrollY: true,
        processing: true,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 0, false ]],
        cache: true,
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: url + '/data',
            type: 'GET',
            data: function(d) {
            d.full_text = $('#full_text').val();
            },
            error: function(xhr, error, thrown) {
            swal('Error!', 'Error connection', 'error');
            tableId.find('tbody').find('td').html('No Data, please try again later');
            }
        },
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['data'];
            });
        },
        columns: [
            { data: 'bank_name_th', name: 'bank_name_th'},
            { data: 'bank_name_en', name: 'bank_name_en'},
            { 
                data: 'logo', 
                name: 'logo', 
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row){
                    return '<img src="'+data+'?process=resize&resize_width=120&resize_height=80" target="_blank" style="width: 120px; height: 80px; border: 2px solid #5a5b5b;" alt="">';
                }
            },
            { data: 'fee', name: 'fee', orderable: false },
            { 
                data: 'status',
                name: 'status', 
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    if (data == 'active') {
                    return '<i class="icon-eye text-teal"></i>';
                    }
                    else {
                    return '<i class="icon-eye-blocked text-grey-300"></i>';
                    }
                }
            }
            
        ]
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

</script>
@include('common._datatable')
