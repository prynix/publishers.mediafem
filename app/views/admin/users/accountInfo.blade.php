<form id="accountInfoForm" action="create_administrator" method="post" class="form-horizontal">
    <input type="hidden" value="{{ $user->getId() }}" name="user_id" />

    @if($user->administrator)
    @if(count($user->administrator->adservers)>0)
    <div class="form-group">
        <label class="col-sm-3 control-label">Usuario Media Buyer en:</label>
        <ul class="col-sm-8">
            @foreach($user->administrator->adservers as $adserver)
            <li>&nbsp;&nbsp;{{ $adserver->getName() }} ({{ $adserver->pivot->adm_adv_adserver_key }})</li>
            @endforeach
        </ul>
    </div>
    @endif
    @else
    @foreach($user->adservers as $adserver)
    <div class="form-group">
        <label class="col-sm-3 control-label">Adserver preasignado:</label>
        <div class="col-sm-8">{{ $adserver->getName() }}</div>
    </div>
    @if($adserver->pivot->media_buyer_id)
    <?php $admin = Administrator::find($adserver->pivot->media_buyer_id); ?>
    <div class="form-group">
        <label class="col-sm-3 control-label">Media buyer preasignado:</label>
        <div class="col-sm-8">{{ $admin->getName() }}</div>
    </div>
    @endif

    @endforeach
    @endif

    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">Correo Electrónico:</label>
        <div class="col-sm-8">{{ $user->getEmail() }}</div>
    </div>

    <div class="form-group">
        <label for="prf_name" class="col-sm-3 control-label">{{ Lang::get('mi_cuenta.nombre_completo'); }}:</label>
        <div class="col-sm-8">
            <input class="form-control" type="text" name="prf_name" value="@if ($user->profile){{ $user->profile->getName() }}@endif">
            <span class="help-block"></span>
        </div>
    </div>
    @if($user->administrator)
        @if(($user->administrator->group->getId() == 2) || ($user->administrator->group->getId() == 3))
            <div class="form-group">
                <label for="prf_name" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-revenue_share'); }}:</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="adm_revshare" value="{{ $user->administrator->adm_revenue_share }}">
                    <span class="help-block"></span>
                </div>
            </div>
        @endif
    @endif

    <div class="form-group">
        <div class="col-sm-9" id="errores"></div>
    </div>
    <div class="modal-footer col-sm-10 col-md-offset-1">
        <!--<button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">Crear Administrador</span></button>-->
        @if(!$user->isActived())
        <button type="submit" id="submit_form2" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">Activar Usuario</span></button>
        @endif
        @if($user->administrator)
        <button type="submit" id="submit_form3" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">Guardar Datos</span></button>
        @endif
    </div>

</form>

<script>
    //Create Administrator (DISABLED)
    $("#accountInfoForm #submit_form").click(function (e) {
        e.preventDefault();
        var datos = {
            'prf_name': $('input[name="prf_name"]').val(),
            'user_id': $('input[name="user_id"]').val()
        };
        $.ajax({
            data: datos,
            url: 'create_administrator',
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

    //Activate User
    $("#accountInfoForm #submit_form2").click(function (e) {
        e.preventDefault();
        var datos = {
            'prf_name': $('input[name="prf_name"]').val(),
            'user_id': $('input[name="user_id"]').val()
        };
        $.ajax({
            data: datos,
            url: 'activate_user',
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
    
    //Save Account data
    $("#accountInfoForm #submit_form3").click(function (e) {
        e.preventDefault();
        var datos = {
            'prf_name': $('input[name="prf_name"]').val(),
            'adm_revshare': $('input[name="adm_revshare"]').val(),
            'user_id': $('input[name="user_id"]').val()
        };
        $.ajax({
            data: datos,
            url: 'update_admin',
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
</script>