<div class="multi-select-full">
    <select class="select-dropdown form-border-select" name="{{isset($name)? $name : ''}}" id="{{ isset($id)? $id : 'select-dropdown' }}" multiple="multiple">

        @if(isset($hasPlaceholder))
        <option></option>
        @endif

        @if(isset($defaultValue))
        <option value="{{ isset($default)? $default : '' }}">
            {!! $defaultValue !!}
        </option>
        @endif

        @if(isset($data))
        @foreach($data as $key => $val)
        <option value="{{ $key }}" {{ isset($value) && $value == $val ? 'selected' : '' }}>
            {!! $val !!}
        </option>
        @endforeach
        @endif
    </select>
</div>
