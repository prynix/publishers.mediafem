@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general-sites') }} @stop

@section ('section-title') {{ Lang::get('admin.general-sites') }} @stop

@section ('content')

<div class="page-content inset container-fluid">
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ Lang::get('admin.sites-validated') }}:&nbsp;
                <select id="validated">
                    <option value="1">{{ Lang::get('admin.yes') }}</option>
                    <option value="0">{{ Lang::get('admin.no') }}</option>
                </select>
            <input type="button" class="btn btn-primary ladda-button" id="filterValidated" value="{{ Lang::get('admin.load') }}" />
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane active" id="sites_table_content">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="categorizeSite"></div>
</div>

<script>
    $(window).bind("load", function () {
        $('#sites_table_content').html(loader);
        var xhr;
        if (xhr && xhr.readystate != 4) {
            xhr.abort();
        }
        xhr = $.ajax({
            url: 'load_sites_table',
            type: 'GET',
            dataType: "html",
            success: function (result) {
                $('#sites_table_content').html(result);
            }
        });

        $("#filterValidated").click(function (e) {
            e.preventDefault();
            if (xhr && xhr.readystate != 4) {
                xhr.abort();
            }
            $("#filterValidated").val("{{ Lang::get('admin.wait') }}");
            $('#sites_table_content').html(loader);
            if ($('#validated').val() == '0') {
                xhr = $.ajax({
                    url: 'load_unvalidated_sites_table',
                    type: 'GET',
                    dataType: "html",
                    success: function (result) {
                        $('#sites_table_content').html(result);
                        $("#filterValidated").val("{{ Lang::get('admin.load') }}");
                    }
                });
            } else {
                xhr = $.ajax({
                    url: 'load_sites_table',
                    type: 'GET',
                    dataType: "html",
                    success: function (result) {
                        $('#sites_table_content').html(result);
                        $("#filterValidated").val("{{ Lang::get('admin.load') }}");
                    }
                });
            }
        });

    });
</script>

@stop