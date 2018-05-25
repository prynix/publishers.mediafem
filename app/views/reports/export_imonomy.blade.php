<div class="row">
    <h1>{{ Session::get('platform.brand') }}</h1>
</div>

<div class="row">
    <h2>{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.' . $type); }}.</h2>
</div>

<div class="row">
    <p>{{ Lang::get('reports.periodo_reporte') }} : {{ date('Y-m-d', strtotime($start_date)) }} {{ Lang::get('reports.al') }} {{ date('Y-m-d', strtotime($end_date)) }}.</p>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <div id="graphic_revenue" style="padding-right: 15px !important;"></div>

            <table class="table table-hover report" border="1">
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
                    if ($reports['report']) {

                        $total['views'] = $total['imps'] = $total['ecpm'] = $total['revenue'] = 0;

                        foreach ($reports['report'] as $report) {
                            echo '<tr>';

                            $align = 'left';
                            $width = '150px';

                            foreach ($reports['columns'] as $column) {

                                echo '<td style="width: ' . $width . '; text-align: ' . $align . ';">';

                                if ($column === 'imps' || $column === 'clicks' || $column === 'revenue')
                                    $total[$column] += $report->$column;

                                if ($type === 'day' && $column === 'day') {
                                    echo $report->$column;
                                } elseif ($type === 'month' && $column === 'day') {
                                    echo Lang::get('meses.' . date("m", strtotime($report->$column))) . ' - ' . date("Y", strtotime($report->$column));
                                } elseif ($column === 'imps' || $column === 'views') {
                                    echo number_format($report->$column, 0, '.', ',');
                                } elseif ($column === 'revenue' || $column === 'ecpm') {
                                    echo '$ ' . number_format($report->$column, 2, '.', ',');
                                } else {
                                    echo $report->$column;
                                }

                                echo '</td>';

                                $align = 'right';
                                $width = '100px';
                            }

                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <?php
                        $align = 'left';
                        $width = '150px';

                        foreach ($reports['columns'] as $column) {

                            echo '<td style="width: ' . $width . '; text-align: ' . $align . ';">';


                            if ($reports['report']) {
                                if ($column === 'imps' || $column === 'views') {
                                    echo '<td>' . number_format($total[$column], 0, '.', ',') . '</td>';
                                } elseif ($column === 'ecpm') {
                                    echo '<td>$ ' . number_format((($total['revenue'] / $total['imps']) * 1000), 2, '.', ',') . '</td>';
                                } elseif ($column === 'revenue') {
                                    echo '<td>$ ' . number_format($total[$column], 2, '.', ',') . '</td>';
                                } else {
                                    echo '<td>-</td>';
                                }
                            } else {
                                echo '-';
                            }

                            echo '</td>';

                            $align = 'right';
                            $width = '100px';
                        }
                        ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>