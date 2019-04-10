<?php
$scripts = [
  'sweetalert',
  'select2',
  'datatables',
  'datatablesFixedColumns',
  'bootstrap-select'
];
?>

@extends('layouts.main')

@section('title', 'Group Menu')

@section('breadcrumb')
<li class="active">Group Menu</li>
@endsection

@section('header_script')
@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
  
            <div class="col-lg-12">
                {!! Form::open([
                    'autocomplete' => 'off',
                    'class'        => 'form-horizontal',
                    'id'           => 'search-form'
                ]) !!}
                
                <div class="row">
                   
                        <div class="form-group">
                            <div class="col-md-12">
                            <label>Group Name</label>
                                {{ Form::text('full_text', null, [
                                    'id'          => 'full_text',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Group Name'
                                ]) }}
                            </div>
                            <div class="clearfix"></div>
                        </div>
                  
                </div>
                <div class="clearfix"></div>
                <div class="row">
                        {{ Form::button('<i class="icon-search4"></i> Search', [
                            'type'  => 'submit',
                            'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                        ]) }}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
 
</div>

<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-striped table-hover datatable-dom-position" id="group_menu-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th width="50"><input type="checkbox" class="check-all"></th>
					<th width="50">No.</th>
					<th width="50">Slug</th>
					<th >Group Menu Name (TH)</th>
          <th >Group Menu Name (EN)</th>
					<th width="50">Published</th>
					<th width="50">Edit</th>
					<th width="50">Add Items</th>
					<th width="50">Delete</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="7" class="text-center">Loading ...</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('footer_script')

<script type="text/javascript">
var url = '/group_menu';
var tableId = $('#group_menu-table');

var oTable = tableId.on('error.dt', function(e, settings, techNote, message) {
  swal('Error!', 'Error connection', 'error');
}).DataTable({
  scrollY: true,
  scrollX: '300px',
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
    data: function(d) {
      d.full_text = $('#full_text').val();
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
      data: '_id',
      name: 'checkbox',
      orderable: false,
      searchable: false,
      className: 'text-center',
      render: function(data, type, row) {
        if(data == '')
          return '-';
        return '<input class="ids check" type="checkbox" name="group_menu_ids[]" value="' + data + '">';
      }
    },
    {data: 'number', name: 'number', orderable: false},
    {data: 'slug', name: 'slug', orderable: true},
    {data: 'group_name_th', name: 'group_name_th', orderable: true},
    {data: 'group_name_en', name: 'group_name_en', orderable: true},
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
    {
      data: 'edit',
      name: 'edit',
      orderable: false,
      searchable: false,
      className: 'text-center',
      render: function(data, type, row) {
        return '<a href="' + data + '"><i class="icon-pencil"></i></a>';
      }
    },
    {
      data: 'add_hilight',
      name: 'add_hilight',
      orderable: false,
      searchable: false,
      className: 'text-center',
      render: function(data, type, row) {
          return '<a href="' + data +'"><i class="icon-plus-circle2"></i></a>';
      }
    },
    {
      data: 'delete',
      name: 'delete',
      orderable: false,
      searchable: false,
      className: 'text-center',
      render: function(data, type, row) {
        if(data == '')
            return '<i class="icon-trash">';
        return '<a onclick="deleteItems(\'' + data + '\')"><i class="icon-trash text-danger"></a>';
      }
    }
  ]
});

$('div.datatable-header').append(`
        @include('common._show_hide_button')&nbsp;
        @include('common._delete_button')&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('group_menu/create/')])
`);
</script>

@include('common._datatable')

@endsection