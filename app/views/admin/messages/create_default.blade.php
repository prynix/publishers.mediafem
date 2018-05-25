<!-- createMessageDefaultModal -->
<div class="modal fade" id="createMessageDefaultModal" tabindex="-1" role="dialog" aria-labelledby="createMessageDefaultModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">{{ Lang::get('admin.messages-new_default') }}.</h4>
            </div>

            <div class="modal-body">
                <div class="tab-pane" id="msgd_en" style="margin-top: 10px;">
                    <form id="default_en" method="post" action="messages/add_default">
                        <input type="hidden" name="msgd_language_id" value="1" />

                        {{ Forms::formGroupV(
                        ['text' => Lang::get('admin.messages-default_name')],
                        ['type' => 'text', 'name' => 'msgd_group_name', 'placeholder' => Lang::get('admin.messages-default_name')]
                        ); }}

                        {{ Forms::formGroupV(
                        ['text' => Lang::get('admin.messages-subject')],
                        ['type' => 'text', 'name' => 'msgd_subject', 'placeholder' => Lang::get('admin.messages-subject')]
                        ); }}

                        {{ Forms::formGroupV(
                        ['text' => Lang::get('admin.messages-sender')],
                        ['type' => 'text', 'name' => 'msgd_from', 'placeholder' => Lang::get('admin.messages-sender')]
                        ); }}

                        <div class="form-group">
                            <label for="msgd_content" class="control-label">{{ Lang::get('admin.messages-message') }}:</label>
                            <textarea name="msgd_content" class="msgd_content_en"></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" id="submit_form" class="btn btn-primary">{{ Lang::get('admin.create') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- end createMessageDefaultModal -->

<script>
    $(document).ready(function() {
    $('.msgd_content_en').summernote({
    height: 150,
            tabsize: 2
    });
            $('#submit_form').click(function(e){
    e.preventDefault();
            data = {
            msgd_language_id : 1,
                    msgd_group : {{ $new_group }},
                    msgd_subject : $.trim($('#default_en input[name="msgd_subject"]').val()),
                    msgd_from    : $.trim($('#default_en input[name="msgd_from"]').val()),
                    msgd_content : $.trim($('#default_en .msgd_content_en').code()),
                    msgd_group_name : $.trim($('#default_en input[name="msgd_group_name"]').val())
            };
            $.ajax({
            data:  data,
                    url:   $('#default_en').attr('action'),
                    type:  'post',
                    dataType: 'json',
                    success:  function (result) {
                        if (result.error == 1){
                            console.log(result.messages);
                        } else{
                            window.location = location.href;
                        }
                    }
            });
            return false;
    });
    });
</script>