@extends ('general.layout')

@section ('title') @parent {{ Lang::get('general.mis_pagos'); }} @stop

@section ('section-title') {{ Lang::get('general.mis_pagos'); }} @stop

@section ('content')

<div class="page-content inset container-fluid">
    <div class="row">
        <h2>{{ Lang::get('mis_pagos.historial_pagos'); }}.</h2>
        <div class="col-md-12">
            <p>{{ Lang::get('mis_pagos.leyenda_1'); }}</p>
            <!--<p>{{ Lang::get('mis_pagos.leyenda_2'); }}</p>-->
        </div>
    </div>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#pagosFinalizados" data-toggle="tab">{{ Lang::get('mis_pagos.ingresos_finalizados'); }}</a></li>
        <li><a href="#pagosProceso" data-toggle="tab">{{ Lang::get('mis_pagos.pagos_proceso'); }}</a></li>
    </ul>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="pagosFinalizados">
                @include('tables.tbl_pagosFinalizados', ['records' => $records])
            </div>

            <div class="tab-pane" id="pagosProceso">
                @include('tables.tbl_pagosProceso', ['billings' => $billings])
            </div>
        </div>
    </div>
</div>

@include('modals.placements_new')

@stop