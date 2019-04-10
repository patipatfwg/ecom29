<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Order Search {{-- [IDM_OrderDetails] --}}</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            {!! Form::open([
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'method'       => 'get',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            {{ Form::text('order_number', null, [
                                'id'          => 'orderNumber',
                                'name'        => 'order_number',
                                'class'       => 'form-control',
                                'placeholder' => 'Sale Order Number {{-- [@OrderNo] --}}'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                {{ Form::button('<i class="icon-search4"></i> Search', array(
                    'type'  => 'submit',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                )) }}
                {{--<a id="btnReset" class="pull-right btn">Reset</a>--}}
                {{--For test: <code>0000000670</code>--}}
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

        <?php
            if (Session::has('msg')) {
                echo 'swal("Error!", "' . Session::get('msg') . '", "error");';
            }
        ?>

    </script>
@endsection