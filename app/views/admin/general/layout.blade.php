<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- start: Meta -->
        <meta charset="utf-8" />
        <title>@section('title') {{ Lang::get('admin.general-admin').' - ' }}@show</title>
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!-- end: Meta -->

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- start: Mobile Specific -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- end: Mobile Specific -->

        <!-- start: CSS -->
        {{ HTML::style('css/font-awesome.min.css'); }}
        {{ HTML::style('css/ladda-themeless.min.css'); }}
        {{ HTML::style('css/daterangepicker-bs3.css'); }}
        {{ HTML::style('css/jquery.dataTables.min.css'); }}
        {{ HTML::style('css/dataTables.bootstrap.css'); }}
        {{ HTML::style('css/jquery.dataTables.yadcf.css'); }}
        {{ HTML::style('css/bootstrap.min.css'); }}
        {{ HTML::style('css/bootstrapValidator.min.css'); }}
        {{ HTML::style('css/summernote-bs3.css'); }}
        {{ HTML::style('css/summernote.css'); }}
        {{ HTML::style('css/select2.css'); }}
        {{ HTML::style('css/select2-bootstrap.css'); }}
        {{ HTML::style('css/sweetalert.css'); }}
        {{ HTML::style('css/style.css'); }}
        {{ HTML::style('http://fonts.googleapis.com/css?family=Lato:300,700'); }}
        <!-- end: CSS -->

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            {{ HTML::script('js/html5shiv.min.js'); }}
            {{ HTML::script('js/respond.min.js'); }}
        <![endif]-->

        <style type='text/css'>
            .table-striped>tbody>tr:nth-child(odd)>td,
            .table-striped>tbody>tr:nth-child(odd)>th {
                background-color: #DFF0D8;
            }
            .table {
                border: 0px !important;
            }
            .highlight-selected-row {
                background-color: #DFF0D8 !important;
            }
        </style>

        <!-- start: JavaScript -->

        <!--[if !IE]>-->
        {{ HTML::script('js/jquery-2.1.0.min.js'); }}
        <!--<![endif]-->

        <!--[if IE]>
            {{ HTML::script('js/jquery-1.11.0.min.js'); }}
        <![endif]-->

        <!-- datepicker -->
        {{ HTML::script('js/moment-with-langs.js'); }}
        {{ HTML::script('js/daterangepicker.js'); }}

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
        @endif

        <!-- editor messages script -->
        {{ HTML::script('js/summernote.min.js'); }}

        <!-- select2 script -->
        {{ HTML::script('js/select2.min.js'); }}

        <!-- processForms script -->
        {{ HTML::script('js/jquery.processForm.js'); }}

        <!-- highstock scripts -->
        {{ HTML::script('js/highstock.js'); }}

        <!-- dataTables scrits -->
        {{ HTML::script('js/jquery.dataTables.min.js'); }}
        {{ HTML::script('js/dataTables.bootstrap.js'); }}
        {{ HTML::script('js/jquery.dataTables.yadcf.js'); }}
        {{ HTML::script('js/dataTables.fixedColumns.js'); }}

        <!-- HTML Table Export -->
        {{ HTML::script('js/tableExport.js'); }}
        {{ HTML::script('js/exporting.js'); }}
        {{ HTML::script('js/jquery.base64.js'); }}

        <!-- Sweet Alert -->
        {{ HTML::script('js/sweetalert.min.js'); }}
        
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
                    <li class="sidebar-brand"><a href="/admin">{{ HTML::image('images/logo.png', 'Adtomatik'); }}</a></li>
                    <li>
                        <a href="/admin">
                            <i class="fa fa-home"></i> {{ Lang::get('general.escritorio'); }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#toggleSites" data-parent="#sidenav01" class="collapsed"><i class="fa fa-list"></i> {{Lang::get('admin.general-publishers')}} & {{ Lang::get('admin.general-sites') }} <i class="fa fa-caret-down pull-right"></i></a>
                        <div class="collapse" id="toggleSites" style="height: 0px;">
                            <ul class="">
                                {{Tabs::permission('publishers', '<li><a href="/admin/publishers">'.Lang::get('admin.general-publishers').'</a></li>')}}
                                {{Tabs::permission('sites', '<li><a href="/admin/sites">'.Lang::get('admin.general-sites').'</a></li>')}}
                                {{Tabs::permission('messages', '<li><a href="/admin/messages">'.Lang::get('admin.general-messages').'</a></li>')}}
                            </ul>
                        </div>
                    </li>
                    {{Tabs::permission('inventory', '<li><a href="javascript:;" data-toggle="collapse" data-target="#toggleReports" data-parent="#sidenav01" class="collapsed"><i class="fa fa-bar-chart-o"></i> '.(Utility::hasPermission('affiliate_revenue') ? Lang::get('admin.general-affiliate_revenue') : Lang::get('admin.general-inventory')).' <i class="fa fa-caret-down pull-right"></i></a><div class="collapse" id="toggleReports" style="height: 0px;"><ul class=""><li><a href="/admin/report/publisher_name"> '. Lang::get('admin.reporte_por') .' '. Lang::get('admin.publisher') .'</a></li><li><a href="/admin/report/site_name"> '. Lang::get('admin.reporte_por') .' '. Lang::get('admin.site') .'</a></li><li><a href="/admin/report/country">'. Lang::get('admin.reporte_por') .' '. Lang::get('admin.country') .'</a></li></ul></div></li>')}}
                    {{Tabs::permission('optimization', '<li><a href="/admin/publishers_optimization"><i class="fa fa-tachometer"></i> '.Lang::get('admin.general-optimization').'</a></li>')}}
                    {{Tabs::permission('payments', '<li><a href="javascript:;" data-toggle="collapse" data-target="#togglePayments" data-parent="#sidenav01" class="collapsed"><i class="fa fa-money"></i> '. Lang::get('admin.general-payments') .' <i class="fa fa-caret-down pull-right"></i></a><div class="collapse" id="togglePayments" style="height: 0px;"><ul class=""><li><a href="/admin/payments"> '. Lang::get('admin.publisher') .'</a></li><li><a href="/admin/payments/affiliate"> '. 'Ingresos de Freelancers' .'</a></li></a></li>'.Tabs::permission('mediabuyer_commission', '<li><a href="/admin/mediabuyer_commissions"> Comisiones de Media Buyers</a></li>').'</ul></div></li>')}}
                    {{Tabs::permission('users', '<li><a href="/admin/users"><i class="fa fa-users"></i> '.Lang::get('admin.general-users').'</a></li>')}}
                    {{Tabs::permission('constants', '<li><a href="/admin/constants"><i class="glyphicon glyphicon-wrench"></i> '.Lang::get('admin.general-constants').'</a></li>')}}  



                    <li>
                        <a href="/admin/help">
                            <i class="fa fa-question-circle"></i> {{ Lang::get('general.ayuda'); }}
                        </a>
                    </li>
                </ul>

                <div class="sidebar-nav-bottom">
                    &COPY; 2009 - 2014 AdTomatik by MediaFem LLC.
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

                        <!--
                        <span class="dropdown">
                            <a class="btn btn-default" id="userNotifications" data-toggle="dropdown">
                                <i class="fa fa-bell"></i>
                                <span class="badge notification-red">1</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userNotifications">
                                <li><a tabindex="-1" href="#">Mis datos</a></li>
                                <li><a tabindex="-1" href="#">Cambiar contraseña</a></li>
                                <li><a tabindex="-1" href="#">Actualizar correo</a></li>
                                <li><a tabindex="-1" href="#">Preferencias de pagos</a></li>
                                <li class="divider"></li>
                                <li class="more"><a tabindex="-1" href="#">Ver todas las notificaciones</a></li>
                            </ul>
                        </span>
                        -->
                        @if(Utility::hasPermission('earnings'))
                        <a class="btn btn-default userEarnings">
                            <b>{{ Lang::get('admin.general-actual_balance') }}</b>
                            <span class="badge notification-green"><i class="glyphicon glyphicon-usd"></i>{{ Session::get('earnings') }}</span>
                        </a>
                        @endif
                        <span class="dropdown">
                            <a class="btn btn-default" id="userLanguaje" data-toggle="dropdown"><img src="/images/flag_{{ Session::get('user.language'); }}.png" alt="{{ Lang::get('idiomas_admin.' . Session::get('user.language')) }}" /></a>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userLanguaje">
                                @foreach( Lang::get('idiomas_admin') as $llave => $valor )
                                <li><a tabindex="-1" href="/admin/profile/setLang/{{ $llave; }}"><img src="/images/flag_{{ $llave; }}.png" alt="{{ $valor; }}" /> {{ $valor; }}</a></li>
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
                                {{Tabs::permission('earnings', '<li><a tabindex="-1" href="" class="userEarnings">'.Lang::get('admin.general-payments').'</a></li>') }}
                                <!--<li><a tabindex="-1" href="/admin/profile#accountPassword">{{ Lang::get('general.cambiar_contrasena'); }}</a></li>
                                <li><a tabindex="-1" href="/admin/profile#accountEmail">{{ Lang::get('general.actualizar_correo'); }}</a></li>-->
                                {{Tabs::permission('earnings', '<li><a tabindex="-1" href="" class="userPayment">'.Lang::get('general.preferencias_pagos').'</a></li>') }}
                                <li class="divider"></li>
                                <li><a tabindex="-1" href="/logout">{{ Lang::get('admin.general-logout'); }}</a></li>
                            </ul>
                        </span>
                    </div>
                </div>

                <!-- Payment history -->
                @include('admin.modals.payment_history')
                @include('admin.modals.payment_preferences')
                <script>
                    $(window).bind("load", function () {
                        $('.userEarnings').click(function (e) {
                            e.preventDefault();
                            $('#viewPaymentsModal').modal('show')
                            $('#viewPaymentsTables').html(loader).load("/admin/item_payments/{{ Session::get('admin.id') }}/affiliate");
                        });
                        $('.userPayment').click(function (e) {
                            e.preventDefault();
                            $('#paymentPreferencesModal').modal('show')
                            $('#paymentPreferences').html(loader).load("/admin/profile/paymentPreferences/{{ Session::get('admin.id') }}");
                        });
                    });
                </script>
                <!-- Payment history -->

                <div class="page-content inset container-fluid">
                    @yield('content')
                </div>
            </div>
            <!-- end: Page content (#page-content-wrapper) -->
        </div>
        <!-- end: Main (#wrapper) -->
    </body>
</html>
