@extends ('admin.general.layout')

@section ('title') @parent {{ Lang::get('admin.general_home'); }} @stop

@section ('section-title') Adtomatik @stop

@section ('content')

<div class="row">
    <h2>
        {{ Lang::get('admin.general-welcome_adtomatik') }}
    </h2>
    <p><i class="glyphicon glyphicon-hand-left" style="font-size: 18px"></i> {{ Lang::get('admin.general-start') }}</p>
    @if(Utility::hasPermission('publishers.all'))
    <hr />
    <h2>URL's para el registro de Publishers seg&uacute;n Adserver:</h2>
    <ul>
        @foreach($adservers as $adserver)
            @if($adserver->adv_is_default == '1') 
            <li>Adserver por Default: <b>{{ $adserver->adv_name }}</b>&nbsp;&rarr;&nbsp;http://publishers.adtomatik.com/register</li>
            @endif
        @endforeach
    </ul>
    <ul>
        @foreach($adservers as $adserver)
            @if($adserver->adv_class_name != 'YaxApi')
                <li><b>{{ $adserver->adv_name }}</b>&nbsp;&rarr;&nbsp;http://publishers.adtomatik.com/register/{{ $adserver->adv_id }}</li>
            @endif
        @endforeach
    </ul>
    @endif
    @if(count($adservers_media_buyer) > 0)
    <hr />
    <h2>{{ Lang::get('admin.general-affiliate_url') }}:</h2>
    <ul>
        @foreach($adservers_media_buyer as $adserver)
            @if($adserver->adv_class_name != 'YaxApi')
                <li>{{Tabs::permission('adserver.show', '<b>' . $adserver->adv_name . '</b>&nbsp;&rarr;&nbsp;') }}http://publishers.adtomatik.com/register/{{ $adserver->adv_id }}/{{ encrypt(Session::get('admin.id')) }}</li>
            @endif
        @endforeach
    </ul>
    @endif
</div>


@stop