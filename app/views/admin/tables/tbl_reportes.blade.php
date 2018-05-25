<table class="table table-hover report">
    <thead>
        <tr>                        
            <th>{{ Lang::get('general.' . $type); }}</th>
            <th>{{ Lang::get('reports.imps'); }}</th>
            <th>{{ Lang::get('reports.clicks'); }}</th>
            <th>{{ Lang::get('reports.ctr'); }}</th>
            <th>{{ Lang::get('reports.cpm'); }}</th>
            <th>{{ Lang::get('reports.revenue'); }}</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total['imps'] = $total['clicks'] = $total['ctr'] = $total['cpm'] = $total['revenue'] = 0;

        foreach ($reports['report'] as $report) {
            echo '<tr>';

            if ($type === 'site_placement')
                $sitio = $espacio = '';

            foreach ($reports['columns'] as $column) {
                if ($column === 'imps' || $column === 'clicks' || $column === 'revenue')
                    $total[$column] += $report->$column;

                if ($type === 'day' && $column === 'day') {
                    echo '<td>' . $report->$column . '</td>';
                } elseif ($type === 'month' && $column === 'day') {
                    echo '<td>' . Lang::get('meses.' . date("m", strtotime($report->$column))) . ' - ' . date("Y", strtotime($report->$column)) . '</td>';
                } elseif ($column === 'imps' || $column === 'clicks') {
                    echo '<td>' . number_format($report->$column, 0, '.', ',') . '</td>';
                } elseif ($column === 'ctr') {
                    echo '<td>' . number_format($report->$column, 2, '.', ',') . '%</td>';
                } elseif ($column === 'revenue' || $column === 'cpm') {
                    echo '<td>$ ' . number_format($report->$column, 2, '.', ',') . '</td>';
                } else {
                    if ($type === 'site_placement') {
                        if ($column === 'site_name')
                            echo '<td>' . $report->site_name . ' - ' . $report->placement_name . '</td>';
                    }elseif ($type === 'country_size') {
                        if ($column === 'country_name')
                            echo '<td>' . $report->country_name . ' - ' . $report->size_name . '</td>';
                    }else {
                        echo '<td>' . $report->$column . '</td>';
                    }
                }
            }

            echo '</tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <?php
            foreach ($reports['columns'] as $column) {
                if ($column === 'imps' || $column === 'clicks') {
                    echo '<td>' . number_format($total[$column], 0, '.', ',') . '</td>';
                } elseif ($column === 'ctr') {
                    echo '<td>' . number_format((($total['clicks'] / $total['imps']) * 100), 2, '.', ',') . '%</td>';
                } elseif ($column === 'cpm') {
                    echo '<td>$ ' . number_format((($total['revenue'] / $total['imps']) * 100), 2, '.', ',') . '</td>';
                } elseif ($column === 'revenue') {
                    echo '<td>$ ' . number_format($total[$column], 2, '.', ',') . '</td>';
                } else {
                    if ($type === 'site_placement') {
                        if ($column === 'site_name')
                            echo '<td>-</td>';
                    }elseif ($type === 'country_size') {
                        if ($column === 'country_name')
                            echo '<td>-</td>';
                    }else {
                        echo '<td>-</td>';
                    }
                }
            }
            ?>
        </tr>
    </tfoot>
</table>

<script>
    $(document).ready(function(){
        $('.report').dataTable({
            'paging':   false,
            'info':     false,
            'bFilter':  false,
            @if ($type === 'placement' || $type === 'site' || $type === 'country')
                'aaSorting': [[ {{ sizeof($reports['columns']) - 1 }}, 'desc' ]]
            @elseif ($type === 'site_placement')
            'aaSorting': [[ {{ sizeof($reports['columns']) - 2 }}, 'desc' ]]
            @else
                'aaSorting': [[0, 'asc' ]]
            @endif
        });
    });
</script>