<!DOCTYPE html>
<html lang="en" @if(config('config.direction') == 'rtl') dir="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Fiskelogg.no">
        <title>{{ config('app.name') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
        <link rel="manifest" href="/images/site.webmanifest">
        <link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        @if(config('config.direction') == 'rtl')
            <link href="{{ mix('/css/style-rtl.css') }}" id="direction" rel="stylesheet">
        @else
            <link href="{{ mix('/css/style.css') }}" id="direction" rel="stylesheet">
        @endif
        <link href="{{ mix('/css/colors/'.config('config.color_theme').'.css') }}" id="theme" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="fix-header fix-sidebar card-no-border">
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
        </div>
        <div id="root">
            <router-view></router-view>
        </div>
        <script src="/js/lang"></script>
        <script src="{{ mix('/js/plugin.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
