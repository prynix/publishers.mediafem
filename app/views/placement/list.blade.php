@if(!$site->sit_is_validated)
    @include('placement.site_validate', ['site' => $site])
@endif

@if($placements && $site->sit_is_validated)
    @if($site->sit_state)
        <div class="widget">
            @include('tables.tbl_espacios', ['placements' => $placements, 'site' => $site])
        </div>

        @include('modals.placements_code', ['site' => $site])
   @else
        <div class="alert alert-danger">
            <b>{{ Lang::get('placements.sitio_no_revisado'); }}</b>

            <p>{{ Lang::get('placements.texto_sitio_no_revisado'); }}</p>
        </div>
   @endif
@endif