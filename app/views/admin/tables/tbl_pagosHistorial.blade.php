@if(count($payments)<1)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> No hay pagos realizados.</div>
@else

<table class="table table-condensed table-hover" style="white-space: nowrap !important; font-size: 11px !important;" id="pagos_historial">
    <thead>
        <tr>
            <th>{{ Lang::get('admin.payments-'.$type) }}</th>
            <th>Descripcion</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @if($type == 'affiliate')
        @foreach($payments as $payment)
            <tr>
                <?php $history_admin = $payment->getAdministrator(); ?>
                <td>{{ $history_admin->user->getEmail() }}</td>
                <td>{{ $payment->getConcept() }}</td>
                <td>{{ number_format($payment->getAmount(),2) }}</td>
                <td>{{ $payment->getDate() }}</td>
                <td><a href="" data-id="{{ $payment->getId() }}" data-payment="{{ $history_admin->user->getEmail() . ': ' . $payment->getConcept() . ' $' . number_format($payment->getAmount(),2) }}" class="revert_payment">Revertir</a></td>
            </tr>
            @endforeach
        @else
            @foreach($payments as $payment)
            <tr>
                <?php $history_publisher = $payment->getPublisher(); ?>
                <td>{{ $history_publisher->getName() }}</td>
                <td>{{ $payment->getConcept() }}</td>
                <td>{{ number_format($payment->getAmount(),2) }}</td>
                <td>{{ $payment->getDate() }}</td>
                <td><a href="" data-id="{{ $payment->getId() }}" data-payment="{{ $history_publisher->getName() . ': ' . $payment->getConcept() . ' $' . number_format($payment->getAmount(),2) }}" class="revert_payment">Revertir</a></td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endif
<a hidden="true" href="" id="open_modal_revertir" data-toggle="modal" data-target="#confirmarModal"></a>
<!-- start: Modal message -->
<div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">¿Est&aacute; seguro que quiere revertir el pago?</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="pago_a_revertir_input" value=""/>
                <span id="pago_a_revertir_span"></span>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="pago_a_revertir_btn">Aceptar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end: Modal message -->

<script>
    $(document).ready(function() {
        $('.revert_payment').click(function(e){
            e.preventDefault();
            $("#pago_a_revertir_input").attr("value", $( this ).attr('data-id'));
            $("#pago_a_revertir_span").html($( this ).attr('data-payment'));
            $("#open_modal_revertir").click();
        });
        
        $('#pago_a_revertir_btn').click(function(e){
                $(document).load('/admin/revert_payments/' + $("#pago_a_revertir_input").val() + '/{{ $type }}', function() {
                    location.reload();
            });
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
                    "aaSorting": [[3, "desc"]], // default sort
                    "bInfo": false, // "Showing x to y of z entries" message
                    "bStateSave": false, // save state into a cookie
                    "iCookieDuration": 0, // save state cookie duration
                    "bScrollAutoCss": true, // datatables.net auto styling of scrolling styles, http://datatables.net/forums/discussion/comment/15072
                    "bProcessing": true, // "processing" message while sorting .. doesn't appear to be doing anything
                    "bJQueryUI": false, // css classes for jQueryUI themes?
                    //"asStripeClasses": [], // remove odd/even row css classes (they will be assigned elsewhere)
                    "aoColumns": [
                        {"sType": "slo"},
                        {"sType": "slo"},
                        {"sSortDataType": "numeric"},
                        {"sType": "uk_date"},
                        null
                    ]

                };

        //datatables_options["sScrollX"] = "100%";
        /*datatables_options["sScrollY"] = "450px";
        /*datatables_options["sScrollXInner"] = '150%';
        datatables_options["bScrollCollapse"] = true;*/
        var $datatable = $("#pagos_historial").dataTable(datatables_options);
    });
</script>