<!-- start: Modal code of Placement -->
<div class="modal fade" id="placementCode" tabindex="-1" role="dialog" aria-labelledby="placementCode" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">{{ Lang::get('placements.implementar_tag', ['brand' => Session::get('platform.brand')]); }}</h4>
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
                            {{ Lang::get('placements.instrucciones_html', ['brand' => Session::get('platform.brand')]); }}

                            <form action="#" method="post" class="form-horizontal">
                                <textarea name="placementCode" class="form-control" rows="5" readonly="true" style="cursor: text;"></textarea>
                            </form>
                        </div>

                        <div class="tab-pane" id="withWordpress">
                            {{ Lang::get('placements.instrucciones_wp', ['site' => $site->sit_name, 'brand' => Session::get('platform.brand')]); }}

                            <form action="#" method="post" class="form-horizontal">
                                <textarea name="placementCode" class="form-control" rows="5" readonly="true" style="cursor: text;"></textarea>
                            </form>
                        </div>

                        <div class="tab-pane" id="withBlogger">
                            {{ Lang::get('placements.instrucciones_blogger', ['brand' => Session::get('platform.brand')]); }}

                            <form action="#" method="post" class="form-horizontal">
                                <textarea name="placementCode" class="form-control" rows="5" readonly="true" style="cursor: text;"></textarea>
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
<!-- end: Modal code of Placement -->

<script>
    $().ready(function(){
        
        $('textarea[name="placementCode"]').click(function(){ this.select(); });

        $('#createSiteForm').bootstrapValidator({
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                infoURL: {
                    validators: {
                        uri:{},
                        notEmpty: {},
                        stringLength: {
                            min: 5,
                            max: 255
                        }
                    }
                }
            }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();

            $('#createSiteForm').processForm(function(){
                if(resultCallback.error === 0){
                    $('#siteSelect').append('<option value="' + resultCallback.sit_id + '" selected="selected">' + resultCallback.sit_name + '</option>');
                    $('#siteSelect').change();                
                    $('#createSiteModal').modal('hide');
                }
            });

            return false;
        });
    });
</script>