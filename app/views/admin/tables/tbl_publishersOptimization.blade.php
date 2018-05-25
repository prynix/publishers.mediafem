@if(count($report)>0)
<p><i class="glyphicon glyphicon-hand-right"></i> Reporte obtenido el d&iacute;a {{ date('d/m/Y',strtotime($report[0]->created_at)) }}, sobre los datos del {{ date('d/m/Y',strtotime('-1 day '.$report[0]->created_at)) }}.</p>

<div class="panel panel-success">
    <div class="panel-heading" id="filtrosHead" style="cursor: pointer"><h4>+ Filtros</h4></div>
    <div class="panel-body" id="filtros" hidden="true">
        <div class="btn btn-default btn-marginR20">            
            Estado:&nbsp;
            <span id="filter_has_to_adjusted"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Ejecutivo de Cuentas:&nbsp;
            <span id="filter_publishers_media_buyer"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Acci&oacute;n sobre Rev Share del Publisher:&nbsp;
            <span id="filter_action_realiced"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Pa&iacute;s:&nbsp;
            <span id="filter_country"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Publisher:&nbsp;
            <span id="filter_publishers_publisher"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Site:&nbsp;
            <span id="filter_publishers_site"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Tama&ntilde;o:&nbsp;
            <span id="filter_publishers_size"></span>
        </div>
    </div>
</div>

<button id="exportar2" class="btn btn-success floatRight">Exportar Excel</button>
<table class="table row-border table-condensed" id="publishers-optimization-table" style="font-size: 10px !important;">
    <thead>
        <tr style="white-space: initial !important; font-weight: bold;">
            <th>&nbsp;</th>
            <th hidden="true">Accion</th>
            <th hidden="true">ID</th>
            <th hidden="true">Media Buyer</th>
            <th align="center">Publisher</th>
            <th hidden="true">Site</th>
            <th align="center">Placement</th>
            <th align="center" style="padding-left: 20px;">Country</th>
            <th align="center">Imps</th>
            <th align="center">Blank</th>
            <th align="center">Psa</th>
            <th align="center">Psa<br />Error</th>
            <th align="center">Default<br />Error</th>
            <th align="center">Default</th>
            <th align="center">Kept</th>
            <th align="center">Resold<br />Imps</th>
            <th align="center">RTB</th>
            <th align="center">Revenue</th>
            <th align="center">Resold<br />Rev</th>
            <th align="center">Cost</th>
            <th align="center">Profit</th>
            <th align="center">AdServing</th>
            <th align="center">Bid<br />Reduction</th>
            <th align="center">Ajuste<br />CPM</th>
            <th align="center">Ajuste<br />USD</th>
            <th align="center">Profit<br />Ajustado</th>
            <th align="center"  hidden="true" >Profit<br />AdServing</th>
            <th align="center">Actual<br />Revenue Share</th>
            <th align="center">Adtomatik<br />Profit Actual</th>
            <th align="center">Profit Ajustado<br />Conveniente</th>
            <th align="center">Publisher Share<br />Nuevo</th>
            <th align="center">Nuevo Profit<br />Ajustado</th>
            <th align="center">Adtomatik<br />Nuevo Profit</th>
            <th align="center">Action</th>
            <th hidden="true">&Uacute;ltima<br />Optimizaci&oacute;n</th>
            <th hidden="true">Acci&oacute;n sobre share de publisher</th>
            <th hidden="true">Size</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report as $row)
        <tr name="fila" 
            @if($row->hasToAdjust) 
                @if($row->hasToBeDisabled) 
                    style="cursor: pointer; font-weight: bold; color: blue;"
                @else 
                    style="cursor: pointer; font-weight: bold; color: red;"
                @endif
            @else
                @if($row->hasToBeDisabled)
                    style="cursor: pointer; font-weight: bold; color: blue;"
                @else 
                    style="cursor: pointer;"
                @endif
            @endif >
            <td><a data-ID="{{ $row->getID() }}"  class="optimizationShow" href="#">Ver</a></td>
            <td hidden="true">
            @if($row->hasToAdjust()) 
                @if($row->hasToBeDisabled) 
                    Se debe desactivar
                @else 
                    Se optimiz&oacute; hoy
                @endif
            @else
                @if($row->hasToBeDisabled)
                    Se debe desactivar
                @else 
                    OK
                @endif
            @endif
            </td>
            <td hidden="true">{{ $row->getPublisherId() }}</td>
            
            @if($row->publisher->mediaBuyer)
            <td hidden="true">{{ $row->publisher->mediaBuyer->user->profile->getName() }}</td>
            @else
            <td hidden="true">Sin Asignar</td>
            @endif
            
            <td>{{ $row->getPublisherName() }}</td>
            <td hidden="true">{{ $row->getSiteName() }}</td>
            
            <td>{{ $row->getPlacementName() }}</td>
            <td>{{ $row->getCountryName() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImps() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsBlank() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsPsa() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsPsaError() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsDefaultError() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsDefaultBidder() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsKept() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsResold() }}</td>
            <td style="padding-left: 20px;">{{ $row->getImpsRtb() }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getRevenue(), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getResellerRevenue(), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getCost(), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getProfit(), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->adServing, 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->bidReduction, 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->adjustmentCpm, 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->adjustmentUsd, 2) }}</td>
            <td 
                @if($row->getImpsResold() == 0) 
                style="text-decoration: line-through; padding-left: 20px;"
                @else
                style="padding-left: 20px;"
                @endif >
                {{ number_format($row->profitAdjusted, 3) }}
        </td>
        <td 
            @if($row->getImpsResold() != 0) 
            style="text-decoration: line-through; padding-left: 20px;"
            @else
            style="padding-left: 20px;"
            @endif hidden="true" >
            {{ number_format($row->profitAdserving, 2) }}
    </td>
    <td style="font-style: italic; padding-left: 20px;">
        {{ number_format($row->publisherShare, 2) }}%
    </td>
    <td style="font-style: italic; padding-left: 20px;">
        {{ number_format($row->adtomatikProfitPercent, 2) }}%
    </td>
    <td style="font-style: italic; padding-left: 20px;">
        {{ number_format($row->adtomatikDueProfit, 3) }}
    </td>
    <td style="font-style: italic; padding-left: 20px; border: solid 1px;">
        @if($row->publisherDueShare !== NULL) {{ number_format($row->publisherDueShare, 2) }}% @else Error @endif
    </td>
    <td style="font-style: italic; padding-left: 20px;">
        @if($row->publisherDueShare !== NULL) {{ number_format($row->newAjustedProfitWithParam, 3) }} @else Error @endif
    </td>
    <td style="font-style: italic; padding-left: 20px;">
        @if($row->publisherDueShare !== NULL) {{ number_format($row->adtomatikProfitPercentWithParam, 2) }}% @else Error @endif
    </td>
    <td style="padding-left: 20px;" align="center">
        <?php $action = $row->action; ?>
        @if($action == "UP") &xwedge; @elseif($action == "DOWN") &xvee; @else &equiv; @endif
    </td>
    <td hidden="true" style="font-style: italic;">
        ???
    </td>
    <td hidden="true" style="font-style: italic;">
        @if($action == "UP") Subi&oacute; @elseif($action == "DOWN") Baj&oacute; @else Sin Cambio @endif
    </td>
    <td hidden="true" style="font-style: italic;">
        {{ $row->placement->size->getName() }}
    </td>
</tr>
@endforeach
</tbody>
</table>

<script>
    $(document).ready(function () {
        $('a.optimizationShow').click(function (e) {
            e.preventDefault();
            $('#publisherOptimizeData').html(loader).load('/admin/publisher_optimize_details/' + $(this).attr('data-ID'));
            return false;
        });
        $('#exportar2').click(function (e) {
            e.preventDefault();
            //window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#publishers-table').html()));

            $('#publishers-optimization-table').tableExport({type: 'excel', escape: 'false', ignoreColumn: '[0]'});
            return false;

            //oSettings._iDisplayLength = 10;
            //datatable1.fnDraw();
        });
        $('#filtrosHead').click(function (e) {
            e.preventDefault();
            filtros = $('#filtros');
            if ($('#filtros').css('display') == 'block') {
                $('#filtrosHead').html('<h4>+ Filtros</h4>');
                filtros.hide("fast");
            } else {
                $('#filtrosHead').html('<h5>- Filtros</h5>');
                filtros.show("fast");
            }
        });
        var datatables_options =
                {
                    "bAutoWidth": true,
                    "sDom": '<"top"i>rt<"bottom"flp><"clear">', //determine render order for datatables.net items, http://datatables.net/ref#sDom
                    "bPaginate": false, // paging
                    "sPaginationType": "full_numbers", // http://datatables.net/release-datatables/examples/basic_init/alt_pagination.html
                    //"iDisplayLength": 10, // page row size
                    "bSort": true, //sorting
                    "bFilter": true, // "search" box
                    "aaSorting": [[2, "desc"]], // default sort
                    "bInfo": false, // "Showing x to y of z entries" message
                    "bStateSave": false, // save state into a cookie
                    "iCookieDuration": 0, // save state cookie duration
                    "bScrollAutoCss": true, // datatables.net auto styling of scrolling styles, http://datatables.net/forums/discussion/comment/15072
                    "bProcessing": true, // "processing" message while sorting .. doesn't appear to be doing anything
                    "bJQueryUI": false, // css classes for jQueryUI themes?
                    "aoColumns": [
                        {"sSortDataType": "numeric"},
                        null,
                        {"sSortDataType": "numeric"},
                        null,
                        null,
                        null,
                        {"sType": "slo"},
                        {"sType": "slo"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sSortDataType": "numeric"},
                        {"sType": "slo"},
                        null,
                        {"sType": "slo"},
                        {"sType": "slo"}
                    ]
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };

        //datatables_options["sScrollX"] = true;
        datatables_options["sScrollX"] = '100%';
        datatables_options["sScrollY"] = "450px";
        datatables_options["sScrollXInner"] = '100%';
        datatables_options["bScrollCollapse"] = true;
        var datatable2 = $("#publishers-optimization-table").dataTable(datatables_options);
        /*new $.fn.dataTable.FixedColumns(datatable2,
         {
         "drawCallback": function () {
         datatable2.adjust().draw();
         },
         "iLeftColumns": 5,
         //"sLeftWidth": 'relative',
         //"iLeftWidth": '150%',
         "sHeightMatch": "none" /* if there aren't any rows that have wrapping text this would be best because it is much faster in IE8 */
        //"sHeightMatch": "semiauto",
        //"sHeightMatch": "auto",
        /* });*/

        datatable2.yadcf([
            {column_number: 35,
                filter_container_id: "filter_action_realiced",
                filter_reset_button_text: "&times;"},
            {column_number: 36,
                filter_container_id: "filter_publishers_size",
                filter_reset_button_text: "&times;"},
            {column_number: 1,
                filter_container_id: "filter_has_to_adjusted",
                filter_reset_button_text: "&times;"},
            {column_number: 3,
                filter_container_id: "filter_publishers_media_buyer",
                filter_reset_button_text: "&times;"},
            {column_number: 4,
                filter_container_id: "filter_publishers_publisher",
                filter_reset_button_text: "&times;"},
            {column_number: 5,
                filter_container_id: "filter_publishers_site",
                filter_reset_button_text: "&times;"},
            {column_number: 7,
                filter_container_id: "filter_country",
                filter_reset_button_text: "&times;"}

        ]);

        function ResetColHeadings() {
            datatable2.fnAdjustColumnSizing();
        }
        $('tr[name=fila]').click(function (e) {
            e.preventDefault();
            if ($(this).hasClass('highlight-selected-row')) {
                $(this).removeClass('highlight-selected-row');
            } else {
                $(this).addClass('highlight-selected-row');
            }
        });
    });
</script>
@else
@include('admin.general.message', ['type' => 2, 'message' => 'No hay datos cargados. Posible error interno.'])
@endif