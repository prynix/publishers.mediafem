@if(!$records)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> No posee registros de pagos.</div>
@else
<table class="table table-condensed table-hover" style="white-space: nowrap !important; font-size: 11px !important;" id="pagos_finalizados">
    <thead>
        <tr>
            <th>{{ Lang::get('mis_pagos.fecha'); }}</th>
            <th>{{ Lang::get('mis_pagos.descripcion'); }}</th>
            <th>{{ Lang::get('mis_pagos.creditos'); }}</th>
            <th>{{ Lang::get('mis_pagos.debitos'); }}</th>
            <th>{{ Lang::get('mis_pagos.saldo_acumulado'); }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
        <tr>
            <td>{{ date("Y-m-d", strtotime($record['date'])) }}</td>
            @if($record['type']=='earning')
            <td>{{ Lang::get('payments.earnings') . ' ' . $record['record']->getConcept() }}</td>
            <td>{{ number_format($record['record']->getAmount(),2) }}</td>
            <td>---</td>
            @else
            <td>{{ $record['record']->getConcept() }}</td>
            <td>---</td>
            <td>-{{ number_format($record['record']->getAmount(),2) }}</td>
            @endif
            <td>{{ number_format($record['balance'],2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<script>
    $(document).ready(function () {
        var datatables_options =
                {
                    "bAutoWidth": true,
                    "sDom": '<"top"i>rt<"bottom"flp><"clear">', //determine render order for datatables.net items, http://datatables.net/ref#sDom
                    "bPaginate": true, // paging
                    "sPaginationType": "simple_numbers", // http://datatables.net/release-datatables/examples/basic_init/alt_pagination.html
                    "iDisplayLength": 10, // page row size
                    "bSort": false, //sorting
                    "bFilter": true, // "search" box
                    //"aaSorting": [[2, "asc"]], // default sort
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
        if (!datatablePagos) {
            var datatablePagos = $("#pagos_finalizados").dataTable(datatables_options);
        }
    });
</script>