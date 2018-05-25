<?php 
set_time_limit(0);
ini_set('post_max_size', '99999M');
ini_set('upload_max_filesize', '999999M');
ini_set('memory_limit', '999999M');
ini_set('max_execution_time', '99999');
ini_set('max_input_time', '99999');
$admin = Administrator::find(Session::get('admin.id'));
?>
<table class="table table-hover report" style="white-space: nowrap !important; font-size: 11px !important;">
    <thead>
        <tr>                        
            <th>{{ Lang::get('general.'.$type); }}</th>
            <th>{{ Lang::get('reports.imps'); }}</th>
            <th>{{ Lang::get('reports.clicks'); }}</th>
            <th>{{ Lang::get('reports.ctr'); }}</th>
            <th>{{ Lang::get('reports.cpm'); }}</th>
            <th>{{ Lang::get('reports.revenue'); }}</th>
            @if(Utility::hasPermission('affiliate_revenue'))
            <th style="white-space: normal !important;">{{ Lang::get('admin.inventory-my_affiliate_revenue') }}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($report as $row)
        <tr>
            <td>
                @if($type == 'country')
                {{ Lang::get('countries.'.$row->column) }}
                @else
                {{ $row->column }}
                @endif
            </td>
            <td>{{ number_format($row->imps, 0, ',', '.') }}</td>
            <td>{{ number_format($row->clicks, 0, ',', '.') }}</td>
            <td>{{ number_format($row->ctr, 2, ',', '.') }}</td>
            <td>{{ '$ '.number_format($row->cpm, 2, ',', '.') }}</td>
            <td>{{ '$ '.number_format($row->revenue, 2, ',', '.') }}</td>
            @if(Utility::hasPermission('affiliate_revenue'))
            <td>{{ '$ '.number_format($row->revenue/100*$admin->getRevenueShare(), 2, ',', '.') }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td> --- </td>
            <td>{{ number_format($totals['imps'], 0, ',', '.') }}</td>
            <td>{{ number_format($totals['clicks'], 0, ',', '.') }}</td>
            <td>{{ number_format($totals['ctr'], 2, ',', '.') }}</td>
            <td>{{ '$ '.number_format($totals['cpm'], 2, ',', '.') }}</td>
            <td>{{ '$ '.number_format($totals['revenue'], 2, ',', '.') }}</td>
            @if(Utility::hasPermission('affiliate_revenue'))
            <td>{{ '$ '.number_format($totals['revenue']/100*$admin->getRevenueShare(), 2, ',', '.') }}</td>
            @endif
        </tr>
    </tfoot>
</table>

<script>
    $(document).ready(function(){
        $('.report').dataTable({
            'paging':   false,
            'info':     false,
            'bFilter':  true,
            'sScrollY': 450,
            'sScrollX': "100%",
        });
    });
</script>