<div class="page-content inset container-fluid">

    <div class="row">
        <h2>In-Image Websites</h2>
    </div>

    <form method="post" action="save_imonomy" id="save_imonomy_form" class="form-horizontal">
        <input type="hidden" value="{{ $publisher->getId() }}" name="publisherId" />
        <div class="form-group">
            <label for="imonomy_publisher_id" class="col-sm-3 control-label">Publisher Imonomy Id:</label>
            <div class="col-sm-8">
                <input type="text" name="imonomy_publisher_id" class="form-control" @if($publisher->imonomy) value="{{ $publisher->imonomy->getImonomyId() }}" @endif />
            </div>
        </div>


        <div class="panel-body">
            @include('admin.tables.tbl_sitiosPublisherInImage', ['sites' => $publisher->sites])
        </div>
        <div class="modal-footer col-sm-10 col-md-offset-1">
            <button type="submit" id="save_imonomy_btn" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('admin.save'); }}</span></button>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        $('#save_imonomy_btn').click(function(e){
            e.preventDefault();
            $.ajax({
                data: $("#save_imonomy_form").serialize(),
                url: $("#save_imonomy_form").attr('action'),
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    if(result['error'] > 0){
                        swal("Datos de Imonomy", result['messages'], "error");
                    }else{
                    swal("Datos de Imonomy", "Guardado satisfactoriamente!", "success");
                }
                }
            });

            return false;
        });
    });
</script>