@if(count($earnings)<1)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> No hay ingresos mensuales.</div>
@else

@if($type=='publisher')
<div class="panel panel-success">
    <div class="panel-heading" id="filtrosHeadIngresosMensuales" style="cursor: pointer"><h4>Filtros</h4></div>
    <div class="panel-body" id="filtrosIngresosMensuales" hidden="true">
        <div class="btn btn-default btn-marginR20">Media Buyer
            <span id="filter_ingresos_mensuales_ejecutivo"></span>
        </div>
    </div>
</div>
@endif

<table class="table table-condensed table-hover" style="white-space: nowrap !important; font-size: 11px !important;" id="ingresos_mensuales">
    <thead>
        <tr>
            <th>{{ Lang::get('admin.payments-'.$type) }}</th>
            <th>Fecha</th>
            <th>Ingresos</th>
            @if($type=='publisher')<th>Ejecutivo</th>@endif
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @if($type=='publisher')
        @foreach($earnings as $earning)
        <?php $publisher_ern = $earning->publisher; ?>
        @if($earning->publisher->getId() != '1476')
        <tr>
            <td>{{ $publisher_ern->getName() }}</td>
            <td>{{ $earning->getConcept() }}</td>
            <td>{{ number_format($earning->getAmount(),2) }}</td>
            <td>@if($publisher_ern->mediaBuyer){{ $publisher_ern->mediaBuyer->getName() }}@else --- @endif</td>
            <td>
                <a href=""
                   class="itemShow"
                   data-itemId="{{ $publisher_ern->getId() }}"
                   >
                    Ver <i class="fa fa-angle-double-right"></i>
                </a>
            </td>
        </tr>
        @endif
        @endforeach
        @else
        @foreach($earnings as $earning)
        <?php $admin_ern = $earning->administrator; ?>
        <tr>
            <td>{{ $admin_ern->user->getEmail() }}</td>
            <td>{{ $earning->getConcept() }}</td>
            <td>{{ number_format($earning->getAmount(),2) }}</td>
            <td>
                <a href=""
                   class="itemShow"
                   data-itemId="{{ $admin_ern->getId() }}"
                   >
                    Ver <i class="fa fa-angle-double-right"></i>
                </a>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endif

<script>
    $(document).ready(function () {
        $('a.itemShow').click(function (e) {
            e.preventDefault();
            $('#publisherDetail').show("slow");
            $('#publisherData').html(loader).load('/admin/item_payments/' + $(this).attr('data-itemId') + '/{{ $type }}');
            $('#publisherBillings').html(loader).load('/admin/item_billings/' + $(this).attr('data-itemId') + '/{{ $type }}');
            $('#publisherDetail').show("slow");
            return false;
        });

        var datatables_options =
                {
                    "bAutoWidth": true,
                    "sDom": '<"top"i>rt<"bottom"flp><"clear">', //determine render order for datatables.net items, http://datatables.net/ref#sDom
                    "bPaginate": true, // paging
                    "sPaginationType": "simple_numbers", // http://datatables.net/release-datatables/examples/basic_init/alt_pagination.html
                    "iDisplayLength": 10, // page row size
                    "bSort": true, //sorting
                    "bFilter": true, // "search" box
                    "aaSorting": [[0, "asc"]], // default sort
                    "bInfo": false, // "Showing x to y of z entries" message
                    "default": true,
                    "bStateSave": false, // save state into a cookie
                    "iCookieDuration": 0, // save state cookie duration
                    "bProcessing": true, // "processing" message while sorting .. doesn't appear to be doing anything
                    "bJQueryUI": false // css classes for jQueryUI themes?
                            //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)

                };

        var datatable3 = $("#ingresos_mensuales").dataTable(datatables_options);
        @if ($type == 'publisher')

            $('#filtrosHeadIngresosMensuales').click(function (e) {
                e.preventDefault();
                filtros = $('#filtrosIngresosMensuales');
                if ($('#filtrosIngresosMensuales').css('display') == 'block') {
                    filtros.hide("fast");
                } else {
                    filtros.show("fast");
                }
            });

            datatable3.yadcf([
                {column_number: 3,
                    filter_container_id: "filter_ingresos_mensuales_ejecutivo",
                    filter_reset_button_text: "&times;"}
            ]);

        @endif
    });
</script>