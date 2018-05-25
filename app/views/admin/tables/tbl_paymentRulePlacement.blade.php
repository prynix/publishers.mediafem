@if($paymentRules)
<hr />
<h4>{{ $paymentRules[0]->placement->site->publisher->getName() }}</h4>
<table class="table row-border table-condensed" id="placements-payment-rule-table" style="white-space: initial !important; font-size: 11px !important;">
    <thead>
        <tr style="white-space: initial !important; font-weight: bold;">
            <th hidden="true">ID</th>
            <th>Sitio</th>
            <th>Espacio</th>
            <th>Pa&iacute;s</th>
            <th>Share (al Publisher)</th>
            <th>Detalle</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paymentRules as $paymentRule)
        <tr name="filaPlacementPaymentRule" style="cursor: pointer;">
            <td hidden="true">{{ $paymentRule->id }}</td>
            <td>{{ $paymentRule->placement->site->getName() }}</td>
            <td>{{ $paymentRule->placement->getName() }}</td>
            <td>{{ $paymentRule->country->cnt_id }}</td>
            <td>{{ $paymentRule->share }}</td>
            <td>{{ $paymentRule->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('tr[name=filaPlacementPaymentRule]').click(function (e) {
            e.preventDefault();
            if ($(this).hasClass('highlight-selected-row')) {
               $(this).removeClass('highlight-selected-row');
            } else {
                $(this).addClass('highlight-selected-row');
            }
        });
        var datatables_optionsPlPR =
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
                        null,
                    ]
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };
        datatables_optionsPlPR["sScrollX"] = "100%";
        datatables_optionsPlPR["sScrollY"] = "100%";
        datatables_optionsPlPR["sScrollXInner"] = '100%';
        datatables_optionsPlPR["bScrollCollapse"] = true;
        var datatablePlPR = $("#placements-payment-rule-table").dataTable(datatables_optionsPlPR);
        
        
    });
</script>
@else
@include('admin.general.message', ['type' => 2, 'message' => 'Este publisher no tiene payment rules creadas.'])
@endif