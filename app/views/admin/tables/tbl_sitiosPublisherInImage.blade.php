@if(count($sites)  == 0)
<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> {{ Lang::get('admin.publishers-hasnt_sites') }}</div>
@else
<table class="table table-hover report">
    <thead>
        <tr>
            <th>URL</th>
            <th>In Image Id</th>
            <th>In Image Tag Id</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sites as $site)
        <tr>
            <td>{{ $site->getName() }}</td>
            <td>@if($site->imonomy) <input type="text" value="{{ $site->imonomy->getImonomyId() }}" name="imonomy_site_{{ $site->getId() }}" class="form-control" />  @else <input type="text" name="imonomy_site_{{ $site->getId() }}" class="form-control" /> @endif</td>
            <td>@if($site->imonomy) <input type="text" value="{{ $site->imonomy->getImonomyTag() }}" name="imonomy_tag_{{ $site->getId() }}" class="form-control" />  @else <input type="text" name="imonomy_tag_{{ $site->getId() }}" class="form-control" /> @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif