<!-- start: Modal new Site -->
<div class="modal fade" id="createSiteModal" tabindex="-1" role="dialog" aria-labelledby="createSiteModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">{{ Lang::get('placements.crear_grupo_anuncios'); }}</h4>
            </div>
            <form id="createSiteModalForm" action="create_site" method="post" class="form-horizontal">
                <div class="modal-body">
                    <p>{{ Lang::get('placements.info_nuevo_anuncio'); }}.</p>

                    <input type="hidden" name="sit_publisher_id" value="{{ Session::get('publisher.id'); }}" />

                    {{ Forms::formGroup(
                    ['text' => Lang::get('placements.url_sitio')],
                    ['type' => 'text', 'name' => 'sit_name', 'placeholder' => 'http://www.domain.com']
                    ); }}
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">{{ Lang::get('general.cancelar'); }}</a>
                    <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('placements.crear_nuevo'); }}</span></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end: Modal new Site -->

<script>
    $().ready(function(){
        $('#createSiteModalForm').bootstrapValidator({
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

            $('#createSiteModalForm').processForm(function(){
                if(resultCallback.error === 0){
                    $('#siteSelect').append('<option value="' + resultCallback.sit_id + '" selected="selected">' + resultCallback.sit_name + '</option>');
                    $('#siteSelect').change();                
                    $('#createSiteModal').modal('hide');
                    $('#msgSiteModal').modal('toggle');
                }
            });

            return false;
        });
    });
</script>