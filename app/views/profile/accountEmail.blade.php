<form id="accountEmailForm" action="email_update" method="post" class="form-horizontal">
    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.email')],
    ['type' => 'text', 'name' => 'email', 'value' => Session::get('user.email'), 'placeholder' => 'example@example.com']
    ); }}
    <div class="modal-footer col-sm-10 col-md-offset-1">
        <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('mi_cuenta.guardar'); }}</span></button>
    </div>
</form>
<script>
    $().ready(function(){
        $('#accountEmailForm').bootstrapValidator({
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                email: {
                    validators: {
                        emailAddress: {
                        },
                        notEmpty: {
                        },
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

            $('#accountEmailForm').processForm(function(){
                if(resultCallback.error == 0)
                    $('#msgUpdateSaveModal').modal('toggle');
            });

            return false;
        });
    });
</script>