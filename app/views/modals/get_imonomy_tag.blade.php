<!-- start: Modal get Codes -->
<div class="modal fade" id="getImonomyTagModal" tabindex="-1" role="dialog" aria-labelledby="getImonomyTagModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">{{ Lang::get('placements.get_all_codes'); }}</h4>
            </div>

            <div class="modal-body">

                <div class="panel-body">
                        In Image Format:

                        <form action="#" method="post" class="form-horizontal">
                            <textarea name="imonomyCode" class="form-control" rows="5" readonly="true" style="cursor: text;"></textarea>
                        </form>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" data-dismiss="modal">{{ Lang::get('general.aceptar'); }}</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end: Modal get Codes -->

<script>
    $().ready(function () {
        $('textarea[name="imonomyCode"]').click(function () {
            this.select();
        });
        $('textarea[name="imonomyCode"]').load("imonomy_code/" + {{ $site->getId() }});
    });
</script>