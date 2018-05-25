<div class="page-content inset container-fluid">
    <div class="row">
        <h2>{{ Lang::get('admin.payments-payment_history') }}</h2>
    </div>

    
    <div class="panel-body">
        @include('admin.tables.tbl_pagosHistorial', ['payments' => $payments])
    </div>
</div>
