<div class="panel">
	<div class="panel-heading"><h4>{{ $optimization->publisher->getName() }}</h4></div>
    <div class="panel-body">
                <div class="col-md-6"><span class="floatRight">Publisher</span></div><div class="col-md-6">{{ $optimization->publisher->getName() }}</div>
                <div class="col-md-6"><span class="floatRight">Sitio</span></div><div class="col-md-6">{{ $optimization->site->getName() }}</div>
                <div class="col-md-6"><span class="floatRight">Espacio</span></div><div class="col-md-6">{{ $optimization->placement->getName() }}</div>
                <div class="col-md-6"><span class="floatRight">Pa&iacute;s</span></div><div class="col-md-6">{{ Lang::get('countries.'.$optimization->country->cnt_id) }}</div>
                <div class="col-md-6"><span class="floatRight">Ejecutivo</span></div><div class="col-md-6">
                    @if($optimization->publisher->mediaBuyer)
                    {{ $optimization->publisher->mediaBuyer->user->profile->getName() }}
                    @else
                    Sin Asignar
                    @endif
                </div>
                @if($optimized)
                <div class="col-md-12"><hr /></div>
                <div class="col-md-6"><span class="floatRight">&Uacute;ltima optimizaci&oacute;n</span></div><div class="col-md-6">{{ date('d/m/Y',strtotime($optimized->optimized_date)) }}</div>
                <div class="col-md-6"><span class="floatRight">Sobre los datos de</span></div><div class="col-md-6">{{ date('d/m/Y',strtotime('-1 day '.$optimized->optimized_date)) }}</div>
                <div class="col-md-12"><hr /></div>
		<div class="col-md-6"><span class="floatRight">Revenue Share Anterior</span></div><div class="col-md-6">{{ number_format($optimized->previous_share, 2) }} %</div>
		<div class="col-md-6"><span class="floatRight">Revenue Share Nuevo</span></div><div class="col-md-6">{{ number_format($optimized->new_share, 2) }} %</div>
		<div class="col-md-6"><span class="floatRight">Profit Anterior</span></div><div class="col-md-6">${{ number_format($optimized->previous_profit, 2) }}</div>
		<div class="col-md-6"><span class="floatRight">Profit Nuevo</span></div><div class="col-md-6">${{ number_format($optimized->new_profit, 2) }}</div>
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