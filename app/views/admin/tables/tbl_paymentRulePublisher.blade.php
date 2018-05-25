@if($paymentRules)
<div class="panel panel-success">
    <div class="panel-heading" id="filtrosHeadPR" style="cursor: pointer"><h4>+ Filtros</h4></div>
    <div class="panel-body" id="filtrosPR" hidden="true">
        <div class="btn btn-default btn-marginR20">            
            Adserver:&nbsp;
            <span id="filter_paymet_rule_adserver"></span>
        </div>
    </div>
</div>
<table class="table row-border table-condensed" id="publishers-payment-rule-table" style="white-space: initial !important; font-size: 11px !important;">
    <thead>
        <tr style="white-space: initial !important; font-weight: bold;">
            <th hidden="true">ID</th>
            <th>Publisher</th>
            <th>Adserver</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paymentRules as $publisher)
        <tr name="filaPaymentRule" style="cursor: pointer;">
            <td hidden="true">{{ $publisher->getId() }}</td>
            <td>{{ $publisher->getName() }}</td>
            <td>{{ $publisher->getFirstAdserverName() }}</td>
            <td><a data-ID="{{ $publisher->getId() }}" class="paymentRuleShow" href="#">Ver</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div id="paymentRuleView"></div>
<script>
    $(document).ready(function () {
        $('.paymentRuleShow').click(function(e){
            e.preventDefault();
            $("#paymentRuleView").html(loader).load('load_publisher_payment_rules_table/'+$( this ).attr('data-ID'));
        });
        
        $('#filtrosHeadPR').click(function (e) {
            e.preventDefault();
            filtros = $('#filtrosPR');
            if ($('#filtrosPR').css('display') == 'block') {
                $('#filtrosHeadPR').html('<h4>+ Filtros</h4>');
                filtros.hide("fast");
            } else {
                $('#filtrosHeadPR').html('<h5>- Filtros</h5>');
                filtros.show("fast");
            }
        });
       var datatables_options_payment_rule =
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
        datatables_options_payment_rule["sScrollX"] = "100%";
        datatables_options_payment_rule["sScrollY"] = "450px";
        datatables_options_payment_rule["sScrollXInner"] = '100%';
        datatables_options_payment_rule["bScrollCollapse"] = true;
        var datatablePR = $("#publishers-payment-rule-table").dataTable(datatables_options_payment_rule);
        datatablePR.yadcf([
            {column_number: 2,
                filter_container_id: "filter_paymet_rule_adserver",
                filter_reset_button_text: "&times;"}
        ]);
        $('tr[name=filaPaymentRule]').click(function (e) {
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
@include('admin.general.message', ['type' => 2, 'message' => 'No hubieron optimizaciones hasta el momento.'])
@endif