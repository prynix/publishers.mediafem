<div class="widget">
    <div class="row">
        <h2>Datos del usuario {{ $user->getEmail() }}</h2>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#accountInfo" data-toggle="tab">Información de la cuenta</a></li>
        <li><a href="#paymentPreferencesInfo" data-toggle="tab">Preferencias de pago</a></li>
    </ul>
    <div class="panel-body" id="user" data-userId="{{ $user->getId() }}">
        <div class="tab-content">
            <div class="tab-pane active" id="accountInfo">
                @include('admin.users.accountInfo', ['user' => $user])
            </div>
            <div class="tab-pane" id="paymentPreferencesInfo">
                @include('admin.users.paymentInfo', ['administrator' => $user->administrator])
            </div>
        </div>
    </div>
</div>