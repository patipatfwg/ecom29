<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Refund List Search</h6>
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
                    <div class="col-md-4">
                        {{--<div class="input-group">--}}
                            {{ Form::text('start_date', null, [
                                'id'          => 'start_date',
                                'class'       => 'form-control',
                                'placeholder' => 'Started Date :'
                            ]) }}
                            {{--<span class="input-group-addon">--}}
                                {{--<i class="icon-calendar2"></i>--}}
                            {{--</span>--}}
                        {{--</div>--}}
                    </div>
                    <div class="col-md-4">
                        {{--<div class="input-group">--}}
                            {{ Form::text('end_date', null, [
                                'id'          => 'end_date',
                                'class'       => 'form-control',
                                'placeholder' => 'End Date :'
                            ]) }}
                            {{--<span class="input-group-addon"><i class="icon-calendar2"></i></span>--}}
                        {{--</div>--}}
                    </div>
                    <div class="col-md-4">
                        <!-- Default select -->
                        {{--<select class="form-control" data-width="100%" name="status">--}}
                            {{--<option value="">Refund Status</option>--}}
                            {{--<option value="1">New Sync</option>--}}
                            {{--<option value="2">Waiting</option>--}}
                            {{--<option value="3">Approve</option>--}}
                        {{--</select>--}}
                        <!-- /default select -->
                        {{ Form::select('status', [
                            '' => 'All Status',
                            '1' => 'NotSettled',
                            '2' => 'InProgress',
                            '3' => 'Settled'
                        ], null, ['class'=>'select-dropdown form-control']) }}
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">

                    {{ Form::button('<i class="icon-search4"></i> Search', array(
                        'type'  => 'submit',
                        'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                    )) }}
                    {{--<a id="btnReset" class="pull-right btn">Reset</a>--}}
                </div>

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
            $('#start_date').val('');
            $('#end_date').val('');
            $('#status select').val('');
            return false;
        });

        <?php
        if (Session::has('msg')) {
            echo 'swal("Error!", "' . Session::get('msg') . '", "error");';
        }
        ?>
    </script>
@endsection