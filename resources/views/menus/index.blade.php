<?php
$scripts = [
    'nestable',
    'sweetalert',
    'bootstrap-iconpicker'
];
?>

@extends('layouts.main')

@section('title', 'Menu')

@section('breadcrumb')
<li class="active">Menu</li>
@endsection

@section('header_script')@endsection

@section('content')

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <button data-toggle="modal" data-target="#modalCreate" type="button" class="btn bg-teal-400 btn-raised legitRipple">
                        <i class="icon-plus-circle2 position-left"></i> Add Data
                    </button>
                </div>

                <div class="dd" id="nestable">
                    <div class="form-group">
                        <?php echo $menus; ?>
                    </div>

                    {{ Form::open([
                        'autocomplete' => 'off',
                        'url'          => '/menu/move',
                        'id'           => 'form-nestable-move'
                    ]) }}

                        {{ Form::text('nestable-output', null, [
                            'class' => 'hide',
                            'id'    => 'nestable-output'
                        ]) }}

                        <div class="pull-right">
                            {{ Form::button('<i class="icon-checkmark"></i> Save', [
                                'id'    => 'nestable-save',
                                'type'  => 'submit',
                                'class' => 'btn bg-teal-400 btn-raised legitRipple'
                            ]) }}
                        </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create -->
<div id="modalCreate" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gray">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">ADD DATA</h5>
            </div>

            {{ Form::open([
                'autocomplete' => 'off',
                'id'           => 'form-nestable-create'
            ]) }}

                <div class="modal-body">
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Menu Icon</label>
                        <div class="input-group">
                            {{ Form::text('icon', 'icon-cog2', [
                                'class'     => 'form-control',
                                'maxlength' => 20,
                                'readonly'
                            ]) }}
                            <div class="input-group-btn">
                                <button id="icon-menu-create" class="btn btn-default" data-icon="icon-cog2" data-iconset="icomoon" role="iconpicker"></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Menu Name</label>
                        {{ Form::text('name', null, [
                            'class'       => 'form-control',
                            'maxlength'   => 30
                        ]) }}
                    </div>
                    <div class="form-group">
                        <label>Menu Url</label>
                        {{ Form::text('url', null, [
                            'class' => 'form-control'
                        ]) }}
                        <span class="help-block text-warning">EX. foo or foo/index</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    {{ Form::button('<i class="icon-checkmark"></i> Save', [
                        'type'  => 'submit',
                        'class' => 'btn bg-teal-400 btn-raised legitRipple loading'
                    ]) }}
                </div>

            {{ Form::close() }}
        </div>
    </div>
</div>

<!-- Update -->
<div id="modalUpdate" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gray">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">EDIT DATA</h5>
            </div>
            {{ Form::open([
                'method'       => 'PUT',
                'autocomplete' => 'off',
                'id'           => 'form-nestable-update'
            ]) }}

                <div class="modal-body">
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Menu Icon</label>
                        <div class="input-group">
                            {{ Form::text('icon', 'icon-cog2', [
                                'class'     => 'form-control',
                                'maxlength' => 20,
                                'readonly'
                            ]) }}
                            <div class="input-group-btn">
                                <button id="icon-menu-update" class="btn btn-default" data-icon="icon-cog2" data-iconset="icomoon" role="iconpicker"></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><span class="text-danger">*</span> Menu Name</label>
                        {{ Form::text('name', null, [
                            'class'       => 'form-control',
                            'maxlength'   => 30
                        ]) }}
                    </div>
                    <div class="form-group">
                        <label>Menu Url</label>
                        {{ Form::text('url', null, [
                            'class' => 'form-control'
                        ]) }}
                        <span class="help-block text-warning">EX. foo or foo/index</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    {{ Form::button('<i class="icon-checkmark"></i> Save', [
                        'type'  => 'submit',
                        'class' => 'btn bg-teal-400 btn-raised legitRipple loading'
                    ]) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('footer_script')
<script type="text/javascript">
var updateOutput = function(e) {
    var list   = e.length ? e : $(e.target);
    var output = list.data('output');
    if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize')));
    } else {
        output.val('JSON browser support required for this demo.');
    }
};

$('#nestable').nestable({
    maxDepth: 2,
}).on('change', updateOutput);

updateOutput($('#nestable').data('output', $('#nestable-output')));

//click loading
$('#nestable-save').click(function() {
    $(this).button('loading');
});

//click show name icon
$('#icon-menu-create, #icon-menu-update').on('change', function(e) {
    $(this).parents('.input-group').find('input').val(e.icon);
});

//edit
var checkClick = true;
$('#nestable').on('click', '._update', function(){
    event.preventDefault();

    if (checkClick) {

        checkClick = false;
        var _this  = $(this);
        var _id    = _this.attr('data-id');
        var form   = $('#form-nestable-update');

        //loading
        _this.find('i').removeClass('icon-pencil').addClass('icon-spinner2 spinner');

        //set action
        form.attr('action', window.location.href + '/' + _id);

        //get ajax data
        $.ajax({
            type: 'GET',
            url: "{{ URL::to('menu') }}/" + _id + "/edit" ,
            data: {_token: '{{ csrf_token() }}'},
            dataType: 'json',
            success:function(data){
                if (data.success) {
                    $('#icon-menu-update').attr('data-icon', data.model.icon).iconpicker('setIcon', data.model.icon);
                    form.find('input[name="icon"]').val(data.model.icon);
                    form.find('input[name="name"]').val(data.model.name);
                    form.find('input[name="url"]').val(data.model.url);
                    $('#modalUpdate').modal('show');
                } else {
                    swal('Edit!', data.messages, 'warning');
                }

                _this.find('i').removeClass('icon-spinner2 spinner').addClass('icon-pencil');
                checkClick = true;
            },
            error: function(){
                swal('Edit!', 'Error connection', 'error');
            }
        });
    }
});

// delete
$('#nestable').on('click', '._delete', function(){
    event.preventDefault();
    var _id = $(this).attr('data-id');
    swal({
        title: 'Are you sure?',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, delete it!',
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
    },
    function(isConfirm){
        if (isConfirm) {
            $.ajax({
                type: 'DELETE',
                url: "{{ URL::to('menu') }}/" + _id,
                data: { _token: '{{ csrf_token() }}'},
                dataType: 'json',
                success:function(data){
                    if (data.success) {
                        swal('Deleted!', data.messages, 'success');
                        $("li[data-id='" + _id + "']").remove();
                    } else {
                        //new PNotify({text: 'Error connection', type: 'error' });
                        swal('Deleted!', data.messages, 'warning');
                    }
                },
                error: function(){
                    swal('Deleted!', 'Error connection', 'error');
                }
            });
        }
    });
});
</script>

{!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
{!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-create') !!}
{!! JsValidator::formRequest('\App\Http\Requests\MenusRequest', '#form-nestable-update') !!}
@endsection