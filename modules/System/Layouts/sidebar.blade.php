@php
    use Modules\Core\Helpers\MenuSystemHelper;
@endphp
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">Menu chức năng</li>
            @foreach ($menuItems as $menu_url => $menu)
                    @if($menu_url == $module)
                        @php echo MenuSystemHelper::print_menu($menu_url,$menu,true); @endphp
                    @else
                        @php echo MenuSystemHelper::print_menu($menu_url,$menu); @endphp
                    @endif
            @endforeach
        </ul>
    </section>
</aside>