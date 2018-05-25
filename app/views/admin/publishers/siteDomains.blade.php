<div class="row">
    <h2>{{ Lang::get('admin.sites-site_domains', ['url' => $site->getName()]) }}:</h2>
</div>
<ul  class="list-group">
    @foreach($domains as $domain)
    <li  class="list-group-item">{{ $domain }}</li>
    @endforeach
</ul>