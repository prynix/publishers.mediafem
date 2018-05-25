@extends ('emails.layout')

@section ('title') Publishers Optimizados @stop

@section ('content')

<p>Se han optimizado los siguientes publishers (<b>{{ $day }}</b>):</p>
<br/>
    @foreach($history as $optimized)
        <ul>
            <li>Reporte obtenido el dia:  <b>{{ date("d/m/Y", strtotime($optimized['date'])) }}</b>, sobre los datos del dia: <b>{{ date("d/m/Y", strtotime('-1 day '.$optimized['date'])) }}</b></li>
            <li>Media Buyer: <b>{{ $optimized['mediaBuyer'] }}</b></li>
            <li>Adserver: <b>{{ $optimized['adserver'] }}</b> - {{ $optimized['comments'] }}</li>
            <li>Publisher <b>{{ $optimized['publisher'] }}</b></li>
            <li>Site <b>{{ $optimized['site'] }}</b></li>
            <li>Placement <b>{{ $optimized['placement'] }}</b></li>
            <li>Pais <b>{{ $optimized['country'] }}</b></li>
            <li>Profit antes del cambio <b>${{ $optimized['previousProfit'] }}</b>, Profit estimativo despues del cambio <b>${{ $optimized['newProfit'] }}</b></li>
            <li>Share anterior <b>{{ $optimized['previousShare'] }}%</b>, Share nuevo <b>{{ floor($optimized['newShare']) }}%</b></li>
            @if( $optimized['newProfit'] < 0)
                <li><u>Debe ser desactivado!</u></li>
            @endif
        </ul>    
    <br />
    @endforeach
@stop