@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general-users') }} @stop

@section ('section-title') {{ Lang::get('admin.general-users') }} @stop

@section ('content')

<div class="page-content inset container-fluid">

    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active">
                <button id="add_user" data-toggle="modal" data-target="#addUserModal" class="btn btn-default btn-marginR20 floatRight"><i class="fa fa-plus-square-o"></i> Agregar usuario</button>
                <div id="users_table_div"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" id="userData"></div>
</div>

<!-- start: Modal add user -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Nuevo usuario</h4>
            </div>
            <form id="createUserForm" action="/admin/add_user" method="post"  class="form-horizontal" >
                <div class="modal-body">
                    <label>Perfil de usuario</label>
                    <select name="group_id" class="form-control">
                        @foreach( Group::all() as $group )
                        <option value="{{ $group->getId() }}">
                            {{ $group->getDescription() }}
                        </option> 
                        @endforeach
                    </select>
                    <br />
                    <label>Ejecutivo de Adserver/s</label>
                    <div class="row">
                        @foreach( Adserver::all() as $adserver )
                        @if($adserver->getId() != 1)
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <input type="checkbox" class="adserver_ids" name="adserver[]" id="adserver{{ $adserver->getId() }}" value="{{ $adserver->getId() }}">
                                </span>
                                <label for="adserver{{ $adserver->getId() }}" class="form-control">{{ $adserver->getName() }}</label>
                            </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                        @endif
                        @endforeach
                    </div><!-- /.row -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="text" id="email" name="email" class="form-control" placeholder="" value="" />
                        </div>
                    </div>
                    <div id="error" style="margin-bottom: 5px !important;"></div>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="add_user_btn">Agregar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end: Modal add user -->

<script>
    $(window).bind("load", function () {
        $('[name="group_id"]').on('change', function(){
            if(this.value == 2){
                $('.adserver_ids').removeAttr('checked');
            }else{
                $(".adserver_ids").prop("disabled", false);
            }
        });
        
        $(".adserver_ids").click(function() {
            
            if($('[name="group_id"]').val() == 2){
                var bol = $(".adserver_ids:checked").length >= 1;     
                $(".adserver_ids").not(":checked").attr("disabled",bol);
            }

        });
        
        $('#users_table_div').html(loader).load('/admin/load_users_table');
        $('#createUserForm').bootstrapValidator({
            framework: 'bootstrap',
            feedbackIcons: {
                valid: 'fa fa-check-circle-o',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            },
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Es obligatorio completar el email'
                        },
                        emailAddress: {
                            message: 'Debe ingresar un email válido'
                        }
                    }
                }
            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            $("#add_user_btn").html(loader);
            $('#createUserForm').processForm(function () {
                location.reload();
            }, function () {
                $("#add_user_btn").html("Agregar");
                $("#error").addClass('alert alert-danger');
                $("#error").html(resultCallback.messages);
            });

            return false;
        });
    });

</script>

@stop