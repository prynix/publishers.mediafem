@extends ('emails.layout')

@section ('title') Nuevo Publisher @stop

@section ('content')

<p>Se ha creado el publisher <b>{{ $publisher->getName() }}</b></p>
<br/><br/>
<p>Id {{ $adserverName }}: <b>{{ $adserverKey }}</b></p>        
<p>Email: <b>{{ $email }}</b></p>
<p>Nombre: <b>{{ $profile->getName() }}</b></p>
@stop