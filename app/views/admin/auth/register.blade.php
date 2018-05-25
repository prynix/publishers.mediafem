@extends ('auth.layout')

@section ('title') Register @stop

@section ('content')

<div class="container">
    <div class="col-md-4 col-md-offset-4" id="registerForm" >
        <div class="logo">
            <img src="http://adtomatik.com/images/logo.png" alt="Adtomatik" />
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><strong>{{ Lang::get('auth.registrarse'); }}</strong></h3></div>
            <div class="panel-body">

                @if( $messages )
                <div class="alert alert-danger">
                    <i class="fa fa-warning"></i> {{ $messages['message'] }}
                </div>
                @endif

                <form action="/register" method="POST">                    
                    <div class="form-group">
                        <label for="email">{{ Lang::get('auth.email'); }}</label>
                        <input type="email" name="email" id="email" placeholder="example@domain.com" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">{{ Lang::get('auth.contrasena'); }}</label>
                        <input type="password" name="password" id="password" placeholder="" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="repeatPassword" class="control-label">{{ Lang::get('auth.repetir_contrasena'); }}</label>
                        <input type="password" name="repeatPassword" id="repeatPassword" placeholder="" class="form-control" />
                    </div>
                    <div class="form-group">
                        <script type="text/javascript">
                            var RecaptchaOptions = {
                                theme : 'white'
                            };
                        </script>
                        {{ Form::captcha() }}
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="politicas" type="checkbox" value="politicas" name="politicas" id="politicas"/>
                                {{ Lang::get('auth.politicas'); }}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="user_submit" value="{{ Lang::get('auth.registrarse'); }}" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center">
            {{ Lang::get('auth.link_login'); }}
        </div>
        <div class="text-center">
            Copyright © 2009 - 2014 AdTomatik by MediaFem LLC.
        </div>
    </div>
</div>

<script>    
    $().ready(function(){
        $('form').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                email: {
                    validators: {
                        notEmpty: {},
                        emailAddress: {},
                        stringLength: {
                            min: 6,
                            max: 255
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {},
                        stringLength: {
                            min: 6,
                            max: 255
                        }
                    }
                },
                repeatPassword: {
                    validators: {
                        identical: {
                            field: 'password'
                        },
                        notEmpty: {},
                        stringLength: {
                            min: 6,
                            max: 255
                        }
                    }
                },
                politicas: {
                    validators: {
                        notEmpty: {
                            message: '{{ Lang::get("auth.aceptar_politicas"); }}'
                        }
                    }
                }
            }
        });
    });
</script>

@stop