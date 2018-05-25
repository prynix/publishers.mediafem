@extends ('emails.layout')

@section ('title') Nuevo sitio @stop

@section ('content')

<p>El publisher <b>{{ $site->publisher->getName() }}</b> ha agregado un nuevo sitio:</p>
<br/><br/>
<p>URL: <b>{{ $site->getName() }}</b></p>
<p>Id {{ $adserverName }}: <b>{{ $adserverKey }}</b></p>        
<p>Email del publisher: <b>{{ $site->publisher->user->getEmail() }}</b></p>
@stop