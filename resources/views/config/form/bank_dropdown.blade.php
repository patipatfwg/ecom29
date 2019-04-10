<div class="col-lg-12 ">
    <div class="row">
        <div class="form-group col-lg-12">
            <div class="col-lg-2 control-label">
                <label for="{{ $text_name }}">
                    <span class="text-danger">*</span> {{ $text_name }}
                </label>
            </div>
            <div class="col-lg-7">

            <select class="form-control" name="bank" >
            @if(isset($bank['data']))
                @foreach($bank['data']['records'] as $key => $data)
                    <option value="{{ $data['id'] }}" {{ ($value == $data['id']) ? 'selected':""}}>{{ $data['name']['th'] }}</option>
                @endforeach
            @endif
            <select>

            </div>
            <div class="col-lg-2">
                    <div class="pull-right">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::button('Manage Bank', [
                                    'type'  => 'button',
                                    'class' => 'btn bg-primary-800 btn-raised',
                                    'id' => 'bankBtn'
                                ]) }}
                            </div>
                        </div>
                    </div>
            </div>
        </div>
                   
    </div>
</div>