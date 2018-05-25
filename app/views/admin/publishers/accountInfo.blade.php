<!-- Solo se muestra si el publisher aun no migro los tags -->
@if($publisher->getShowAlert()) 
<form id="unlockReports" action="admin/hide_alert/{{ $publisher->getId() }}" method="POST" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-3 control-label">{{ Lang::get('admin.publishers-has_migrated') }}:</label>
        <div class="col-sm-8">
            <select name="alert" class="form-control">
                <option value="1">{{ Lang::get('admin.no') }}</option>
                <option value="0">{{ Lang::get('admin.yes') }}</option>
            </select>
        </div>
    </div>
    <div class="modal-footer col-sm-10 col-md-offset-1">
        <span id="problemUnlockReports" style="color: #d2322d;"></span>
        <button type="submit" id="submit_form_unlock_reports" data-publisherId="{{ $publisher->getId() }}" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('admin.save') }}</span></button>
    </div>
</form>
@endif

<form id="accountInfoForm" action="admin/update_account_data/{{ $publisher->user->getId() }}" method="post" class="form-horizontal">
    <fieldset @unless(Utility::hasPermission('publishers.change_data')) disabled @endunless >
               <div class="form-group">
            <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-email') }}:</label>
            <div class="col-sm-8">{{ $publisher->user->getEmail() }}</div>
        </div>
        <div class="form-group">
            <label for="infoURL" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-primary_url') }}:</label>
            <div class="col-sm-8">{{ $publisher->getName() }}</div>
        </div>

        <div class="form-group">
            <label for="infoURL" class="col-sm-3 control-label">{{ Lang::get('admin.language') }}:</label>
            <div class="col-sm-8">{{ $publisher->user->profile->language->getName() }}</div>
        </div>

        {{ Forms::formGroup(
    ['text' => Lang::get('admin.publishers-payment_days')],
    ['type' => 'text', 'name' => 'pbl_days_to_billing', 'value' => empty($publisher->pbl_days_to_billing) ? Config::get('constants.pbl_days_to_billing') : $publisher->pbl_days_to_billing , 'placeholder' => '']
    ); }}

        {{ Forms::formGroup(
    ['text' => Lang::get('admin.publishers-revenue_share')],
    ['type' => 'text', 'name' => 'pbl_revenue_share', 'value' => $publisher->pbl_revenue_share, 'placeholder' => '']
    ); }}
    
    <div id="hasToOptimize" class="form-group">
            <label for="pbl_has_to_optimize" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-has_to_optimize') }}:</label>
            <div class="col-sm-8">
                <select name="pbl_has_to_optimize" class="form-control">
                    <option value="0" {{ $publisher->pbl_has_to_optimize == 0 ? 'selected="selected"' : '' }}>{{ Lang::get('admin.no') }}</option>
                    <option value="1" {{ $publisher->pbl_has_to_optimize == 1 ? 'selected="selected"' : '' }}>{{ Lang::get('admin.yes') }}</option>
                </select>
                <span class="help-block"></span>
            </div>
        </div>
    
    <div id="hasToOptimize" class="form-group">
            <label for="platform_id" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-platform') }}:</label>
            <div class="col-sm-8">
                <select name="platform_id" class="form-control">
                    <option value="1" {{ $publisher->user->platform_id == 1 ? 'selected="selected"' : '' }}>Adtomatik</option>
                    <option value="2" {{ $publisher->user->platform_id == 2 ? 'selected="selected"' : '' }}>MediaFem</option>
                </select>
                <span class="help-block"></span>
            </div>
        </div>
    
        {{ Forms::formGroup(
    ['text' => Lang::get('admin.publishers-full_name')],
    ['type' => 'text', 'name' => 'prf_name', 'value' => $publisher->user->profile->getName(), 'placeholder' => Lang::get('mi_cuenta.nombre_apellido')]
    ); }}

        <div id="infoCountry" class="form-group">
            <label for="prf_country" class="col-sm-3 control-label">{{ Lang::get('admin.country') }}:</label>
            <div class="col-sm-8">
                <select name="prf_country" class="form-control">
                    @foreach( Lang::get('countries') as $llave => $valor )
                    <option value="{{ $llave }}" {{ $llave === $publisher->user->profile->prf_country_id ? 'selected="selected"' : '' }}>{{ $valor }}</option>
                    @endforeach
                </select>
                <span class="help-block"></span>
            </div>
        </div>

        {{ Forms::formGroup(
    ['text' => Lang::get('admin.publishers-address')],
    ['type' => 'text', 'name' => 'prf_address', 'value' => $publisher->user->profile->prf_address, 'placeholder' => Lang::get('mi_cuenta.calle_numero')]
    ); }}

        {{ Forms::formGroup(
    ['text' => Lang::get('admin.publishers-city')],
    ['type' => 'text', 'name' => 'prf_city', 'value' => $publisher->user->profile->prf_city, 'placeholder' => Lang::get('mi_cuenta.ciudad')]
    ); }}

        {{ Forms::formGroup(
    ['text' => Lang::get('admin.publishers-zip')],
    ['type' => 'text', 'name' => 'prf_zip_code', 'value' => $publisher->user->profile->prf_zip_code, 'placeholder' => '9999']
    ); }}

        {{ Forms::formGroup(
    ['text' => Lang::get('admin.publishers-phone') . ' (' . Lang::get('mi_cuenta.opcional'). ')'],
    ['type' => 'text', 'name' => 'prf_phone', 'value' => $publisher->user->profile->prf_phone, 'placeholder' => '9999-9999']
    ); }}

    @if(Utility::hasPermission('publishers.change_data'))
        <div class="modal-footer col-sm-10 col-md-offset-1">
            <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('admin.save') }}</span></button>
        </div>
    @endif
    </fieldset>
</form>

<script>
    $(document).ready(function () {
        $("#accountInfoForm #submit_form").click(function (e) {
            e.preventDefault();
            $( this ).html(loader);
            $.ajax({
                data: $("#accountInfoForm").serialize(),
                url: $("#accountInfoForm").attr('action'),
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    $("#accountInfoForm #submit_form").html("{{ Lang::get('admin.save') }}");
                    if (result.error == 1 || result.error == 2) {
                        $('#errores').html(result.messages);
                        return false;
                    }

                }
            });

            return false;
        });
        $("#unlockReports #submit_form_unlock_reports").click(function (e) {
            e.preventDefault();
            $.ajax({
                data: $("#unlockReports").serialize(),
                url: $("#unlockReports").attr('action'),
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    if (result.error == 1 || result.error == 2) {
                        $('#problemUnlockReports').html(result.messages);
                        return false;
                    } else {
                        var idPublisher = $('#submit_form_unlock_reports').attr('data-publisherId');
                        $('#publisherData').html(loader).load('admin/publisher_details/' + idPublisher);
                        return false;
                    }
                }
            });

            return false;
        });
    });
</script>