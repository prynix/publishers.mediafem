@if(empty($reports["data"]))

    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('reports.sin_ingresos'); }}.</div>
    
@else

    <div id="graphic"></div>

    <script>
        $(document).ready(function(){      
            
            $('#graphic').highcharts({
                chart: {
                    marginTop: 0,
                    zoomType: 'xy'
                },

                title: {
                    text: ''
                },

                xAxis: {
                    categories: [{{ $reports['categories'] }}],
                    min: 0,
                    max: {{ $reports['max'] }}
                },

                yAxis: {
                    title: {text: ''},
                    plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                },

                tooltip: {
                    crosshairs: true,
                    valueDecimals: 2
                },

                credits: {
                    enabled: true,
                    href: "https://www.{{ Session::get('platform.name') }}.com",
                    text: "www.{{ Session::get('platform.name') }}.com"
                },

                rangeSelector : {
                    inputEnabled: $('#graphic').width() > 480,
                    selected : 1,
                    enabled : false
                },

                navigator : {
                    enabled : false
                },

                scrollbar : {
                    enabled: false
                },

                colors: ["{{ Session::get('platform.color1') }}"],           

                series : [{
                        name : '{{ Lang::get("reports.revenue"); }}',
                        type: 'area',
                        data : [{{ $reports['data'] }}],
                    }]
            });
        });
    </script>

@endif