@if($placements)
<hr />
<input type="button" data-publisherId="{{ $publisherId }}" class="btn btn-default btn-marginR20 floatRight" id="getAllHistory" value="Ver historial completo"/>
<br />
<button id="exportar3" class="btn btn-success floatLeft">Exportar Excel</button>
<table class="table row-border table-condensed" id="placement-optimization-history-table" style="white-space: initial !important; font-size: 11px !important;">
    <thead>
        <tr style="white-space: initial !important; font-weight: bold;">
            <th hidden="true">ID</th>
            <th>Site</th>
            <th>Placement</th>
            <th>Country</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach($placements as $optimized)
        <tr name="filaHistorialPlacement" style="cursor: pointer;">
            <td hidden="true">{{ $optimized->id }}</td>
            <?php $site = Site::find($optimized->site_id) ?>
            <?php $placement = Placement::find($optimized->placement_id) ?>
            <td>@if($site){{ $site->getName() }}@else---@endif</td>
            <td>@if($placement){{ $placement->getName() }}@else---@endif</td>
            <td>@if($optimized->country_id){{ Lang::get('countries.'.$optimized->country_id) }}@else---@endif</td>
            <td>{{ $optimized->getDetails() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('#exportar3').click(function (e) {
            e.preventDefault();
            //window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#publishers-table').html()));

            $('#placement-optimization-history-table').tableExport({type: 'excel', escape: 'false', ignoreColumn: '[0]'});
            return false;
            //oSettings._iDisplayLength = 10;
            //datatable1.fnDraw();
        });
        $('#getAllHistory').click(function (e) {
            e.preventDefault();
            $('#historyBySitePlacementCountry').html(loader).load('/admin/load_optimized_publisher_table/' + $(this).attr('data-publisherId'));
            return false;
        });
        $('tr[name=filaHistorialPlacement]').click(function (e) {
            e.preventDefault();
            if ($(this).hasClass('highlight-selected-row')) {
               $(this).removeClass('highlight-selected-row');
            } else {
                $(this).addClass('highlight-selected-row');
            }
        });
        var datatables_options5 =
                {
                    "bAutoWidth": true,
                    "sDom": '<"top"i>rt<"bottom"flp><"clear">', //determine render order for datatables.net items, http://datatables.net/ref#sDom
                    "bPaginate": false, // paging
                    "sPaginationType": "full_numbers", // http://datatables.net/release-datatables/examples/basic_init/alt_pagination.html
                    "bSort": true, //sorting
                    "aaSorting": [[0, "desc"]], // default sort
                    "bFilter": true, // "search" box
                    "bInfo": false, // "Showing x to y of z entries" message
                    "bStateSave": false, // save state into a cookie
                    "iCookieDuration": 0, // save state cookie duration
                    "bScrollAutoCss": true, // datatables.net auto styling of scrolling styles, http://datatables.net/forums/discussion/comment/15072
                    "bProcessing": true, // "processing" message while sorting .. doesn't appear to be doing anything
                    "bJQueryUI": false, // css classes for jQueryUI themes?
                    "aoColumns": [
                        null,
                        null,
                        null,
                        null,
                        null,
                    ]
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };
        datatables_options5["sScrollX"] = "100%";
        datatables_options5["sScrollY"] = "100%";
        datatables_options5["sScrollXInner"] = '100%';
        datatables_options5["bScrollCollapse"] = true;
        var datatable3 = $("#placement-optimization-history-table").dataTable(datatables_options5);
        
        
    });
</script>
@else
@include('admin.general.message', ['type' => 2, 'message' => 'No hubieron optimizaciones en el rango de fechas seleccionado.'])
@endif