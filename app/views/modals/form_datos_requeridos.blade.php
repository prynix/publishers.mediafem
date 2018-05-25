<div class="modal fade" id="accountInfoModal" tabindex="-1" role="dialog" aria-labelledby="accountInfoModal" aria-hidden="false" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ Lang::get('mi_cuenta.informacion_cuenta'); }}</h4>
            </div>

            <form id="accountInfoModalForm" method="post" class="form-horizontal" action="profile_update">
                <div class="modal-body">
                    <p class="alert alert-info">{{ Lang::get('mi_cuenta.info_requerido_1'); }}.</p>
                    <div id="infoEmail" class="form-group">
                        <label for="infoEmail" class="col-sm-4 control-label">{{ Lang::get('mi_cuenta.email'); }}:</label>
                        <div class="col-sm-7">
                            <input type="text" name="infoEmail" class="form-control" value="{{ Session::get('user.email') }}" placeholder="example@domain.com" disabled="disabled" />
                        </div>
                    </div>

                    {{ Forms::formGroup(
                    ['text' => Lang::get('mi_cuenta.url'), 'col' => 4],
                    ['type' => 'text', 'name' => 'pbl_name', 'placeholder' => 'http://www.domain.com', 'col' => 7]
                    ); }}

                    {{ Forms::formGroup(
                    ['text' => Lang::get('mi_cuenta.nombre_completo'), 'col' => 4],
                    ['type' => 'text', 'name' => 'prf_name', 'placeholder' => Lang::get('mi_cuenta.nombre_apellido'), 'col' => 7]
                    ); }}

                    <div id="infoCountry" class="form-group">
                        <label for="prf_country" class="col-sm-4 control-label">{{ Lang::get('mi_cuenta.pais'); }}:</label>
                        <div class="col-sm-7">
                            <select name="prf_country" class="form-control">
                                <!--@foreach( Lang::get('countries') as $llave => $valor )
                                <option value="{{ $llave }}">{{ $valor }}</option>
                                @endforeach-->
                                @foreach( Country::all() as $country )
                                    @if(($country->cnt_id != '--') && Lang::get('countries.'.$country->cnt_id) != 'countries.'.$country->cnt_id)
                                        <option value="{{ $country->cnt_id }}">{{ Lang::get('countries.'.$country->cnt_id) }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    {{ Forms::formGroup(
                    ['text' => Lang::get('mi_cuenta.domicilio'), 'col' => 4],
                    ['type' => 'text', 'name' => 'prf_address', 'placeholder' => Lang::get('mi_cuenta.calle_numero'), 'col' => 7]
                    ); }}

                    {{ Forms::formGroup(
                    ['text' => Lang::get('mi_cuenta.ciudad'), 'col' => 4],
                    ['type' => 'text', 'name' => 'prf_city', 'placeholder' => Lang::get('mi_cuenta.ciudad'), 'col' => 7]
                    ); }}

                    {{ Forms::formGroup(
                    ['text' => Lang::get('mi_cuenta.codigo_postal'), 'col' => 4],
                    ['type' => 'text', 'name' => 'prf_zip_code', 'placeholder' => '9999', 'col' => 7]
                    ); }}

                    {{ Forms::formGroup(
                    ['text' => Lang::get('mi_cuenta.telefono') . ' (' . Lang::get('mi_cuenta.opcional'). ')', 'col' => 4],
                    ['type' => 'text', 'name' => 'prf_phone_number', 'placeholder' => '9999-9999', 'col' => 7]
                    ); }}
                </div>
                <div class="modal-footer">
                    <a href="/logout" class="btn btn-default">{{ Lang::get('general.cerrar_session'); }}</a>
                    <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('mi_cuenta.guardar_datos'); }}</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $().ready(function(){
        $('#accountInfoModal').modal('toggle');

        $('#accountInfoModalForm').bootstrapValidator({
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                pbl_name: {
                    validators: {
                        /*uri:{},*/
                        notEmpty: {},
                        stringLength: {
                            min: 5,
                            max: 255
                        }
                    }
                },
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

            $('#accountInfoModalForm').processForm(function(){
                $('#accountInfoModal').modal('hide');
            });

            return false;
        });
    });
</script>