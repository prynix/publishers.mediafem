<table class="table table-hover row-border table-striped" style="white-space: nowrap !important; font-size: 11px !important;">
    @if($validated == 'validated')
    <thead>
        <tr>
            <th>URL</th>
            @if (Utility::hasPermission('sites.categorize'))
            <th>{{ Lang::get('admin.sites-categorized') }}</th>
            @endif
            <th>{{ Lang::get('admin.publisher') }}</th>
            <th>{{ Lang::get('admin.media_buyer') }}</th>
            @if (Utility::hasPermission('adserver.show'))
                <th>{{ Lang::get('admin.adserver') }}</th>
                <th>{{ Lang::get('admin.sites-site_id') }}</th>
            @endif
            <th>{{ Lang::get('admin.created_at') }}</th>
            <th>Id</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sites as $site)
        <tr @unless($site->categorized == 'Yes') style="font-weight: bold;" @endunless>
            <td>{{ $site->url }}</td>
            @if (Utility::hasPermission('sites.categorize'))
            <td>@if($site->categorized == 'Yes') {{ Lang::get('admin.yes') }} @else {{ Lang::get('admin.no') }} @endif</td>
            @endif
            <td>{{ $site->publisher }}</td>
            @if($site->media_buyer == 'Unassigned')
            <td>{{ Lang::get('admin.unassigned') }}</td>
            @else
            <td>{{ $site->media_buyer }}</td>
            @endif
            @if (Utility::hasPermission('adserver.show'))
                <td>{{ $site->adserver_name }}</td>
                <td>{{ $site->adserver_key }}</td>
            @endif
            <td>@if($site->created_at == 'Before migration'){{ Lang::get('admin.before_migration') }} @else{{ $site->created_at }} @endif</td>
            <td>{{ $site->id }}</td>
        </tr>
        @endforeach
    </tbody>
    @else
    <thead>
        <tr>
            <th>URL</th>
            <th>{{ Lang::get('admin.publisher') }}</th>
            <th>{{ Lang::get('admin.media_buyer') }}</th>
            @if (Utility::hasPermission('adserver.show'))
            <th>{{ Lang::get('admin.adserver') }}</th>
            @endif
            <th>{{ Lang::get('admin.created_at') }}</th>
            <th>Id</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sites as $site)
        <tr @unless($site->media_buyer !== 'Unassinged') style="font-weight: bold;" @endunless>
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
            <td>{{ $site->id }}</td>
        </tr>
        @endforeach
    </tbody>
    @endif
</table>