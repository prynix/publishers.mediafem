@extends ('auth.layout')

@section ('title') Activate @stop

@section ('content')

<div class="container">
    <div class="col-md-4 col-md-offset-4" id="forgotForm" >
        <div class="logo">
            <img src="http://adtomatik.com/images/logo.png" alt="Adtomatik" />
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><strong>{{ Lang::get('auth.cuenta_activacion'); }}</strong></h3></div>
            <div class="panel-body">
                <div class="alert alert-success">
                    <p><i class="fa fa-info-circle"></i> <b>{{ Lang::get('auth.listo'); }}</b></p>
                    <p>{{ Lang::get('auth.puede_ingresar'); }}</p>
                    <p><a href="/admin/login">{{ Lang::get('auth.iniciar_sesion'); }}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

@stop