@extends ('auth.layout')

@section ('title') Register @stop

@section ('content')

<div class="container">
    <div class="col-md-4 col-md-offset-4" id="forgotForm" >
        <div class="logo">
            <img src="http://adtomatik.com/images/logo.png" alt="Adtomatik" />
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><strong>{{ Lang::get('auth.cuenta_creada'); }}</strong></h3></div>
            <div class="panel-body">
                <div class="alert alert-success">
                    <p><i class="fa fa-info-circle"></i> <b>{{ Lang::get('auth.gracias'); }}</b></p>
                    <p>{{ Lang::get('auth.confirmar_email'); }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@stop