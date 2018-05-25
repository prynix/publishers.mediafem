<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- start: Meta -->
        <meta charset="utf-8" />
        <title>@yield('title', 'Admin Adtomatik - ')</title>
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!-- end: Meta -->

        <!-- start: Mobile Specific -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- end: Mobile Specific -->

        <!-- start: CSS -->
        {{ HTML::style('css/font-awesome.min.css'); }}
        {{ HTML::style('css/bootstrap.min.css'); }}
        {{ HTML::style('css/style.css'); }}
        {{ HTML::style('css/bootstrapValidator.min.css'); }}
        {{ HTML::style('http://fonts.googleapis.com/css?family=Lato:300,700'); }}
        <!-- end: CSS -->

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

        <!-- pace scripts (load page) -->
        {{ HTML::script('js/pace.min.js'); }}

        <!-- bootstrap scripts -->
        {{ HTML::script('js/bootstrap.min.js'); }}

        <!-- bootstrap validator scripts -->
        {{ HTML::script('js/bootstrapValidator.min.js'); }}
        {{ HTML::script('js/language/en_US.js'); }}

        <!-- end: JavaScript -->
    </head>
    <body>
        @yield('content')
    </body>
</html>