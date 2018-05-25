<!-- sendMessageModal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="sendMessageModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ Lang::get('admin.messages-send') }}</h4>
            </div>

            <form id="sendMessage" method="post" action="messages/send_message">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">{{ Lang::get('admin.messages-default') }}:</label>
                        <select name="menssages_default" class="form-control">
                            <option value="0" selected="selected">[ {{ Lang::get('admin.messages-none') }} ]</option>

                            @foreach($messages_default as $default)
                            <option value="{{ $default->msgdg_id }}">{{ $default->msgdg_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">{{ Lang::get('admin.messages-receiver') }}:</label>
                        <select name="msg_user_id" class="form-control" multiple="multiple">
                            <option value="0">{{ Lang::get('admin.messages-all_users') }}</option>

                            @foreach($publishers as $publisher)
                            <option value="{{ $publisher->user->id }}">{{ $publisher->getName() }} ({{ $publisher->user->email . ' - ' . Lang::get('idiomas.'.$publisher->user->profile->language->getShort()) }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{ Forms::formGroupV(
                    ['text' => Lang::get('admin.messages-subject')],
                    ['type' => 'text', 'name' => 'msg_subject', 'placeholder' => Lang::get('admin.messages-subject')]
                    ); }}

                    {{ Forms::formGroupV(
                    ['text' => Lang::get('admin.messages-sender')],
                    ['type' => 'text', 'name' => 'msg_from', 'placeholder' => Lang::get('admin.messages-sender')]
                    ); }}

                    <div class="form-group">
                        <label for="msg_content" class="control-label">{{ Lang::get('admin.messages-message') }}:</label>
                        <div class="msg_content"></div>
                    </div>

                    <div class="form-group">
                        <label><input type="checkbox" name="msg_send_email" value="1" /> {{ Lang::get('admin.messages-send_email') }}</label>
                    </div>
                </div>                

                <div class="modal-footer">
                    <button type="submit" id="submit_form" class="btn btn-primary">{{ Lang::get('admin.messages-send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end sendMessageModal -->


<script>
    $(document).ready(function(){
        $('.msg_content').summernote({
            height: 100,
            tabsize: 2
        });
        
        $('#sendMessage select[name="msg_user_id"]').select2({
            placeholder: "{{ Lang::get('admin.messages-select_receivers') }}"
        });
        
        $('#sendMessage select[name="menssages_default"]').change(function(){
            if($(this).val() == 0){
                $('#sendMessage input[name="msg_subject"]').val('');
                $('#sendMessage input[name="msg_from"]').val('');
                $('#sendMessage .msg_content').code('');
            }else{
                
                var data = {
                    msgd_id : $(this).val()
                };
                
                $.ajax({
                    data:  data,
                    url:   'messages/get_default',
                    type:  'post',
                    dataType: 'json',
                    success:  function (result) {                
                        $('#sendMessage input[name="msg_subject"]').val(result.subject);
                        $('#sendMessage input[name="msg_from"]').val(result.from);
                        $('#sendMessage .msg_content').code(result.content);
                    }
                });
            }
        });
        
        $('#sendMessage #submit_form').click(function(e){
            e.preventDefault();
            
            $(this).val('Enviando...');
            
            var send_email = $('#sendMessage input[name="msg_send_email"]').is(':checked') ? 1 : 0;
            
            var data = {
                msg_user_id : $('#sendMessage select[name="msg_user_id"]').val(),
                msg_subject : $.trim($('#sendMessage input[name="msg_subject"]').val()),
                msg_from    : $.trim($('#sendMessage input[name="msg_from"]').val()),
                msg_content : $.trim($('#sendMessage .msg_content').code()),
                msg_send_email : $.trim(send_email),
                msgdg_id    : $('#sendMessage select[name="menssages_default"]').val()
            };
            
            $.ajax({
                data:  data,
                url:   $('#sendMessage').attr('action'),
                type:  'post',
                dataType: 'json',
                success:  function () {
                    alert('Mensaje enviado');
                    window.location = location.href;
                }
            });
            
            return false; 
        });
    });
</script>