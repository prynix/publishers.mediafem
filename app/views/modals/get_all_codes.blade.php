<!-- start: Modal get Codes -->
<div class="modal fade" id="getAllCodesModal" tabindex="-1" role="dialog" aria-labelledby="getAllCodesModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">{{ Lang::get('placements.get_all_codes'); }}</h4>
            </div>

                <div class="modal-body">
                    
                     <div class="alert alert-info">{{ Lang::get('placements.info_demora_placement'); }}.</div>

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#withHtml" data-toggle="tab">HTML</a></li>
                    <li><a href="#withWordpress" data-toggle="tab">Wordpress</a></li>
                    <li><a href="#withBlogger" data-toggle="tab">Blogger</a></li>
                </ul>

                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="withHtml">
                            {{ Lang::get('placements.instrucciones_html', ['brand' => Session::get('platform.brand')]) }}

                            <form action="#" method="post" class="form-horizontal">
                                <textarea name="placementsCodes" class="form-control" rows="5" readonly="true" style="cursor: text;"></textarea>
                            </form>
                        </div>

                        <div class="tab-pane" id="withWordpress">
                            {{ Lang::get('placements.instrucciones_wp', ['site' => $site->sit_name, 'brand' => Session::get('platform.brand')]) }}

                            <form action="#" method="post" class="form-horizontal">
                                <textarea name="placementsCodes" class="form-control" rows="5" readonly="true" style="cursor: text;"></textarea>
                            </form>
                        </div>

                        <div class="tab-pane" id="withBlogger">
                            {{ Lang::get('placements.instrucciones_blogger', ['brand' => Session::get('platform.brand')]) }}

                            <form action="#" method="post" class="form-horizontal">
                                <textarea name="placementsCodes" class="form-control" rows="5" readonly="true" style="cursor: text;"></textarea>
                            </form>
                        </div>
                    </div>
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
        $('textarea[name="placementsCodes"]').click(function(){ this.select(); });
        $('textarea[name="placementsCodes"]').load("placements_codes/"+{{$site->getId()}});
    });
</script>