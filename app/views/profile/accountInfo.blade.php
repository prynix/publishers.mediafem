<form id="accountInfoForm" action="profile_update" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('mi_cuenta.email') }}:</label>
        <div class="col-sm-8">{{ $infoAccount->user->getEmail() }}</div>
    </div>
    <div class="form-group">
        <label for="infoURL" class="col-sm-3 control-label">{{ Lang::get('mi_cuenta.url') }}:</label>
        <div class="col-sm-8">{{ $infoAccount->user->publisher->getName() }}</div>
    </div>

    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.nombre_completo')],
    ['type' => 'text', 'name' => 'prf_name', 'value' => $infoAccount->prf_name, 'placeholder' => Lang::get('mi_cuenta.nombre_apellido')]
    ); }}

    <div id="infoCountry" class="form-group">
        <label for="prf_country" class="col-sm-3 control-label">{{ Lang::get('mi_cuenta.pais'); }}:</label>
        <div class="col-sm-8">
            <select name="prf_country" class="form-control">
                @foreach( Lang::get('countries') as $llave => $valor )
                <option value="{{ $llave }}" {{ $llave === $infoAccount->prf_country_id ? 'selected="selected"' : '' }}>{{ $valor }}</option>
                @endforeach
            </select>
            <span class="help-block"></span>
        </div>
    </div>

    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.domicilio')],
    ['type' => 'text', 'name' => 'prf_address', 'value' => $infoAccount->prf_address, 'placeholder' => Lang::get('mi_cuenta.calle_numero')]
    ); }}

    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.ciudad')],
    ['type' => 'text', 'name' => 'prf_city', 'value' => $infoAccount->prf_city, 'placeholder' => Lang::get('mi_cuenta.ciudad')]
    ); }}

    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.codigo_postal')],
    ['type' => 'text', 'name' => 'prf_zip_code', 'value' => $infoAccount->prf_zip_code, 'placeholder' => '9999']
    ); }}

    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.telefono') . ' (' . Lang::get('mi_cuenta.opcional'). ')'],
    ['type' => 'text', 'name' => 'prf_phone_number', 'value' => $infoAccount->prf_phone_number, 'placeholder' => '9999-9999']
    ); }}
    <div class="modal-footer col-sm-10 col-md-offset-1">
        <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('mi_cuenta.guardar_datos'); }}</span></button>
    </div>
</form>

<script>
    $().ready(function(){
        $('#accountInfoForm').bootstrapValidator({
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                prf_name: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 5,
                            max: 50
                        }
                    }
                },
                prf_address: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 5,
                            max: 50
                        }
                    }
                },
                prf_city: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 5,
                            max: 50
                        }
                    }
                },
                prf_zip_code: {
                    validators: {
                        notEmpty: {
                        }
                    }
                }
            }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();

            $('#accountInfoForm').processForm(function(){
                if(resultCallback.error == 0)
                    $('#msgUpdateSaveModal').modal('toggle');
            });

            return false;
        });
    });
</script>