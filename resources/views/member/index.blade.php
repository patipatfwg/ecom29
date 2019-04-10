<?php
$scripts = [
    'datatables',
    'datatablesFixedColumns',
    'select2',
    'sweetalert',
    'datetimepicker',
    'bootstrap_multiselect',
    'uniform',
];
?>

@extends('layouts.main')

@section('title', 'Member')

@section('breadcrumb')
<li class="active">Member</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body search_section">
        <div class="col-lg-12">
            {!! Form::open([
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-2">
                        <label>Username</label>
                        {{ Form::text('username', null, [
                            'id'          => 'username',
                            'class'       => 'form-control',
                            'placeholder' => 'Username'
                        ]) }}
                    </div>
                    <div class="col-md-4">
                        <label>Email</label>
                        {{ Form::text('email', null, [
                            'id'          => 'email',
                            'class'       => 'form-control',
                            'placeholder' => 'sample@sample.com'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>First Name</label>
                        {{ Form::text('first_name', null, [
                            'id'          => 'first_name',
                            'class'       => 'form-control',
                            'placeholder' => 'First Name'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Last Name</label>
                        {{ Form::text('last_name', null, [
                            'id'          => 'last_name',
                            'class'       => 'form-control',
                            'placeholder' => 'Last Name'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-2">
                        <label>Mobile Number</label>
                        {{ Form::text('phone', null, [
                            'id'          => 'phone',
                            'class'       => 'form-control',
                            'placeholder' => '099xxxxxxx'
                        ]) }}
                    </div>
                    <div class="col-md-2">
                        <label>Registered Store ID</label>
                        @if(count($stores)!=1)
                            {{ Form::select('makro_register_store_id', $stores, null, [
                                'id'          => 'makro_register_store_id',
                                'class'       => 'form-control select2',
                                'placeholder' => 'Select Stores...'
                            ]) }}
                        @else
                            <input type="hidden" name="makro_register_store_id" value="{{ $current_store }}">
                            {{ Form::select('makro_register_store_id', $stores, null, [
                                'id'          => 'makro_register_store_id',
                                'class'       => 'form-control select2',
                                'disabled'    => true
                            ]) }}
                        @endif
                    </div>
                    <div class="col-md-2">
                        <label>Member Number</label>
                        {{ Form::text('makro_member_card', null, [
                            'id'          => 'makro_member_card',
                            'class'       => 'form-control',
                            'placeholder' => '13-digit or 14-digit'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Tax ID</label>
                        {{ Form::text('tax_id', null, [
                            'id'          => 'tax_id',
                            'class'       => 'form-control',
                            'placeholder' => 'Company/Personal Tax ID'
                        ]) }}
                    </div>
                     <div class="col-md-3">
                        <label>Company Name/Personal Name</label>
                        {{ Form::text('shop_name', null, [
                            'id'          => 'shop_name',
                            'class'       => 'form-control',
                            'placeholder' => 'Company Name/Personal Name'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        <label>Registration Date (From)</label>
                        {{ Form::text('start_date', null, [
                            'id'          => 'start_date',
                            'class'       => 'form-control',
                            'placeholder' => 'DD/MM/YYYY'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Registration Date (To)</label>
                        {{ Form::text('end_date', null, [
                            'id'          => 'end_date',
                            'class'       => 'form-control',
                            'placeholder' => 'DD/MM/YYYY'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Last Login Date (From)</label>
                        {{ Form::text('start_last_login_date', null, [
                            'id'          => 'start_last_login_date',
                            'class'       => 'form-control',
                            'placeholder' => 'DD/MM/YYYY'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Last Login Date (To)</label>
                        {{ Form::text('end_last_login_date', null, [
                            'id'          => 'end_last_login_date',
                            'class'       => 'form-control',
                            'placeholder' => 'DD/MM/YYYY'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        <label>Customer Type</label>
                        {{ Form::text('customer_type', null, [
                            'id'          => 'shop_type',
                            'class'       => 'form-control',
                            'placeholder' => 'ID, Type'
                        ]) }}
                    </div>
                    <div class="col-md-3">
                        <label>Customer Channel</label>
                        <div class="form-group">
                            @include('common._select_multiple',[
                                'data' => config('config.customer_channel'),
                                'id'   => 'select-customer-channel'
                            ])
                            <input type="hidden" name="customer_channel" id="customer_channel">
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                {{ Form::button('<i class="icon-search4"></i> Search', array(
                    'type'  => 'submit',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                )) }}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped datatable-dom-position" id="members-table" data-page-length="10" width="160%">
			<thead>
				<tr>
					<th width="80">No.</th>
					<th width="200" style="min-width:200px;">Username</th>
					<th>Email</th>
					<th>Registration Date</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Mobile Number</th>
					<th>Member Number</th>
					<th>Company Name/Personal Name</th>
					<th>Customer Type</th>
					<th>Customer Channel</th>
					<th>Tax ID</th>
					<th>Date Registered At Store</th>
                    <th>Registered Store ID</th>
					<th>Last Login Date</th>
					<th width="50">Edit</th>
                    <th width="50">Delete</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="17" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('footer_script')
<script type="text/javascript">

    var $table = $('#members-table');
    var $thead = $table.find('thead');
    var $tbody = $table.find('tbody');

    // Basic initialization
    $('#select-customer-channel').multiselect({
        onChange: function(option, checked) {
            $.uniform.update();

            var values = '';
            var str    = '';
            $('#select-customer-channel').each(function() {
                if ($(this).val() && $(this).val() !== option.val()) {
                    values += str + $(this).val();
                    str    = ',';
                }
            });
            $("#customer_channel").val(values);
        }
    });
    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});
    //set dataTabel
    var oTable = $table.on('error.dt', function(e, settings, techNote, message) {
        new PNotify({text: 'Error connection', type: 'error' });
    }).DataTable({
        deferRender:    true,
        scrollY:        true,
        scrollX:        true,
        scrollCollapse: true,
        scroller:       true,
        fixedColumns: {
            leftColumns: 2,
            rightColumns: 1,
            heightMatch: 'none'
        },
        lengthMenu: [ 10, 50, 100, 500, 1000 ],
        processing: false,
        serverSide: true,
        searching: false,
        retrieve : true,
        destroy : true,
        order: [[ 1, "asc" ]],
        cache: true,
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        ajax: {
            url: $("meta[name='root-url']").attr('content') + '/member/data',
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
        fnServerParams: function(data) {
            data['order'].forEach(function(items, index) {
                data['order'][index]['column'] = data['columns'][items.column]['name'];
            });
        },
        columns: [
            { data: 'number', name: 'number', width: '80px', orderable: false, searchable: false },
            { data: 'username', name: 'username' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'first_name', name: 'first_name' },
            { data: 'last_name', name: 'last_name' },
            { data: 'phone', name: 'phone' },
            { data: 'makro_member_card', name: 'makro_member_card' },
            { data: 'shop_name', name: 'business.shop_name' },
            { data: 'shop_type', name: 'business.shop_type' },
            { data: 'customer_channel', name: 'customer_channel' },
            { data: 'tax_id', name: 'tax_id' },
            { data: 'makro_register_date', name: 'makro_register_date' },
            { data: 'makro_register_store_id',name:'makro_register_store_id',orderable:false, searchable: false},
            { data: 'last_login_date', name: 'last_login_date' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            { data: 'delete_action', name: 'delete_action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    $('div.datatable-header').append(`
        @include('common._print_button')&nbsp;
    `);

    $('.print-report').on('click', function(event) {
        event.preventDefault();
        var data   = oTable.ajax.params();
        var search = {
            start: data.start,
            length: data.length,
            search: data.search,
            order: data.order
        };
        window.location.replace($("meta[name='root-url']").attr('content') + '/member/report?' + $.param(search));
    });

    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('#makro_register_store_id').select2({
        placeholder: 'Select Store List ...',
        allowClear: true
    });

    function convertDate(date) {
        var splitTime = date.split(' ');
        var splitDate = splitTime[0].split('-');
        return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0] + ' ' + splitTime[1];
    }
    
    function deleteMember(id)
    {
        swal({
            title: "Are you sure?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!"
        }, function(isConfirm){
            if(isConfirm){
                $.ajax({
                    type: 'DELETE',
                    url: '/member',
                    data: { _token: '{{ csrf_token() }}', id: id},
                    dataType: 'json',
                    success: function(data) {
                        if(data.status == false){
                            swal({
                            title: "Oops...",
                            text: data.message,
                            confirmButtonColor: "#EF5350",
                            type: "error"
                        });
                        } else {
                            location.reload();
                        }
                    },
                    error: function(data) {
                        swal({
                            title: "Oops...",
                            text: "Something went wrong!",
                            confirmButtonColor: "#EF5350",
                            type: "error"
                        });
                    }            
                });
            }
        });
    }

</script>

@include('common._datetime_range_script', [
    'format_start' => 'd/m/Y H:i:00',
    'format_end'   => 'd/m/Y H:i:00',
    'refer_start'  => '#start_date',
    'refer_end'    => '#end_date',
    'timepicker'   => true,
    'editable'     => true
])
@include('common._datetime_range_script', [
    'format_start' => 'd/m/Y H:i:00',
    'format_end'   => 'd/m/Y H:i:00',
    'refer_start'  => '#start_last_login_date',
    'refer_end'    => '#end_last_login_date',
    'timepicker'   => true,
    'editable'     => true
])
@endsection