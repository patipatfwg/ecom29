<ul class="dropdown-menu">
    @if(!is_null($disableLevel) && $disableLevel == $parent_level)
    @else
    <li><a tabindex="-1" class="dropdown-item" value="{{ $parent_id }}" display-text="{{ $parent_name }}" group-name="{!! $group !!}">--- {{  trans('form.all') }} ---</a></li>
    @endif
    @foreach($options as $val)
        @if(isset($val['children']) && count($val['children']) > 0)
            <li class="dropdown-child">
                <a tabindex="-1" class="dropdown-parent" value="{{ $val['id'] }}" group-name="{!! $group !!}" {!! isset($value) && $value == $val['id'] ? 'selected' : '' !!}>{!! $val['name'][$language] !!}<span class="caret pull-right"></span></a>
                @include('common._dropdown_menu',[
                    'options' => $val['children'],
                    'parent_id' => $val['id'],
                    'parent_name' => $val['name'][$language],
                    'parent_level' => $val['level'],
                    'disableLevel' => isset($disableLevel)? $disableLevel : NULL
                ])
            </li>
        @else
            <li><a tabindex="-1" class="dropdown-item" value="{{ $val['id'] }}" group-name="{!! $group !!}" {!! isset($value) && $value == $val['id'] ? 'selected' : '' !!}>{!! $val['name'][$language] !!}</a></li>
        @endif
    @endforeach
</ul>