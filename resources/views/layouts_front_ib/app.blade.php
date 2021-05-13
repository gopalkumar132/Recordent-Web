<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Laravel') }}</title>

<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">


<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
<link href="{{config('app.url')}}assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="{{config('app.url')}}assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
<link href="{{config('app.url')}}assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="{{config('app.url')}}assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{config('app.url')}}assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{config('app.url')}}assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link href="{{config('app.url')}}assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
<link href="{{config('app.url')}}assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{config('app.url')}}assets/pages/css/login-4.min.css" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<!-- END THEME LAYOUT STYLES -->
<link rel="shortcut icon" href="favicon.ico" />
</head>
<body class=" login">
<div id="app">
  @yield('content') 
<!-- Scripts -->
    <!--[if lt IE 9]>
    <script src="{{config('app.url')}}assets/global/plugins/respond.min.js"></script>
    <script src="{{config('app.url')}}assets/global/plugins/excanvas.min.js"></script> 
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="{{config('app.url')}}assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{config('app.url')}}assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="{{config('app.url')}}assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="{{config('app.url')}}assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{config('app.url')}}assets/pages/scripts/login-4.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->
<!-- Fonts -->
</div>
</body>
</html>
