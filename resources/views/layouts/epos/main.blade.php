@spaceless
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1990 9:00:00 GMT" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="root-url" content="{!! url('/') !!}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MAKRO CMS - @yield('title', '<span class="label bg-danger">MISSING TITLE</span>')</title>

    <!-- start: CSS -->
    {{ Html::style('assets/css/icons/icomoon/styles.css') }}
    {{ Html::style('assets/css/fonts.css') }}
    {{ Html::style('assets/css/bootstrap.css') }}
    {{ Html::style('bower_components/nprogress/nprogress.css') }}
    {{ Html::style('bower_components/toastr/toastr.min.css') }}
    {{ Html::style('assets/css/core.css') }}
    {{ Html::style('assets/css/components.css') }}
    {{ Html::style('assets/css/colors.css') }}
    {{ Html::style('assets/css/custom.css') }}
    <!-- end: CSS -->

    @include('_main_script')
    <?php if (isset($scripts)) header_script($scripts); ?>

    @yield('header_script', '<span class="label bg-danger">MISSING HEADER SCRIPT</span>')

</head>
<body class="navbar-top">

    <!-- start: HERDER -->
    @include('layouts._header')
    <!-- end: HERDER -->

    <div class="page-container">
        <div class="page-content">

            <!-- start: MENU -->
            @include('layouts._menu')
            <!-- end: MENU -->

            <div class="content-wrapper">
                <div class="page-header page-header-default">

                    <!-- start: TITLE -->
                    @include('layouts.epos._title')
                    <!-- end: TITLE -->

                    <!-- start: BREADCRUMB -->
                    @include('layouts.epos._breadcrumb')
                    <!-- end: BREADCRUMB -->

                </div>
                <div class="content">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">{{ Html::ul($errors->all()) }}</div>
                    @endif

                    <!-- start: CONTENT -->
                    @yield('content', '<span class="label bg-danger">MISSING CONTENT</span>')
                    <!-- end: CONTENT -->

                    <!-- start: FOOTER -->
                    @include('layouts._footer')
                    <!-- end: FOOTER -->

                </div>
            </div>
        </div>
    </div>

    <!-- start: JS -->
    {{ Html::script('assets/js/core/libraries/jquery/2.1.4/jquery.min.js') }}
    {{ Html::script('assets/js/core/libraries/bootstrap.min.js') }}
    {{ Html::script('bower_components/nprogress/nprogress.js') }}
    {{ Html::script('assets/js/plugins/notifications/pnotify.min.js') }}
    {{ Html::script('assets/js/core/app.js') }}

    {{ Html::script('assets/js/plugins/uploaders/fileinput.min.js') }}
    {{ Html::script('assets/js/pages/uploader_bootstrap.js') }}
    {{ Html::script('assets/js/plugins/ui/ripple.min.js') }}
    {{ Html::script('assets/js/moment.min.js') }}

    <!-- end: JS -->
    <script type="text/javascript">$.ajaxSetup({headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});</script>
    <script type="text/javascript">$(function(){NProgress.start()}),$(document).ajaxStart(function(){NProgress.set(.4),NProgress.start()}),$(document).ajaxStop(function(){NProgress.done()}),$(window).load(function(){NProgress.done()});</script>

    <?php if (isset($scripts)) footer_script($scripts); ?>

    @yield('footer_script', '<span class="label bg-danger">MISSING FOOTER SCRIPT</span>')

    {{ Html::script('assets/js/plugins/ui/ripple.min.js') }}

    @if (session()->has('messages'))
    <script type="text/javascript">new PNotify({text: '{!! session()->get("messages.text") !!}', type: '{!! session()->get("messages.type") !!}' });</script>
    @endif

</body>
</html>
@endspaceless