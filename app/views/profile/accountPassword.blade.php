<form id="accountPasswordForm" action="password_update" method="post" class="form-horizontal">
    <input type="hidden" name="code" value="{{ $resetCode }}" />
    <input type="hidden" name="user_id" value="{{ Session::get('user.id') }}" />

    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.new_password')],
    ['type' => 'password', 'name' => 'password', 'placeholder' => 'password']
    ); }}

    {{ Forms::formGroup(
    ['text' => Lang::get('mi_cuenta.repeat_new_password')],
    ['type' => 'password', 'name' => 'repeat_password', 'placeholder' => 'password']
    ); }}

    <div class="modal-footer col-sm-10 col-md-offset-1">
        <button type="submit" id="submit_form" class="btn btn-primary ladda-button" data-style="zoom-out"><span class="ladda-label">{{ Lang::get('mi_cuenta.guardar'); }}</span></button>
    </div>
</form>
<script>
    $().ready(function(){
        $('#accountPasswordForm').bootstrapValidator({
            excluded: [':disabled'],
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                password: {
                    validators: {
                        notEmpty: {
                        },
                        stringLength: {
                            min: 5,
                            max: 255
                        }
                    }
                },
                repeat_password: {
                    validators: {
                        identical: {
                            field: 'password'
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

            $('#accountPasswordForm').processForm(function(){
                if(resultCallback.error == 0)
                    $('#msgUpdateSaveModal').modal('toggle');
            });

            return false;
        });
    });
</script>