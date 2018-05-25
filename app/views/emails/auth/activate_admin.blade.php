@extends ('emails.layout')

@section ('title') Activar @stop

@section ('content')

<p>Thank you for registering to AdTomatik advertising program for Publishers. In order to complete your account creation you must follow the link below:</p>

<p>Usuario: {{ $email }}</p>
<p>Password: {{ $password }}</p>

<p><a href="{{ $activation_url }}" >{{ $activation_url }}</a></p>

<p>To access your account, you must log in using your email {{ $email }} and the password you entered upon registration.</p>

<p>You can reply this email with your comments and suggestions on AdTomatik´s advertising program for publishers.</p>

@stop