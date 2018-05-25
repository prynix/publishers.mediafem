@if(count($sites)  == 0)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('admin.publishers-hasnt_sites') }}</div>
@else
<table class="table table-hover report">
    <thead>
        <tr>
            <th>URL</th>
            @if (Utility::hasPermission('adserver.show'))
            <th>{{ Lang::get('admin.adserver') }}</th>
            <th>{{ Lang::get('admin.sites-site_id') }}</th>
            @endif
            <th>{{ Lang::get('admin.created_at') }}</th>
            <th>{{ Lang::get('admin.sites-validated') }}</th>
            <th>{{ Lang::get('admin.sites-domains') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sites as $site)
        <tr>
            <td>{{ $site->getName() }}</td>
            @if (Utility::hasPermission('adserver.show'))
            <td>{{ $site->publisher->getFirstAdserverName() }}</td>
            <td>{{ $site->getAdserverKey($site->publisher->getFirstAdserverId()) }}</td>
            @endif
            <td>@if($site->created_at == '-0001-11-30 00:00:00'){{ Lang::get('admin.before_migration') }} @else{{ $site->created_at }} @endif</td>
            <td>@if($site->isValidated() === TRUE) {{ Lang::get('admin.yes') }} @else {{ Lang::get('admin.no') }} @endif</td>
            <td>@if($site->isValidated() === TRUE && $site->getValidationType() == 3) <a href="" class="listarDominios" data-siteId="{{ $site->getId() }}">{{ Lang::get('admin.see') }}</a> @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('a.listarDominios').click(function(e) {
            e.preventDefault();
            $('#domains').html(loader).load('admin/site_domains/' + $(this).attr('data-siteId'));
            return false;
        });
    });
</script>
@endif