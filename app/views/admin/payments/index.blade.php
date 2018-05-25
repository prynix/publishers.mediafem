@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general-payments') }} @stop

@section ('section-title') {{ Lang::get('admin.general-payments') }} @stop

@section ('content')

<div class="page-content inset container-fluid">
    <div class="row">
        <h2>{{ Lang::get('admin.payments-'.$type.'-payments') }}</h2>
    </div>

    <ul class="nav nav-tabs">
        <li class="active"><a id="payments" href="#pagosProcesoTodos" data-toggle="tab">{{ Lang::get('admin.payments-process_payments') }}</a></li>
        <li><a id="payments" href="#ingresosMensuales" data-toggle="tab">{{ Lang::get('admin.payments-monthly_incomes') }}</a></li>
        <li><a id="payments" href="#pagosHistorial" data-toggle="tab">{{ Lang::get('admin.payments-payment_history') }}</a></li>
    </ul>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="pagosProcesoTodos"></div>

            <div class="tab-pane" id="ingresosMensuales"></div>

            <div class="tab-pane" id="pagosHistorial"></div>
        </div>
    </div>
</div>
<div class="widget" hidden="true" id="publisherDetail">
    @include('admin.payments.publisherPayments')
</div>

<script>
    $(window).bind("load", function() {
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs #payments[href=#' + url.split('#')[1] + ']').tab('show');
        }
        
        $('#pagosProcesoTodos').html(loader).load('/admin/pagosprocesotodos/{{ $type }}');
        $('#ingresosMensuales').html(loader).load('/admin/ingresosMensuales/{{ $type }}');
        $('#pagosHistorial').html(loader).load('/admin/pagosHistorial/{{ $type }}');
        
    });
</script>

@stop