<div class="page-content inset container-fluid">
    <div class="row">
        <h2>{{ Lang::get('admin.payments-monthly_incomes') }}</h2>
    </div>

    
    <div class="panel-body">
        @include('admin.tables.tbl_ingresosMensuales', ['earnings' => $earnings])
    </div>
</div>
