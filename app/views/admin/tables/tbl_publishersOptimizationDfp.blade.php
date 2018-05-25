@if(count($report)>0)
<p><i class="glyphicon glyphicon-hand-right"></i> Reporte obtenido el d&iacute;a {{ date('d/m/Y',strtotime($report[0]->created_at)) }}, sobre los datos del {{ date('d/m/Y',strtotime('-1 day '.$report[0]->created_at)) }}.</p>

<div class="panel panel-success">
    <div class="panel-heading" id="filtrosHeadDfp" style="cursor: pointer"><h4>+ Filtros</h4></div>
    <div class="panel-body" id="filtrosDfp" hidden="true">
        <div class="btn btn-default btn-marginR20">            
            Estado:&nbsp;
            <span id="filter_dfp_has_to_adjusted"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Ejecutivo de Cuentas:&nbsp;
            <span id="filter_dfp_publishers_media_buyer"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Acci&oacute;n sobre Rev Share del Publisher:&nbsp;
            <span id="filter_dfp_action_realiced"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Pa&iacute;s:&nbsp;
            <span id="filter_dfp_country"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Publisher:&nbsp;
            <span id="filter_dfp_publishers_publisher"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Site:&nbsp;
            <span id="filter_dfp_publishers_site"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Tama&ntilde;o:&nbsp;
            <span id="filter_dfp_size"></span>
        </div>
        <div class="btn btn-default btn-marginR20">        
            Tipo:&nbsp;
            <span id="filter_dfp_type"></span>
        </div>
    </div>
</div>

<button id="exportarDfp" class="btn btn-success floatRight">Exportar Excel</button>
<table class="table row-border table-condensed" id="publishers-dfp-optimization-table" style="font-size: 10px !important;">
    <thead>
        <tr style="white-space: initial !important; font-weight: bold;">
            <th>&nbsp;</th>
            <th hidden="true">ID</th>
            <th hidden="true">Media Buyer</th>
            <th hidden="true">Publisher</th>
            <th hidden="true">Site</th>
            <th align="center"  style="padding-left: 20px;">Placement</th>
            <th align="center">Country</th>
            <th align="center">Type</th>
            <th align="center">Imps</th>
            <th align="center">Revenue</th>
            <th align="center">Costo Adserving</th>
            <th align="center">Revenue (-Ad)</th>
            <th align="center">Costo</th>
            <th align="center">Profit</th>
            <th align="center">% Profit</th>
            <th align="center">% New Profit</th>
            <th align="center">New Share</th>
            <th align="center">Action</th>
            <th hidden="true">Action</th>
            <th hidden="true">Estado</th>
            <th hidden="true">Size</th>
        </tr>
    </thead>
    <tbody>
        @foreach($report as $row)
        @if($row->hasToAnalize('adserver'))
        <tr name="fila" 
            <?php $action = $row->getAction('adserver'); ?>
            @if($action != 'EQUAL') 
                @if($row->hasToBeDisabled('adserver')) 
                    style="cursor: pointer; font-weight: bold; color: blue;"
                @else 
                    style="cursor: pointer; font-weight: bold; color: red;"
                @endif
            @else
                @if($row->hasToBeDisabled('adserver'))
                    style="cursor: pointer; font-weight: bold; color: blue;"
                @else 
                    style="cursor: pointer;"
                @endif
            @endif >
            <td><a data-ID="{{ $row->getID() }}" data-type='adserver'  class="optimizationDfpShow" href="#">Ver</a></td>

            <td hidden="true">{{ $row->getPublisherId() }}</td>

            @if($row->publisher->mediaBuyer)
            <td hidden="true">{{ $row->publisher->mediaBuyer->user->profile->getName() }}</td>
            @else
            <td hidden="true">Sin Asignar</td>
            @endif

            <td hidden="true">{{ $row->getPublisherName() }}</td>
            <td hidden="true">{{ $row->getSiteName() }}</td>

            <td>{{ $row->placement->getName() }}</td>
            <td>{{ $row->getCountryName() }}</td>
            <td>Adserver</td>
            <td style="padding-left: 20px;">{{ $row->getImps('adserver') }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getRevenue('adserver'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getAdservingCost('adserver'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getRevenueWithoutAdserving('adserver'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getCost(null, 'adserver'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getProfit(null, 'adserver'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getProfitPercent(null, 'adserver'), 2) }}</td>
            <?php $share = $row->getDueShare('adserver') ?>
            <td style="padding-left: 20px;">{{ number_format($row->getProfitPercent($share, 'adserver'), 2) }}</td>
            <td style="padding-left: 20px;">{{ floor($share) }}</td>
            <td style="padding-left: 20px;" align="center">
                @if($action == "UP") &xwedge; @elseif($action == "DOWN") &xvee; @else &equiv; @endif
            </td>
            <td hidden="true" style="font-style: italic;">
                @if($action == "UP") Subi&oacute; @elseif($action == "DOWN") Baj&oacute; @else Sin Cambio @endif
            </td>
            <td hidden="true">
                @if($action != 'EQUAL') 
                @if($row->hasToBeDisabled('adserver')) 
                Se debe desactivar
                @else 
                Se optimiz&oacute; hoy
                @endif
                @else
                @if($row->hasToBeDisabled('adserver'))
                Se debe desactivar
                @else 
                OK
                @endif
                @endif
            </td>
            <td hidden="true">{{ $row->placement->size->getName() }}</td>
        </tr>
        @endif
        @if($row->hasToAnalize('exchange'))
        <?php $action = $row->getAction('exchange'); ?>
               <tr name="fila" 
            @if($action != 'EQUAL') 
            @if($row->hasToBeDisabled('exchange')) 
            style="cursor: pointer; font-weight: bold; color: blue;"
            @else 
            style="cursor: pointer; font-weight: bold; color: red;"
            @endif
            @else
            @if($row->hasToBeDisabled('exchange'))
            style="cursor: pointer; font-weight: bold; color: blue;"
            @else 
            style="cursor: pointer;"
            @endif
            @endif >
            <td><a data-ID="{{ $row->getID() }}" data-type='exchange'  class="optimizationDfpShow" href="#">Ver</a></td>

            <td hidden="true">{{ $row->getPublisherId() }}</td>

            @if($row->publisher->mediaBuyer)
            <td hidden="true">{{ $row->publisher->mediaBuyer->user->profile->getName() }}</td>
            @else
            <td hidden="true">Sin Asignar</td>
            @endif

            <td hidden="true">{{ $row->getPublisherName() }}</td>
            <td hidden="true">{{ $row->getSiteName() }}</td>

            <td>{{ $row->placement->getName() }}</td>
            <td>{{ $row->getCountryName() }}</td>
            <td>Exchange</td>
            <td style="padding-left: 20px;">{{ $row->getImps('exchange') }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getRevenue('exchange'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getAdservingCost('exchange'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getRevenueWithoutAdserving('exchange'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getCost(null, 'exchange'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getProfit(null, 'exchange'), 2) }}</td>
            <td style="padding-left: 20px;">{{ number_format($row->getProfitPercent(null, 'exchange'), 2) }}</td>
            <?php $share = $row->getDueShare('exchange') ?>
            <td style="padding-left: 20px;">{{ number_format($row->getProfitPercent($share, 'exchange'), 2) }}</td>
            <td style="padding-left: 20px;">{{ floor($share) }}</td>
            <td style="padding-left: 20px;" align="center">
                @if($action == "UP") &xwedge; @elseif($action == "DOWN") &xvee; @else &equiv; @endif
            </td>
            <td hidden="true" style="font-style: italic;">
                @if($action == "UP") Subi&oacute; @elseif($action == "DOWN") Baj&oacute; @else Sin Cambio @endif
            </td>
            <td hidden="true">
                @if($action != 'EQUAL') 
                @if($row->hasToBeDisabled('exchange')) 
                Se debe desactivar
                @else 
                Se optimiz&oacute; hoy
                @endif
                @else
                @if($row->hasToBeDisabled('exchange'))
                Se debe desactivar
                @else 
                OK
                @endif
                @endif
            </td>
            <td hidden="true">{{ $row->placement->size->getName() }}</td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('a.optimizationDfpShow').click(function (e) {
            e.preventDefault();
            $('#publisherDfpOptimizeData').html(loader).load('/admin/publisher_dfp_optimize_details/' + $(this).attr('data-ID') + '/' + $(this).attr('data-type'));
            return false;
        });
        $('#exportarDfp').click(function (e) {
            e.preventDefault();
            //window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#publishers-table').html()));

            $('#publishers-dfp-optimization-table').tableExport({type: 'excel', escape: 'false', ignoreColumn: '[0]'});
            return false;

            //oSettings._iDisplayLength = 10;
            //datatable1.fnDraw();
        });
        $('#filtrosHeadDfp').click(function (e) {
            e.preventDefault();
            filtros = $('#filtrosDfp');
            if ($('#filtrosDfp').css('display') == 'block') {
                $('#filtrosHeadDfp').html('<h4>+ Filtros</h4>');
                filtros.hide("fast");
            } else {
                $('#filtrosHeadDfp').html('<h5>- Filtros</h5>');
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
                        {"sType": "slo"},
                        null,
                        null,
                        {"sType": "slo"}

                    ]
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };

        //datatables_options["sScrollX"] = true;
        datatables_options["sScrollX"] = '100%';
        datatables_options["sScrollY"] = "450px";
        datatables_options["sScrollXInner"] = '100%';
        datatables_options["bScrollCollapse"] = true;
        var datatable4 = $("#publishers-dfp-optimization-table").dataTable(datatables_options);

        datatable4.yadcf([
            {column_number: 18,
                filter_container_id: "filter_dfp_action_realiced",
                filter_reset_button_text: "&times;"},
            {column_number: 19,
                filter_container_id: "filter_dfp_has_to_adjusted",
                filter_reset_button_text: "&times;"},
            {column_number: 20,
                filter_container_id: "filter_dfp_size",
                filter_reset_button_text: "&times;"},
            {column_number: 7,
                filter_container_id: "filter_dfp_type",
                filter_reset_button_text: "&times;"},
            {column_number: 2,
                filter_container_id: "filter_dfp_publishers_media_buyer",
                filter_reset_button_text: "&times;"},
            {column_number: 3,
                filter_container_id: "filter_dfp_publishers_publisher",
                filter_reset_button_text: "&times;"},
            {column_number: 4,
                filter_container_id: "filter_dfp_publishers_site",
                filter_reset_button_text: "&times;"},
            {column_number: 6,
                filter_container_id: "filter_dfp_country",
                filter_reset_button_text: "&times;"}

        ]);

        function ResetColHeadings() {
            datatable4.fnAdjustColumnSizing();
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