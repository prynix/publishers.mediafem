<div class="page-content inset container-fluid">
    <div class="row">
        <h2>{{ Lang::get('mis_pagos.historial_pagos'); }}.</h2>
    </div>

    <ul class="nav nav-tabs">
        <li class="active"><a id="pagosPublisher" href="#pagosFinalizados" data-toggle="tab">{{ Lang::get('mis_pagos.ingresos_finalizados'); }}</a></li>
        <li><a id="pagosPublisher" href="#pagosProceso" data-toggle="tab">{{ Lang::get('mis_pagos.pagos_proceso'); }}</a></li>
    </ul>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="pagosFinalizados">
                @include('admin.tables.tbl_pagosFinalizados', ['records' => $records])
            </div>

            <div class="tab-pane" id="pagosProceso">
                @include('admin.tables.tbl_pagosProceso', ['billings' => $billings])
            </div>
        </div>
    </div>
</div>

<script>
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs #pagosPublisher[href=#' + url.split('#')[1] + ']').tab('show');
    }
</script>