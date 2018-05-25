@if($history)

<table class="table row-border table-condensed" id="publishers-optimization-history-table" style="white-space: initial !important; font-size: 11px !important;">
    <thead>
        <tr style="white-space: initial !important; font-weight: bold;">
            <th hidden="true">ID</th>
            <th>Publisher</th>
            <th>Adserver</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($history as $optimized)
        <tr name="filaHistorial" style="cursor: pointer;">
            <td hidden="true">{{ $optimized->id }}</td>
            <?php $publisher = Publisher::find($optimized->publisher_id); ?>
            <td>{{ $publisher->getName() }}</td>
            <td>{{ $publisher->getFirstAdserverName() }}</td>
            <td><a href="#" class="verHistorialDelPublisher" data-range="{{ $range }}" data-publisherId="{{ $optimized->publisher_id }}"><b>Ver</b></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div id="historyBySitePlacementCountry">
    
</div>
<script>
    $(document).ready(function () {
        $('a.verHistorialDelPublisher').click(function (e) {
            e.preventDefault();
            $('#historyBySitePlacementCountry').html(loader).load('/admin/load_optimized_publisher_table/' + $(this).attr('data-publisherId')+'/'+$(this).attr('data-range'));
            return false;
        });
        
        var datatables_options2 =
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
                        {"sType": "slo"},
                        {"sType": "slo"},
                        null,
                    ]
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };
        datatables_options2["sScrollX"] = "100%";
        datatables_options2["sScrollY"] = "450px";
        datatables_options2["sScrollXInner"] = '100%';
        datatables_options2["bScrollCollapse"] = true;
        var datatable1 = $("#publishers-optimization-history-table").dataTable(datatables_options2);
        $('tr[name=filaHistorial]').click(function (e) {
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
@include('admin.general.message', ['type' => 2, 'message' => 'No hubieron optimizaciones en el rango de fechas seleccionado.'])
@endif