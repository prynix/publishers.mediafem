<form id="paymentInfoForm"  method="post" class="form-horizontal">
    @if($publisher->bankDetail)
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-account_name') }}:</label>
        <div class="col-sm-8">{{ $publisher->bankDetail->bnk_account_name }}</div>
    </div>
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-account_number') }}:</label>
        <div class="col-sm-8">{{ $publisher->bankDetail->bnk_account_number }}</div>
    </div>
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-benefit_bank') }}:</label>
        <div class="col-sm-8">{{ $publisher->bankDetail->bnk_bank_name }}</div>
    </div>
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-route_code') }}:</label>
        <div class="col-sm-8">{{ $publisher->bankDetail->bnk_route_code }}</div>
    </div>
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-bank_city') }}:</label>
        <div class="col-sm-8">{{ $publisher->bankDetail->bnk_city }}</div>
    </div>
    

    <div id="infoCountry" class="form-group">
        <label for="prf_country" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-bank_country') }}:</label>
        <div class="col-sm-8">
            <select name="prf_country" class="form-control" disabled="true">
                @foreach( Lang::get('countries') as $llave => $valor )
                <option value="{{ $llave }}" {{ $llave === $publisher->bankDetail->bnk_country_id ? 'selected="selected"' : '' }}>{{ $valor }}</option>
                @endforeach
            </select>
            <span class="help-block"></span>
        </div>
    </div>
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-bic_code') }}:</label>
        <div class="col-sm-8">{{ $publisher->bankDetail->bnk_bic_code }}</div>
    </div>
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-intermediary_bank') }}:</label>
        <div class="col-sm-8">{{ $publisher->bankDetail->bnk_intermediary_bank }}</div>
    </div>
    @else
    <div class="alert alert-warning" role="alert">{{ Lang::get('admin.publishers-bank_incomplete') }}</div>
    @endif
    @if($publisher->paypalDetail)
    <div class="form-group">
        <label for="infoEmail" class="col-sm-3 control-label">{{ Lang::get('admin.publishers-paypal') }}:</label>
        <div class="col-sm-8">{{ $publisher->paypalDetail->ppl_email }}</div>
    </div>
    @else
    <div class="alert alert-warning" role="alert">{{ Lang::get('admin.publishers-paypal_incomplete') }}</div>
    @endif
</form>