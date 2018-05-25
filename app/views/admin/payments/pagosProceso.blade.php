<div class="page-content inset container-fluid">
    <div class="row">
        <h2>{{ Lang::get('admin.payments-process_payments') }}</h2>
    </div>

    
    <div class="panel-body">
        @include('admin.tables.tbl_pagosProcesoTodos', ['allBillings' => $allBillings])
    </div>
</div>
