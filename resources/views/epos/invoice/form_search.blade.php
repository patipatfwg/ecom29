<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Invoice Search</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        {{--for test: <code>1706018012</code>--}}
        <div class="col-lg-12">
            {!! Form::open([
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'method'       => 'get',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4">
                        {{--<select class="select-dropdown form-border-select" name="search_type" id="search_type">--}}
                            {{--<option value="sale_order_number">Sale Order Number</option>--}}
                            {{--<option value="return_order_number" >Return Order Number</option>--}}
                            {{--<option value="invoice_number" >Invoice Number</option>--}}
                        {{--</select>--}}

                        {{ Form::select('search_type', [
                            'sale_order_number' => 'Sale Order Number',
                            'return_order_number' => 'Return Order Number',
                            'invoice_number' => 'Invoice Number'
                        ], null, ['class'=>'select-dropdown form-border-select']) }}
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            {{ Form::text('search_value', null, [
                                'id'          => 'search_value',
                                'class'       => 'form-control',
                                'placeholder' => 'Sale Order Number'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="row">--}}
                {{--<div class="form-group">--}}
                    {{--<div class="col-md-3">--}}
                        {{--{!! Html::decode(Form::label('order_number', 'Sale Order Number<span class="text-danger">*</span> : ')) !!}--}}
                    {{--</div>--}}
                    {{--<div class="col-md-9">--}}
                        {{--<div class="input-group">--}}
                            {{--{{ Form::text('order_number', null, [--}}
                                {{--'id'          => 'order_number',--}}
                                {{--'class'       => 'form-control',--}}
                                {{--'placeholder' => '201705300009'--}}
                            {{--]) }}--}}
                            {{--<span class="input-group-addon"><i class="icon-pen6"></i></span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
                {{--<div class="form-group">--}}
                    {{--<div class="col-md-3">--}}
                        {{--{!! Html::decode(Form::label('return_order_number', 'Return Order Number<span class="text-danger">*</span> : ')) !!}--}}
                    {{--</div>--}}
                    {{--<div class="col-md-9">--}}
                        {{--<div class="input-group">--}}
                            {{--{{ Form::text('return_order_number', null, [--}}
                               {{--'id'          => 'return_order_number',--}}
                               {{--'class'       => 'form-control',--}}
                               {{--'placeholder' => 'Return Order Number'--}}
                           {{--]) }}--}}
                            {{--<span class="input-group-addon"><i class="icon-pen6"></i></span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
                {{--<div class="form-group">--}}
                    {{--<div class="col-md-3">--}}
                        {{--{!! Html::decode(Form::label('invoice_number', 'Invoice Number<span class="text-danger">*</span> : ')) !!}--}}
                    {{--</div>--}}
                    {{--<div class="col-md-9">--}}
                        {{--<div class="input-group">--}}
                            {{--{{ Form::text('invoice_number', null, [--}}
                                {{--'id'          => 'invoice_number',--}}
                                {{--'class'       => 'form-control',--}}
                                {{--'placeholder' => 'Invoice Number'--}}
                            {{--]) }}--}}
                            {{--<span class="input-group-addon"><i class="icon-pen6"></i></span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="clearfix"></div>
            <div class="row">
                    <span>
                        * Please enter search input as one of field: Sale Order Number, Return Order Number or Invoice Number
                    </span>
                {{ Form::button('<i class="icon-search4"></i> Search', array(
                    'id' => 'buttonSubmit',
                    'type'  => 'submit',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                )) }}
                {{--<a id="btnReset" class="pull-right btn">Reset</a>--}}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@section('footer_script')
    @parent
    <script>
        var formSubmit = $('#search-form');
        $('#btnReset').click(function(e){
            e.preventDefault();
            formSubmit[0].reset();
            return false;
        });
        $('.select-dropdown').select2({
            minimumResultsForSearch: -1
        });
        $('.select-dropdown').change(function () {
//            alert($(this).val());
            if ($(this).val() == 'sale_order_number') {
                $('#search_value').attr('placeholder','Sale Order Number');
            }
            if ($(this).val() == 'return_order_number') {
                $('#search_value').attr('placeholder','Return Order Number');
            }
            if ($(this).val() == 'invoice_number') {
                $('#search_value').attr('placeholder','Invoice Number');
            }
        });

        <?php
        if (Session::has('msg')) {
            echo 'swal("Error!", "' . Session::get('msg') . '", "error");';
        }
        ?>
    </script>
@endsection