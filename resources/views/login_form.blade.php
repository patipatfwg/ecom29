{{ Form::open([
    'autocomplete' => 'off',
    'class'        => 'form-login',
    'id'           => 'form-login',
    'url'          => '/',
]) }}

    <div class="panel panel-body login-form">
        <div class="text-center mb-20">
            <img src="{{ URL::asset('/logo.png') }}" alt="Logo">
        </div>
        <div class="text-center">
            <h5 class="content-group-lg">Login to your account</h5>
        </div>
        <div class="form-group has-feedback has-feedback-left">
            {{ Form::text('username', null, [
                'class'       => 'form-control',
                'placeholder' => 'Username',
                'maxlength'   => 30
            ]) }}
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
        </div>
        <div class="form-group has-feedback has-feedback-left">
            {{ Form::password('password', [
                'class'       => 'form-control',
                'placeholder' => 'Password',
                'maxlength'   => 30
            ]) }}
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>
        <div class="form-group">
            {{ Form::button('Login <i class="icon-arrow-right14 position-right"></i>', [
                'type'  => 'submit',
                'class' => 'btn bg-danger-600 btn-block loading'
            ]) }}
        </div>
    </div>
{{ Form::close() }}