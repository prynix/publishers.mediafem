<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- start: Meta -->
        <meta charset="utf-8" />
        <title>@section('title') {{ Lang::get('general.publishers', array('brand' => Session::get('platform.brand'))).' - ' }}@show</title>
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!-- end: Meta -->

        <link rel="icon" type="image/x-icon" href="{{ Session::get('platform.favicon') }}">

        <!-- start: Mobile Specific -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- end: Mobile Specific -->

        <!-- start: CSS -->
        {{ HTML::style('css/font-awesome.min.css'); }}
        {{ HTML::style('css/ladda-themeless.min.css'); }}    

        @if(Request::segment(1) === 'report' || Request::segment(1) === 'report_imonomy')
        {{ HTML::style('css/daterangepicker-bs3.css'); }}
        @endif

        {{ HTML::style('css/jquery.dataTables.min.css'); }}
        {{ HTML::style('css/dataTables.bootstrap.css'); }}        
        {{ HTML::style('css/bootstrap.min.css'); }}
        {{ HTML::style('css/bootstrapValidator.min.css'); }}
        {{ HTML::style('css/bootstrap-tour.min.css'); }}
        {{ HTML::style('css/style_'.Session::get('platform.name').'.css'); }}
        {{-- HTML::style('http://fonts.googleapis.com/css?family=Lato:300,700'); --}}<!-- end: CSS -->

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            {{ HTML::script('js/html5shiv.min.js'); }}
            {{ HTML::script('js/respond.min.js'); }}
        <![endif]-->

        <!-- start: JavaScript -->

        <!--[if !IE]>-->
        {{ HTML::script('js/jquery-2.1.0.min.js'); }}
        <!--<![endif]-->

        <!--[if IE]>
            {{ HTML::script('js/jquery-1.11.0.min.js'); }}
        <![endif]-->

        @if(Request::segment(1) === 'report' || Request::segment(1) === 'report_imonomy')
        <!-- datepicker -->
        {{ HTML::script('js/moment-with-langs.js'); }}
        {{ HTML::script('js/daterangepicker.js'); }}
        @endif

        <!-- ladda (Spin for ajax buttons) -->
        {{ HTML::script('js/spin.min.js'); }}
        {{ HTML::script('js/ladda.min.js'); }}

        <!-- pace scripts (load page) -->
        {{ HTML::script('js/pace.min.js'); }}

        <!-- bootstrap scripts -->
        {{ HTML::script('js/bootstrap.min.js'); }}

        <!-- bootstrap validator scripts -->
        {{ HTML::script('js/bootstrapValidator.min.js'); }}

        @if(Session::get('user.language') == 'es')
        {{ HTML::script('js/language/es_ES.js'); }}
        @elseif(Session::get('user.language') == 'en')
        {{ HTML::script('js/language/en_US.js'); }}
        @elseif(Session::get('user.language') == 'ru')
        {{ HTML::script('js/language/ru_RU.js'); }}
        @elseif(Session::get('user.language') == 'zh')
        {{ HTML::script('js/language/zh_TW.js'); }}
        @endif

        <!-- Tour script -->
        {{ HTML::script('js/bootstrap-tour.min.js'); }}

        <!-- processForms script -->
        {{ HTML::script('js/jquery.processForm.js'); }}        

        <!-- dataTables scrits -->
        {{ HTML::script('js/jquery.dataTables.min.js'); }}
        {{ HTML::script('js/dataTables.bootstrap.js'); }}

        <!-- highstock scripts -->
        {{ HTML::script('js/highcharts.js'); }}

        <!-- theme scripts -->
        {{ HTML::script('js/core.js'); }}

        <!-- end: JavaScript -->
    </head>
    <body>
        <!-- start: Main -->
        <div id="wrapper">

            <!-- start: Main Menu (#sidebar-wrapper) -->
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand"><a href="#">{{ HTML::image('images/'.Session::get('platform.logo'), Session::get('platform.brand')); }}</a></li>
                    <li>
                        <a href="/">
                            <i class="fa fa-home"></i> {{ Lang::get('general.escritorio'); }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#togglePlacement" data-parent="#sidenav01" class="collapsed">
                            <i class="fa fa-image"></i>  {{ Lang::get('general.espacios'); }} <i class="fa fa-caret-down pull-right"></i>
                        </a>
                        <div class="collapse" id="togglePlacement" style="height: 0px;">
                            <ul class="">
                                <li><a href="/placements#createSiteModal">{{ Lang::get('general.crear_espacio'); }}</a></li>
                                <li><a href="/placements">{{ Lang::get('general.listar_espacio'); }}</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#toggleReports" data-parent="#sidenav01" class="collapsed">
                            <i class="fa fa-bar-chart-o"></i> {{ Lang::get('general.reportes'); }} <i class="fa fa-caret-down pull-right"></i>
                        </a>
                        <div class="collapse" id="toggleReports" style="height: 0px;">
                            <ul class="">
                                <li><a href="/report/site_name/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.site_name'); }}</a></li>
                                <li><a href="/report/placement/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.placement'); }}</a></li>
                                <li><a href="/report/site_placement/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.site_placement'); }}</a></li>
                                <li><a href="/report/country/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.country'); }}</a></li>
                                <li><a href="/report/format/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.format'); }}</a></li>
                                <li><a href="/report/country_size/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.country_size'); }}</a></li>
                                <li><a href="/report/day/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.day'); }}</a></li>
                                <li><a href="/report/month/month_to_date">{{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.month'); }}</a></li>
                                @if(Session::get('imonomy'))
                                <li><a href="/report_imonomy/day/month_to_date">In-Image {{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.day'); }}</a></li>
                                <li><a href="/report_imonomy/month/month_to_date">In-Image {{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.month'); }}</a></li>
                                <li><a href="/report_imonomy/site_name/month_to_date">In-Image {{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.site_name'); }}</a></li>
                                <li><a href="/report_imonomy/country_name/month_to_date">In-Image {{ Lang::get('general.reporte_por') . ' ' . Lang::get('general.country'); }}</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="/payments">
                            <i class="fa fa-money"></i> {{ Lang::get('general.mis_pagos'); }}
                        </a>
                    </li>                    
                    <li>
                        <a href="http://help.adtomatik.com" target="_BLANK">
                            <i class="fa fa-question-circle"></i> {{ Lang::get('general.ayuda'); }}
                        </a>
                    </li>                    
                </ul>

                <div class="sidebar-nav-bottom">
                    &COPY; 2009 - 2014 {{Session::get('platform.brand')}} by MediaFem LLC.
                </div>
            </div>
            <!-- end: Main Menu (#sidebar-wrapper) -->

            <!-- start: Page content -->
            <div id="page-content-wrapper">
                <div class="content-header">
                    <h1>
                        <!-- Boton para mobile, no borrar -->
                        <a id="menu-toggle" href="#" class="btn btn-default"><i class="fa fa-bars"></i></a>
                        @yield('section-title')
                    </h1>

                    <div class="user-top-nav">
                        <a class="btn btn-default" href="http://help.adtomatik.com" target="_BLANK"><i class="fa fa-question-circle"></i></a>

                        <span id="userNotifications" class="profile_messages_button"></span>

                        <span class="dropdown">
                            <a class="btn btn-default" id="userLanguaje" data-toggle="dropdown"><img src="/images/flag_{{ Session::get('user.language'); }}.png" alt="{{ Lang::get('idiomas.' . Session::get('user.language')) }}" /></a>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userLanguaje">
                                @foreach( Lang::get('idiomas') as $llave => $valor )
                                <li><a tabindex="-1" href="/profile/setLang/{{ $llave; }}"><img src="/images/flag_{{ $llave; }}.png" alt="{{ $valor; }}" /> {{ $valor; }}</a></li>
                                @endforeach
                            </ul>
                        </span>

                        <span class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="userMenu1" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userMenu1">
                                <li class="user-email"><span>{{ Session::get('user.email') }}</span></li>
                                <li class="divider"></li>
                                <li><a class="profile_button" tabindex="-1" href="/profile#accountInfo">{{ Lang::get('general.mis_datos'); }}</a></li>
                                <li><a class="profile_button" tabindex="-1" href="/profile#accountPassword">{{ Lang::get('general.cambiar_contrasena'); }}</a></li>
                                <li><a class="profile_button" tabindex="-1" href="/profile#accountEmail">{{ Lang::get('general.actualizar_correo'); }}</a></li>
                                <li><a class="profile_button" tabindex="-1" href="/profile#accountPayment">{{ Lang::get('general.preferencias_pagos'); }}</a></li>
                                <li><a class="profile_button" tabindex="-1" href="/profile#accountMessages">{{ Lang::get('general.mensajes'); }}</a></li>
                                <li><a class="profile_button" tabindex="-1" href="/profile#accountTax">Account tax data</a></li>
                                <li class="divider"></li>
                                <li><a tabindex="-1" href="/logout">{{ Lang::get('general.cerrar_session'); }}</a></li>
                            </ul>
                        </span>
                    </div>
                </div>

                <div class="page-content inset container-fluid">
                    
                    @if(Session::get('publisher.show_alert') == 1)
                    <div class="alert alert-warning" role="alert">
                        @if(Request::segment(1) === 'report')
                            {{ Lang::get('escritorio.report_alert'); }}
                        @else
                            {{ Lang::get('escritorio.migration_alert'); }}
                        @endif
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
            <!-- end: Page content (#page-content-wrapper) -->
        </div>
        <!-- end: Main (#wrapper) -->

        @if(!Session::get('user.completeData'))
        <!-- start: Modal para saber si completo los datos (#accountInfo) -->
        @include('modals.form_datos_requeridos');
        <!-- end: Modal para saber si completo los datos (#accountInfo) -->
        @endif

        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-54020812-1', 'auto');
            ga('send', 'pageview');
        </script>

    </body>
</html>