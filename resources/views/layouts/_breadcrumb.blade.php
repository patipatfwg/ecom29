<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <?php if (Request::path() !== 'dashboard'): ?>
         <!--   <li><a href="{!! URL::to('/dashboard') !!}">Dashboard</a></li> -->
        <?php endif; ?>
        @yield('breadcrumb', '<span class="label bg-danger">MISSING BREADCRUMB</span>')
    </ul>
</div>
