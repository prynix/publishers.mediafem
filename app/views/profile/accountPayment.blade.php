<p>{{ Lang::get('mi_cuenta.texto_transferencia') }}</p>

<ul class="nav nav-tabs" id="acoountPaymentTab">
    <li class="active"><a href="#accountPaypal" data-toggle="tab"><img src="images/paypal-logo.png" alt="PayPal" /></a></li>
    <li><a href="#accountBank" data-toggle="tab">{{ Lang::get('mi_cuenta.transferencia_bancaria') }} - Payoneer</a></li>
</ul>

<div class="panel-body">
    <div class="tab-content">
        <div class="tab-pane active" id="accountPaypal">
            <form id="accountPayPalForm" action="payment_paypal_update" method="post" class="form-horizontal">
                <input type="hidden" name="ppl_publisher_id" value="{{ Session::get('publisher.id'); }}" />
                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.paypal')],
                ['type' => 'text', 'name' => 'ppl_email', 'value' => $paypal ? $paypal->ppl_email : '', 'placeholder' => 'example@domain.com']
                ); }}

                <div class="modal-footer col-sm-10 col-md-offset-1">
                    <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('mi_cuenta.guardar'); }}</span></button>
                </div>
            </form>
        </div>
        <div class="tab-pane" id="accountBank">
            <form id="accountBankForm" action="payment_bank_update" method="post" class="form-horizontal">
                <input type="hidden" name="bnk_publisher_id" value="{{ Session::get('publisher.id'); }}" />

                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.nombre_cuenta_banco')],
                ['type' => 'text', 'name' => 'bnk_account_name']
                ); }}

                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.numero_cuenta_banco')],
                ['type' => 'text', 'name' => 'bnk_account_number']
                ); }}

                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.nombre_banco')],
                ['type' => 'text', 'name' => 'bnk_bank_name']
                ); }}

                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.codigo_ruta')],
                ['type' => 'text', 'name' => 'bnk_route_code']
                ); }}

                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.ciudad_banco')],
                ['type' => 'text', 'name' => 'bnk_city']
                ); }}

                <div id="infoCountry" class="form-group">
                    <label for="prf_country" class="col-sm-3 control-label">{{ Lang::get('mi_cuenta.pais_banco'); }}:</label>
                    <div class="col-sm-8">
                        <select name="bnk_country_id" class="form-control">
                            @foreach( Lang::get('countries') as $llave => $valor )
                            <option value="{{ $llave }}">{{ $valor }}</option>
                            @endforeach
                        </select>
                        <span class="help-block"></span>
                    </div>
                </div>

                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.swift_banco')],
                ['type' => 'text', 'name' => 'bnk_bic_code']
                ); }}

                {{ Forms::formGroup(
                ['text' => Lang::get('mi_cuenta.banco_intermidiario')],
                ['type' => 'text', 'name' => 'bnk_intermediary_bank']
                ); }}

                <div class="modal-footer col-sm-10 col-md-offset-1">
                    <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('mi_cuenta.guardar'); }}</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $().ready(function(){

        $('#accountBankForm button[type="submit"]').click(function(e){
            e.preventDefault();

            $('#accountBankForm').processForm(function(){
                if(resultCallback.error == 0)
                    $('#msgUpdateSaveModal').modal('toggle');
            });

            return false;
        });

        $('#accountPayPalForm').bootstrapValidator({
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                ppl_email: {
                    validators: {
                        emailAddress: {
                        },
                        notEmpty: {
                        },
                        stringLength: {
                            min: 5,
                            max: 255
                        }
                    }
                }
            }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();

            $('#accountPayPalForm').processForm(function(){
                if(resultCallback.error == 0)
                    $('#msgUpdateSaveModal').modal('toggle');
            });

            return false;
        });
    });
</script>