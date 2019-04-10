 <?php
    $scripts = [
        'angular',
        'datatables',
        'nestable',
        'sweetalert',
        'multi',
        'select2',
        'inputupload',
        'dropzone',
        'ckeditor',
        'sortable',
        'datetimepicker',
        'to-markdown',
        'showdown'
    ];
    ?>

@extends('layouts.main')

@section('title', 'Payment Option')

@section('breadcrumb')
<li class="active">Payment Option</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Payment Option</h6>
    </div>

    <div class="panel-body table-responsive">
        <table class="table table-striped table-hover datatable-dom-position" id="payment-options-table" data-page-length="10" width="100%">
            <thead>
            <tr>
                <th width="50"><input type="checkbox" class="check-all"></th>
                <th width="50">Gateway</th>
                <th width="50">Name (TH)</th>
                <th width="50">Name (EN)</th>
                <th >Max Amount</th>
                <th >Paymrnt Fee</th>
                <th width="50">Published</th>
                <th width="50">Priority</th>
                <th width="50">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="9" class="text-center">Loading ...</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


{{--<div class="panel-body">--}}
    {{--<div class="row">--}}
        {{--<div class="col-lg-12">--}}
            {{--<form id="form-submit" class="form-horizontal" autocomplete="off">--}}
                {{--@foreach ($configs as $config)--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-md-12">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<div class="checkbox checkbox-switch">--}}
                                        {{--<label>--}}
                                            {{--@if ($config['status'] == 'active')--}}
                                                {{--<input name="{{$config['code']}}" type="checkbox" data-on-color="success" data-off-color="danger" data-on-text="Active" data-off-text="Inactive" class="switch" checked="checked" value="{{$config['id']}}">{{$config['name']['th']}}--}}
                                            {{--@else--}}
                                                {{--<input name="{{$config['code']}}" type="checkbox" data-on-color="success" data-off-color="danger" data-on-text="Active" data-off-text="Inactive" class="switch" value="{{$config['id']}}">{{$config['name']['th']}}--}}
                                            {{--@endif--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endforeach--}}
                {{--@if(Session::has('permission_menus.payment_option.update') && Session::get('permission_menus.payment_option.update') == true)--}}
                    {{--<div class="col-md-12">--}}
                        {{--<div class="pull-right">--}}
                            {{--<div class="form-group">--}}
                                {{--{{ Form::button('<i class="icon-checkmark"></i> Save', [ 'type' => 'submit', 'class' => 'btn bg-primary-800 btn-raised btn-submit', 'id' => 'save-status' ]) }}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</form>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}



@endsection

@section('footer_script')
<script type="text/javascript">
var url = $("meta[name='root-url']").attr('content') + '/config/payment_method';
var tableId = $('#payment-options-table');
var drawT = 1;

var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
  swal('Error!', 'Error connection', 'error');
}).DataTable({
  draw: drawT,
  scrollY: true,
  scrollX: '400 px',
  fixedColumns: {
      leftColumns: 4,
      rightColumns: 1,
        //heightMatch: 'none'
  },
  processing: true,
  serverSide: true,
  searching: false,
  retrieve: true,
  destroy: true,
  order: [[0, false]],
  cache: true,
  dom: '<"datatable-header"fl><t><"datatable-footer"ip>',
  language: {
    lengthMenu: '<span>Show :</span> _MENU_',
    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
  },
  ajax: {
    url: url + '/data',
    type: 'GET',
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
            swal('Error!', 'Error connection', 'error');
            tableId.find('tbody').find('td').html('No Data, please try again later');
        }
    }
  },
  fnServerParams: function(data) {
    drawT = drawT++;
    data['order'].forEach(function(items, index) {
        data['order'][index]['column'] = data['columns'][items.column]['data'];
    });
  },
  columns: [
    {
      data: 'id',
      name: 'checkbox',
      orderable: false,
      searchable: false,
      className: 'text-center',
      render: function(data, type, row) {
        return '<input class="ids check" type="checkbox" name="installment_option_ids[]" value="' + data + '">';
      }
    },
    {data: 'payment_gateway', name: 'payment_gateway', orderable: true},
    {data: 'name.th', name: 'name.th', orderable: true},
    {data: 'name.en', name: 'option_name_en', orderable: true},
    {data: 'max_amount', name: 'max_amount', orderable: false},
    {data: 'percent_of_charge', name: 'percent_of_charge', orderable: true},
    {
      data: 'status',
      name: 'status',
      orderable: true,
      className: 'text-center',
      render: function(data, type, row) {
        if (data == 'active') {
          return '<i class="icon-eye text-teal"></i>';
        }
        else {
          return '<i class="icon-eye-blocked text-grey-300"></i>';
        }
      }
    },
    { data: 'priority', name: 'priority', searchable: false, className: 'text-center'},
    {
      data: 'id',
      name: 'action',
      orderable: false,
      searchable: false,
      className: 'text-center',
      render: function(data, type, row) {
        return '<a href="'+url +'/edit/'+ data + '"><i class="icon-pencil"></i></a>';
      }
    }
  ]
});

$('div.datatable-header').append(`
        @include('common._show_hide_button')&nbsp;
        @include('common._priority_button')&nbsp;
`);
</script>
@include('config._footer_script',['appUrl' => 'payment_method/status_payment'])
@include('common._datatable')
@include('common._priority_script')

@endsection

