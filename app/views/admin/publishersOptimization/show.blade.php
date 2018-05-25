<div class="panel">
	<div class="panel-heading"><h4>{{ $optimization->getPublisherName() }}</h4></div>
    <div class="panel-body">
                <div class="col-md-6"><span class="floatRight">Publisher</span></div><div class="col-md-6">{{ $optimization->getPublisherName() }}</div>
                <div class="col-md-6"><span class="floatRight">Sitio</span></div><div class="col-md-6">{{ $optimization->getSiteName() }}</div>
                <div class="col-md-6"><span class="floatRight">Espacio</span></div><div class="col-md-6">{{ $optimization->getPlacementName() }}</div>
                <div class="col-md-6"><span class="floatRight">Pa&iacute;s</span></div><div class="col-md-6">{{ Lang::get('countries.'.$optimization->getCountryName()) }}</div>
                <div class="col-md-6"><span class="floatRight">Ejecutivo</span></div><div class="col-md-6">
                    @if($optimization->publisher->mediaBuyer)
                    {{ $optimization->publisher->mediaBuyer->user->profile->getName() }}
                    @else
                    Sin Asignar
                    @endif
                </div>
                <div class="col-md-12"><hr /></div>
		<div class="col-md-6"><span class="floatRight">Revenue Share Anterior</span></div><div class="col-md-6">{{ number_format($optimization->getPublisherShare(), 2) }} %</div>
		<div class="col-md-6"><span class="floatRight">Revenue Share Nuevo</span></div><div class="col-md-6">{{ number_format($optimization->getPublisherDueShare(), 2) }} %</div>
		<div class="col-md-6"><span class="floatRight">Profit Ajustado Anterior</span></div><div class="col-md-6">${{ number_format($optimization->getProfitAdjusted(), 3) }}</div>
		<div class="col-md-6"><span class="floatRight">Profit Ajustado Nuevo</span></div><div class="col-md-6">${{ number_format($optimization->getNewAjustedProfit(), 3) }}</div>
                @if($optimized)
                <div class="col-md-12"><hr /></div>
                <div class="col-md-6"><span class="floatRight">&Uacute;ltima optimizaci&oacute;n</span></div><div class="col-md-6">{{ date('d/m/Y',strtotime($optimized->optimized_date)) }}</div>
                <div class="col-md-6"><span class="floatRight">Sobre los datos de</span></div><div class="col-md-6">{{ date('d/m/Y',strtotime('-1 day '.$optimized->optimized_date)) }}</div>
                @endif
	</div>
</div>

<script>
$(window).bind("load", function() {
	
});
function actualize(id){
        $.ajax({
            url: 'optimize_publisher/'+id,
            type: 'GET',
            dataType: "html",
            success: function(result) {
            	if(result["error"] != undefined)
                	alert(result["error"]);
                else
                	location.reload();
            }
        });
        return false;
}
</script>