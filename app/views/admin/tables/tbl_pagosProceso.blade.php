@if(!$billings)
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> No hay pagos en proceso.</div>
@else
    <table id="pagos_proceso" class="table table-condensed table-hover" style="white-space: nowrap !important; font-size: 11px !important;">
        <thead>
            <tr>
                <th>{{ Lang::get('mis_pagos.descripcion'); }}</th>
                <th>{{ Lang::get('mis_pagos.fecha_estimada'); }}</th>
                <th>{{ Lang::get('mis_pagos.importe'); }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($billings as $billing)
            <tr>
                <td>{{ Lang::get('payments.earnings') . ' ' . $billing->getConcept() }}</td>
                <td>{{ $billing->getStipulatedDate() }}</td>
                <td>{{ number_format($billing->getBalance(),2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script>
        var datatables_options =
                {
                    "bAutoWidth": true,
                    "sDom": '<"top"i>rt<"bottom"flp><"clear">', //determine render order for datatables.net items, http://datatables.net/ref#sDom
                    "bPaginate": true, // paging
                    "sPaginationType": "simple_numbers", // http://datatables.net/release-datatables/examples/basic_init/alt_pagination.html
                    "iDisplayLength": 10, // page row size
                    "bSort": true, //sorting
                    "bFilter": true, // "search" box
                    "aaSorting": [[2, "asc"]], // default sort
                    "bInfo": false, // "Showing x to y of z entries" message
                    "bStateSave": false, // save state into a cookie
                    "iCookieDuration": 0, // save state cookie duration
                    "bScrollAutoCss": true, // datatables.net auto styling of scrolling styles, http://datatables.net/forums/discussion/comment/15072
                    "bProcessing": true, // "processing" message while sorting .. doesn't appear to be doing anything
                    "bJQueryUI": false // css classes for jQueryUI themes?
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };

        /*datatables_options["sScrollY"] = "450px";
        datatables_options["bScrollCollapse"] = true;*/
        var $datatable = $("#pagos_proceso").dataTable(datatables_options);
</script>