@spaceless
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Makro CMS</title>
    <link rel="shortcut icon" href="{{ URL::asset('/favicon.ico') }}" />
    {{ Html::style(asset('assets/css/icons/icomoon/styles.css')) }}
    {{ Html::style(asset('assets/css/bootstrap.css')) }}
    {{ Html::style(asset('assets/css/core.css')) }}
    {{ Html::style(asset('assets/css/components.css')) }}
    {{ Html::style(asset('assets/css/colors.css')) }}
    {{ Html::style(asset('assets/css/login.css')) }}
    {{ Html::style(asset('assets/css/custom.css')) }}
    <script type="text/javascript">
        var redirect_before = ({!! env('REFRESH_LOGIN_BEFORE_SEC') !!}) * 1000;
        var time = (({!! env('SESSION_LIFETIME') !!} * 60000 ) - redirect_before);
        setTimeout(function() {
            location.reload();
        }, time);
    </script>
</head>
<body class="login-container">
    <div class="page-container">
        <div class="page-content">
            <div class="content-wrapper">
                @if($errors->any())
                    <div class="login-form">
                        <div class="alert alert-danger alert-bordered">
                            <button data-dismiss="alert" class="close">
                                &times;
                            </button>
                            <strong>ERROR!</strong> {{$errors->first()}}
                        </div>
                    </div>
                @endif
                <div id="login-form" class="content pb-20">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('assets/js/core/libraries/jquery/2.1.4/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/ui/ripple.min.js') }}"></script>
    <script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('\App\Http\Requests\LoginRequest', '#form-login') !!}
    @include('_footer_script')
</body>
</html>
@endspaceless