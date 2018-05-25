<div class="page-content inset container-fluid">
    <div class="row">
        <h2>{{ Lang::get('admin.publishers-sites') }}</h2>
    </div>

    
    <div class="panel-body">
        @include('admin.tables.tbl_sitiosPublisher', ['sites' => $publisher->sites])
    </div>
    <div class="panel-body" id="domains">
        
    </div>
</div>
