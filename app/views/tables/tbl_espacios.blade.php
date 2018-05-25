<a href="" class="btn btn-default btn-marginR20" data-toggle="modal" data-target="#getAllCodesModal"><i class="fa fa-file-code-o"></i> {{ Lang::get('placements.get_all_codes'); }}</a>
@if($new_placement)
<a href="" class="btn btn-default btn-marginR20" data-toggle="modal" data-target="#createPlacementModal"><i class="fa fa-plus-square-o"></i> {{ Lang::get('placements.crear_placement'); }}</a>
@endif
@if($site->imonomy)
    @if($site->imonomy->getImonomyTag())
        <a href="" class="btn btn-default btn-marginR20" data-toggle="modal" data-target="#getImonomyTagModal"><i class="fa fa-file-code-o"></i> {{ Lang::get('placements.crear_placement'); }}</a>
        @include('modals.get_imonomy_tag', ['site' => $site])
    @endif
@endif
<br/>&nbsp;
<table class="table table-hover">
    <thead>
        <tr>
            <th>{{ Lang::get('placements.nombre'); }}</th>
            <th>{{ Lang::get('placements.tamano'); }}</th>
            <th>{{ Lang::get('placements.formato'); }}</th>
            <th>{{ Lang::get('placements.estado'); }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach($placements as $placement)
        <tr>
            <td>{{ $placement->getName() }}</td>
            <td>{{ $placement->size->getName() }}</td>
            <td>{{ $placement->size->sizeType->getName() }}</td>
            <td><span class="label label-success">{{ Lang::get('placements.activo'); }}</span></td>
            <td>
                <a href=""
                   data-toggle="modal"
                   data-target="#placementCode"
                   data-adserverKey="{{ $placement->getKey() }}"
                   data-siteName="{{ $placement->site->getName() }}"
                   data-placementName="{{ str_replace(' ', '', $placement->getName()) }}"
                   data-aditionalKey="<?php if ($placement->getAditionalAdserverKey() == NULL) {
                        echo 0;
                    } else {
                        echo $placement->getAditionalAdserverKey();
                    } ?>"
                   data-size="{{ $placement->size->getName() }}"
                   data-height="{{ $placement->size->getHeight() }}"
                   data-width="{{ $placement->size->getWidth() }}"
                   data-placementAdserverName="{{ str_replace(' ', '', $placement->getAdserverName()) }}"
                   data-formatName="{{ strtolower(str_replace(' ', '', $placement->size->sizeType->getName())) }}"
                   >
                    {{ Lang::get('placements.obtener_codigo'); }} <i class="fa fa-angle-double-right"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('a[data-target="#placementCode"]').click(function (e) {
            $('textarea[name="placementCode"]').load('placement_code/' + $(this).attr('data-adserverKey') + '/' + $(this).attr('data-siteName') + '/' + $(this).attr('data-placementName') + '/' + $(this).attr('data-placementAdserverName') + '/' + $(this).attr('data-size') + '/' + $(this).attr('data-height') + '/' + $(this).attr('data-width') + '/' + $(this).attr('data-aditionalKey') + '/' + $(this).attr('data-formatName'));
        });
    });
</script>

@include('modals.placement_new', ['sizes' => $sizes, 'site' => $site])
@include('modals.get_all_codes', ['site' => $site])