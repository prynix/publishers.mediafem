@extends ('emails.layout')

@section ('title') Activar @stop

@section ('content')

<p>To create a new password click here or visit the following link: <a href="{{ $resetPassword_url }}" >{{ $resetPassword_url }}</a></p>

@stop