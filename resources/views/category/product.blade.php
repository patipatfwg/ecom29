<?php

$scripts = [
    'nestable',
    'sweetalert',
    'select2',
    'datatables',
    'datetimepicker',
    'datatablesFixedColumns',
    "uniform",
    "bootstrap_multiselect"
];
?>

@extends('layouts.main')

@section('title', $name . ' - Product List')

@section('breadcrumb')
    <li><a href="/{{ $type == 'product' ? 'category' : 'category_business' }}">{{ $name }} </a></li>
    @if( ! empty($breadcrumb))
        @foreach($breadcrumb as $value)
        <li class="active"><a href="/category/{{ $value['id'] }}">{{ $value['name'] }}</a></li>
        @endforeach
    @endif
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Add Product</h6>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
        {{ Form::open(['autocomplete' => 'off','class' => 'form-horizontal','id' => 'search-form' , 'url' => '/category/'.$category_id.'/product/'.$type ]) }}
        <div class="row">
            <div class="form-group">
                <div class="col-lg-6">
                    <div class="col-xs-2">
                        <label>Product</label>
                    </div>
                    <div class="col-xs-10">
                        <div class="multi-select-full">
                            {{ Form::select('product[]', $product , null, [
                                'id'          => 'product-add',
                                'multiple'    => 'multiple',
                                'class'       => 'multiselect-filtering',
                            ]) }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    {{ Form::button('<i class="icon-plus3"></i> Add Product', [
                        'id'    => 'btn-add-product',
                        'type'  => 'submit',
                        'class' => 'btn bg-teal-400 btn-raised legitRipple legitRipple'
                    ]) }}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            <div class="row">
            <div class="form-group">
                <div class="col-lg-6">
                    <div class="col-xs-2">
                        <label>Store</label>
                    </div>
                    <div class="col-xs-10">
                        <div class="multi-select-full">
                            {{ Form::select('stores', $stores , null, [
                                'id'          => 'store_id',
                                'class'       => 'form-control select2',
                            ]) }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    {{ Form::button('<i class="icon-search4"></i> Search', [
                        'type'  => 'submit',
                        'id'    => 'btn-search-store',
                        'class' => 'btn bg-primary btn-raised legitRipple legitRipple'
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel">
	<div class="panel-body table-responsive">
		<table class="table table-border-gray table-striped datatable-dom-position" id="store-table" data-page-length="10" width="100%">
			<thead>
				<tr>
					<th class="text-center" width="50" style="min-width:50px;">No.</th>
					<th width="50" style="min-width:120px;">Item ID</th>
					<th>Product Name(TH)</th>
					<th>Product Name(EN)</th>
					<th class="text-center" width="50">Published</th>
                    <th class="text-center" width="50">Delete</th>
				</tr>
			</thead>
                <tr>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td colspan="6" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td>
                </tr>
            <tbody>

            </tbody>
		</table>
	</div>
</div>
<div id="loading-submit" class="loader loader-default" data-text></div>
@endsection

@section('footer_script')
<script type="text/javascript">
var app_url       = '/category/{{ $category_id }}';
var product_multi = $('#product-add');
var status_load   = false;
var text_input    = '';
var _table        = '';
$(function() {
    // set button loading
    $('#btn-add-product').click(function(event) {
        // event.preventDefault();
        $(this).button('loading');
        $('#loading-submit').addClass('is-active');
    });
    // select Search
    product_multi.multiselect({
        enableFiltering: true,
        templates: {
            filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text" onkeyup="keyupSearch(this.value);"></li><li><center><i class="icon-spinner2 spinner search-loading"></i></center></li>'
        }
    });
    $(".styled, .multiselect-container input[type='checkbox']").uniform({ radioClass: 'choice'});
    // selete Store
    $('#store_id').select2({
        placeholder: 'Select Store List ...',
    });

    // load get product by store
    _table = $('#store-table').DataTable({
        searching: false,
        dom: '<"datatable-header"fl><"datatable-body"t><"datatable-footer"ip>',
        language: {
            lengthMenu: '<span>Show :</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        columns: [
            { name: 'number' },
            { name: 'item_id' },
            { name: 'category_name_th' },
            { name: 'category_name_en' },
            { name: 'status', orderable: true, className: 'text-center'},
            { name: 'delete', orderable: false, className: 'text-center' }
        ]
    });
    getProductByStore();

    // Click Seach Product By Store
    $('#btn-search-store').click(function(){
        $('#loading-submit').addClass('is-active');
        getProductByStore();
    });
});
function getProductByStore()
{
    if (status_load == true) {
        return false;
    }
    status_load = true
    $.ajax({
        url: app_url + '/data',
        type: 'GET',
        dataType: 'json',
        data: {'store_id': $('#store_id').val()},
        success: function(datas) {
            status_load = false;
            if (datas.length > 0) {

                _table.clear();
                $.each(datas, function(vKey, vData) {
                    var main = $('<tr>');
                    main.append($('<td>').text(vData.number));
                    main.append($('<td>').text(vData.item_id));
                    main.append($('<td>').text(vData.name_th));
                    main.append($('<td>').text(vData.name_en));
                    main.append($('<td>').html(vData.status));
                    main.append($('<td>').html(vData.delete));
                    // var status = vData.published_status == '1' ? '<i class="icon-eye text-teal">&nbsp;</i>' : '<i class="icon-eye"></i>';
                    // var main = $('<tr><td>'+vData.number+'</td><td>'+vData.item_id+'</td><td>'+vData.name_th+'</td><td>'+vData.name_en+'</td><td>'+status+'</td><td>'+vData.delete+'</td></tr>')
                    _table.row.add(main);
                });

                _table.draw();
            }else{
                _table.clear().draw();
            }
            $('#loading-submit').removeClass('is-active');
        }
    });
}
function keyupSearch(text)
{
    text_input = text;
    if ( text_input.length >= 3 ) {
        var product_select = [];

        if (product_multi.val() != '' && product_multi.val() != null ) {

            if (product_multi.val() != '') {
                $.each(product_multi.val(), function(vKey, vData) {
                    product_select.push({label: product_multi.find("option[value='"+vData+"']").text(), value: vData, selected: true});
                });
            }
        }

        getDataProduct(product_select);

    }
}
function getDataProduct(product_select)
{
    if (text_input != '' && status_load === false) {
        $('.search-loading').css('display','block');
        status_load = true;
        $.ajax({
            url: app_url + "/get_product_search",
            type: 'GET',
            dataType: 'json',
            // async:false,
            data: {'text_search': text_input},
            success: function(data) {

                if (data.length > 0) {

                    $.each(data, function(vKey, vData) {

                        var result = checkProductDuplicate(vData.item_id , product_select);
                        if (result  === false ) {
                            product_select.push({value:vData.item_id , label: vData.name_show});
                        }
                    });

                    appendSelect(product_select);
                }
                status_load = false;
                $('.search-loading').css('display','none');
            }
        });
    }
}
function appendSelect(datas)
{
    if (datas.length > 0) {

        product_multi.multiselect('dataprovider', datas);
        var selectconfig = {
            enableFiltering: true,
            templates: {
                filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text" onkeyup="keyupSearch(this.value);"></li><li><center><i class="icon-spinner2 spinner search-loading"></i></center></li>'
            }
        }

        product_multi.multiselect('setOptions', selectconfig);
        product_multi.multiselect('rebuild');
        $('.multiselect-filter input').focus().val(text_input);

        $(".styled, .multiselect-container input[type='checkbox']").uniform({ radioClass: 'choice'});
    }
}
function checkProductDuplicate(item_id , arr )
{
    var status = false;
    if (arr.length > 0) {
        $.each(arr, function(vKey, vData) {
            if (item_id == vData.value) {
                status = true;
            }
        });
    }

    return status;
}
function deleteItems(ids){

    swal({
        title: "Confirm to delete product linkage?",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonColor: '#DD6B55',
        confirmButtonText: "Confirm",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
    },
    function(isConfirm){
        if (isConfirm) {
            $.ajax({
                type: 'DELETE',
                url: app_url + '/product/' + ids,
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        swal("Success", "", "success");
                        getProductByStore();
                    }else{
                        swal("Error", "", "error");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    
                }
            });
        }
    });
}
</script>

@endsection
