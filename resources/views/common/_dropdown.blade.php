<div class="dropdown">
    @if(isset($nullDefault))
        <input type="hidden" name="{{ isset($name) ? $name : 'dropdown_selected' }}" class="dropdown-input" value="" group-name="{!! $group !!}">
    @else
        <input type="hidden" name="{{ isset($name) ? $name : 'dropdown_selected' }}" class="dropdown-input" value="undefine" group-name="{!! $group !!}">
    @endif
    <button class="dropdown-toggle btn btn-block btn-default border-width-thin border-default bg-white" type="button" data-toggle="dropdown">
        <span class="dropdown-selected" style="text-transform:none;" group-name="{!! $group !!}">{!! $defaultText !!}</span>
        <span class="caret pull-right"></span>
    </button>

    <ul class="dropdown-menu">
        @if(isset($disableSelectAll))

        @else
            <li><a tabindex="-1" class="dropdown-item" value="undefine" group-name="{!! $group !!}" {!! isset($value) && $value == 'undefine' ? 'selected' : '' !!}>--- {{  trans('form.all') }} ---</a></li>
        @endif

        @foreach($data as $val)
            @if(isset($val['children']) && count($val['children']) > 0)
                <li class="dropdown-child">
                    <a tabindex="-1" class="dropdown-parent" value="{{ $val['id'] }}" group-name="{!! $group !!}" {!! isset($value) && $value == $val['id'] ? 'selected' : '' !!}>{!! $val['name'][$language] !!}<span class="caret pull-right"></span></a>
                    @include('common._dropdown_menu',[
                        'options'      => $val['children'],
                        'parent_id'    => $val['id'],
                        'parent_name'  => $val['name'][$language],
                        'parent_level' => $val['level'],
                        'value'        => isset($value) ? $value : NULL,
                        'disableLevel' => isset($disableLevel)? $disableLevel : NULL
                    ])
                </li>
            @else
                <li>
                    <a tabindex="-1" class="dropdown-item" value="{{ $val['id'] }}" group-name="{!! $group !!}" {!! isset($value) && $value == $val['id'] ? 'selected' : '' !!}>
                        @if(isset($val['name'][$language]))
                            {!! $val['name'][$language] !!}
                        @endif
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>