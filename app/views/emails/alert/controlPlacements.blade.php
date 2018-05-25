@extends ('emails.layout')

@section ('title') Control de Placements @stop

@section ('content')

<p><i>{{ $executive }}</i></p>
<br/>
    @foreach($placements as $placement)
        <ul>
            <li>Publisher:  <b>{{ $placement['publisher'] }}</b></li>
            <li>Site: <b>{{ $placement['site'] }}</b></li>
            <li>Placement: <b>{{ $placement['placement'] }}</b></li>
            <li>Formato: <b>{{ $placement['formato'] }}</b></li>
            <li><u>{{ $placement['status'] }}</u></li>
            <li><i>{{ $placement['razon'] }}</i></li>
        </ul>  
    <br />
    @endforeach
@stop