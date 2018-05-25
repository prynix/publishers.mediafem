@extends ('auth.layout')

@section ('title') Forgot Password @stop

@section ('content')

<div class="container">
    <div class="col-md-4 col-md-offset-4" id="forgotForm" >
        <div class="logo">
            <img src="http://adtomatik.com/images/logo.png" alt="Adtomatik" />
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><strong>Forgot Password</strong></h3></div>
            <div class="panel-body">
                <div class="alert alert-success">
                    <p><i class="fa fa-info-circle"></i> <b>¡PERFECTO!</b>.</p>
                    <p>Se envió un correo electrónico, al correo proporcionado, dando indicaciones de como proseguir para porder resetear su contraseña.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@stop