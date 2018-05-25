@extends ('general.layout')

@section ('title') @parent {{ Lang::get('general.mi_cuenta'); }} @stop

@section ('section-title') {{ Lang::get('general.mi_cuenta'); }} @stop

@section ('content')

<div class="row">
    <h2>{{ Lang::get('mi_cuenta.informacion_cuenta') }}.</h2>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#accountInfo" data-toggle="tab">{{ Lang::get('mi_cuenta.informacion_cuenta') }}</a></li>
    <li><a href="#accountEmail" data-toggle="tab">{{ Lang::get('mi_cuenta.actualizar_email') }}</a></li>
    <li><a href="#accountPassword" data-toggle="tab">{{ Lang::get('mi_cuenta.cambiar_contrasena') }}</a></li>
    <li><a href="#accountPayment" data-toggle="tab">{{ Lang::get('mi_cuenta.preferencias_pagos') }}</a></li>
    <li><a href="#accountMessages" data-toggle="tab">{{ Lang::get('general.mensajes'); }}</a></li>
    <li><a href="#accountTax" data-toggle="tab">Tax data</a></li>
</ul>
<div class="panel-body">
    <div class="tab-content">
        <div class="tab-pane active" id="accountInfo">
            @include('profile.accountInfo', ['infoAccount' => $infoAccount])
        </div>

        <div class="tab-pane" id="accountEmail">
            @include('profile.accountEmail')
        </div>

        <div class="tab-pane" id="accountPassword">
            @include('profile.accountPassword', ['resetCode' => $resetCode])
        </div>

        <div class="tab-pane" id="accountPayment">
            @include('profile.accountPayment', ['paypal' => $infoPaypal, 'bank' => $infoBank])
        </div>

        <div class="tab-pane" id="accountMessages">
            @include('tables.tbl_profile_messages', ['messages' => $messages])
        </div>

        <div class="tab-pane" id="accountTax">
            @include('profile.accountTax', ['tax' => $tax])
        </div>
    </div>
</div>

<script>
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown', function(e) {
        window.location.hash = e.target.hash;
    })

    $('.profile_button').click(function(e) {
        e.preventDefault(); // Prevent the browser from handling the link normally, this stops the page from jumping around. Remove this line if you do want it to jump to the anchor as normal.
        var linkHref = $(this).attr('href'); // Grab the URL from the link
        if (linkHref.indexOf("#") != -1) { // Check that there's a # character
            var hash = linkHref.substr(linkHref.indexOf("#") + 1); // Assign the hash to a variable (it will contain "myanchor1" etc
            $(location).attr('href', window.location.host + "/profile#" + hash);
            window.location.replace("/profile#" + hash);
            location.reload();
        }
    });
    
    $('.profile_messages_button').click(function(e) {
        e.preventDefault(); // Prevent the browser from handling the link normally, this stops the page from jumping around. Remove this line if you do want it to jump to the anchor as normal.
        var linkHref = '/profile#accountMessages'; // Grab the URL from the link
        if (linkHref.indexOf("#") != -1) { // Check that there's a # character
            var hash = linkHref.substr(linkHref.indexOf("#") + 1); // Assign the hash to a variable (it will contain "myanchor1" etc
            $(location).attr('href', window.location.host + "/profile#" + hash);
            window.location.replace("/profile#" + hash);
            location.reload();
        }
    });
</script>

@include('modals.placements_new')

@include('modals.update_data')

@stop