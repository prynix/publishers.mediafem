@extends ('emails.layout')

@section ('title') AdUnits Excluidos @stop

@section ('content')

@if(count($excluded)>0)
@if(!$error)
<p>Se excluyeron los siguientes AdUnits del Line Item:</p>
@else
<p>(Hubieron problemas con la API) Excluir <u>MANUALMENTE</u> los siguientes AdUnits del Line Item:</p>
@endif
<br/>
@foreach($excluded as $key=>$adunit)
<ul>
    <li>Id:  <b>{{ $key }}</b></li>
    <li>Nombre:  <b>{{ $adunit['name'] }}</b></li>
    <li>Imps:  <b>{{ $adunit['imps'] }}</b></li>
    <li>Clics:  <b>{{ $adunit['clicks'] }}</b></li>
    <li>CTR:  <b>{{ $adunit['ctr'] }}%</b></li>
</ul>    
<br />
@endforeach
<hr />
@endif
@if(count($high_ctr)>0)
<p>Los siguientes AdUnits simplemente tienen un CTR alto <br />(no fueron excluidos por su baja cantidad de impresiones):</p>
@foreach($high_ctr as $key=>$adunit)
<ul>
    <li>Id:  <b>{{ $key }}</b></li>
    <li>Nombre:  <b>{{ $adunit['name'] }}</b></li>
    <li>Imps:  <b>{{ $adunit['imps'] }}</b></li>
    <li>Clics:  <b>{{ $adunit['clicks'] }}</b></li>
    <li>CTR:  <b>{{ $adunit['ctr'] }}%</b></li>
</ul>    
<br />
@endforeach
@endif
@stop