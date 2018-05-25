<table class="table table-hover report">
    <thead>
        <tr>                        
            <th>{{ Lang::get('general.' . $type); }}</th>
            <th>{{ Lang::get('reports.views'); }}</th>
            <th>{{ Lang::get('reports.imps'); }}</th>
            <th>{{ Lang::get('reports.ecpm'); }}</th>
            <th>{{ Lang::get('reports.revenue'); }}</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total['views'] = $total['imps'] = $total['ecpm'] = $total['revenue'] = 0;

        foreach ($reports['report'] as $report) {
            echo '<tr>';

            foreach ($reports['columns'] as $column) {
                if ($column === 'views' || $column === 'imps' || $column === 'revenue')
                    $total[$column] += $report->$column;

                if ($type === 'day' && $column === 'day') {
                    echo '<td>' . $report->$column . '</td>';
                } elseif ($type === 'month' && $column === 'day') {
                    echo '<td>' . Lang::get('meses.' . date("m", strtotime($report->$column))) . ' - ' . date("Y", strtotime($report->$column)) . '</td>';
                } elseif ($column === 'views' || $column === 'imps') {
                    echo '<td>' . number_format($report->$column, 0, '.', ',') . '</td>';
                } elseif ($column === 'revenue' || $column === 'ecpm') {
                    echo '<td>$ ' . number_format($report->$column, 2, '.', ',') . '</td>';
                } else {
                    if ($type === 'site' && $column === 'site_name') {
                        echo '<td>' . $report->site_name . '</td>';
                    } elseif ($type === 'country' && $column === 'country_name') {
                        echo '<td>' . $report->country_name . '</td>';
                    } else {
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
                if ($column === 'imps' || $column === 'views') {
                    echo '<td>' . number_format($total[$column], 0, '.', ',') . '</td>';
                } elseif ($column === 'cpm') {
                    echo '<td>$ ' . number_format((($total['revenue'] / $total['imps']) * 1000), 2, '.', ',') . '</td>';
                } elseif ($column === 'revenue') {
                    echo '<td>$ ' . number_format($total[$column], 2, '.', ',') . '</td>';
                } else {
                    echo '<td>-</td>';
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
            @ else
            'aaSorting': [[0, 'asc' ]]
            @endif
    });
    });
</script>