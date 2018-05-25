@extends ('general.layout')

@section ('title') @parent {{ Lang::get('escritorio.escritorio'); }} @stop

@section ('section-title') {{ Lang::get('escritorio.escritorio'); }} @stop

@section ('content')

<div class="row">
    <h2>{{ Lang::get('escritorio.ingresos_estimados'); }}.</h2>
    <div class="col-md-3">
        <div class="widget revenue-widget">
            <div class="value">$ <span id="revenue_yesterday"></span></div>
            <div class="period">{{ Lang::get('escritorio.ayer'); }}</div>
            <div class="report">
                <a href="/report/day/yesterday">{{ Lang::get('escritorio.ver_reporte'); }} <i class="fa fa-angle-double-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="widget revenue-widget">
            <div class="value">$ {{ number_format($report && $report->revenue != '' ? ($report->revenue+$imonomyRevenue) : 0, 2, '.', ',') }}</div>
            <div class="period">{{ Lang::get('escritorio.durante_mes'); }}</div>
            <div class="report">
                <a href="/report/day/month_to_date">{{ Lang::get('escritorio.ver_reporte'); }} <i class="fa fa-angle-double-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="widget revenue-widget">
            <div class="value">$  <span id="revenue_last_month"></span></div>
            <div class="period">{{ Lang::get('escritorio.mes_pasado'); }}: {{ Lang::get('meses.' . date('m', strtotime('now - 1 month'))); }}</div>
            <div class="report">
                <a href="/report/day/last_month">{{ Lang::get('escritorio.ver_reporte'); }} <i class="fa fa-angle-double-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="widget revenue-widget">
            <div class="value">$ <span id="actual_balance"></span></div>
            <div class="period">{{ Lang::get('escritorio.saldo_acumulado'); }}</div>
            <div class="report">
                <a href="/payments">{{ Lang::get('escritorio.ver_pagos'); }} <i class="fa fa-angle-double-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <h2>{{ Lang::get('escritorio.rendimiento_mes'); }}.<a href="/report/day/month_to_date" class="more">{{ Lang::get('escritorio.ver_reporte'); }} <i class="fa fa-angle-double-right"></i></a></h2>

    <div class="col-md-12">
        <div class="widget">
            <div id="graphic_revenue" style="padding-right: 15px !important;"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="widget data-widget">
            <div class="value">{{ number_format($report && $report->imps != '' ? $report->imps : 0, 0, '.', ',') }}</div>
            <div class="period">{{ Lang::get('reports.imps'); }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="widget data-widget">
            <div class="value">{{ number_format($report && $report->clicks != '' ? $report->clicks : 0, 0, '.', ',') }}</div>
            <div class="period">{{ Lang::get('reports.clicks'); }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="widget data-widget">
            <div class="value">{{ number_format($report && $report->ctr != '' ? $report->ctr : 0, 2, '.', ',') }}%</div>
            <div class="period">{{ Lang::get('reports.ctr'); }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="widget data-widget">
            <div class="value">$ {{ number_format($report && $report->cpm != '' ? $report->cpm : 0, 2, '.', ',') }}</div>
            <div class="period">{{ Lang::get('reports.cpm'); }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="widget data-widget">
            <div class="value">$ {{ number_format($report && $report->revenue != '' ? $report->revenue : 0, 2, '.', ',') }}</div>
            <div class="period">{{ Lang::get('reports.ingresos_mes'); }}</div>
        </div>
    </div>
</div>

<script>
    $(window).bind("load", function() {
        $('#graphic_revenue').html(loader).load('/report_graph/month_to_date/day');
        @if(Session::get('user.completeData'))
            $('#revenue_yesterday').html(loader).load('/revenue_by_date/yesterday');
            $('#revenue_last_month').html(loader).load('/revenue_by_date/last_month');
            $('#actual_balance').html(loader).load('/actual_balance');
        @else
            $('#revenue_yesterday').html('0.00');
            $('#revenue_last_month').html('0.00');            
            $('#actual_balance').html('0.00');  
        @endif
    });
</script>

@stop