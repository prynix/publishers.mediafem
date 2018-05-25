<p class="col-sm-10"><b>We need you to send us a tax form as soon as possible. Otherwise, your future payments will not be processed.</b></p>
<form method="GET" action="/tax_data_usa_download" class="form-horizontal">
    <div class="form-group">
        <div class="col-sm-10 col-md-offset-1">
            <input name="usa_tax" type="submit" value="Download tax form for US Publishers" id="usa_tax" class="btn btn-default btn-marginR20">
        </div>
    </div>
</form>
<form method="GET" action="/tax_data_other_download" class="form-horizontal">
    <div class="form-group">
        <div class="col-sm-10 col-md-offset-1">
            <input name="other_tax" type="submit" value="Download tax form for NON-US Publishers" id="other_tax" class="btn btn-default btn-marginR20">
        </div>
    </div>
</form>
<hr class="col-sm-10 col-md-offset-1" />
<form method="POST" action="/tax_data_update" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group">
        <label for="file" class="col-sm-3 control-label">Upload PDF or Image file with Tax data:</label>
        <div class="col-sm-6">
            <input name="file" type="file" id="file" class="btn btn-default btn-marginR20">
        </div>
        <button type="submit" id="save_file" class="btn btn-primary ladda-button col-sm-2" data-style="zoom-out"><span class="ladda-label">Upload</span></button>
    </div>
</form>
@if(Session::get('tax.send'))
    @include('admin.general.message', ['type' => Session::pull('tax.send'), 'message' => Session::pull('tax.message')])
@endif
<script>
    $().ready(function () {

    });
</script>