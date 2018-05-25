@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general-optimization') }} @stop

@section ('section-title') {{ Lang::get('admin.general-optimization') }} @stop

@section ('content')

<?php
if($history){
    $startDate = $history[0]->created_at;
    $endDate = $history[count($history)-1]->created_at;
}else{
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d');
}
?>
<div class="page-content inset container-fluid">
    <ul class="nav nav-tabs">
        <li class="active"><a id="optimizationsTable" href="#tabTablePublisherOptimizacions" data-toggle="tab">Informe de Appnexus</a></li>
        <li><a id="optimizationsDfpTable" href="#tabTablePublisherDfpOptimizacions" data-toggle="tab">Informe de Dfp</a></li>
        <li><a id="history" href="#tabOptimizationHistory" data-toggle="tab" data-startDate="{{ $startDate }}" data-endDate="{{ $endDate }}">Historial de Optimizaci&oacute;n</a></li>
        <li><a id="paymentRules" href="#tabPaymentRules" data-toggle="tab">Payment Rules</a></li>
    </ul>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tabTablePublisherOptimizacions">
                <h4>Appnexus</h4>
                <p><i class="glyphicon glyphicon-hand-right"></i> Los publisher que se muestran en <b><span style="color: red;">rojo</span></b> fueron optimizados <u>EL D&Iacute;A DE LA FECHA</u> y los que están en <b><span style="color: blue;">azul</span></b> aún con 0% de share seguir&aacute;n dando perdida - deben ser desactivados.</p>
            <p><i class="glyphicon glyphicon-hand-right"></i> El resumen diario se env&iacute;a a {{$emails}}<br /><i>Agregar m&aacute;s emails desde <a href="http://publishers.adtomatik.com/admin/constants">Constantes</a>.</i></p>
                <br />
                <div class="btn btn-default btn-marginR20">
                    <i class="fa fa-calendar"></i>&nbsp;Datos obtenidos del d&iacute;a:&nbsp;
                    <select id="filter-by-day">
                        <option value="0" selected>Seleccione fecha</option>
                        @foreach($days as $day)
                        <option
                            @if($day->day == date('Y-m-d')) selected @endif
                            value="{{$day->day}}">{{date('d/m/Y', strtotime('-1 day '.$day->day))}}</option>
                        @endforeach
                    </select>
                    &nbsp;(Cada reporte persistir&aacute; a lo largo de {{ Constant::value('day_to_delete_publishers_optimization') }} d&iacute;as - <i>se puede modificar desde <a href="http://publishers.adtomatik.com/admin/constants">Constantes</a></i>)
                </div> <br /><hr />
                <div id="publishersOptimization_table_content"></div>
                <div class="row">
                    <div class="col-md-12" id="publisherOptimizeData"></div>
                </div>
            </div>
            
            <div class="tab-pane" id="tabTablePublisherDfpOptimizacions">
                <h4>Dfp</h4>
                <p><i class="glyphicon glyphicon-hand-right"></i> Los publisher que se muestran en <b><span style="color: red;">rojo</span></b> fueron optimizados <u>EL D&Iacute;A DE LA FECHA</u> y los que están en <b><span style="color: blue;">azul</span></b> aún con 0% de share seguir&aacute;n dando perdida - deben ser desactivados.</p>
            <p><i class="glyphicon glyphicon-hand-right"></i> El resumen diario se env&iacute;a a {{$emails}}<br /><i>Agregar m&aacute;s emails desde <a href="http://publishers.adtomatik.com/admin/constants">Constantes</a>.</i></p>
                <br />
                <div class="btn btn-default btn-marginR20">
                    <i class="fa fa-calendar"></i>&nbsp;Datos obtenidos del d&iacute;a:&nbsp;
                    <select id="filter-by-day-dfp">
                        <option value="0" selected>Seleccione fecha</option>
                        @foreach($daysDfp as $day)
                        <option
                            @if($day->day == date('Y-m-d')) selected @endif
                            value="{{$day->day}}">{{date('d/m/Y', strtotime('-1 day '.$day->day))}}</option>
                        @endforeach
                    </select>
                    &nbsp;(Cada reporte persistir&aacute; a lo largo de {{ Constant::value('day_to_delete_publishers_optimization') }} d&iacute;as - <i>se puede modificar desde <a href="http://publishers.adtomatik.com/admin/constants">Constantes</a></i>)
                </div> <br /><hr />
                <div id="publishersDfpOptimization_table_content"></div>
                <div class="row">
                    <div class="col-md-12" id="publisherDfpOptimizeData"></div>
                </div>
            </div>

            <div class="tab-pane" id="tabOptimizationHistory">
                <div id="reportrange" class="btn btn-default btn-marginR20">
                    <i class="fa fa-calendar"></i>
                    <span></span> <i class="caret"></i>
                </div>    
                <br />
                <br />
                <div id="tabHistory">
                    @include('admin.tables.tbl_optimizedPublisherHistory', ['history' => $history, 'range' => date('Y-m-d', strtotime($startDate)).'-to-'.date('Y-m-d', strtotime($endDate))])
                </div>
            </div>
            <div class="tab-pane" id="tabPaymentRules">
            </div>
        </div>
    </div>
</div>

<script>
    $(window).bind("load", function () {

        loadPublisherOptimizationTable();
        loadPublisherDfpOptimizationTable();
        loadPaymentRulesTable();
        
        //Appnexus Day Filter
        $('#filter-by-day').change(function () {
            $("#filter-by-day option:selected").each(function () {
                if ($(this).val() !== '0') {
                    loadPublisherOptimizationTable($(this).val());
                }
            });

        });
        
        //Dfp Day Filter
        $('#filter-by-day-dfp').change(function () {
            $("#filter-by-day-dfp option:selected").each(function () {
                if ($(this).val() !== '0') {
                    loadPublisherDfpOptimizationTable($(this).val());
                }
            });

        });

        //Load Appnexus Table
        function loadPublisherOptimizationTable(day) {
            $('#publishersOptimization_table_content').html(loader);
            var url = "";
            if (day === undefined) {
                url = "load_publishers_optimization_table";
            } else {
                url += "load_publishers_optimization_table/" + day;
            }
            $.ajax({
                url: url,
                type: 'GET',
                dataType: "html",
                success: function (result) {
                    $('#publishersOptimization_table_content').html(result);
                }
            });
            $('#publisherOptimizeData').html('');
        }
        
        //Load Dfp Table
        function loadPublisherDfpOptimizationTable(day) {
            $('#publishersDfpOptimization_table_content').html(loader);
            var url = "";
            if (day === undefined) {
                url = "load_publishers_optimization_dfp_table";
            } else {
                url += "load_publishers_optimization_dfp_table/" + day;
            }
            $.ajax({
                url: url,
                type: 'GET',
                dataType: "html",
                success: function (result) {
                    $('#publishersDfpOptimization_table_content').html(result);
                }
            });
            $('#publisherDfpOptimizeData').html('');
        }
        
        //Load Payment Rules Table
        function loadPaymentRulesTable() {
            $('#tabPaymentRules').html(loader).load('load_payment_rules_table');
        }
        
        //Data Range Picker - History
        var startDateHistory = moment($('#history').attr('data-startDate'));
        var endDateHistory = moment($('#history').attr('data-endDate'));
        var startDate = startDateHistory;
        var endDate = endDateHistory;
        $('#reportrange span').html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        $('#reportrange').daterangepicker({
            ranges: {
                'Optimizado Hoy': [moment(), moment()],
                'Optimizado Ayer': [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Optimizado Ultimos 7 dias': [moment().subtract('days', 6), moment()],
                'Optimizado Ultimos 30 dias': [moment().subtract('days', 29), moment()],
                'Optimizado Ultimo mes': [moment().startOf('month'), moment().endOf('month')],
                'Optimizado Mes Pasado': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            startDate: startDate,
            endDate: endDate,
            format: 'MMMM D, YYYY',
            locale: {
                applyLabel: 'Filtrar',
                cancelLabel: 'Borrar',
                fromLabel: 'Desde',
                toLabel: 'Hasta',
                customRangeLabel: 'Fecha especifica',
                daysOfWeek: ['{{ Lang::get("dias.domingo"); }}', '{{ Lang::get("dias.lunes"); }}', '{{ Lang::get("dias.martes"); }}', '{{ Lang::get("dias.miercoles"); }}', '{{ Lang::get("dias.jueves"); }}', '{{ Lang::get("dias.viernes"); }}', '{{ Lang::get("dias.sabado"); }}'],
                monthNames: ['{{ Lang::get("meses.01"); }}', '{{ Lang::get("meses.02"); }}', '{{ Lang::get("meses.03"); }}', '{{ Lang::get("meses.04"); }}', '{{ Lang::get("meses.05"); }}', '{{ Lang::get("meses.06"); }}', '{{ Lang::get("meses.07"); }}', '{{ Lang::get("meses.08"); }}', '{{ Lang::get("meses.09"); }}', '{{ Lang::get("meses.10"); }}', '{{ Lang::get("meses.11"); }}', '{{ Lang::get("meses.12"); }}'],
                firstDay: 1
            }
        },
        function (start, end) {
            $('#filter_by_publisher input').click();
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            var url = '/admin/history_optimization/' + start.format('YYYY-M-D') + '-to-' + end.format('YYYY-M-D');
            $('#tabHistory').html(loader).load(url);
        });
    });
</script>

@stop