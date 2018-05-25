@extends ('admin.general.layout')

@section ('title') @parent {{ (Utility::hasPermission('affiliate_revenue') ? Lang::get('admin.general-affiliate_revenue') : Lang::get('admin.general-inventory')) }} @stop

@section ('section-title') {{ (Utility::hasPermission('affiliate_revenue') ? Lang::get('admin.general-affiliate_revenue') : Lang::get('admin.general-inventory')) }} @stop

@section ('content')

<div class="row">
    <h2>{{ Lang::get('admin.reporte_por') . ' ' . Lang::get('admin.inventory-' . $type); }}</h2>
    <div class="col-md-12">
        <p>* {{ Lang::get('admin.inventory-suggest') }}</p>
        @if(Utility::hasPermission('affiliate_revenue'))
        <?php $admin = Administrator::find(Session::get('admin.id')); ?>
        <p>* {{ Lang::get('admin.inventory-improve_affiliate_revenue', array('revenue' => $admin->getRevenueShare())) }}</p>
        @endif
        <div id="reportrange" class="btn btn-default btn-marginR20 pull-left">
            <i class="fa fa-calendar"></i>
            <span></span> <i class="caret"></i>
        </div>
        <button class="btn btn-default" id="execute" data-date="">{{ Lang::get('admin.inventory-execute') }}</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <div id="tbl_reportes"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {


        //$('#tbl_reportes').html(loader).load('/admin/report_table/{{ $type }}');        

        $('#execute').click(function () {
            $('#tbl_reportes').html(loader).load('/admin/report_table/{{ $type }}/' + $('#execute').attr('data-date'));
        });

        moment.lang('{{ Session::get("user.language"); }}');


        @if ($interval === 'yesterday')
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
        $('#execute').attr('data-date', startDate.format('YYYY-M-D') + '-to-' + endDate.format('YYYY-M-D'));
        $('#reportrange').daterangepicker({
            ranges: {
                //'{{ Lang::get("reports.today"); }}': [moment(), moment()],
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
        }, function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            $('#execute').attr('data-date', start.format('YYYY-M-D') + '-to-' + end.format('YYYY-M-D'));
        });


    });
</script>

@stop