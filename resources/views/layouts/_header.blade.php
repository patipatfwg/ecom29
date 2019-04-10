<div class="navbar navbar-inverse navbar-fixed-top bg-danger-600">
    <div class="navbar-header bg-danger-700">
        <a class="navbar-brand" href="{{ URL::to('/dashboard') }}">MAKRO <i class="icon-arrow-up-right3"></i></a>
        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>
    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false">
                        <span><?php echo (Session::has('userName')) ? Session::get('userName') : ''; ?></span>
                        <i class="caret"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                        <li><a href="{!! URL::to('/logout') !!}"><i class="icon-switch2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>