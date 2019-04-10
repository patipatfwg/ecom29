<div class="row margin-left-10">
    <div class="col-lg-4 margin-top-10">
        {{ $sub_menu_key }}
    </div>
    @foreach($sub_menu['permission'] as $permission => $value)
    <div class="col-lg-2">
        <div class="checkbox checkbox-success checkbox-circle">
            <input id="permission[{{ $menu_key }}][{{ $sub_menu_key }}][{{ $permission }}]"
            @if($permission=='write')
                onchange="autoCheck('{{ $menu_key }}','{{ $sub_menu_key }}')"
            @else if($permission=='read')
                onchange="autoUnCheck('{{ $menu_key }}','{{ $sub_menu_key }}')"
            @endif
            name="permission[{{ $menu_key }}][{{ $sub_menu_key }}][{{ $permission }}]" class="styled" type="checkbox" {{ $value ? 'checked="checked"' : '' }}>
            <label for="permission[{{ $menu_key }}][{{ $sub_menu_key }}][{{ $permission }}]">
                {{ convertToDisplayWords($permission) }}
            </label>
        </div>
    </div>
    @endforeach
</div>
