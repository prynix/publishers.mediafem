@if(count($commissions)<1)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> No hay comisiones de Media Buyers registrados.</div>
@else
<div class="panel">
        <div class="panel-heading">
        <button id="exportarMBC" class="btn btn-default btn-marginR20 floatRight"><i class="fa fa-file-excel-o"></i> Exportar Excel</button>
        <br /><br />
        {{ Forms::filters([['Media Buyer', 'filter_media_buyer'],
            ['Adserver', 'filter_adserver'],
            ['Mes', 'filter_month']
            ]) }}
        </div>
        <div class="panel-body">
        <table id="media_buyer_commissions-table" class="table table-condensed table-hover" style="white-space: nowrap !important; font-size: 11px !important;">
            <thead>
                <tr>
                    <th hidden="true">&nbsp;</th>
                    <th>Media Buyer</th>
                    <th>Adserver</th>
                    <th>Mes</th>
                    <th>Imps</th>
                    <th>Revenue</th>
                    <th>Cost</th>
                    <th>Profit</th>
                    <th hidden="true">Comisi&oacute;n</th>
                    <th>Booked Revenue</th>
                    <th>Resold Revenue</th>
                    <th>Profit Adjusted</th>
                    <th>Adjustment Usd</th>
                    <th>Adserving</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commissions as $commission)
                <tr>
                    <td hidden="true">{{ $commission->mbc_id }}</td>
                    <td>{{ $commission->administrator->user->getEmail() }}</td>
                    <td>{{ $commission->adserver->getName() }}</td>
                    <td>{{ $commission->getMonth() }}</td>
                    <td>{{ $commission->getImps() }}</td>
                    <td>{{ $commission->getRevenue() }}</td>
                    <td>{{ $commission->getCost() }}</td>
                    <td>{{ $commission->getProfit() }}</td>
                    <td hidden="true">{{ $commission->getCommission() }}</td>
                    <td>@if($commission->mbc_booked_revenue){{ $commission->mbc_booked_revenue }}@else&#8212;&#8212;@endif</td>
                    <td>@if($commission->mbc_reseller_revenue){{ $commission->mbc_reseller_revenue }}@else&#8212;&#8212;@endif</td>
                    <td>@if($commission->mbc_profit_adjusted){{ $commission->mbc_profit_adjusted }}@else&#8212;&#8212;@endif</td>
                    <td>@if($commission->mbc_adjustment_usd){{ $commission->mbc_adjustment_usd }}@else&#8212;&#8212;@endif</td>
                    <td>@if($commission->mbc_adserving){{ $commission->mbc_adserving }}@else&#8212;&#8212;@endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        addFilters("{{ Lang::get('admin.filters') }}");
        tableHighlightRow();
        $('#exportarMBC').click(function (e) {
            e.preventDefault();
            $('#media_buyer_commissions-table').tableExport({type: 'excel', escape: 'false', ignoreColumn: '[0, 8]'});
            return false;
        });
        var datatableMBC = $('#media_buyer_commissions-table').dataTable({
            "sDom": '<"top"i>rt<"bottom"lp><"clear">',
            'paging': false,
            'info': false,
            "bSort": true, //sorting
            "aaSorting": [[0, "desc"]], // default sort
            'bFilter': true,
            'bScrollCollapse': true,
            'sScrollY': "450px",
            'sScrollX': "100%",
        });
        datatableMBC.yadcf([
            {column_number: 1,
                filter_container_id: "filter_media_buyer",
                filter_reset_button_text: "&times;"},
            {column_number: 2,
                filter_container_id: "filter_adserver",
                filter_reset_button_text: "&times;"},
            {column_number: 3,
                filter_container_id: "filter_month",
                filter_reset_button_text: "&times;"}
        ]);
        $(datatableMBC).ready(function(){
            var select_last_month = "{{ $commissions[count($commissions)-1]->getMonth() }}";
            $('#yadcf-filter--media_buyer_commissions-table-3 [value="'+select_last_month+'"]').attr('selected', 'selected').change();
        });
    });
</script>
@endif