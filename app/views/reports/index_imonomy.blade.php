@extends ('general.layout')

@section ('title') @parent Imonomy - {{ Lang::get('general.reportes'); }} @stop

@section ('section-title') Imonomy - {{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.' . $type); }} @stop

@section ('content')

<div class="row">
    <h2>{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.' . $type); }}.</h2>
    <div class="col-md-12">
        <div id="reportrange" class="btn btn-default btn-marginR20 pull-left">
            <i class="fa fa-calendar"></i>
            <!--<span>{{ date("F j, Y", strtotime("-30 days")) . ' - ' . date("F j, Y"); }}</span> <i class="caret"></i>-->
            <span></span> <i class="caret"></i>
        </div>

        <a id="exportExcel" href="/report/export_imonomy/{{ $type }}/{{ $interval }}/excel" class="btn btn-default btn-marginR20"><i class="fa fa-file-excel-o"></i> {{ Lang::get('reports.export_to') }} Excel</a>
        <a id="exportPdf" href="/report/export_imonomy/{{ $type }}/{{ $interval }}/pdf" class="btn btn-default btn-marginR20"><i class="fa fa-file-pdf-o"></i> {{ Lang::get('reports.export_to') }} PDF</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <div id="graphic_revenue" style="padding-right: 15px !important;"></div>

            <div id="tbl_reportes"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        /*@if($interval !== 'today')
            @if($type === 'country')
                $('#graphic_revenue').html(loader).load('/report_graph_map/{{ $interval }}');
            @else
                $('#graphic_revenue').html(loader).load('/report_graph/{{ $interval }}/{{ $type }}');
            @endif
        @endif*/
        
        $('#tbl_reportes').html(loader).load('/report_imonomy_table/{{ $type }}/{{ $interval }}');        
        
        moment.lang('{{ Session::get("user.language"); }}');
        
        
        @if($interval === 'yesterday')
            var startDate = moment().subtract('days', 1);
            var endDate = moment().subtract('days', 1);
        @elseif($interval === 'month_to_date')
            var startDate = moment().startOf('month');
            var endDate = moment().endOf('month');
        @elseif($interval === 'last_month')
            var startDate = moment().subtract('month', 1).startOf('month');
            var endDate = moment().subtract('month', 1).endOf('month');
        @endif
        
        $('#reportrange span').html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        
        $('#reportrange').daterangepicker({
            ranges: {
                '{{ Lang::get("reports.today"); }}': [moment(), moment()],
                '{{ Lang::get("reports.yesterday"); }}': [moment().subtract('days', 1), moment().subtract('days', 1)],
                '{{ Lang::get("reports.last_7_days"); }}': [moment().subtract('days', 6), moment()],
                '{{ Lang::get("reports.last_30_days"); }}': [moment().subtract('days', 29), moment()],
                '{{ Lang::get("reports.this_month"); }}': [moment().startOf('month'), moment().endOf('month')],
                '{{ Lang::get("reports.last_month"); }}': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            startDate: startDate,
            endDate: endDate,
            format: 'MMMM D, YYYY',
            locale: {
                applyLabel: '{{ Lang::get("general.enviar"); }}',
                cancelLabel: '{{ Lang::get("general.borrar"); }}',
                fromLabel: '{{ Lang::get("general.desde"); }}',
                toLabel: '{{ Lang::get("general.hasta"); }}',
                customRangeLabel: '{{ Lang::get("general.fecha_especifica"); }}',
                daysOfWeek: ['{{ Lang::get("dias.domingo"); }}', '{{ Lang::get("dias.lunes"); }}', '{{ Lang::get("dias.martes"); }}', '{{ Lang::get("dias.miercoles"); }}', '{{ Lang::get("dias.jueves"); }}', '{{ Lang::get("dias.viernes"); }}', '{{ Lang::get("dias.sabado"); }}'],
                monthNames: ['{{ Lang::get("meses.01"); }}', '{{ Lang::get("meses.02"); }}', '{{ Lang::get("meses.03"); }}', '{{ Lang::get("meses.04"); }}', '{{ Lang::get("meses.05"); }}', '{{ Lang::get("meses.06"); }}', '{{ Lang::get("meses.07"); }}', '{{ Lang::get("meses.08"); }}', '{{ Lang::get("meses.09"); }}', '{{ Lang::get("meses.10"); }}', '{{ Lang::get("meses.11"); }}', '{{ Lang::get("meses.12"); }}'],
                firstDay: 1
            }
        },
        function(start, end) {        
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));            
            
            if(start.format('YYYY-M-D') !== '{{ date("Y-n-j") }}'){
                @if($type === 'country')
                    $('#graphic_revenue').html(loader).load('/report_graph_map/' + start.format('YYYY-M-D') + '-to-' +  end.format('YYYY-M-D'));
                @else
                    $('#graphic_revenue').html(loader).load('/report_graph/' + start.format('YYYY-M-D') + '-to-' +  end.format('YYYY-M-D') + '/{{ $type }}');
                @endif
            }else{
                $('#graphic_revenue').html('');
            }
                   
            var url = '/report_imonomy_table/{{ $type }}/' + start.format('YYYY-M-D') + '-to-' +  end.format('YYYY-M-D');
            $('#tbl_reportes').html(loader).load(url);
            
            $('#exportExcel').attr('href', '/report/export_imonomy/{{ $type }}/' + start.format('YYYY-M-D') + '-to-' +  end.format('YYYY-M-D') + '/excel');
            $('#exportPdf').attr('href', '/report/export_imonomy/{{ $type }}/' + start.format('YYYY-M-D') + '-to-' +  end.format('YYYY-M-D') + '/pdf');
        });
    });
</script>

@stop