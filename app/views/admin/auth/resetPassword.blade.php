@extends ('auth.layout')

@section ('title') Reset Password @stop

@section ('content')

<div class="container">
    <div class="col-md-4 col-md-offset-4" id="forgotForm" >
        <div class="logo">
            <img src="http://adtomatik.com/images/logo.png" alt="Adtomatik" />
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><strong>Reset Password</strong></h3></div>
            <div class="panel-body">

                @if( $messages )
                <div class="alert alert-danger">
                    <i class="fa fa-warning"></i> {{ $messages['message'] }}
                </div>
                @endif
                
                <form action="/reset_password" method="POST">
                    <input type="hidden" name="code" value="{{ $code }}" />
                    <input type="hidden" name="user_id" value="{{ $user_id }}" />
                    <div class="form-group">
                        <label for="password">Enter new password:</label>
                        <input type="password" name="password" id="password" required="required" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="repeat_password">Repeat new password:</label>
                        <input type="password" name="repeat_password" id="repeat_password" required="required" class="form-control" />
                    </div>
                    <input type="submit" name="user_submit_forgot" value="Confirm" class="btn btn-primary" />
            </form>
            </div>
        </div>
    </div>
</div>

@stop