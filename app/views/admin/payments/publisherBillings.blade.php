<div class="row">
    <h2>Pagar</h2>
</div>

<table class="table" style="font-size: 11px !important;">
    @foreach($billings as $billing)
    <tr id="{{ $billing->getId() }}">
        <td>{{ Lang::get('payments.earnings') . ' ' . $billing->getConcept() }}</td>
        <td>{{ $billing->getStipulatedDate() }}</td>
        <td>US$ <span class="importe">{{ number_format($billing->getBalance(),2) }}</span></td>
        <td>
            <label class="checkbox-inline">
                <input type="radio" name="billing" value="{{ $billing->getId() }}">
            </label>
        </td>
    </tr>
    @endforeach
</table>
@if(!$billings)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> No hay pagos en proceso.</div>
@else
<form id="pagoForm" action="pyment_generate" method="post" class="form-horizontal">
    <input type="hidden" name="billingId" id="billingId" value="" />
    <input type="hidden" name="pym_type" id="pym_type" value="0" />
    <div class="form-group">
        <label class="col-sm-3 control-label">Seleccione tipo de pago:</label>
        <div class="col-sm-5">
            <select name="pym_type_select" class="form-control">
                <option value="0" selected="selected">
                    Pago Total
                </option>
                <option value="1">
                    Pago Parcial
                </option>
                <option value="2">
                    Ajuste por impreciones/clics invalidos
                </option>
                <option value="3">
                    Otro
                </option>
            </select>
        </div>
    </div>

    <div class="form-group pym_description_control" hidden="true">
        <label class="col-sm-3 control-label">Ingrese descripci&oacute;n:</label>
        <div class="col-sm-5">
            <input type="text" name="pym_description" value="" class="form-control" />
        </div>
    </div>
    <div class="form-group pym_description_control" hidden="true">
        <label class="col-sm-3 control-label">&nbsp;</label>
        <div class="col-sm-5" style="text-align: left !important;">
            <label class="control-label">(<u><i>descripci&oacute;n</i></u> - Mes A&ntilde;o)</label>
        </div>    
    </div>



    <div class="form-group">
        <label class="col-sm-3 control-label">Importe final: US$</label>
        <div class="col-sm-5">
            <input type="text" name="importe" value="" class="form-control" />
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Fecha:</label>
        <div class="col-sm-5">
            <div class="input-prepend input-group">
                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span><input type="text" style="width: 200px" name="fecha" id="fecha" class="form-control"  /> 
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="modal-footer col-sm-10 col-md-offset-1">
            <div class="col-sm-9">
                <h3><span class="label label-danger" id="errores"></span></h3>
            </div> 
            <div class="col-sm-3">
                <button type="buton" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">Pagar</span></button>
            </div> 
        </div> 
    </div> 
</form>
@endif
<div class="row">
    <h2>Nuevo Pago en proceso</h2>
</div>

<form id="nuevoBillingForm" action="billing_generate" method="post" class="form-horizontal">
    <input type="hidden" name="itemId" id="itemId" value="{{ $itemId }}" />
    <input type="hidden" name="type" id="type" value="{{ $type }}" />

    <div class="form-group">
        <label class="col-sm-3 control-label">Importe final: US$</label>
        <div class="col-sm-5">
            <input type="text" name="importeBilling" value="" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Descripci&oacute;n</label>
        <div class="col-sm-5">
            <input type="text" name="descripcionBilling" value="" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">&nbsp;</label>
        <div class="col-sm-5" style="text-align: left !important;">
            <label class="control-label">(Ingresos <u><i>descripci&oacute;n</i></u> - Mes A&ntilde;o)</label>
        </div>    
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Fecha:</label>
        <div class="col-sm-5">
            <div class="input-prepend input-group">
                <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span><input type="text" style="width: 200px" name="fechaBilling" id="fechaBilling" class="form-control"  /> 
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="modal-footer col-sm-10 col-md-offset-1">
            <div class="col-sm-9">
                <h3><span class="label label-danger" id="errores2"></span></h3>
            </div> 
            <div class="col-sm-3">
                <button type="buton" id="submit_form2" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">Generar Pago en Proceso</span></button>
            </div> 
        </div> 
    </div> 
</form>

<script>
    $(document).ready(function () {
        $("input[name=importe]").attr('disabled', 'disabled');
        $('#fecha').val(moment().format('YYYY/MM/DD HH:mm:ss'));
        $("input[name=billing]").click(function () {
            $("#billingId").val($(this).val());
            var importe = $('#' + $("#billingId").val() + ' span.importe').text();
            importe = importe.replace(/,/g, "");
            // completo formulario
            $("input[name=importe]").val(importe);
        });

        $("select[name=pym_type_select]").change(function () {
            $("#pym_type").val($(this).val());

            if ($(this).val() === '0') {
                var importe = $('#' + $("#billingId").val() + ' span.importe').text();
                importe = importe.replace(/,/g, "");
                // completo formulario
                $("input[name=importe]").val(importe);
                $("input[name=importe]").attr('disabled', 'disabled');
                $(".pym_description_control").hide("fast");
                $("input[name=pym_description]").val("");
            }
            else {
                if ($(this).val() === '3')
                {
                    $("input[name=importe]").removeAttr('disabled');
                    $(".pym_description_control").show("fast");
                }
                else
                {
                    $("input[name=importe]").removeAttr('disabled');
                    $(".pym_description_control").hide("fast");
                    $("input[name=pym_description]").val("");
                }
            }
        });

        $('#fechaBilling').val(moment().format('YYYY/MM/DD HH:mm:ss'));

        $("#submit_form").click(function (e) {
            e.preventDefault();

            var datos = {'billingId': $('input[name="billingId"]').val(),
                'importe': $('input[name="importe"]').val(),
                'fecha': $('input[name="fecha"]').val(),
                'pym_type': $('input[name="pym_type"]').val(),
                'pym_description': $('input[name="pym_description"]').val(),
                'type': '{{ $type }}'
            };

            $.ajax({
                data: datos,
                url: '/admin/pyment_generate',
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    if (result.error == 1 || result.error == 2) {
                        $('#errores').html(result.messages);
                        return false;
                    }
                    else
                        location.reload();

                }
            });

            return false;
        });

        $('#fecha').daterangepicker({
            singleDatePicker: true,
            startDate: moment(),
            endDate: moment(),
            format: 'YYYY/MM/DD HH:mm:ss'
        }, function (start, end, label) {
            $('#fecha').val(start.format('YYYY/MM/DD HH:mm:ss'));
        });

        $("#submit_form2").click(function (e) {
            e.preventDefault();
            $("#submit_form2").html(loader);
            var datos = {'itemId': $('input[name="itemId"]').val(),
                'type': $('input[name="type"]').val(),
                'importeBilling': $('input[name="importeBilling"]').val(),
                'fechaBilling': $('input[name="fechaBilling"]').val(),
                'descripcionBilling': $('input[name="descripcionBilling"]').val()
            };

            $.ajax({
                data: datos,
                url: '/admin/billing_generate',
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    $("#submit_form2").html('Generar Pago en Proceso');
                    if (result.error == 1 || result.error == 2) {
                        $('#errores2').html(result.messages);
                        return false;
                    }
                    else
                        location.reload();

                }
            });

            return false;
        });

        $('#fechaBilling').daterangepicker({
            singleDatePicker: true,
            startDate: moment(),
            endDate: moment(),
            format: 'YYYY/MM/DD HH:mm:ss'
        }, function (start, end, label) {
            $('#fechaBilling').val(start.format('YYYY/MM/DD HH:mm:ss'));
        });
    });
</script>
