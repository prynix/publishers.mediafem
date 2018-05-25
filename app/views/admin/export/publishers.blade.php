<table class="table table-hover row-border table-striped" id="publishers-table"  style="white-space: nowrap !important; font-size: 11px !important;">
    <thead>
        <tr>
            <th>URL</th>
            <th>{{ Lang::get('admin.publishers-name') }}</th>
            <th>{{ Lang::get('admin.publishers-email') }}</th>
            <th>{{ Lang::get('admin.media_buyer') }}</th>
            @if (Utility::hasPermission('adserver.show'))
            <th>{{ Lang::get('admin.adserver') }}</th>
            <th>{{ Lang::get('admin.publishers-publisher_id') }}</th>
            @endif
            <th>{{ Lang::get('admin.created_at') }}</th>
            <th>Id</th>
            <!--Solo para Excel-->
            <th>{{ Lang::get('admin.publishers-payment_days') }}</th>
            <th>{{ Lang::get('admin.country') }}</th>
            <th>{{ Lang::get('admin.language') }}</th>
            <th>{{ Lang::get('admin.publishers-city') }}</th>
            <th>{{ Lang::get('admin.publishers-zip') }}</th>
            <th>{{ Lang::get('admin.publishers-address') }}</th>
            <th>{{ Lang::get('admin.publishers-phone') }}</th>
            <th>{{ Lang::get('admin.publishers-account_name') }}</th>
            <th>{{ Lang::get('admin.publishers-account_number') }}</th>
            <th>{{ Lang::get('admin.publishers-benefit_bank') }}</th>
            <th>{{ Lang::get('admin.publishers-route_code') }}</th>
            <th>{{ Lang::get('admin.publishers-bank_city') }}</th>
            <th>{{ Lang::get('admin.publishers-bank_country') }}</th>
            <th>{{ Lang::get('admin.publishers-bic_code') }}</th>
            <th>{{ Lang::get('admin.publishers-intermediary_bank') }}</th>
            <th>{{ Lang::get('admin.publishers-paypal') }}</th>

            <th>{{ Lang::get('admin.publishers-tax_info') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($publishers as $publisher)
        <tr @unless ($publisher->media_buyer != 'Unassigned') style="font-weight: bold;" @endunless >
            <td>{{ $publisher->url }}</td>
            <td>{{ $publisher->name }}</td>
            <td>{{ $publisher->email }}</td>
            <td>{{ $publisher->media_buyer }}</td>
            @if (Utility::hasPermission('adserver.show'))
            <td>{{ $publisher->adserver_name }}</td>
            <td>{{ $publisher->adserver_key }}</td>
            @endif
            <td>{{ $publisher->created_at }}</td>
            <td>{{ $publisher->id }}</td>
            <!--Solo para Excel-->
            <td>{{ $publisher->days_to_billing }}</td>
            <td>{{ Lang::get('countries.'.$publisher->prf_country_id) }}</td>
            <td>{{ $publisher->lng_name }}</td>
            <td>{{ $publisher->prf_city }}</td>
            <td>{{ $publisher->prf_zip_code }}</td>
            <td>{{ $publisher->prf_address }}</td>
            <td>{{ $publisher->prf_phone_number }}</td>

            <td>{{ $publisher->bnk_account_name }}</td>
            <td>{{ $publisher->bnk_account_number }}</td>
            <td>{{ $publisher->bnk_bank_name }}</td>
            <td>{{ $publisher->bnk_route_code }}</td>
            <td>{{ $publisher->bnk_city }}</td>
            <td>{{ Lang::get('countries.'.$publisher->bnk_country_id) }}</td>
            <td>{{ $publisher->bnk_bic_code }}</td>
            <td>{{ $publisher->bnk_intermediary_bank }}</td>

            <td>{{ $publisher->ppl_email }}</td>
            @if($publisher->pbl_tax_complete == '0')
            <td>NO</td>
            @else
            <td>SI</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>