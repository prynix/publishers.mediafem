<?php
/*
echo '<pre>';
var_dump($reports);
echo '</pre>';
die();*/
?>

<table class="table table-hover report">
    <thead>
        <tr>                        
            <th>{{ Lang::get('general.' . $type); }}</th>
            <th>{{ Lang::get('reports.imps'); }}</th>
            <th>{{ Lang::get('reports.clicks'); }}</th>
            <?php if(isset($reports['columns']['ctr'])) {?>
                <th>{{ Lang::get('reports.ctr'); }}</th>
            <?php }?>
            <th>{{ Lang::get('reports.cpm'); }}</th>
            <th>{{ Lang::get('reports.revenue'); }}</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($reports['columns'] as $column) {

            if ($type === 'day' && $column === 'day') {
                echo '<td>-</td>';
            } elseif ($type === 'month' && $column === 'day') {
                echo '<td>-</td>';
            } elseif ($column === 'imps' || $column === 'clicks' || $column === 'views') {
                echo '<td>' . number_format(0, 0, '.', ',') . '</td>';
            } elseif ($column === 'ctr') {
                echo '<td>' . number_format(0, 2, '.', ',') . '%</td>';
            } elseif ($column === 'revenue' || $column === 'cpm' || $column === 'ecpm') {
                echo '<td>$ ' . number_format(0, 2, '.', ',') . '</td>';
            } else {
                if ($type === 'site_placement') {
                    if ($column === 'site_name')
                        echo '<td>-</td>';
                }else {
                    echo '<td>-</td>';
                }
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <?php
            foreach ($reports['columns'] as $column) {
                if ($column === 'imps' || $column === 'clicks') {
                    echo '<td>' . number_format(0, 0, '.', ',') . '</td>';
                } elseif ($column === 'ctr') {
                    echo '<td>' . number_format(0, 2, '.', ',') . '%</td>';
                } elseif ($column === 'cpm') {
                    echo '<td>$ ' . number_format(0, 2, '.', ',') . '</td>';
                } elseif ($column === 'revenue') {
                    echo '<td>$ ' . number_format(0, 2, '.', ',') . '</td>';
                } else {
                    if ($type === 'site_placement') {
                        if ($column === 'site_name')
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
            'bFilter':  false
        });
    });
</script>