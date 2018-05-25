<div class="widget">
    <div class="row">
        <h2>Datos del sitio {{ $site->getName() }}</h2>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#accountInfo" data-toggle="tab">Información</a></li>
    </ul>
    <div class="panel-body" id="user" data-userId="{{ $site->getId() }}">
        <div class="tab-content">
            <div class="tab-pane active" id="accountInfo">
                <form id="accountSiteForm" action="admin/update_site_data/{{ $site->getId() }}" method="post" class="form-horizontal">

                    <div id="hasToOptimize" class="form-group">
                        <label for="sit_state" class="col-sm-3 control-label">Verificado por ejecutivo:</label>
                        <div class="col-sm-8">
                           <select name="sit_state" class="form-control">
                                <option value="0" {{ $site->sit_state == 0 ? 'selected="selected"' : '' }}>{{ Lang::get('admin.no') }}</option>
                                <option value="1" {{ $site->sit_state == 1 ? 'selected="selected"' : '' }}>{{ Lang::get('admin.yes') }}</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="modal-footer col-sm-10 col-md-offset-1">
                        <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('admin.save') }}</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#accountSiteForm #submit_form").click(function (e) {
            e.preventDefault();
            $( this ).html(loader);
            $.ajax({
                data: $("#accountSiteForm").serialize(),
                url: $("#accountSiteForm").attr('action'),
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    $("#accountSiteForm #submit_form").html("{{ Lang::get('admin.save') }}");
                    if (result.error == 1 || result.error == 2) {
                        $('#errores').html(result.messages);
                        return false;
                    }

                }
            });

            return false;
        });
    });
</script>