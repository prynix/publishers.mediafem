<div class="widget">
    <div class="row">
        <h2>{{ Lang::get('admin.publishers-publisher_info', ['url' => $publisher->getName()]) }}</h2>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#accountInfo" data-toggle="tab">{{ Lang::get('admin.publishers-account_info') }}</a></li>
        @if (Utility::hasPermission('publishers.change_data'))
        <li><a href="#paymentInfo" data-toggle="tab">{{ Lang::get('admin.publishers-bank_data') }}</a></li>
        @endif
        <li><a href="#accountExecutive" data-toggle="tab">{{ Lang::get('admin.media_buyer') }}</a></li>
        <li><a href="#publisherSites" data-toggle="tab">{{ Lang::get('admin.publishers-sites') }}</a></li>
        @if (Utility::hasPermission('adserver.show'))
        <li><a href="#publisherSitesImonomy" data-toggle="tab">In Image</a></li>
        @endif
        <li><a href="#publisherPayments" data-toggle="tab">{{ Lang::get('admin.publishers-payments') }}</a></li>
    </ul>
    <div class="panel-body" id="publisher" data-publisherId="{{ $publisher->getId() }}">
        <div class="tab-content">
            <div class="tab-pane active" id="accountInfo">
                @include('admin.publishers.accountInfo', ['publisher' => $publisher])
            </div>
            @if (Utility::hasPermission('publishers.change_data'))
            <div class="tab-pane" id="paymentInfo">
                @include('admin.publishers.paymentInfo', ['publisher' => $publisher])
            </div>
            @endif
            <div class="tab-pane" id="accountExecutive">
                @include('admin.publishers.accountExecutive', ['publisher' => $publisher])
            </div>

            <div class="tab-pane" id="publisherSites">
                @include('admin.publishers.publisherSites', ['publisher' => $publisher])
            </div>
            @if (Utility::hasPermission('adserver.show'))
            <div class="tab-pane" id="publisherSitesImonomy">
                @include('admin.publishers.publisherSitesInImage', ['publisher' => $publisher])
            </div>
            @endif
            <div class="tab-pane" id="publisherPayments">
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('#publisherPayments').html(loader).load('admin/publisher_payments/' + $('#publisher').attr('data-publisherId'));
        $('#publisherPayments').change();
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
        }
    });
</script>