@if (isset($menus) && count($menus) > 0)
    <ul class="navigation navigation-main navigation-accordion">
        @foreach ($menus as $menu)
            <li {!! (array_search($urlFirst[0], [$menu['url']]) !== false) ? 'class="active"' : '' !!} >
                <a id="{{ (isset($menu['id'])) ? $menu['id'] : '' }}" href="{{ (isset($menu['url']) && $menu['url'] !== false)? url($menu['url']) : 'javascript:void(0)' }}">
                    {!! (isset($menu['icon'])) ? '<i class="' . $menu['icon'] . '"></i>' : '<i class="icon-cog2"></i>' !!}
                    <span>{{ (isset($menu['text'])) ? $menu['text'] : '-' }}</span>
                </a>
                @if (isset($menu['submenu']) && count($menu['submenu']) > 0)
                    <ul>
                        @foreach ($menu['submenu'] as $subMenu)
                            <li {!! (isset($subMenu['url']) && $urlCurrent === $subMenu['url']) ? 'class="active"' : '' !!}>
                                <a id="{{ (isset($subMenu['id'])) ? $subMenu['id'] : '' }}" href="{{ (isset($subMenu['url']) && $subMenu['url'] !== false)? url($subMenu['url']) : 'javascript:void(0)' }}">
                                    {{ $subMenu['text'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif