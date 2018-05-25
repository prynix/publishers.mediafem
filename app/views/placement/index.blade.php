@extends ('general.layout')

@section ('title') @parent {{ Lang::get('general.espacios'); }} @stop

@section ('section-title') {{ Lang::get('general.espacios'); }} @stop

@section ('content')

<div class="row">
    <h2>{{ Lang::get('placements.administrar_anuncios'); }}.</h2>
    <div class="col-md-12">
        <form action="#" method="post" class="form-inline btn-marginR20" style="display: inline-block;">
            <div class="form-group">
                <label for="siteSelect" class="control-label">{{ Lang::get('placements.espacios_sitio'); }}:</label>
                <select id="siteSelect" class="form-control input-sm">
                    @foreach( $sites as $site )
                    <option value="{{ $site->sit_id }}">{{ $site->sit_name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <a href="" class="btn btn-default btn-marginR20" data-toggle="modal" data-target="#createSiteModal"><i class="fa fa-plus-square-o"></i> {{ Lang::get('placements.crear_grupo_anuncios'); }}</a>
        <!--
        <a href="">{{ Lang::get('placements.ejemplos_anuncios'); }}</a> 
        <a href="">{{ Lang::get('placements.como_crear_anuncios'); }}</a>
        -->
    </div>
</div>

<div class="row">
    <div class="col-md-12 alert alert-info">{{ Lang::get('placements.info_copiar_placement'); }}.</div>
</div>

<div class="row">
    <div class="col-md-12" id="placement_list"></div>
</div>

<script>
    $(document).ready(function () {
        $('a[data-target="#createSiteModal"]').click(function () {
            $('input[name="sit_name"]').val('');
        });

        $('#siteSelect').change(function () {
            $('#placement_list').html(loader).load('/placements_list/' + $('#siteSelect option:selected').val());
        });

        $('#siteSelect').change();

        // abro la ventana modal de crear un nuevo sitio si es necesario
        var url = document.location.toString();
        if (url.match('#')) {
            if (url.split('#')[1] === 'createSiteModal') {
                $('#createSiteModal').modal('toggle');
            }
        }
    });
</script>

@include('modals.placements_new')

@include('modals.msg_placement_new')

@include('modals.msg_site_new')

@include('modals.site_validation')

@stop