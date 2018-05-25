@if (Utility::hasPermission('adserver.show'))
{{ Forms::filters([[Lang::get('admin.media_buyer'), 'filter_sitios_media_buyer'],
            [Lang::get('admin.adserver'), 'filter_sitios_adserver']]) }}
@endif
{{ Forms::exportButton('export/sites/unvalidated') }}

<table class="table row-border" id="sites-table" style="white-space: nowrap !important; font-size: 11px !important;">
    <thead>
        <tr>
            <th hidden="true">Id</th>
            <th>URL</th>
            <th>{{ Lang::get('admin.publisher') }}</th>
            <th>{{ Lang::get('admin.media_buyer') }}</th>
            @if (Utility::hasPermission('adserver.show'))
            <th>{{ Lang::get('admin.adserver') }}</th>
            @endif
            <th>{{ Lang::get('admin.created_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sites as $site)
        <tr name="fila" @unless($site->media_buyer !== 'Unassinged') style="font-weight: bold;" @endunless>
            <td hidden="true">{{ $site->id }}</td>
            <td>{{ $site->url }}</td>
            <td>{{ $site->publisher }}</td>
            @if($site->media_buyer == 'Unassigned')
            <td>{{ Lang::get('admin.unassigned') }}</td>
            @else
            <td>{{ $site->media_buyer }}</td>
            @endif
            @if (Utility::hasPermission('adserver.show'))
            <td>{{ $site->adserver_name }}</td>
            @endif
            <td>@if($site->created_at == 'Before migration'){{ Lang::get('admin.before_migration') }} @else{{ $site->created_at }} @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function () {
        tableHighlightRow();
                @if (Utility::hasPermission('adserver.show'))addFilters("{{ Lang::get('admin.filters') }}"); @endif

                $('#exportSites').click(function (e) {
            e.preventDefault();
            $('#sites-table').tableExport({type: 'excel', escape: 'false', ignoreColumn: '[0]'});
            return false;
        });

        var datatable2 = $("#sites-table").dataTable({
            "sDom": '<"top"i>rt<"bottom"flp><"clear">',
            'paging': false,
            'info': false,
            "bSort": true, //sorting
            "aaSorting": [[0, "desc"]], // default sort
            'bFilter': true,
            'bScrollCollapse': true,
            'sScrollY': "450px",
            'sScrollX': "100%"
        });

        @if (Utility::hasPermission('adserver.show'))
            datatable2.yadcf([
                {column_number: 2,
                        filter_container_id: "filter_sitios_media_buyer",
                        filter_reset_button_text: "&times;"},
                {column_number: 3,
                        filter_container_id: "filter_sitios_adserver",
                        filter_reset_button_text: "&times;"}
            ]);
       @endif
    });
</script>