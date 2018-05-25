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
                        <th>{{ Lang::get('reports.imps'); }}</th>
                        <th>{{ Lang::get('reports.clicks'); }}</th>
                        <th>{{ Lang::get('reports.ctr'); }}</th>
                        <th>{{ Lang::get('reports.cpm'); }}</th>
                        <th>{{ Lang::get('reports.revenue'); }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($reports['report']) {

                        $total['imps'] = $total['clicks'] = $total['ctr'] = $total['cpm'] = $total['revenue'] = 0;

                        foreach ($reports['report'] as $report) {
                            echo '<tr>';

                            if ($type === 'site_placement')
                                $sitio = $espacio = '';

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
                                } elseif ($column === 'imps' || $column === 'clicks') {
                                    echo number_format($report->$column, 0, '.', ',');
                                } elseif ($column === 'ctr') {
                                    echo number_format($report->$column, 2, '.', ',') . '%';
                                } elseif ($column === 'revenue' || $column === 'cpm') {
                                    echo '$ ' . number_format($report->$column, 2, '.', ',');
                                } else {
                                    if ($type === 'site_placement') {
                                        if ($column === 'site_name')
                                            echo $report->site_name . ' - ' . $report->placement_name;
                                    }elseif ($type === 'country_size') {
                                        if ($column === 'country_name')
                                            echo $report->country_name . ' - ' . $report->size_name;
                                    }else {
                                        echo $report->$column;
                                    }
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
                                if ($column === 'imps' || $column === 'clicks') {
                                    echo number_format($total[$column], 0, '.', ',');
                                } elseif ($column === 'ctr') {
                                    echo number_format((($total['clicks'] / $total['imps']) * 100), 2, '.', ',') . '%';
                                } elseif ($column === 'cpm') {
                                    echo '$ ' . number_format((($total['revenue'] / $total['imps']) * 100), 2, '.', ',');
                                } elseif ($column === 'revenue') {
                                    echo '$ ' . number_format($total[$column], 2, '.', ',');
                                } else {
                                    if ($type === 'site_placement') {
                                        if ($column === 'site_name')
                                            echo '-';
                                    }elseif ($type === 'country_size') {
                                        if ($column === 'country_name')
                                            echo '-';
                                    }else {
                                        echo Lang::get('reports.totales');
                                    }
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