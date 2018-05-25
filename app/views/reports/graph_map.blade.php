@if(empty($reports["data"]))

<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('reports.sin_ingresos'); }}.</div>

@else
{{ HTML::script('js/map.js'); }}
{{ HTML::script('js/world.js'); }}

<div id="graphic"></div>

<script>
    function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
            r: parseInt(result[1], 16),
                    g: parseInt(result[2], 16),
                    b: parseInt(result[3], 16)
            } : null;
    }
    var color = "{{ Session::get('platform.color1') }}";
            alert('rgba(' + hexToRgb(color).r + ', ' + hexToRgb(color).g + ', ' + hexToRgb(color).b + ', 0.3)');
            $(document).ready(function(){

    $('#graphic').highcharts('Map', {
    chart: {
    marginTop: 0,
            zoomType: 'xy'
    },
            credits: {
            enabled: true,
                    href: "https://www.{{ Session::get('platform.name') }}.com",
                    text: "www.{{ Session::get('platform.name') }}.com"
            },
            title: {
            text: ''
            },
            subtitle: {
            text: ''
            },
            legend: {
            layout: 'vertical',
                    align: 'left',
                    verticalAlign: 'bottom'
            },
            colorAxis: {
            min: 1,
                    max: {{ $reports['max'] }},
                    minColor: 'rgba(' + hexToRgb(color).r + ', ' + hexToRgb(color).g + ', ' + hexToRgb(color).b + ', 0.3)',
                    maxColor: 'rgba(' + hexToRgb(color).r + ', ' + hexToRgb(color).g + ', ' + hexToRgb(color).b + ', 1)',
            },
            series : [{
            name: '{{ Lang::get("general.country") }}',
                    mapData: Highcharts.maps['custom/world'],
                    data: [{{ $reports['data'] }}],
                    joinBy: ['iso-a2', 'code'],
                    states: {
                    hover: {
                    color: color
                    }
                    }
            }]
    });
    });

</script>

@endif