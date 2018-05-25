@extends ('auth.layout')

@section ('title') @parent Login @stop

@section ('content')

<div class="container">
    <div class="col-md-4 col-md-offset-4" id="loginForm" >
        <div class="logo">
            {{ HTML::image('images/color-'.$platform->logo(), $platform->brand()) }}
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><strong>Sign in </strong></h3></div>
            <div class="panel-body">

                @if( $messages )
                <div class="alert alert-danger">
                    <i class="fa fa-warning"></i> {{ $messages['message'] }}
                </div>
                @endif

                <form action="/login" method="POST">                    
                    <input type="hidden" name="platform" value="{{ $platform->id() }}" />  
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="example@domain.com" required="required" class="form-control" tabindex="1" />
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label> <a href="#" class="floatRight" data-toggle="modal" data-target="#forgotPassword" tabindex="5" >forgot password?</a>
                        <input type="password" name="password" id="password" placeholder="" required="required" class="form-control" tabindex="2" />
                    </div>
                    <div>
                        <input type="checkbox" name="remember" id="remember" value="1" tabindex="3" /> <label for="remember">Remember my password.</label>
                        <input type="submit" name="user_submit" value="Sign in" class="btn btn-primary floatRight" tabindex="4" />
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center">
            Do not have an account? @if($platform->id() == 2)<a href="/register_mf">@else<a href="/register">@endif sign up</a>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="forgotPassword" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Can not access your account?</h4>
            </div>
            <form action="/forgot_password" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email_forgot">Enter your Email Address:</label>
                        <input type="email" name="email_forgot" id="email_forgot" placeholder="example@domain.com" required="required" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" name="user_submit_forgot" value="Confirm" class="btn btn-primary" />
                </div>
            </form>
        </div>
    </div>
</div>
@stop