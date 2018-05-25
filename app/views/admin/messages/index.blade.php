@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general-messages') }} @stop

@section ('section-title') {{ Lang::get('admin.general-messages') }} @stop

@section ('content')

<div class="row widget">
    <div class="col-md-12">
        <a href="" class="btn btn-default" data-toggle="modal" data-target="#sendMessageModal"><i class="fa fa-envelope-o"></i> {{ Lang::get('admin.messages-send') }}</a>
        <!--<a href="" class="btn btn-default"><i class="fa fa-file-text-o"></i> Mensajes predeterminados</a>-->
        <a href="" class="btn btn-default" data-toggle="modal" data-target="#createMessageDefaultModal"><i class="fa fa-plus-square-o"></i> {{ Lang::get('admin.messages-new_default') }}</a>
    </div>
</div>

<div class="row widget">
    <div class="col-md-12">
        @include('admin.tables.tbl_messagesList', ['messages' => $messages])
    </div>
</div>

@include('admin.messages.create_default', ['new_group' => $new_group])

@include('admin.messages.send')

@stop