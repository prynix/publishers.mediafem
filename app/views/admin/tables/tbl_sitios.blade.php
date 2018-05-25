<?php
$categorize_permission = Utility::hasPermission('sites.categorize');
$adserver_permission = Utility::hasPermission('adserver.show');
?>
@if (Utility::hasPermission('sites.categorize'))
{{ Forms::filters([[Lang::get('admin.media_buyer'), 'filter_sitios_media_buyer'],
            [Lang::get('admin.adserver'), 'filter_sitios_adserver'],
            [Lang::get('admin.sites-categorized'), 'filter_sitios_categorizado']]) }}
@endif
{{ Forms::exportButton('export/sites/validated') }}
<table class="table row-border" id="sites-table" style="white-space: nowrap !important; font-size: 11px !important;">
    <thead>
        <tr>
            <th hidden="true">Id</th>
            @if ($categorize_permission)
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            @endif
            <th>URL</th>
            @if ($categorize_permission)
            <th>{{ Lang::get('admin.sites-categorized') }}</th>
            @endif
            <th>{{ Lang::get('admin.publisher') }}</th>
            <th>{{ Lang::get('admin.media_buyer') }}</th>
            @if ($adserver_permission)
            <th>{{ Lang::get('admin.adserver') }}</th>
            <th>{{ Lang::get('admin.sites-site_id') }}</th>
            @endif
            <th>{{ Lang::get('admin.created_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sites as $site)
        <tr name="fila" @unless($site->categorized == 'Yes') style="font-weight: bold;" @endunless>
            <td hidden="true">{{ $site->id }}</td>
            @if ($categorize_permission)
            <td>
                <a href=""
                   class="siteCategorize"
                   data-siteId="{{ $site->id }}"
                   >
                    {{ Lang::get('admin.sites-categorize') }} <i class="fa fa-angle-double-right"></i>
                </a>
            </td>
            <td>
                <a href=""
                   class="siteView"
                   data-siteId="{{ $site->id }}"
                   >Ver<i class="fa fa-angle-double-right"></i>
                </a>
            </td>
            @endif
            <td>{{ $site->url }}</td>
            @if ($categorize_permission)
            <td>@if($site->categorized == 'Yes') {{ Lang::get('admin.yes') }} @else {{ Lang::get('admin.no') }} @endif</td>
            @endif
            <td>{{ $site->publisher }}</td>
            @if($site->media_buyer == 'Unassigned')
            <td>{{ Lang::get('admin.unassigned') }}</td>
            @else
            <td>{{ $site->media_buyer }}</td>
            @endif
            @if ($adserver_permission)
            <td>{{ $site->adserver_name }}</td>
            <td>{{ $site->adserver_key }}</td>
            @endif
            <td>@if($site->created_at == 'Before migration'){{ Lang::get('admin.before_migration') }} @else{{ $site->created_at }} @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function() {
        tableHighlightRow();

        $('a.siteCategorize').click(function(e) {
            e.preventDefault();
            $('#categorizeSite').html(loader).load('admin/site_categories/' + $(this).attr('data-siteId'));
            return false;
        });

        $('a.siteView').click(function(e) {
            e.preventDefault();
            $('#categorizeSite').html(loader).load('admin/site_view/' + $(this).attr('data-siteId'));
            return false;
        });

        $('#exportSites').click(function(e) {
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


        @if (Utility::hasPermission('sites.categorize'))
                addFilters("{{ Lang::get('admin.filters') }}");
        datatable2.yadcf([
        {column_number: 4,
                filter_container_id: "filter_sitios_media_buyer",
                filter_reset_button_text: "&times;"},
        {column_number: 5,
                filter_container_id: "filter_sitios_adserver",
                filter_reset_button_text: "&times;"},
        {column_number: 2,
                filter_container_id: "filter_sitios_categorizado",
                filter_reset_button_text: "&times;"}
        ]);
                @endif
    });
</script>