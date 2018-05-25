@extends ('auth.layout')
<?php 
$es_mediafem="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"=='http://publishers.adtomatik.com/register/3';
if ($es_mediafem){
?>
@section ('title') MediaFem - Register for our publishers program @stop
<?php }else{?>
@section ('title') @parent Register for our publishers program @stop
<?php } ?>

@section ('content')

<div class="container">
    <div class="col-md-4 col-md-offset-4" id="registerForm" >
                <?php 
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$es_mediafem=$actual_link=='http://publishers.adtomatik.com/register/3';
	if ($actual_link=='http://publishers.adtomatik.com/register/3'){
?>
<div style="background: #EA3086;" class="logo">		<img src="https://sitios.mediafem.com/images/mediafem-blanco.png" alt="MediaFem">
</div>
<?php	

}else{
	?>    
<div class="logo">	{{ HTML::image('images/color-'.$platform->logo(), $platform->brand()) }}
        </div>
<?php }
	?>
	
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><strong>{{ Lang::get('auth.registrarse_gratis'); }}</strong></h3></div>
            <div class="panel-body">

                @if( $messages )
                <div class="alert alert-danger">
                    <i class="fa fa-warning"></i> {{ $messages['message'] }}
                </div>
                @elseif(Session::get('messages'))
                <div class="alert alert-danger">
                    <i class="fa fa-warning"></i> {{ Session::get('messages')['message'] }}
                </div>
                @endif

                <form action="/register" method="POST">
                    <input type="hidden" name="adserver" value="{{ $adserver }}" />                    
                    <input type="hidden" name="media_buyer" value="{{ $media_buyer }}" />                    
                    <input type="hidden" name="platform" value="{{ $platform->id() }}" />                    
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
                                
<?php if ($es_mediafem){ ?>
{{ Lang::get('auth.politicas', ['brand' => 'MediaFem']); }}
<?php }else{ ?>
{{ Lang::get('auth.politicas', ['brand' => $platform->brand()]); }}
<?php } ?>
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
            {{ Lang::get('auth.link_login'); }}@if($platform->id() == 2)<a href="/login_mf">@else<a href="/login">@endif log in</a>
        </div>
        <div class="text-center">
            Copyright © 2009 -<?php echo date("Y"); ?> -  MediaFem LLC.
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
                            message: '{{ Lang::get("auth.aceptar_politicas", ["brand" => $platform->brand()]); }}'
                        }
                    }
                }
            }
        });
    });
</script>

@stop
