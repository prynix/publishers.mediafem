@extends ('admin.general.layout')

@section ('title') @parent {{Lang::get('admin.constants-constants')}} @stop

@section ('section-title') {{Lang::get('admin.constants-constants')}} @stop

@section ('content')

<div class="panel panel-success">

    <div class="panel-heading"><h4 id="content_table" calss="panel-title" style="color:#d2322d;"><i class="glyphicon glyphicon-warning-sign"></i> {{Lang::get('admin.constants-suggest')}}</h4></div>
    <div class="panel-body">
        @if(count($constant_groups)>0)
        <ul>
        @foreach($constant_groups as $group)
        @if(count($group->constants)>0)
        <li><a href="#{{ $group->cns_grp_id }}">{{ $group->getName() }}</a></li>
        @endif
        @endforeach
        </ul>      
        <hr/>
        @foreach($constant_groups as $group)
        @if(count($group->constants)>0)
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title" id="{{ $group->cns_grp_id }}"><i class="glyphicon glyphicon-wrench"></i> {{ $group->getName() }}</h3>
            </div>
            <div class="panel-body">
                @foreach($group->constants as $constant)
                <form role="form" class="form-inline form" method="post" id="form{{$constant->cns_id}}" action="/admin/change_constant/{{$constant->cns_id}}" style="font-size: 12px !important;">
                    <div class="form-group">
                        <label for="inputValue{{$constant->cns_id}}" class="control-label">{{$constant->cns_description}}: </label>
                        <input type="text" name="cns_value" class="form-control" id="inputValue{{$constant->cns_id}}" placeholder="{{ Lang::get('admin.enter_value') }}" value="{{$constant->cns_value}}">
                    </div>
                    <button type="submit" class="btn btn-default btn-marginR20 changeConstant" data-style="zoom-out" data-constantId="{{$constant->cns_id}}">{{ Lang::get('admin.change') }}</button>
                    <span id="error{{$constant->cns_id}}" class="help-inline" style="color: #d43f3a"></span>
                </form>
                <br />
                @endforeach
                <a href="#content_table">&uarr; Volver</a>
            </div>
        </div>
        @endif
        @endforeach
        @else
        @include('admin.general.message', ['message' => Lang::get('admin.constants-no_constants'), 'type' => '2'])
        @endif
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".changeConstant").click(function (e) {
            e.preventDefault();
            $( this ).html(loader);
            var constantId = $(this).attr('data-constantId');
            $.ajax({
                data: $("#form" + constantId).serialize(),
                url: $("#form" + constantId).attr('action'),
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    if (result.error == 1 || result.error == 2) {
                        $('#error' + constantId).html(result.messages);
                        return false;
                    } else {
                        location.reload();
                    }
                }
            });
            return false;
        });
    });
</script>

@stop